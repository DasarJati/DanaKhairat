<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Models\AhliKariah;
use App\Models\Tanggungan;

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AjkRegisterController;
use App\Http\Controllers\AjkApprovalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AjkStatusController;
use App\Http\Controllers\AjkDashboardController;
use App\Http\Controllers\PofileController;
use App\Http\Controllers\AjkSetupController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TuntutanController;
use App\Http\Controllers\ApproveTuntutanController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\AjkProfileController;
use App\Http\Controllers\UserApprovalController;
use App\Http\Controllers\TanggunganController;
use App\Http\Controllers\AjkMasjidController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\AdminwalletController;
use App\Http\Controllers\PublicRegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MasjidController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ToyyibpayController;
use App\Http\Controllers\ChangeKetuaController;
use App\Http\Controllers\BulkRegisterController;

use Illuminate\Support\Facades\Artisan;


Route::get('/pakej', function () {
    return view('register.pakej');
})->name('pakej');


Route::get('/', function () {
    return view('public');
})->name('public');

// terus load view tanpa controller
// Route::get('/public', function () {
//     return view('public');
// })->name('public');

Route::get('/pilihan', function () {
    return view('pic.option');
})->name('pilihan');

// REGISTER PAGE
Route::get('/ajk/register', [AjkRegisterController::class, 'showForm'])->name('ajk.register');
Route::post('/register/complete', [AjkRegisterController::class, 'completeRegistration'])->name('ajk.complete');

Route::get('/user/register', [RegisterController::class, 'showform'])->name('ahliregister');
Route::post('/userregister', [RegisterController::class, 'store'])->name('user.register');
Route::get('/get-bank/{masjidId}', [RegisterController::class, 'getBankByMasjid']);

// AUTH ROUTES
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// REGISTER Ahli Khairat
Route::get('/get-hargakhairat/{masjid}', [RegisterController::class, 'getHargaKhairat']);
Route::get('/khairat/receipt/{tuntutanId}', [AjkDashboardController::class, 'generateReceipt'])->name('khairat.generate.receipt');


// DEPENDENT DROPDOWN AJAX ROUTES
Route::get('/get-poskods', [LocationController::class, 'getPoskods']);
Route::get('/get-bandar/{poskod}', [LocationController::class, 'getBandarByPoskod']);
Route::get('/get-poskod-by-negeri/{negeriId}', [LocationController::class, 'getPoskodByNegeri']);
Route::get('/get-kariah/{poskodId}/{bandar}', [LocationController::class, 'getKariahByPoskod']);
Route::get('/test-database', [LocationController::class, 'testDatabase']);
Route::get('/debug-poskod', [LocationController::class, 'debugPoskod']);

Route::get('/user/get-poskod/{negeri_id}', [LocationController::class, 'getPoskod']);
Route::get('/user/get-bandar/{poskod}', [LocationController::class, 'getBandar']);
Route::get('/user/get-masjid/{poskod_id}/{bandar}', [LocationController::class, 'getMasjid']);
Route::get('/get-masjid-by-poskod/{poskodId}/{bandar}', [LocationController::class, 'getMasjidByPoskod']);
//AJK
// Route::get('/get-kariahs/{negeri}', [App\Http\Controllers\AjkRegisterController::class, 'getKariahsByNegeri']);
// Route::get('/get-poskods', [AjkRegisterController::class, 'getPoskods']);
// Route::get('/get-bandar/{poskod}', [AjkRegisterController::class, 'getBandarByPoskod']);

// CHECK STATUS
Route::get('/checkstatus', [AjkStatusController::class, 'index'])->name('check.status');
Route::post('/checkstatus', [AjkStatusController::class, 'check'])->name('status.check');

// FORGOT PASSWORD
Route::get('/forgot-password', [AuthController::class, 'showEmailForm']);
Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);
Route::get('/reset-password', [AuthController::class, 'showResetForm'])->name('password.reset')->middleware('signed');
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// ADMIN ROUTES

Route::get('/admin/main', [AdminController::class, 'main'])->name('admin.main');

Route::get('/admin/dashboard', [AjkApprovalController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/list-masjid', [AjkApprovalController::class, 'listMasjid'])->name('admin.list.masjid');
Route::get('/admin/view/{id}', [AjkApprovalController::class, 'view'])->name('admin.view');
Route::post('/admin/approve-register/{id}', [AjkApprovalController::class, 'approveRegister'])->name('admin.approve.register');
Route::post('/admin/reject/{id}', [AjkApprovalController::class, 'reject'])->name('admin.reject');
Route::get('/details/{id}', [AjkApprovalController::class, 'details'])->name('admin.details');
Route::get('/admin/list-kariah', [AdminController::class, 'listKariah'])
    ->name('admin.listkariah');

Route::get('/adminwallet', [AdminwalletController::class, 'wallet'])->name('admin.wallet');
Route::get('/admin/payment/{id}/upload', [AdminwalletController::class, 'showUpload'])->name('payment.upload');
Route::post('/admin/payment/{id}/upload', [AdminwalletController::class, 'uploadResit'])->name('resit.upload');

Route::get('/user-approval', [UserApprovalController::class, 'index'])->name('admin.user');
Route::get('/user-approval/{id}', [UserApprovalController::class, 'show'])->name('user.view');
Route::put('/admin/user/{id}/approve', [UserApprovalController::class, 'approve'])->name('user.approve');
Route::put('/admin/user/{id}/reject', [UserApprovalController::class, 'reject'])->name('user.reject');


Route::post('/payment/callback', [ToyyibpayController::class, 'paymentCallback'])->name('payment.callback');

Route::middleware(['auth'])->group(function () {

    // ===== PUBLIC ROUTES (No checks needed) =====
    // Initiate Payment
    Route::post('/pay', [SubscriptionController::class, 'pay'])->name('subscription.pay');

    Route::get('/subscription/view', [SubscriptionController::class, 'displayPackage'])->name('subscription.view');
    // User returns here after bank screen
    Route::get('/payment/return', [ToyyibpayController::class, 'paymentReturn'])->name('payment.return');
    // routes/web.php
    Route::match(['get', 'post'], '/subscription/callback', [SubscriptionController::class, 'paymentCallback'])->name('subscription.callback');
    Route::get('/subscription/return', [SubscriptionController::class, 'paymentReturn'])->name('subscription.return');
    // Subscription routes - accessible to everyone (even without setup or subscription)
    Route::prefix('subscription')->name('subscription.')->group(function () {
        Route::get('/package', [SubscriptionController::class, 'showPackage'])->name('package');
        Route::post('/store', [SubscriptionController::class, 'store'])->name('store');
        Route::get('/status', [SubscriptionController::class, 'showStatus'])->name('status');
        Route::post('/renew', [SubscriptionController::class, 'renew'])->name('renew');
    });

    // Setup routes - accessible to AJK users without masjid
    Route::get('/ajk/setup', [AjkSetupController::class, 'index'])->name('ajk.setup');
    Route::post('/ajk/setup', [AjkSetupController::class, 'store'])->name('ajk.setup.store');

    // Profile routes - accessible to all authenticated users
    Route::get('/ajk/profile', [AjkProfileController::class, 'index'])->name('ajk.profile');
    Route::put('/profile/update-password', [AjkProfileController::class, 'updatePassword'])->name('password.update');

    // ===== PROTECTED ROUTES =====
    // FIRST: Check subscription (keutamaan)
    // SECOND: Check setup (kelengkapan data)

    Route::middleware(['check.subscription'])->group(function () {




        // DASHBOARD
        Route::get('/pic/dashboard', [AjkDashboardController::class, 'dashboard'])->name('pic.dashboard');

        // MEMBERS
        Route::get('/senaraiahli', [AjkDashboardController::class, 'index'])->name('senariahli');
        Route::get('/list', [AjkDashboardController::class, 'index'])->name('kariah.list');
        Route::get('/senarai/pengesahan', [UserApprovalController::class, 'indexAJK'])->name('kariah.list.pengesahan');


        Route::get('/export-excel', [UserApprovalController::class, 'exportExcel'])->name('kariah.export.excel');
        Route::get('/export-all', [UserApprovalController::class, 'exportAll'])->name('kariah.export.all');
        Route::post('/send-bulk-email', [UserApprovalController::class, 'sendBulkEmail'])->name('kariah.send.bulk.email');
        Route::get('/send-individual-email/{id}', [UserApprovalController::class, 'sendIndividualEmail'])->name('kariah.send.individual.email');


        Route::get('/butiran/{id}', [AjkDashboardController::class, 'butiranAhli'])->name('butiran.ahli');
        Route::get('/khairat/{type}/{id}', [AjkDashboardController::class, 'butiranKhairat'])->name('khairat.butiran');
        Route::put('/khairat/update-status/tuntutan/{id}', [AjkDashboardController::class, 'updateStatusTuntutan'])->name('khairat.update.status.tuntutan');
        Route::get('/pic/IDlist', function () {
            return view('pic.members');
        })->name('pic.members');

        Route::get('/bulk-register', [BulkRegisterController::class, 'index'])->name('ajk.bulk-register.index');
        Route::get('/bulk-register/template', [BulkRegisterController::class, 'downloadTemplate'])->name('ajk.bulk-register.template');
        Route::post('/bulk-register/upload', [BulkRegisterController::class, 'upload'])->name('ajk.bulk-register.upload');
        Route::post('/bulk-register/preview', [BulkRegisterController::class, 'preview'])->name('ajk.bulk-register.preview');

        Route::get('/approveKariah/{id}', [UserApprovalController::class, 'indexApprove'])->name('index.approve.kariah');
        Route::post('/approveKariah/{id}', [UserApprovalController::class, 'approveKariah'])->name('approve.kariah');
        Route::post('/rejectKariah/{id}', [UserApprovalController::class, 'rejectKariah'])->name('reject.kariah');

        // CLAIMS (TUNTUTAN)
        Route::get('/ajk/tuntutan', [ApproveTuntutanController::class, 'index'])->name('tuntut');
        Route::get('/ajk/keluarga/{user}', [ApproveTuntutanController::class, 'show'])->name('ajk.keluarga');
        Route::get('/ajk/tuntutan/{id}', [ApproveTuntutanController::class, 'showForm'])->name('ajk.approve');
        Route::put('/tuntutan/pengurusan/{id}', [ApproveTuntutanController::class, 'pengurusan'])->name('ajk.pengurusan');
        Route::put('/ajk/tuntutan/{id}/update', [ApproveTuntutanController::class, 'update'])->name('ajk.update');
        Route::put('/ajk/pembayaran/{id}', [PembayaranController::class, 'update'])->name('pembayaran.update');
        Route::get('/manual/{id}', [TuntutanController::class, 'manualForm'])->name('khairat.manual');
        Route::post('/manual/tuntutan', [ApproveTuntutanController::class, 'storeManual'])->name('khairat.manual.store');

        Route::get('/khairat/lihat-kematian/{userId}', [ApproveTuntutanController::class, 'viewDeathRecord'])->name('khairat.viewDeath');
        Route::post('/khairat/tambah-kematian-tanggungan', [ApproveTuntutanController::class, 'storeDeathRecordTanggungan'])->name('khairat.storeDeathTanggungan');
        Route::get('/khairat/lihat-kematian-tanggungan/{tanggunganId}', [ApproveTuntutanController::class, 'viewDeathRecordTanggungan'])->name('khairat.viewDeathTanggungan');
        Route::post('/khairat/death-store/ahli', [ApproveTuntutanController::class, 'storeDeathRecordAhli'])->name('khairat.death.store.ahli');
        Route::post('/khairat/death-store/luar', [ApproveTuntutanController::class, 'storeDeathRecordLuar'])->name('khairat.death.store.luar');
        // Route::post('/khairat/tambah-kematian', [ApproveTuntutanController::class, 'storeDeathRecord'])->name('khairat.storeDeath');

        Route::get('/change-ketua', [ChangeKetuaController::class, 'index'])->name('change-ketua.index');
        Route::get('/change-ketua/{ahliId}/form', [ChangeKetuaController::class, 'showChangeForm'])->name('change-ketua.form');
        Route::post('/change-ketua/{ahliId}/change', [ChangeKetuaController::class, 'changeKetua'])->name('change-ketua.change');


        // REPORTS
        Route::get('/laporanahli', function () {
            return view('kariah.laporan');
        })->name('laporanahli');

        // PAYMENT/FINANCE
        Route::get('/yuran', function () {
            return view('payment.yuran');
        })->name('yuran');

        Route::get('/finance', [WalletController::class, 'index'])->name('finance');
        Route::get('/finance-dana', [WalletController::class, 'indexDana'])->name('finance.dana');
        Route::post('/finance-dana/status', [WalletController::class, 'updatePaymentStatus'])->name('finance.status.update');
        Route::post('/update/finance-dana', [WalletController::class, 'updateDana'])->name('finance.dana.update');

        Route::get('/report', function () {
            return view('payment.report');
        })->name('report');

        // MASJID SETTINGS
        Route::get('/masjid/info', [AjkMasjidController::class, 'index'])->name('masjid.index');
        Route::get('/masjid/edit', [AjkMasjidController::class, 'edit'])->name('masjid.edit');
        Route::put('/masjid/update', [AjkMasjidController::class, 'update'])->name('masjid.update');
        Route::put('/masjid/update-harga', [AjkMasjidController::class, 'updateHarga'])->name('masjid.update-harga');
        Route::put('/masjid/update-sumbangan', [AjkMasjidController::class, 'updateSumbangan'])->name('masjid.update-sumbangan');
        Route::put('/masjid/update-bank', [AjkMasjidController::class, 'updateBank'])->name('masjid.update-bank');
        Route::put('/masjid/update-policy-header', [AjkMasjidController::class, 'updatePolicyHeader'])->name('masjid.update-policy-header');
        Route::put('/masjid/update-policy/{id}', [AjkMasjidController::class, 'updatePolicy'])->name('masjid.update-policy');
        Route::put('/masjid/update-image', [AjkMasjidController::class, 'updateImage'])->name('masjid.update-image');
    });

    // USER routes (no checks needed for regular users)
    Route::get('/dashboard', [UserController::class, 'index'])->name('user.dashboard');
    Route::get('/ahli-keluarga', [UserController::class, 'Keluarga'])->name('ahlikeluarga.user');
});

Route::prefix('daftar')->name('public.daftar.')->group(function () {
    // Form with masjid name/slug in URL
    Route::get('/{masjidName}', [PublicRegisterController::class, 'create'])->name('create');

    // Store registration
    Route::post('/{slug}', [PublicRegisterController::class, 'store'])->name('store');

    // Generate shareable link (AJAX endpoint)
    Route::post('/generate-link', [PublicRegisterController::class, 'generateShareableLink'])->name('generate-link');
});

Route::get('/success-daftar', [PublicRegisterController::class, 'success'])->name('success-daftar');

Route::get('/user/check-ic', [RegisterController::class, 'checkIc']);



// ============== USER ROUTES (Ahli Khairat) ==============
Route::middleware(['auth:web'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('user.dashboard');
    Route::get('/ahliKeluarga', [UserController::class, 'Keluarga'])->name('ahlikeluarga.index');
    Route::get('/tanggungan/create', [TanggunganController::class, 'create'])->name('tanggungan.create');
    Route::post('/tanggungan', [TanggunganController::class, 'store'])->name('tanggungan.store');
    // TUNTUTAN ROUTES
    Route::get('/tuntutan/{user}', [UserController::class, 'tuntutan'])->name('user.tuntutan');
    Route::get('/tuntutan/borang/{ahli_id}', [TuntutanController::class, 'modalForm'])->name('tuntutan.form');
    Route::post('/tuntutan/store', [TuntutanController::class, 'store'])->name('tuntutan.store');
    Route::get('/tuntutan/{tuntutan}', [TuntutanController::class, 'show'])->name('tuntutan.show');
    Route::put('/tuntutan/{tuntutan}', [TuntutanController::class, 'update'])->name('tuntutan.update');
    Route::get('/tuntutankhairat', [DashboardController::class, 'tuntut'])->name('user.rekodtuntut');
    Route::get('/kewangan/{user}', [UserController::class, 'transactions'])->name('user.transactions');
    Route::post('/user/{user}/transactions', [UserController::class, 'storeTransaction'])->name('user.transactions.store');
    Route::put('/tuntutan/approve/{id}', [ApproveTuntutanController::class, 'approve'])->name('tuntutan.approve');
    Route::put('/tuntutan/reject/{id}', [ApproveTuntutanController::class, 'reject'])->name('tuntutan.reject');
});

// ============== STAFF ROUTES ==============
Route::middleware(['auth:staff'])->group(function () {
    // BAHAGIAN ADMIN
});
Route::get('/debug-keluarga', [UserController::class, 'Keluarga']);

// Add this route for checking IC existence
Route::post('/check-ic-exists', function (Request $request) {
    $icNumber = $request->ic_number;
    $masjidId = $request->masjid_id;

    // Check in your users/ahli table
    $exists = \App\Models\User::where('ic_number', $icNumber)
        ->where('masjid_id', $masjidId)
        ->exists();

    return response()->json(['exists' => $exists]);
})->name('check.ic.exists');

// Search Ahli Khairat
Route::get('/search-ahli', function (Request $request) {
    $query = $request->get('q');
    $ahli = AhliKariah::where('masjid_id', auth()->user()->masjid_id)
        ->where(function ($q) use ($query) {
            $q->where('nama', 'like', "%{$query}%")
                ->orWhere('ic', 'like', "%{$query}%");
        })
        ->limit(10)
        ->get(['id', 'nama', 'ic']);

    return response()->json($ahli);
})->middleware('auth');

// Get Tanggungan by User ID
Route::get('/get-tanggungan/{userId}', function ($userId) {
    $tanggungan = Tanggungan::where('user_id', $userId)->get();
    return response()->json($tanggungan);
})->middleware('auth');

Route::get('/artisan-clear', function () {

    Artisan::call('optimize:clear');

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');

    return nl2br(
        Artisan::output() .
            "\nAll caches cleared and optimized successfully."
    );
});

Route::get('/show-log', function () {

    $logFile = storage_path('logs/laravel.log');

    $lines = file($logFile);

    return '<pre>' .
        implode('', array_slice($lines, -100))
        . '</pre>';
});

Route::get('/test-mailtrap', function () {
    try {
        Mail::raw('This is a test email from Dana Khairat system. Time: ' . now(), function ($message) {
            $message->to('muhdsyazwan552@gmail.com')
                ->subject('Test Mailtrap Connection');
        });

        return "✅ Email sent successfully! Check your Mailtrap inbox.";
    } catch (\Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
});
