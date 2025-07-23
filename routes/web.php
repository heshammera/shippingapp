<?php
use App\Http\Controllers\ProductPriceController;

use App\Http\Controllers\Reports\ExpensesReportController;
use App\Http\Controllers\Reports\CollectionsReportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Services\GoogleSheetImporter;
use App\Http\Controllers\{
    DashboardController,
    ShipmentController,
    ShipmentExportController,
    ShippingCompanyController,
    CollectionController,
    DeliveryAgentController,
    ExpenseController,
    AccountingController,
    ReportController,
    ProductController,
    ShipmentStatusController,
    SettingController,
    UserController,
    RoleController
};


Route::prefix('backup')->middleware(['auth'])->group(function () {
    Route::get('/', [\App\Http\Controllers\BackupController::class, 'index'])->name('backup.index');
    Route::post('/create', [\App\Http\Controllers\BackupController::class, 'create'])->name('backup.create');
    Route::post('/create-db', [\App\Http\Controllers\BackupController::class, 'createDb'])->name('backup.create.db');
    Route::get('/list', [\App\Http\Controllers\BackupController::class, 'listBackups'])->name('backup.list');
    Route::get('/download/{file}', [\App\Http\Controllers\BackupController::class, 'download'])->name('backup.download');
    Route::get('/delete/{file}', [\App\Http\Controllers\BackupController::class, 'delete'])->name('backup.delete');
    Route::get('/restore/{file}', [\App\Http\Controllers\BackupController::class, 'restore'])->name('backup.restore');
});
Route::middleware('auth')->post('/user/theme-color', [UserController::class, 'updateThemeColor']);

Route::get('/products/{product}/prices', [ProductPriceController::class, 'edit'])->name('product.prices.edit');
Route::post('/products/{product}/prices', [ProductPriceController::class, 'update'])->name('product.prices.update');
Route::get('/admin/sync-google-sheet', function () {
    app(GoogleSheetImporter::class)->importOrders();
    return back()->with('success', '✅ تمت مزامنة الشحنات من Google Sheet بنجاح!');
})->middleware('auth');

// ✅ صفحة الدخول
Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');

    Route::post('/login', function (Request $request) {
        if (Auth::attempt(['name' => $request->name, 'password' => $request->password])) {
            return redirect()->intended('/redirect-by-role');
        }
        return back()->with('error', 'بيانات الدخول غير صحيحة');
    })->name('login.post');
});


// ✅ تسجيل الخروج
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// ✅ التوجيه حسب الدور
Route::get('/redirect-by-role', function () {
    $user = auth()->user();
    return match ($user->role) {
        'admin' => redirect()->route('dashboard'),
        'accountant' => redirect()->route('accounting.index'),
        'moderator' => redirect()->route('shipments.create'),
        'delivery_agent' => redirect()->route('shipments.index'),
                'viewer' => redirect()->route('dashboard'),
        default => redirect()->route('login'),
    };
})->middleware('auth')->name('redirect.by.role');

// ✅ الصفحة الرئيسية توجّه حسب الدور إذا كان المستخدم مسجل
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('redirect.by.role')
        : redirect()->route('login');
});

// ✅ لوحة التحكم للأدمن فقط
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// ✅ صفحة الشحنات (لكل المسجلين)
Route::get('/shipments', [ShipmentController::class, 'index'])
    ->middleware(['auth'])
    ->name('shipments.index');

Route::get('/test-email', function () {
    try {
        Mail::raw('هذه رسالة تجريبية من إعدادات SMTP.', function ($message) {
            $message->to('hesham.mera@gmail.com') // ← غيّرها لبريدك
                    ->subject('اختبار البريد من Laravel');
        });

        return 'تم إرسال البريد بنجاح.';
    } catch (\Exception $e) {
        return 'فشل الإرسال: ' . $e->getMessage();
    }
})->middleware('auth');

    Route::get('/products/{product}/details', function(App\Models\Product $product) {
        return response()->json([
            'price' => $product->price,
            'colors' => explode(',', $product->colors),
            'sizes' => explode(',', $product->sizes)
        ]);
    });
// ---------------------------
// للمستخدم المسجل
// ---------------------------
Route::middleware(['web', 'auth', 'prevent.viewer.modification', 'check.expiration', 'restrict.by.role', 'role.access'])->group(function () {
    //Route::delete('/shipments/bulk-delete', [\App\Http\Controllers\ShipmentController::class, 'bulkDelete'])->name('shipments.bulk-delete');
    Route::get('/delivery-agents/{agent}/shipments', [\App\Http\Controllers\DeliveryAgentController::class, 'shipments'])->name('delivery-agents.shipments');
Route::post('/shipments/{shipment}/quick-update', [ShipmentController::class, 'quickUpdate'])->name('shipments.quick-update');
Route::get('/shipments', [ShipmentController::class, 'index'])->name('shipments.index');
Route::resource('collections', CollectionController::class);
Route::get('/collections/{collection}', [CollectionController::class, 'show'])->name('collections.show');

Route::delete('/shipments/{shipment}/quick-delete', [ShipmentController::class, 'quickDelete'])->name('shipments.quick-delete');


Route::get('/settings/google-sheet', [SettingController::class, 'googleSheet'])->name('settings.google_sheet');
Route::post('/settings/google-sheet', [SettingController::class, 'updateGoogleSheet'])->name('settings.google_sheet.update');



    Route::delete('/shipments/bulk-delete', [ShipmentController::class, 'bulkDelete'])->name('shipments.bulk-delete');
Route::post('/shipments/{shipment}/update-status', [ShipmentController::class, 'updateStatus'])->name('shipments.update-status');
Route::post('/shipments/{shipment}/assign-agent', [ShipmentController::class, 'assignAgent']);
//Route::post('/shipments/{shipment}/assign-agent', [\App\Http\Controllers\ShipmentController::class, 'assignAgent']);
Route::post('/shipments/{shipment}/assign-agent', [ShipmentController::class, 'assignAgent'])->name('shipments.assignAgent');

    Route::post('/shipments/import', [ShipmentController::class, 'import'])->name('shipments.import');
    Route::get('/shipments/import', [ShipmentController::class, 'importForm'])->name('shipments.import.form');

    Route::get('/settings/notifications', [SettingController::class, 'notifications'])->name('settings.notifications');
    Route::get('/settings/system', [SettingController::class, 'system'])->name('settings.system');
    Route::post('/settings/create-backup', [SettingController::class, 'createBackup'])->name('settings.create-backup');

    Route::post('/settings/update-notifications', [SettingController::class, 'updateNotifications'])->name('settings.update-notifications');
    Route::post('/settings/update-system', [SettingController::class, 'updateSystem'])->name('settings.update-system');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');

    Route::post('/settings/update-system', [SettingController::class, 'updateSystem'])
    ->name('settings.update-system');
    Route::post('/settings/update-notifications', [SettingController::class, 'updateNotifications'])
    ->name('settings.update-notifications');

    Route::post('/shipments/{shipment}/update-return-date', [ShipmentController::class, 'updateReturnDate'])
    ->name('shipments.update-return-date');


//    Route::get('/expenses/{expense}', [ExpenseController::class, 'show'])->name('expenses.show');
//Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
//Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
//Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
Route::match(['put', 'post'], '/shipments/{shipment}/update-status', [ShipmentController::class, 'updateStatus'])->name('shipments.update-status');
Route::put('shipments/{shipment}/update-delivery', [ShipmentController::class, 'updateDeliveryDetails'])->name('shipments.updateDeliveryDetails');

    Route::get('expenses', [ExpensesReportController::class, 'index'])->name('expenses.report');
    Route::get('/reports/collections', [CollectionsReportController::class, 'index'])->name('collections.report');
    Route::get('/collections/{collection}/edit', [CollectionController::class, 'edit'])->name('collections.edit');
Route::delete('/collections/{collection}', [CollectionController::class, 'destroy'])->name('collections.destroy');
        Route::get('/shipments/export-print', [\App\Http\Controllers\ShipmentExportController::class, 'exportPrint'])->name('shipments.export.print');
//Route::post('shipments/{shipment}/quick-update', [ShipmentController::class, 'quickUpdate'])->name('shipments.quick-update');
            Route::get('shipments/excel', [ReportController::class, 'exportShipmentsExcel'])->name('reports.shipments.excel');
    Route::get('/shipments/excel', [ReportController::class, 'exportShipmentsExcel'])->name('shipments.excel');
Route::post('/shipments/update-shipping-company/{shipment}', [ShipmentController::class, 'updateShippingCompany'])->name('shipments.update-company');
            Route::get('/shipments/print-selected', [ShipmentController::class, 'printSelected'])->name('shipments.print.selected');
            
        Route::get('/shipments/print-invoices', [ShipmentController::class, 'printInvoices'])->name('shipments.print.invoices');
        Route::get('import/form', [ShipmentController::class, 'importForm'])->name('shipments.import.form');
    Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
    Route::get('/collections/create', [CollectionController::class, 'create'])->name('collections.create');
    Route::get('/collections/{collection}', [CollectionController::class, 'show'])->name('collections.show');

    Route::post('/collections', [CollectionController::class, 'store'])->name('collections.store');
    Route::put('{shipment}/update-status', [ShipmentController::class, 'updateStatus'])->name('shipments.update-status');
    //Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::resource('expenses', ExpenseController::class);
    //Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    //Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
Route::post('/shipments/{shipment}/quick-update', [ShipmentController::class, 'quickUpdate']);
    Route::get('/accounting', [AccountingController::class, 'index'])->name('accounting.index');
    Route::get('/accounting/treasury-report', [AccountingController::class, 'treasuryReport'])->name('accounting.treasury-report');
Route::post('/shipments/{shipment}/quick-update', [ShipmentController::class, 'quickUpdate'])->name('shipments.quickUpdate');

    Route::resource('shipments', ShipmentController::class);
    Route::resource('products', ProductController::class);
    Route::resource('delivery-agents', DeliveryAgentController::class);
    Route::resource('shipment-statuses', ShipmentStatusController::class);
    Route::resource('shipping-companies', ShippingCompanyController::class);

    Route::get('shipments/export-print', [ShipmentExportController::class, 'exportPrint'])->name('shipments.export.print');

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/daily', [ReportController::class, 'daily'])->name('daily');
        Route::get('/monthly', [ReportController::class, 'monthly'])->name('monthly');
        Route::get('/shipments', [ReportController::class, 'shipments'])->name('shipments');
        Route::get('/shipments/excel', [ReportController::class, 'exportShipmentsExcel'])->name('shipments.excel');
        Route::get('/shipments/pdf', [ReportController::class, 'exportShipmentsPdf'])->name('shipments.pdf');

        Route::get('/expenses', [ExpensesReportController::class, 'index'])->name('expenses');
        Route::get('/expenses/excel', [ExpensesReportController::class, 'exportExcel'])->name('expenses.excel');
        Route::get('/expenses/pdf', [ExpensesReportController::class, 'exportPdf'])->name('expenses.pdf');

        Route::get('/collections/excel', [CollectionsReportController::class, 'exportExcel'])->name('collections.excel');
        Route::get('/collections/pdf', [CollectionsReportController::class, 'exportPdf'])->name('collections.pdf');

        Route::get('/treasury/excel', [ReportController::class, 'exportTreasuryExcel'])->name('treasury.excel');
        Route::get('/treasury/pdf', [ReportController::class, 'exportTreasuryPdf'])->name('treasury.pdf');
    });

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);

    Route::prefix('settings')->controller(SettingController::class)->name('settings.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'update')->name('update');
        Route::get('notifications', 'notifications')->name('notifications');
        Route::post('notifications', 'updateNotifications')->name('notifications.update');
        Route::get('system', 'system')->name('system');
        Route::post('system', 'updateSystem')->name('system.update');
        Route::get('create-backup', 'createBackup')->name('create-backup');
    });
});
