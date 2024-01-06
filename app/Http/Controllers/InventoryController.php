<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\InventoryUpdates;
use App\Models\SalesOrderDetail;
use App\Models\PurchaseOrderDetail;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    function index() {
        $filename = 'inventory';
        $filename_script = getContentScript(true, $filename);
        // $this->calculateStock();
        $user = Auth::guard('admin')->user();
        $data = Inventory::with('products.categories', 'products.units', 'products.sizes', 'products.brands')->orderBy('id', 'DESC')->get();
        // dd($data);
        return view('admin-page.'.$filename, [
            'script' => $filename_script,
            'title' => 'Persediaan Barang',
            'auth_user' => $user,
            'resultData' => $data
        ]);
    }
    
    function detailInventory(int $id) {
        $filename = 'detail_inventory';
        $filename_script = getContentScript(true, $filename);

        $user = Auth::guard('admin')->user();
        $data = Inventory::with('products.categories', 'products.units', 'products.sizes', 'products.brands')->where('id', $id)->first();
        // dd($data);
        return view('admin-page.'.$filename, [
            'script' => $filename_script,
            'title' => 'Persediaan Barang Detail',
            'auth_user' => $user,
            'resultData' => $data
        ]);
    }

}
