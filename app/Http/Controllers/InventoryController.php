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

    function calculateStock() {
        // $Inventory = Inventory::get();

        $products = Product::get();
        $stockProduct = 0;
        foreach ($products as $items) {
            $id_product = $items->id;
            
            $InventoryUpdates = InventoryUpdates::with('products')->get();
            echo '<pre>';
            echo '<b>Inventory Update</b>';
            echo '</b>';
            foreach ($InventoryUpdates as $item1) {

                if($id_product == $item1->product_id) {
                    $stockProduct += $item1->qty;
                    echo '<pre>';
                    print_r($item1->products->name);
                    echo '</pre>';
                    echo '<pre>';
                    print_r($stockProduct);
                    echo '</pre>';
                }
                // Inventory::where(['product_id' => $item->product_id])->update(['stock' => $item->qty]);
            }

            $PurchaseOrderDetail = PurchaseOrderDetail::with('products')->get();
            echo '<hr>';
            echo '<pre>';
            echo '<b>Pembelian</b>';
            echo '</b>';
            foreach ($PurchaseOrderDetail as $item2) {
                if($id_product == $item2->product_id) {
                    $stockProduct += $item2->qty;
                    echo '<pre>';
                    print_r($item2->products->name);
                    echo '</pre>';
                    echo '<pre>';
                    print_r($stockProduct);
                    echo '</pre>';
                }

            }

            
            // echo '<h2>';
            // echo '<pre>';
            // print_r($items->name);
            // echo '</pre>';
            // echo '<pre>';
            // print_r($stockProduct);
            // echo '</pre>';
            // echo '</h2>';
            
        }
        exit;
        echo '<hr>';
        echo '<pre>';
        echo '<b>Penjualan</b>';
        echo '</b>';
        $SalesOrderDetail = SalesOrderDetail::with('products', 'sales_order')->get();
        foreach ($SalesOrderDetail as $item) {
            if($item->sales_order->status == 'Y') {
                echo '<pre>';
                print_r($item->products->name);
                echo '</pre>';
                echo '<pre>';
                print_r($item->qty);
                echo '</pre>';
            }
        }
        die();
    }
}
