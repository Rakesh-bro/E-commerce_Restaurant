<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BkashController;
use App\Http\Controllers\BkashRefundController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\SslCommerzPaymentController;
use App\Http\Controllers\HomeController;

// Public site Dashboard
use App\Http\Controllers\DashboardController;

// Admin
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\ProductImportController;

// ----------------------
// Public Routes
// ----------------------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/register/confirm', [HomeController::class, 'register'])->name('register/confirm');
Route::get('/redirects', [HomeController::class, 'redirects']);

Route::get('/menu', [MenuController::class, 'menu'])->name('menu');
Route::get('/trace-my-order', [ShipmentController::class, 'trace'])->name('trace-my-order');
Route::get('/my-order', [ShipmentController::class, 'my_order'])->name('my-order');

Route::get('/rate/{id}', [HomeController::class, 'rate'])->name('rate');
Route::get('/top/rated', [HomeController::class, 'top_rated'])->name('top/rated');
Route::get('/edit/rate/{id}', [HomeController::class, 'edit_rate'])->name('edit/rate');
Route::post('/coupon/apply', [ShipmentController::class, 'coupon_apply'])->name('coupon/apply');
Route::get('/delete/rate', [HomeController::class, 'delete_rate'])->name('delete/rate');
Route::get('/rate/confirm/{value}', [HomeController::class, 'store_rate'])->name('rate.confirm');

Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/menu/{product}', [CartController::class, 'store'])->name('cart.store');
Route::post('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('/mails/shipped/{total}', [ShipmentController::class, 'place_order'])->name('mails.shipped');
Route::post('/confirm_place_order/{total}', [ShipmentController::class, 'send'])->name('confirm_place_order');
Route::post('/checkout/{total}', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/reserve/confirm', [HomeController::class, 'reservation_confirm'])->name('reserve.confirm');
Route::post('/trace/confirm', [ShipmentController::class, 'trace_confirm'])->name('trace.confirm');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// ----------------------
// Payment Routes
// ----------------------
Route::get('ssl/pay', [BkashController::class, 'ssl']);
Route::get('ssl/pay2', [BkashController::class, 'ssl2']);

Route::group(['middleware' => ['customAuth']], function () {
    Route::post('bkash/get-token', [BkashController::class, 'getToken'])->name('bkash-get-token');
    Route::post('bkash/create-payment', [BkashController::class, 'createPayment'])->name('bkash-create-payment');
    Route::post('bkash/execute-payment', [BkashController::class, 'executePayment'])->name('bkash-execute-payment');
    Route::get('bkash/query-payment', [BkashController::class, 'queryPayment'])->name('bkash-query-payment');
    Route::post('bkash/success', [BkashController::class, 'bkashSuccess'])->name('bkash-success');

    Route::get('bkash/refund', [BkashRefundController::class, 'index'])->name('bkash-refund');
    Route::post('bkash/refund', [BkashRefundController::class, 'refund'])->name('bkash-refund');
});

// SSLCOMMERZ
Route::get('/example1', [SslCommerzPaymentController::class, 'exampleEasyCheckout']);
Route::get('/example2', [SslCommerzPaymentController::class, 'exampleHostedCheckout']);
Route::post('/pay', [SslCommerzPaymentController::class, 'index']);
Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax']);
Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);
Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);

// ----------------------
// Admin Routes
// ----------------------
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'is_admin'])
    ->group(function () {

         Route::prefix('products')->name('products.')->group(function () {
        Route::get('/import', [ProductImportController::class, 'importForm'])->name('import.form');
    });

        // Admin Dashboard
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', AdminProductController::class); // must be here
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    });

        // AdminController routes (all prefixed with /admin)
        Route::get('/home', [AdminController::class, 'home'])->name('home');
        Route::get('/food-menu', [AdminController::class, 'food_menu'])->name('food-menu');
        Route::get('products/import', [AdminProductController::class, 'importForm'])->name('products.import.form');
        Route::get('/orders/process', [AdminController::class, 'orders_process'])->name('orders.process');
        Route::get('/orders/cancel', [AdminController::class, 'orders_cancel'])->name('orders.cancel');

        Route::get('/add/menu', [AdminController::class, 'add_menu'])->name('add.menu');
        Route::get('/add/chef', [AdminController::class, 'add_chef'])->name('add.chef');

        Route::get('/chefs', [AdminController::class, 'chefs'])->name('chefs');
        Route::get('/orders-incomplete', [AdminController::class, 'order_incomplete'])->name('orders.incomplete');
        Route::get('/orders-complete', [AdminController::class, 'order_complete'])->name('orders.complete');

        Route::get('/reservation', [AdminController::class, 'reservation'])->name('reservation');
        Route::get('/coupon', [AdminController::class, 'coupon_show'])->name('coupon');
        Route::get('/show', [AdminController::class, 'admin_show'])->name('show');
        Route::get('/customer', [AdminController::class, 'user_show'])->name('customer');

        Route::get('/charge', [AdminController::class, 'charge'])->name('charge');
        Route::get('/banner/all', [AdminController::class, 'banner'])->name('banner.all');
        Route::get('/customize', [AdminController::class, 'customize'])->name('customize');
        Route::get('/add/banner', [AdminController::class, 'banner_add'])->name('add.banner');

        // POST routes
        Route::post('/menu/add/process', [AdminController::class, 'menu_add_process'])->name('menu.add.process');
        Route::post('products/import', [AdminProductController::class, 'import'])->name('products.import');
        Route::post('/chef/add/process', [AdminController::class, 'chef_add_process'])->name('chef.add.process');
        Route::post('/menu/edit/process/{id}', [AdminController::class, 'menu_edit_process'])->name('menu.edit.process');
        Route::post('/edit/chef/process/{id}', [AdminController::class, 'chef_edit_process'])->name('chef.edit.process');
        Route::post('/invoice/approve/{id}', [AdminController::class, 'invoice_approve'])->name('invoice.approve');
        Route::post('/invoice/location/edit', [AdminController::class, 'edit_order_location'])->name('invoice.location.edit');
        Route::post('/admin-add-process', [AdminController::class, 'add_admin_process'])->name('admin.add.process');
        Route::post('/add-delivery-boy-process', [AdminController::class, 'add_delivery_boy_process'])->name('delivery_boy.add.process');
        Route::post('/banner/add/process', [AdminController::class, 'banner_add_process'])->name('banner.add.process');
        Route::post('/banner/edit/process/{id}', [AdminController::class, 'banner_edit_process'])->name('banner.edit.process');
        Route::post('/coupon-add-process', [AdminController::class, 'add_coupon_process'])->name('coupon.add.process');
        Route::post('/coupon-edit-process/{id}', [AdminController::class, 'edit_coupon_process'])->name('coupon.edit.process');
        Route::post('/charge-add-process', [AdminController::class, 'add_charge_process'])->name('charge.add.process');
        Route::post('/charge-edit-process/{id}', [AdminController::class, 'edit_charge_process'])->name('charge.edit.process');
        Route::post('/customize_edit_process', [AdminController::class, 'edit_customize_process'])->name('customize.edit.process');

        // GET routes with parameters
        Route::get('/menu/delete/{id}', [AdminController::class, 'menu_delete_process'])->name('menu.delete');
        Route::get('/chef/delete/{id}', [AdminController::class, 'chef_delete_process'])->name('chef.delete');
        Route::get('/menu/edit/{id}', [AdminController::class, 'menu_edit'])->name('menu.edit');
        Route::get('/chef/edit/{id}', [AdminController::class, 'chef_edit'])->name('chef.edit');
        Route::get('/invoice/details/{id}', [AdminController::class, 'invoice_details'])->name('invoice.details');
        Route::get('/invoice/cancel-order/{id}', [AdminController::class, 'invoice_cancel'])->name('invoice.cancel-order');
        Route::get('/invoice/complete/{id}', [AdminController::class, 'invoice_complete'])->name('invoice.complete');
        Route::get('/order/location', [AdminController::class, 'order_location'])->name('order.location');
        Route::get('/delivery-boy', [AdminController::class, 'delivery_boy'])->name('delivery_boy');
        Route::get('/admin/delete/{id}', [AdminController::class, 'delete_admin'])->name('admin.delete');
        Route::get('/admin/edit/{id}', [AdminController::class, 'edit_admin'])->name('admin.edit');
        Route::get('/delivery_boy/delete/{id}', [AdminController::class, 'delete_delivery_boy'])->name('delivery_boy.delete');
        Route::get('/delivery_boy/edit/{id}', [AdminController::class, 'edit_delivery_boy'])->name('delivery_boy.edit');
        Route::get('/admin/banner/edit/{id}', [AdminController::class, 'banner_edit'])->name('banner.edit');
        Route::get('/admin/banner/delete/{id}', [AdminController::class, 'banner_delete_process'])->name('banner.delete');
        Route::get('/admin/coupon/delete/{id}', [AdminController::class, 'delete_coupon'])->name('coupon.delete');
        Route::get('/admin/coupon/edit/{id}', [AdminController::class, 'edit_coupon'])->name('coupon.edit');
        Route::get('/admin/charge/delete/{id}', [AdminController::class, 'delete_charge'])->name('charge.delete');
        Route::get('/admin/charge/edit/{id}', [AdminController::class, 'edit_charge'])->name('charge.edit');
        Route::get('/admin/products/import', [ProductImportController::class, 'importForm'])->name('admin.products.import.form');
        Route::post('/admin/products/import', [ProductController::class, 'importSubmit'])->name('admin.products.import.submit');
