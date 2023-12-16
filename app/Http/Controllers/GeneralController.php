<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use App\Models\SalesOrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GeneralController extends Controller {
    
    function checkDataUser(Request $request,int $productId) {
        $user = Auth::guard('customer')->user();
        // dd($productId);
        if($user->place_of_birth == null OR
            $user->date_of_birth == null OR 
            $user->phone == null OR 
            $user->address == null
        ) {
            $request->session()->flash('message', 'Anda belum bisa melakukan pembelian, lengkapi data untuk melanjutkan.');
            return redirect('/detail-product/'.$productId);
        } else {

            $productData = Product::with('inventory')->where(['id' => $productId])->first();
            $stock =  $productData->inventory ? $productData->inventory->stock : 0;

            if($stock <= 0) {
                $request->session()->flash('message', 'Produk yang anda pilih telah habis.');
                return redirect('/detail-product/'.$productId);
            }
            $checkOrderHeader = SalesOrder::with('salesOrderDetails.products')
                                ->where(['customer_code' => $user->code])
                                ->first();
                                // dd($checkOrderHeader);
            if($checkOrderHeader) {
                if($productId == $checkOrderHeader->salesOrderDetails->products->id) {
                    $request->session()->flash('message', 'Anda mempunyai pesanan pada produk yang sama, <a href="/payment/'.$checkOrderHeader->id.'">lihat pesanan</a>.');
                    return redirect('/detail-product/'.$productId);
                }
            }

            // exit;

            $dataHeader = [
                'customer_code' => $user->code,
                'date' => date('Y-m-d'),
            ];
            
            $getIdHeader = SalesOrder::insertGetId($dataHeader);

            $dataDetail = [
                'sales_order_id' => $getIdHeader,
                'customer_code' => $user->code,
                'product_id' => $productId,
                'date' => date('Y-m-d'),
            ];

            SalesOrderDetail::create($dataDetail);

            return redirect('/payment/'.$getIdHeader);
            
        }
        
    }

}
