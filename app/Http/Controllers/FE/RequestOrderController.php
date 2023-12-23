<?php

namespace App\Http\Controllers\FE;

use App\Models\Size;
use App\Models\DeliveryType;
use App\Models\RequestOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RequestOrderController extends Controller
{
    function index() {
        $user = Auth::guard('customer')->user();
        $filename = 'request_order';
        $filename_script = getContentScript(false, $filename);

        // $result = RequestOrder::with('customers')->where(['customer_code' => $user->code])->get();
        // dd($result);
        $sizes = Size::get();

        if(ucwords(trim($user->city)) == 'Tangerang') {
            $deliveryId = 1;
        } else {
            $deliveryId = 2;
        }

        $delivery = DeliveryType::find($deliveryId);
        return view('user-page.'.$filename, [
            'script' => $filename_script,
            'title' => 'Custom Permintaan',
            'user' => $user,
            'sizes' => $sizes,
            'delivery' => $delivery,
        ]);
    }

    function store(Request $request) {
        // dd($request);
        $user = Auth::guard('customer')->user();

        $validateData = $request->validate([
            'size_id' => 'required',
            'qty' => 'required',
            'image'         => 'file|image|max:1024'
        ]);

        $data = [
            'code' => $request->code,
            'date' => $request->date,
            'customer_code' => $user->code,
            'description' => $request->description,
            'size_id' => $request->size_id,
            'qty' => $request->qty,
            'charge' => $request->charge,
        ];

        if($request->file('image')) {
            $data['image'] = $request->file('image')->store('request-images');
        }
        
        $result = RequestOrder::create($data);
        if($result) {
            $request->session()->flash('success', 'Akun berhasil dibuat');
            return redirect('/products');
        } else {
            die('Proses gagal, Hubungi administrator');
        }
    }
}
