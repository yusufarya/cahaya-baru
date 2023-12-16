<?php

namespace App\Http\Controllers\FE;

use App\Models\Customer;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use App\Models\SalesOrderDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    function index(int $id) {
        $filename = 'payment';
        $filename_script = getContentScript(false, $filename);

        $code = Auth::guard('customer')->user()->code;
        $data = Customer::where('code', $code)->first();  
        
        $result = SalesOrder::with('salesOrderDetails.products.categories', 'salesOrderDetails.products.sizes', 'salesOrderDetails.products.brands')->where(['id' => $id])->first();
        
        return view('user-page.'.$filename, [
            'script' => $filename_script,
            'title' => 'Profil Saya',
            'auth_user' => $data,
            'resultData' => $result
        ]);
    }

    function payOrder(Request $request, int $sales_order_id) {
        dd($sales_order_id);
    }

    function cancelOrders(Request $request) {
        $salesDetail = SalesOrderDetail::where(['sales_order_id' => $request->id])->delete();
        $sales = SalesOrder::where(['id' => $request->id])->delete();
        return true;
    }
}
