<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderDetail;

class PurchaseOrderDetailController extends Controller
{
    function storeData(Request $request) {

        $data = [
            'purchase_order_id' => $request->purchase_order_id,
            'sequence' => $request->sequence,
            'product_id' => $request->product_id,
            'date' => $request->date,
            'qty' => $request->qty_dt,
            'price' => cleanSpecialChar($request->price_dt),
        ];

        $insertedId = DB::table('purchase_order_details')->insertGetId($data);
        
        if($insertedId) {
            return response()->json(['status' => 'success', 'dataId' => $insertedId]);
        } else {
            return response()->json(['status' => 'failed']);
        }
    }

    function getAllDetail(int $id) {
        
        $resuldData = PurchaseOrderDetail::with('products.categories', 'products.sizes', 'products.units')->where(['purchase_order_id' => $id])
                        ->orderBy('sequence', 'ASC')->get();

        if ($resuldData) {
            return response()->json(['status' => 'success', 'data' => $resuldData]);
        } else {
            return response()->json(['status' => 'failed']);
        }

    }
}
