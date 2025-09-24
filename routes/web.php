<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;;
use App\Http\Controllers\AdminConsumerController;
use App\Http\Controllers\PlumberController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminReadingController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\WaterRateController;
use App\Http\Controllers\AccountantController;
use App\Http\Controllers\AccountantDashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AccountManagementController;
use App\Http\Controllers\ConsumerAuthController;
use App\Http\Controllers\ConsumerController;
use App\Http\Controllers\ReadingController;
use App\Http\Controllers\ConsumerDashboardController;
use App\Http\Controllers\ConsumerBillingController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\PaymentHistoryController;
use App\Http\Controllers\OnlinePaymentController;
use App\Http\Controllers\AccountantManageController;
use App\Http\Controllers\DisconnectController;

Route::get('/admin-register', [AuthController::class, 'showRegistrationForm'])->name('admin-register');
Route::post('/admin-register', [AuthController::class, 'register']);

Route::get('/admin-login', [AuthController::class, 'showLoginForm'])->name('admin-login');
Route::post('/admin-login', [AuthController::class,'login']);

Route::get('/admin-dashboard', [AuthController::class,'showDashboard'])->name('admin-dashboard');
Route::post('/admin-dashboard', [AuthController::class,'dashboard']);

Route::get('/admin-dashboard', [DashboardController::class, 'index']);

Route::get('/admin-consumer', [AdminConsumerController::class, 'index'])->name('admin-consumer');
   Route::prefix('admin-consumer')->group(function() {
   Route::post('/', [AdminConsumerController::class, 'store'])->name('admin.consumer.store');
    Route::get('/create', [AdminConsumerController::class, 'create'])->name('admin.consumer.create'); // Show create form
    Route::get('/{adminConsumer}/edit', [AdminConsumerController::class, 'edit'])->name('admin.consumer.edit'); // Show edit form
    Route::get('/{adminConsumer}', [AdminConsumerController::class, 'show'])->name('admin.consumer.show');
   Route::put('/{adminConsumer}', [AdminConsumerController::class, 'update'])->name('admin.consumer.update');
    Route::patch('/{adminConsumer}', [AdminConsumerController::class, 'update']); // Alternative to PUT
    Route::delete('/{adminConsumer}', [AdminConsumerController::class, 'destroy'])->name('admin.consumer.destroy');
});

Route::get('/admin-plumber-consumer', [AuthController::class, 'showPlumberConsumerForm'])->name('admin.plumber-consumer');
Route::post('admin-plumber-consumer', [AuthController::class, 'plumberconsumer']);

 Route::get('/consumers/{consumer}/last-reading', [BillingController::class, 'getLastReading']);
Route::get('/billing/last-reading/{consumerId}', [BillingController::class, 'getLastReading'])->name('billing.lastReading');


Route::get('/', [BillingController::class, 'index']);
Route::get('/create', [BillingController::class, 'create']);
Route::post('/', [BillingController::class, 'store']);
Route::resource('billings', BillingController::class);
Route::get('/billings', [BillingController::class, 'index'])->name('billings.index');
Route::post('/billings/{billing}/disconnect', [BillingController::class, 'disconnect'])->name('billings.disconnect');

    Route::get('/water-rates', [AuthController::class,'showRatesForm'])->name('water-rates');
    Route::post('/water-rates', [AuthController::class,'rates']);
   // Water Rates API Routes
    Route::get('/water-rates/all', [WaterRateController::class, 'getAllRates']);
    Route::post('/water-rates/calculate', [WaterRateController::class, 'calculateBill']);
    
    Route::get('/admin-consumer-form', [AuthController::class, 'showManageConsumerForm'])->name('admin-consumer-form');
    Route::post('admin-consumer-form', [AuthController::class, 'manageconsumer']);
    
    Route::get('water-rates', [WaterRateController::class, 'index'])->name('water-rates.index');
    Route::post('water-rates', [WaterRateController::class, 'store'])->name('water-rates.store');
    Route::get('water-rates/create', [WaterRateController::class, 'create'])->name('water-rates.create');
    Route::get('water-rates/{waterRate}/edit', [WaterRateController::class, 'edit'])->name('water-rates.edit');
    Route::put('water-rates/{waterRate}', [WaterRateController::class, 'update'])->name('water-rates.update');
    Route::delete('water-rates/{waterRate}', [WaterRateController::class, 'destroy'])->name('water-rates.destroy');
    
    Route::get('/admin-plumber-dashboard', [AuthController::class,'showPlumberForm'])->name('admin.plumber-dashboard');
    Route::post('/admin-plumber-dashboard', [AuthController::class,'plumber']);
   
    Route::get('/admin-accountant-dashboard', [AuthController::class, 'showAccountantForm'])->name('admin.accountant-dashboard');
    Route::post('/admin-accountant-dashboard', [AuthController::class,'accountant']);

    Route::get('/admin-accountant-consumer', [AuthController::class, 'showAccountantConsumerForm'])->name('admin.accountant-consumer');
    Route::post('/admin-accountant-consumer', [AuthController::class,'accountantconsumer']);
    
     Route::get('/admin-accountant-reports', [AuthController::class, 'showAccountantReportsForm'])->name('admin.accountant-reports');
    Route::post('/admin-accountant-reports', [AuthController::class,'accountantreports']);

    Route::get('/consumer-portal', [AuthController::class, 'showConsumerPortalForm'])->name('consumer-portal');
    Route::post('/consumer-portal', [AuthController::class,'consumerportal']);
    
    Route::get('/admin-accountant', [AuthController::class, 'showAdminAccountant'])->name('admin-accountant');
    Route::post('/admin-accountant', [AuthController::class,'adminaccountant']);

    Route::get('/consumer-history', [AuthController::class, 'showHistoryForm'])->name('consumer-history');
    Route::post('/consumer-history', [AuthController::class,'consumerhistory']);
    
    Route::get('/consumer-dashboard', [AuthController::class, 'showPaymentForm'])->name('consumer-dashboard');
    Route::post('/consumer-dashboard', [AuthController::class,'consumerpayment']);

    Route::get('/consumer-history', [ConsumerController::class, 'history'])->name('consumer.history');

    Route::get('/consumer-paid', [AuthController::class, 'showPaidForm'])->name('consumer-paid');
    Route::post('/consumer-paid', [AuthController::class,'consumerpaid']);

    Route::get('/online-billing', [AuthController::class, 'showOnlineBillingForm'])->name('online-billing');
    Route::post('/online-billing', [AuthController::class,'onlinebilling']);
   
    Route::get('/verify', [AuthController::class, 'showVerifyForm'])->name('verify');
    Route::post('/verify', [AuthController::class,'consumerverify']);
   
  // Verification Routes
Route::get('/verify', [AuthController::class, 'showVerifyForm'])->name('verify');
Route::post('/verify-code', [AuthController::class, 'verifyCode'])->name('verify-code');
Route::post('/resend-code', [AuthController::class, 'resendCode'])->name('resend-code');

    Route::prefix('consumer')->group(function () {
    Route::get('/login', [ConsumerAuthController::class, 'showLoginForm'])->name('consumer.login');
    Route::post('/login', [ConsumerAuthController::class, 'login']);
    Route::post('/logout', [ConsumerAuthController::class, 'logout'])->name('consumer.logout');
});

// For consumers
Route::middleware(['auth:consumer'])->group(function () {
    Route::get('/consumer/current-billing/{consumerId}', [ConsumerBillingController::class, 'getCurrentBilling']);
});

// Consumer Auth Routes
Route::get('/consumer/login', [ConsumerAuthController::class, 'showLoginForm'])->name('consumer.login');
Route::post('/consumer/login', [ConsumerAuthController::class, 'login']);
Route::post('/consumer/logout', [ConsumerAuthController::class, 'logout'])->name('consumer.logout');

// Consumer Dashboard Routes
Route::middleware(['consumer.auth'])->group(function () {
    Route::get('/consumer/history', [ConsumerDashboardController::class, 'history'])->name('consumer.history');
    Route::get('/consumer/payment', [ConsumerDashboardController::class, 'payment'])->name('consumer.payment');
    Route::post('/consumer/payment', [ConsumerDashboardController::class, 'processPayment']);
});

    Route::get('/admin-accountant-dashboard', [AccountantDashboardController::class, 'index']);
   // routes/web.php
Route::get('/accountant/reports/data', [ReportController::class, 'data'])->name('accountant.reports.data');

Route::get('/accountant/billings/{billing}/details', [AccountantController::class, 'getBillingDetails'])
    ->name('accountant.billings.details');


Route::get('/accountant/billings/{id}/receipt', [AccountantController::class, 'getReceiptData'])->name('accountant.billings.receipt');

Route::prefix('accountant')->group(function() {
    Route::get('/billings', [AccountantController::class, 'index'])->name('accountant.billings');
    Route::get('/billings/data', [AccountantController::class, 'getBillings'])->name('accountant.billings.data');
    Route::get('/billings/last-reading/{consumerId}', [AccountantController::class, 'getLastReading']);
    Route::post('/billings', [AccountantController::class, 'store'])->name('accountant.billings.store');
    Route::get('/billings/{id}/edit', [AccountantController::class, 'edit'])->name('accountant.billings.edit');
    Route::put('/billings/{id}', [AccountantController::class, 'update'])->name('accountant.billings.update');
    Route::delete('/billings/{id}', [AccountantController::class, 'destroy'])->name('accountant.billings.destroy');
});

Route::middleware(['auth'])->group(function () {
    // Consumer billing routes
    Route::get('/consumer/billings/data', [ConsumerBillingController::class, 'getBillingsData'])->name('consumer.billings.data');
    Route::get('/consumer/billings/{id}/details', [ConsumerBillingController::class, 'getBillingDetails']);
    Route::post('/consumer/payments/process', [PaymentController::class, 'processPayment']);
    Route::get('/consumer/payments/receipt/{id}', [PaymentController::class, 'getReceipt']);
});
// In your routes/web.php
Route::post('/payments/process', [PaymentController::class, 'processPayment'])->name('payments.process');

Route::get('/consumer/payment-history', [PaymentHistoryController::class, 'index'])->name('consumer.payment.history');

// In your routes/web.php file
Route::prefix('account-management')->group(function() {
    Route::get('/', [AccountManagementController::class, 'index'])->name('account-management.index');
    Route::get('/data', [AccountManagementController::class, 'data'])->name('account-management.data');
    Route::post('/', [AccountManagementController::class, 'store'])->name('account-management.store');
    Route::get('/{id}/edit', [AccountManagementController::class, 'edit'])->name('account-management.edit');
    Route::put('/{id}', [AccountManagementController::class, 'update'])->name('account-management.update');
    Route::delete('/{id}', [AccountManagementController::class, 'destroy'])->name('account-management.destroy');
});

Route::get('/admin-plumber-dashboard', [ReadingController::class, 'index']);

Route::get('/admin-plumber', [PlumberController::class, 'index'])->name('admin-plumber');
    Route::prefix('admin-plumber')->group(function() {
    Route::post('/', [PlumberController::class, 'store'])->name('admin.plumber.store');
    Route::get('/create', [PlumberController::class, 'create'])->name('admin.plumber.create');
    Route::get('/{plumber}/edit', [PlumberController::class, 'edit'])->name('admin.plumber.edit');
    Route::get('/{plumber}', [PlumberController::class, 'show'])->name('admin.plumber.show');
    Route::put('/{plumber}', [PlumberController::class, 'update'])->name('admin.plumber.update');
    Route::patch('/{plumber}', [PlumberController::class, 'update']);
   Route::delete('/{plumber}', [PlumberController::class, 'destroy'])->name('admin.plumber.destroy');
});

// Online Billing Routes
Route::prefix('online-billing')->group(function () {
    Route::get('/', [OnlineBillingController::class, 'index'])->name('online-billing.index');
    Route::get('/data', [OnlineBillingController::class, 'index'])->name('online-billing.data');
    Route::get('/consumers', [OnlineBillingController::class, 'getConsumers'])->name('online-billing.consumers');
    Route::post('/', [OnlineBillingController::class, 'store'])->name('online-billing.store');
    Route::get('/{id}', [OnlineBillingController::class, 'show'])->name('online-billing.show');
    Route::put('/{id}', [OnlineBillingController::class, 'update'])->name('online-billing.update');
    Route::delete('/{id}', [OnlineBillingController::class, 'destroy'])->name('online-billing.destroy');
    
    // Add these additional routes that your JavaScript expects:
    Route::get('/last-reading/{id}', [OnlineBillingController::class, 'getLastReading'])->name('online-billing.last-reading');
    Route::post('/calculate', [OnlineBillingController::class, 'calculateWaterBill'])->name('online-billing.calculate');
});

Route::post('/consumer/login', [ConsumerAuthController::class, 'login']);
Route::post('/consumer/logout', [ConsumerAuthController::class, 'logout']);

// Protected consumer routes
Route::middleware('auth:consumer')->group(function () {
    Route::get('/consumer/billings', [ConsumerBillingController::class, 'index']);
    Route::post('/consumer/payment/process', [ConsumerPaymentController::class, 'processPayment']);
});


// Consumer authentication routes
Route::get('/consumer/login', [ConsumerAuthController::class, 'showLoginForm'])->name('consumer.login');
Route::post('/consumer/login', [ConsumerAuthController::class, 'login']);
Route::post('/consumer/logout', [ConsumerAuthController::class, 'logout'])->name('consumer.logout');

// Consumer dashboard (protected)
Route::get('/consumer/dashboard', [ConsumerAuthController::class, 'dashboard'])->name('consumer.dashboard');


    Route::get('/paymentVerificationSection', [AuthController::class, 'showPaymentVerificationForm'])->name('paymentVerificationSection');
    Route::post('/paymentVerificationSection', [AuthController::class,'paymentverification']);

// Consumer dashboard route (protected)
Route::get('/consumer-dashboard', function() {
    // Check if consumer is authenticated
    if (!Auth::guard('consumer')->check()) {
        return redirect('/consumer/login');
    }

    // Get consumer and their bills
    $account = Auth::guard('consumer')->user();
    $consumer = $account->consumer;
    $bills = Billing::where('consumer_id', $consumer->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
    
    return view('auth.consumer-dashboard', [
        'consumer' => $consumer,
        'bills' => $bills
    ]);
})->name('consumer.dashboard');

// Consumer payment routes
Route::prefix('consumer')->group(function () {
    Route::post('/payment/submit', [OnlinePaymentController::class, 'store'])->name('consumer.payment.submit');
});
Route::get('/admin/payments/datatable', [OnlinePaymentController::class, 'datatable'])
    ->name('admin.payments.datatable');
Route::get('/admin/payments', [OnlinePaymentController::class, 'datatable'])->name('admin.payments.index');
// Admin/Accountant payment management routes
Route::prefix('admin')->group(function () {
    Route::get('/payments', [OnlinePaymentController::class, 'index'])->name('admin.payments.index');
    Route::get('/payments/{id}', [OnlinePaymentController::class, 'show'])->name('admin.payments.show');
    Route::post('/payments/{id}/verify', [OnlinePaymentController::class, 'verify'])->name('admin.payments.verify');
});

// Accountant Management Routes
Route::get('/admin-accountant', [AccountantManageController::class, 'index'])->name('admin.accountant');
Route::post('/admin-accountant', [AccountantManageController::class, 'store']);
Route::get('/admin-accountant/{id}/edit', [AccountantManageController::class, 'edit']);
Route::put('/admin-accountant/{id}', [AccountantManageController::class, 'update']);
Route::delete('/admin-accountant/{id}', [AccountantManageController::class, 'destroy']);

Route::get('/consumer-information', [AuthController::class, 'showInformation'])->name('consumer-information');
    Route::post('/consumer-information', [AuthController::class,'consumerinformation']);

//Route::get('/admin-plumber-disconnection', [AuthController::class, 'showDisconnectionForm'])->name('admin-plumber-disconnection');
//Route::post('/admin-plumber-disconnection', [AuthController::class,'admindisconnection']);


Route::prefix('disconnect')->group(function () {
    Route::get('/', [DisconnectController::class, 'index'])->name('disconnect.index');
    Route::post('/{billingId}', [DisconnectController::class, 'disconnect'])->name('disconnect.store');
    Route::put('/reconnect/{disconnectionId}', [DisconnectController::class, 'reconnect'])->name('disconnect.reconnect');
    Route::get('/status/{consumerId}', [DisconnectController::class, 'getDisconnectionStatus'])->name('disconnect.status');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', function () {
    return view('welcome');
    
});
