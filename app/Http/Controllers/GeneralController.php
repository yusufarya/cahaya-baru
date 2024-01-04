<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use App\Models\SalesOrderDetail;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderDetail;
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
            if($checkOrderHeader) {
                if($checkOrderHeader->status == 'N') {
                    $salesOrderDetails = SalesOrderDetail::where('product_id', $productId)->first();
                    dd($salesOrderDetails);
                    if($salesOrderDetails != NULL) {
                        if($productId == $salesOrderDetails->product_id) {
                            $request->session()->flash('message', 'Anda mempunyai pesanan pada produk yang sama, <a href="/payment/'.$checkOrderHeader->code.'">lihat pesanan</a>.');
                            return redirect('/detail-product/'.$productId);
                        }
                    }
                }
            }

            // exit;

            $dataHeader = [
                'customer_code' => $user->code,
                'date' => date('Y-m-d'),
            ];

            if($checkOrderHeader) {
                if($checkOrderHeader->status == 'Y') {
                    $code = getLasCodeTransaction('S');
        
                    $dataHeader['code'] = $code;
                    SalesOrder::create($dataHeader);
                } else {
                    $code = $checkOrderHeader->code;
                }
            } else {
                $code = getLasCodeTransaction('S');
    
                $dataHeader['code'] = $code;
                SalesOrder::create($dataHeader);
            }
            
            $dataDetail = [
                'sequence' => 1,
                'sales_order_code' => $code,
                'product_id' => $productId,
                'date' => date('Y-m-d'),
            ];

            SalesOrderDetail::create($dataDetail);

            return redirect('/payment/'.$code);
            
        }
        
    }
    
    function checkTransactionProduct(Request $request) {
        $isValid = true;
        $result = PurchaseOrderDetail::where(['product_id' => $request->product_id])->count();
        if($result) {
            $typeTrans = "Pembelian";
            $isValid = false;
        }
        $result = SalesOrderDetail::where(['product_id' => $request->product_id])->count();
        // dd($result);
        if($result) {
            $typeTrans = "Penjualan";
            $isValid = false;
        }
        $result = Inventory::where(['product_id' => $request->product_id])->count();
        // dd($result);
        if($result) {
            $typeTrans = "Update Stock";
            $isValid = false;
        }
        if($isValid === true) {
            return response()->json(['status' => 'success', 'message' => "Produk berhasil di hapus"]);
        } else {
            return response()->json(['status' => 'failed', 'message' => "Produk tidak dapat dihapus, karna telah digunakan pada transaksi ". $typeTrans]);
        }
    }

}
