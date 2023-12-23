<?php

namespace App\Http\Controllers;

use App\Models\RequestOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomOrdersController extends Controller
{
    function index() {
        $filename = 'request_order';
        $filename_script = getContentScript(true, $filename);

        $user = Auth::guard('admin')->user();
        $data = RequestOrder::with('customers')->orderBy('code', 'DESC')->get();
        // dd($data);
        return view('admin-page.'.$filename, [
            'script' => $filename_script,
            'title' => 'Request Order',
            'auth_user' => $user,
            'resultData' => $data
        ]);
    }

    function DetailRequest(string $order_code) {
        dd($order_code);
    }

    public function updateStatusDelivery(Request $request) {

        $data = ['delivery' => $request->delivery];
        $result = RequestOrder::find($request->code)->update($data);
        if($result) {
            $request->session()->flash('success', 'Transaksi berhasil diperbaharui');
        } else {
            $request->session()->flash('success', 'Proses gagal, Hubungi administrator');
        }
        return redirect('/request-order');
    }
}
