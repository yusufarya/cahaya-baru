<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryTransactionController extends Controller
{
    function index() {
        $filename = 'deliveries';
        $filename_script = getContentScript(true, $filename);

        $user = Auth::guard('admin')->user();
        $data = SalesOrder::with('customers')
                            ->select('sales_orders.*', 'order_payments.status as status_payment')
                            ->leftJoin('order_payments', 'sales_orders.code', '=', 'order_payments.order_code')
                            ->orderBy('sales_orders.code', 'DESC')->where(['sales_orders.status'=> 'Y'])->get();
        // dd($data);
        return view('admin-page.'.$filename, [
            'script' => $filename_script,
            'title' => 'Pengiriman Pesanan ',
            'auth_user' => $user,
            'resultData' => $data
        ]);
    }
}
