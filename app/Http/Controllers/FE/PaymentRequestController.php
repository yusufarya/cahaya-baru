<?php

namespace App\Http\Controllers\FE;

use App\Models\Customer;
use App\Models\SalesOrder;
use App\Models\OrderPayment;
use App\Models\RequestOrder;
use Illuminate\Http\Request;
use App\Models\PaymentMethod; 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PaymentRequestController extends Controller
{
    function index(string $code_tr) {
        
        $filename = 'payment_req';
        $filename_script = getContentScript(false, $filename);

        $code = Auth::guard('customer')->user()->code;
        $data = Customer::find($code)->first();  
        
        $result = RequestOrder::with('customers', 'sizes')->find($code_tr); 
        $checkOrderPayment = OrderPayment::where(['order_code' => $code_tr])->first();
        
        if($checkOrderPayment) {
            return redirect('/pay-order/'.$code_tr);
        }

        return view('user-page.'.$filename, [
            'script' => $filename_script,
            'title' => 'Detail Pesanan Saya',
            'auth_user' => $data,
            'resultData' => $result,
        ]);
    }

    function prosesPayOrder(Request $request) {
        // dd($request);
        $req_order_code = $request->code;
         
        $orderHD = [
            'nett' => cleanSpecialChar($request->netto)
        ]; 
        // dd($request);

        $user = Customer::find(Auth::guard('customer')->user()->code)->first();

        RequestOrder::where(['customer_code' => $user->code, 'code' => $req_order_code])->update($orderHD); 
        
        $order_payment_code = getLastPayCode();

        $dataInsert = [
            'code' => $order_payment_code,
            'order_code' => $req_order_code,
            'date' => date('Y-m-d h:i:s')
        ];

        $checkOrderPayment = OrderPayment::where(['order_code' => $req_order_code])->first();

        if($checkOrderPayment) {
            $result = OrderPayment::where(['order_code' => $req_order_code])->update(['date' => date('Y-m-d h:i:s')]);
        } else {
            $result = OrderPayment::create($dataInsert);
        }

        if($result) {
            return response()->json(['status' => 'success', 'code' => $req_order_code]);
        } else {
            return response()->json(['status' => 'failed']);
        }
        
    }

    function payOrder(string $req_order_code) {
        $getData = RequestOrder::find($req_order_code);
        $orderHD = [
            'nett' => ($getData->qty*$getData->price)+$getData->charge
        ];

        $code_user = Auth::guard('customer')->user()->code;

        RequestOrder::where(['customer_code' => $code_user, 'code' => $req_order_code])->update($orderHD); 
        
        if($req_order_code) {
            $filename = 'pay_order_req';
            $filename_script = getContentScript(false, $filename);
            
            $payment_method = PaymentMethod::get();  
            
            $result = RequestOrder::with('sizes', 'customers')->where(['customer_code' => $code_user, 'code' => $req_order_code])->first();
            // dd($result);
            if(!$result) {
                return redirect('/');    
            }
    
            $checkOrderPayment = OrderPayment::where(['order_code' => $req_order_code])->first();
    
            // dd($result);
            return view('user-page.'.$filename, [
                'script' => $filename_script,
                'title' => 'Pembayaran',
                'auth_user' => Auth::guard('customer')->user(),
                'resultData' => $result,
                'payment_method' => $payment_method,
                'dp' => $checkOrderPayment ? $checkOrderPayment->dp : false,
                'payment_id' => $checkOrderPayment ? $checkOrderPayment->payment_method_id : ''
            ]);
        } else {
            return redirect('/');
        }

    }

    function updatePaymentMethod(Request $request) {
        $result = OrderPayment::where(['order_code' => $request->order_code])->update(['payment_method_id' => $request->payment_method]);
        return response()->json(['result' => $result]);
    }

    function uploadImgPayment(Request $request) {
        // dd($request);
        $order_code = $request->order_code;
        $image = $request->file('images')->store('payment-upload');

        $data = ['image' => $image, 'dp' => 0];

        $updated = OrderPayment::where(['order_code' => $order_code])->update($data);

        if($updated) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'failed']);
        }

    }

    function cancelOrders(Request $request) {
        // dd($request->code);
        RequestOrder::where(['code' => $request->code])->delete();
        return true;
    }
}
