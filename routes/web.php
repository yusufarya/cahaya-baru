<?php

use App\Models\Product;
use App\Http\Controllers\AuthAdmin;
use App\Http\Controllers\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\VendorController;
use App\Http\Controllers\FE\HomeController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ExportDataController;
use App\Http\Controllers\FE\PaymentController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\FE\CustomerController;
use App\Http\Controllers\UpdateStockController;
use App\Http\Controllers\CustomOrdersController;
use App\Http\Controllers\FE\ProductFEController;
use App\Http\Controllers\SalesTransactionReport;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\FE\RequestOrderController;
use App\Http\Controllers\FE\ShoppingCartController;
use App\Http\Controllers\PurchaseTransactionReport;
use App\Http\Controllers\TrainingContentController;
use App\Http\Controllers\FE\PaymentRequestController;
use App\Http\Controllers\DeliveryTransactionController;
use App\Http\Controllers\PurchaseOrderDetailController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/getProducts', function () {
    $resultData = Product::with('categories', 'units', 'sizes', 'brands')->orderBy('id', 'ASC')->get();
    return response()->json(['status' => 'success', 'data' => $resultData]);
});

Route::middleware('guest')->group(function () {
    Route::get('/admin', function () {
        return redirect('/login-admin');
    });
    
    Route::get('/login-admin', [AuthAdmin::class, 'index']);
    Route::post('/login-admin', [AuthAdmin::class, 'auth']);
    Route::get('/register-admin', [AuthAdmin::class, 'register']);
    Route::post('/register-admin', [AuthAdmin::class, 'store']);
});


Route::middleware('admin')->group(function () {
    
    Route::get('/check-product', [GeneralController::class, 'checkTransactionProduct']);
    Route::get('/check-payment', [GeneralController::class, 'checkPayment']);

    Route::get('/profile', [AdminController::class, 'index']);
    Route::get('/dashboard', [Dashboard::class, 'index']);
    
    Route::get('/data-admin', [AdminController::class, 'dataAdmin']);
    Route::get('/getDetailAdmin', [AdminController::class, 'getDetailAdmin']);
    Route::get('/form-add-admin', [AdminController::class, 'addFormAdmin']);
    Route::post('/add-new-admin', [AdminController::class, 'storeAdmin']);
    Route::get('/form-edit-admin/{number}', [AdminController::class, 'editFormAdmin']);
    Route::post('/edit-new-admin', [AdminController::class, 'updateAdmin']);
    Route::delete('/delete-admin/{number}', [AdminController::class, 'deleteAdmin']);

    Route::get('/data-customer', [AdminController::class, 'dataCustomer']);
    Route::get('/detail-customer/{code}', [AdminController::class, 'detailCustomer']); 
    
    // MODUL PERSEDIAAN // 
    Route::resource('/units', UnitController::class)->only("index", "store", "update", "destroy");
    Route::resource('/sizes', SizeController::class)->only("index", "store", "update", "destroy");
    Route::resource('/brands', BrandController::class)->only("index", "store", "update", "destroy");
    Route::resource('/categories', CategoryController::class)->only("index", "store", "update", "destroy");
    Route::resource('/products', ProductController::class);
    Route::resource('/update-stock', UpdateStockController::class);
    
    Route::get('/inventories', [InventoryController::class, 'index']);
    Route::get('/detail-inventory', [InventoryController::class, 'detailInventory']);
    
    // ================ MODUL PEMBELIAN ================= // 
    // LIST DATA VENDOR
    Route::resource('/vendors', VendorController::class)->only("index", "store", "update", "destroy");

    // LIST PURCHASE TRANSACTION
    Route::get('/purchase-order', [PurchaseOrderController::class, 'index']);
    Route::delete('/delete-purchase-order/{code}', [PurchaseOrderController::class, 'deleteData']);
    // FORM ADD //
    Route::get('/purchase-order/create', [PurchaseOrderController::class, 'addData']);
    Route::post('/purchase-order/addDetail', [PurchaseOrderController::class, 'storeData']); // ADD TRANSACTION HEADER //
    // FORM Edit //
    Route::get('/purchase-order/{code}/edit', [PurchaseOrderController::class, 'editData']);
    Route::post('/purchase-order_detail/add', [PurchaseOrderDetailController::class, 'storeData']); // ADD TRANSACTION DETAIL //
    Route::post('/purchase-order_detail/edit', [PurchaseOrderDetailController::class, 'updateData']); // EDIT TRANSACTION DETAIL //
    Route::delete('/purchase-order_detail/delete', [PurchaseOrderDetailController::class, 'removeData']); // DELETE TRANSACTION DETAIL //
    
    Route::get('/purchase-order_detail/checkData', [PurchaseOrderDetailController::class, 'checkData']); // CHECK TRANSACTION DETAIL //
    Route::get('/getPurchase_order_detail/{code}', [PurchaseOrderDetailController::class, 'getAllDetail']); // GET TRANSACTION DETAIL BY PURCHASE CODE //
    
    Route::get('/purchase-report', [PurchaseTransactionReport::class, 'index']); // VIEW REPORT PURCASE TRANSACTION //
    Route::get('/purchase-rpt', [PurchaseTransactionReport::class, 'purchaseReport']); // SROTE REQUEST TO SESSION //
    Route::get('/open-purchase-rpt', [PurchaseTransactionReport::class, 'openPurchaseReport']); // OPEN REPORT PURCASE TRANSACTION //
    Route::get('/export_purchase_report', [ExportDataController::class, 'purchase_export_data']);

    Route::post('/submit-purchase_order', [PurchaseOrderController::class, 'submitData']); // SUBMIT TRANSACTION HEADER //
    
    // =============== MODULE PENJUALAN =============== //
    // LIST SALES ORDER TRANSACTION //
    Route::get('/orders', [SalesOrderController::class, 'index']);
    Route::get('/orders/{code}/detail', [SalesOrderController::class, 'detailOrder']);
    Route::get('/product_order_details/{sequence}', [SalesOrderController::class, 'productOrderDetails']);
    Route::delete('/delete-order/{code}', [SalesOrderController::class, 'deleteData']);
    Route::get('/sales-order', [SalesOrderController::class, 'salesOrder']);
    Route::get('/sales-order/{code}/detail', [SalesOrderController::class, 'detailSalesOrder']);
    Route::post('/update-status-delivery', [SalesOrderController::class, 'updateStatusDelivery']);
    
    Route::get('/request-order', [CustomOrdersController::class, 'index']);
    Route::get('/request-order/{code}/detail', [CustomOrdersController::class, 'detailRequest']);
    Route::post('/update-price-req-order/{code}', [CustomOrdersController::class, 'updatePriceRequest']);
    Route::post('/request-orders/{code}', [CustomOrdersController::class, 'accPaymentRequest']);
    Route::post('/update-req-status-delivery', [CustomOrdersController::class, 'updateStatusDelivery']);

    Route::get('/sales-report', [SalesTransactionReport::class, 'index']); // VIEW REPORT PURCASE TRANSACTION //
    Route::get('/sales-rpt', [SalesTransactionReport::class, 'salesReport']); // SROTE REQUEST TO SESSION //
    Route::get('/open-sales-rpt', [SalesTransactionReport::class, 'openSalesReport']); // OPEN REPORT PURCASE TRANSACTION //
    Route::get('/export_sales_report', [ExportDataController::class, 'sales_export_data']);

    // =============== MODULE PENGIRIMAN ================== //
    Route::resource('/delivery-types', DeliveryController::class)->only("index", "store", "update", "destroy");
    Route::get('/delivery', [DeliveryTransactionController::class, 'index']);
    Route::post('/update-status-delivery-d', [DeliveryTransactionController::class, 'updateStatusDelivery']);

    // =============== MODULE KEUANGAN =============== //
    // PAYMENT METHOD //
    Route::resource('/payment-method', PaymentMethodController::class)->only("index", "store", "update", "destroy");

    Route::get('/settings', [SettingsController::class, 'index']);
    Route::post('/settings', [SettingsController::class, 'update']);
    Route::get('/set-period', [SettingsController::class, 'setPeriod']);
    Route::post('/set-period', [SettingsController::class, 'savePeriodActive']);
    
    Route::post('/logout-admin', [AuthAdmin::class, 'logout']);
});

Route::get('/', [HomeController::class, 'index']);

Route::get('/our-products', [ProductFEController::class, 'index']);
Route::get('/our-products/{id}', [ProductFEController::class, 'index']);
Route::get('/detail-product/{id}', [ProductFEController::class, 'detail']);
Route::get('/getDataProducts', [ProductFEController::class, 'getDataProducts']);

Route::middleware('customer')->group(function () {
    Route::get('/custom-request', [RequestOrderController::class, 'index']);
    Route::post('/send-custom-request', [RequestOrderController::class, 'store']);
    Route::get('/my-req-orders', [RequestOrderController::class, 'myRequestOrders']);

    Route::get('/my-orders', [CustomerController::class, 'myOrders']);
    Route::get('/my-orders-detail/{code}', [CustomerController::class, 'myOrderDetail']);
    Route::post('/acc-order', [CustomerController::class, 'accOrder']);
    
    Route::get('/_profile', [CustomerController::class, 'profile']);
    Route::get('/update-profile', [CustomerController::class, 'updateProfile']);
    Route::put('/update-profile/{number}', [CustomerController::class, 'updateProfileData']);
    Route::get('/checkDataUser/{code}', [GeneralController::class, 'checkDataUser']);

    Route::get('/shopping-cart', [ShoppingCartController::class, 'index']);
    Route::post('/add-to-cart/{id}', [ShoppingCartController::class, 'store']);
    Route::post('/submit-from-cart', [ShoppingCartController::class, 'submitCart']);
    Route::delete('/submit-del-cart', [ShoppingCartController::class, 'deleteCart']);
    
    // PAYMENT SALES ORDER //
    Route::get('/payment/{code}', [PaymentController::class, 'index']);
    Route::get('/payment/{code}/{page}', [PaymentController::class, 'index']);
    Route::post('/prosesPayOrder', [PaymentController::class, 'prosesPayOrder']);
    Route::get('/pay-order/{code}', [PaymentController::class, 'payOrder']);
    Route::post('/dpPayment', [PaymentController::class, 'dpPayment']);
    Route::post('/uploadImgDp', [PaymentController::class, 'uploadImgDp']);
    Route::post('/updatePaymentMethod', [PaymentController::class, 'updatePaymentMethod']);
    Route::post('/uploadImgPayment', [PaymentController::class, 'uploadImgPayment']);
    Route::delete('/cancel-order', [PaymentController::class, 'cancelOrders']);
    
    // PAYMENT REQUEST ORDER //
    Route::get('/payment-req/{code}', [PaymentRequestController::class, 'index']);
    // Route::get('/payment-req/{code}/{page}', [PaymentRequestController::class, 'index']);
    Route::post('/prosesPayOrder-req', [PaymentRequestController::class, 'prosesPayOrder']);
    Route::get('/pay-order-req/{code}', [PaymentRequestController::class, 'payOrder']);
    // Route::post('/updatePaymentMethod', [PaymentRequestController::class, 'updatePaymentMethod']);
    Route::post('/uploadImgPayment-req', [PaymentRequestController::class, 'uploadImgPayment']);
    Route::delete('/cancel-order-req', [PaymentRequestController::class, 'cancelOrders']);
    
    Route::post('/logout', [CustomerController::class, 'logout']);
});

Route::get('/register', [CustomerController::class, 'index']);
Route::post('/register', [CustomerController::class, 'store']);
Route::get('/login', [CustomerController::class, 'login']);
Route::post('/login', [CustomerController::class, 'loginValidation']);
