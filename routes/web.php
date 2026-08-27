<?php
use App\Http\Controllers\CampNewController;
use App\Http\Controllers\CampPharmacyController;
use App\Http\Controllers\OperationRegisterController;
use App\Http\Controllers\InpatientRegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\LabSubTestController;
use App\Http\Controllers\LabTestController;
use App\Http\Controllers\ManualLabTestController;
use App\Http\Controllers\ManualRadiologyTestController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\MedicinePurchaseController;
use App\Http\Controllers\MedicineSaleController;
use App\Http\Controllers\OpLabTestController;
use App\Http\Controllers\OpRegisterController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\RadiologyController;
use App\Http\Controllers\RadiologyTestController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\PatientAppointmentController;
use App\Http\Controllers\PatientAuthController;
use App\Http\Controllers\PatientReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\TeamController;

// ---------- LOGIN ----------
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/donar', [HomeController::class, 'donar'])->name('donar');
Route::get('/gallery', [HomeController::class, 'gallery'])->name('gallery');
Route::get('/therapists', [HomeController::class, 'therapists'])->name('therapists');
Route::get('/therapist/{id}', [HomeController::class, 'therapistDetails'])->name('therapist.details');
Route::get('/blog', [HomeController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [HomeController::class, 'blogDetails'])->name('blog.details');
Route::get('/testimonials', [HomeController::class, 'testimonials'])->name('testimonials');
Route::get('/faqs', [HomeController::class, 'faqs'])->name('faqs');
Route::get('/appointment', [HomeController::class, 'appointment'])->name('appointment');
Route::get('/service/{slug}', [HomeController::class, 'serviceDetails'])->name('service.details');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/404', [HomeController::class, 'notFound'])->name('404');


Route::prefix('website')->name('website.')->group(function () {
    Route::resource('slider', SliderController::class);
});

// Add this route group
Route::prefix('website')->name('website.')->group(function () {
    Route::resource('gallery', GalleryController::class);
});

// Add this route group
Route::prefix('website')->name('website.')->group(function () {
    // Service routes
    Route::resource('service', ServiceController::class);
    Route::patch('service/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])
        ->name('service.toggle-status');
});

Route::prefix('website')->name('website.')->group(function () {
    Route::resource('team', TeamController::class);
    Route::patch('team/{team}/toggle-status', [TeamController::class, 'toggleStatus'])
        ->name('team.toggle-status');
});

Route::prefix('website')->name('website.')->group(function () {
    Route::resource('review', ReviewController::class);
    Route::patch('review/{review}/toggle-status', [ReviewController::class, 'toggleStatus'])
        ->name('review.toggle-status');
});
Route::prefix('website')->name('website.')->group(function () {
    Route::resource('donor', DonorController::class);
    Route::patch('donor/{donor}/toggle-status', [DonorController::class, 'toggleStatus'])
        ->name('donor.toggle-status');
});
// Frontend route for storing enquiry
Route::post('/enquiry/store', [EnquiryController::class, 'store'])->name('enquiry.store');

// Admin routes
Route::prefix('website')->name('website.')->group(function () {
    Route::resource('enquiry', EnquiryController::class)->except(['create', 'edit']);
    Route::patch('enquiry/{enquiry}/replied', [EnquiryController::class, 'markAsReplied'])->name('enquiry.replied');
    Route::patch('enquiry/{enquiry}/unread', [EnquiryController::class, 'markAsUnread'])->name('enquiry.unread');
    Route::post('enquiry/bulk-delete', [EnquiryController::class, 'bulkDestroy'])->name('enquiry.bulk-destroy');
});

Route::prefix('website')->name('website.')->group(function () {
    Route::get('notice', [NoticeController::class, 'index'])->name('notice.index');
    Route::put('notice', [NoticeController::class, 'update'])->name('notice.update');
});


// Patient Authentication Routes
Route::get('/patient/login', [PatientAuthController::class, 'showLogin'])->name('patient.login');
Route::post('/patient/login', [PatientAuthController::class, 'login'])->name('patient.login.submit');

// Registration Routes
Route::get('/patient/register', [PatientAuthController::class, 'showRegister'])->name('patient.register');
Route::post('/patient/register', [PatientAuthController::class, 'register'])->name('patient.register.submit');

// OTP Verification Routes
Route::get('/patient/verify-email', [PatientAuthController::class, 'showVerifyEmail'])->name('patient.verify.email');
Route::post('/patient/verify-otp', [PatientAuthController::class, 'verifyOtp'])->name('patient.verify.otp');
Route::post('/patient/resend-otp', [PatientAuthController::class, 'resendOtp'])->name('patient.resend.otp');
Route::prefix('patient')->name('patient.')->group(function () {
    // Forgot Password Routes
    Route::get('/forgot-password', [PatientAuthController::class, 'showForgotPassword'])->name('forgot.password');
    Route::post('/forgot-password', [PatientAuthController::class, 'sendForgotPasswordOtp'])->name('forgot.password.submit');

    // Reset Password OTP Verification
    Route::get('/reset-password/verify', [PatientAuthController::class, 'showResetPasswordVerify'])->name('reset.password.verify');
    Route::post('/reset-password/verify', [PatientAuthController::class, 'verifyResetPasswordOtp'])->name('reset.password.verify.submit');

    // Reset Password Form
    Route::get('/reset-password', [PatientAuthController::class, 'showResetPasswordForm'])->name('reset.password.form');
    Route::post('/reset-password', [PatientAuthController::class, 'resetPassword'])->name('reset.password.submit');

    Route::get('/change-password', [PatientAuthController::class, 'showChangePasswordForm'])->name('changepassword');
    Route::post('/change-password', [PatientAuthController::class, 'updatePassword'])->name('changepassword.update');
});
// Patient Dashboard (Protected)
Route::middleware(['auth:patient'])->group(function () {
    Route::get('/patient/dashboard', [PatientController::class, 'dashboard'])->name('patient.dashboard');
    Route::post('/patient/logout', [PatientAuthController::class, 'logout'])->name('patient.logout');
    Route::get('/patient/profile', [PatientController::class, 'profile'])->name('patient.profile');
});

Route::prefix('online')->name('online.')->middleware(['auth:patient'])->group(function () {

    // Reports
    Route::get('/reports', [PatientReportController::class, 'myReports'])->name('patient.reports');
    Route::get('/report/details', [PatientReportController::class, 'myReport'])->name('patient.report');
    Route::get('/patient/reports', [PatientReportController::class, 'myReports'])->name('patient.reports');

    // View individual records (if you want detailed views)
    Route::get('/op/{opRegister}', [PatientReportController::class, 'viewOpDetails'])->name('patient.op.view');
    Route::get('/ip/{inpatientRegister}', [PatientReportController::class, 'viewIpDetails'])->name('patient.ip.view');
    Route::get('/operation/{operationRegister}', [PatientReportController::class, 'viewOperationDetails'])->name('patient.operation.view');
    Route::get('/radiology/reports', [PatientReportController::class, 'patientRadiologyReports'])->name('radiology.reports');
    Route::get('/lab/reports', [PatientReportController::class, 'patientLabReports'])->name('lab.reports');
    Route::get('/lab/test/{testId}', [PatientReportController::class, 'viewLabTestResult'])->name('lab.test.view');
    Route::get('/lab/test/{testId}/print', [PatientReportController::class, 'printLabTestResult'])->name('lab.test.print');
});
// Patient Appointment Routes
Route::middleware(['auth:patient'])->group(function () {
    // Online Appointments
    Route::get('/patient/appointments', [PatientAppointmentController::class, 'index'])->name('patient.appointments');
    Route::get('/patient/appointments/create', [PatientAppointmentController::class, 'create'])->name('patient.appointments.create');
    Route::post('/patient/appointments', [PatientAppointmentController::class, 'store'])->name('patient.appointments.store');
    Route::get('/patient/appointments/{id}/edit', [PatientAppointmentController::class, 'edit'])->name('patient.appointments.edit');
    Route::put('/patient/appointments/{id}', [PatientAppointmentController::class, 'update'])->name('patient.appointments.update');
    Route::delete('/patient/appointments/{id}', [PatientAppointmentController::class, 'destroy'])->name('patient.appointments.destroy');
    Route::get('/patient/appointments/{id}', [PatientAppointmentController::class, 'show'])->name('patient.appointments.show');
    Route::post('/patient/appointments/{id}/cancel', [PatientAppointmentController::class, 'cancel'])->name('patient.appointments.cancel');

    // Available Slots
    Route::get('/patient/appointments/slots/{doctorId}/{date}', [PatientAppointmentController::class, 'getAvailableSlots'])->name('patient.appointments.slots');
});


Route::get('/login', [UserController::class, 'showLogin'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.validate');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/change-password', [DashboardController::class, 'showChangePassword'])
        ->name('password.change');

    Route::post('/change-password', [DashboardController::class, 'updatePassword'])
        ->name('password.update');
});


// ---------- PROTECTED ADMIN ROUTES ----------
Route::middleware(['auth', 'role:admin'])->group(function () {


    // User Management Routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/success', [UserController::class, 'success'])->name('users.success');
});

// Medicine Management Routes
Route::middleware(['auth', 'role:admin,reception,pharmacy,doctor'])->group(function () {
    Route::get('/medicines', [MedicineController::class, 'index'])->name('medicines.index');
    Route::get('/medicines/create', [MedicineController::class, 'create'])->name('medicines.create');
    Route::post('/medicines', [MedicineController::class, 'store'])->name('medicines.store');
    Route::get('/medicines/{medicine}/edit', [MedicineController::class, 'edit'])->name('medicines.edit');
    Route::put('/medicines/{medicine}', [MedicineController::class, 'update'])->name('medicines.update');
    Route::delete('/medicines/{medicine}', [MedicineController::class, 'destroy'])->name('medicines.destroy');
    Route::get('/medicines/success', [MedicineController::class, 'success'])->name('medicines.success');
});

// Supplier Management Routes
Route::middleware(['auth', 'role:admin,reception,pharmacy,doctor'])->group(function () {
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    Route::get('/suppliers/success', [SupplierController::class, 'success'])->name('suppliers.success');
});

// Radiology Tests Management Routes
Route::middleware(['auth', 'role:admin,radiology'])->group(function () {
    Route::get('/radiology-tests', [RadiologyTestController::class, 'index'])->name('radiology-tests.index');
    Route::get('/radiology-tests/create', [RadiologyTestController::class, 'create'])->name('radiology-tests.create');
    Route::post('/radiology-tests', [RadiologyTestController::class, 'store'])->name('radiology-tests.store');
    Route::get('/radiology-tests/{radiologyTest}/edit', [RadiologyTestController::class, 'edit'])->name('radiology-tests.edit');
    Route::put('/radiology-tests/{radiologyTest}', [RadiologyTestController::class, 'update'])->name('radiology-tests.update');
    Route::delete('/radiology-tests/{radiologyTest}', [RadiologyTestController::class, 'destroy'])->name('radiology-tests.destroy');
    Route::get('/radiology-tests/success', [RadiologyTestController::class, 'success'])->name('radiology-tests.success');
});

// Lab Tests Management Routes
Route::middleware(['auth', 'role:admin,lab,doctor'])->group(function () {
    Route::get('/lab-tests', [LabTestController::class, 'index'])->name('lab-tests.index');
    Route::get('/lab-tests/create', [LabTestController::class, 'create'])->name('lab-tests.create');
    Route::post('/lab-tests', [LabTestController::class, 'store'])->name('lab-tests.store');
    Route::get('/lab-tests/{labTest}/edit', [LabTestController::class, 'edit'])->name('lab-tests.edit');
    Route::put('/lab-tests/{labTest}', [LabTestController::class, 'update'])->name('lab-tests.update');
    Route::delete('/lab-tests/{labTest}', [LabTestController::class, 'destroy'])->name('lab-tests.destroy');
    Route::get('/lab-tests/success', [LabTestController::class, 'success'])->name('lab-tests.success');
});

// routes/web.php
Route::middleware(['auth', 'role:admin,lab,doctor'])->group(function () {
    // Lab Sub Tests Routes
    Route::get('/lab-sub-tests', [LabSubTestController::class, 'index'])->name('lab-sub-tests.index');
    Route::get('/lab-sub-tests/create', [LabSubTestController::class, 'create'])->name('lab-sub-tests.create');
    Route::post('/lab-sub-tests', [LabSubTestController::class, 'store'])->name('lab-sub-tests.store');
    Route::get('/lab-sub-tests/{labTest}/show', [LabSubTestController::class, 'show'])->name('lab-sub-tests.show');
    Route::get('/lab-sub-tests/{labTest}/edit', [LabSubTestController::class, 'edit'])->name('lab-sub-tests.edit');
    Route::put('/lab-sub-tests/{labTest}', [LabSubTestController::class, 'update'])->name('lab-sub-tests.update');
    Route::delete('/lab-sub-tests/{labTest}', [LabSubTestController::class, 'destroy'])->name('lab-sub-tests.destroy');
});

// Patient Management Routes
Route::middleware(['auth', 'role:admin,reception,doctor,lab,pharmacy'])->group(function () {
    // Patient Routes
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
    Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
    Route::get('/patients/success', [PatientController::class, 'success'])->name('patients.success');
    Route::get('/patients/search', [PatientController::class, 'search'])->name('patients.search');

    // OP Register Routes
    Route::get('/op-registers', [OpRegisterController::class, 'index'])->name('op-registers.index');
    Route::get('/op-registers/create', [OpRegisterController::class, 'create'])->name('op-registers.create');
    Route::post('/op-registers', [OpRegisterController::class, 'store'])->name('op-registers.store');
    Route::get('/op-registers/{opRegister}/edit', [OpRegisterController::class, 'edit'])->name('op-registers.edit');
    Route::put('/op-registers/{opRegister}', [OpRegisterController::class, 'update'])->name('op-registers.update');
    Route::delete('/op-registers/{opRegister}', [OpRegisterController::class, 'destroy'])->name('op-registers.destroy');
    Route::get('/op-registers/success', [OpRegisterController::class, 'success'])->name('op-registers.success');
    Route::get('/op-registers/patient-details/{patientId}', [OpRegisterController::class, 'getPatientDetails'])->name('op-registers.patient-details');
    Route::get('report', [OpRegisterController::class, 'report'])->name('report');

    Route::get('/op-registers/{opRegister}/print-clinic', [OpRegisterController::class, 'printOPReport'])->name('op-registers.print-clinic');

    Route::get('op-registers/{opRegister}/preview', [OpRegisterController::class, 'preview'])->name('op-registers.preview');
    Route::get('op-registers/{opRegister}/print-details', [OpRegisterController::class, 'printDetails'])->name('op-registers.print-details');
});
Route::middleware(['auth', 'role:admin,reception,doctor'])->group(function () {

    Route::get('patient-reports', [PatientController::class, 'patientReport'])->name('patient-reports.report');
    Route::get('patient-reports/{patient}/details', [PatientController::class, 'patientDetails'])->name('patient-reports.details');
    // In your routes file
    Route::get('patient-reports/{patient}/print', [PatientController::class, 'patientPrint'])->name('patient-reports.print');
});

Route::middleware(['auth', 'role:admin,reception,pharmacy'])->group(function () {
    // Stock Report - MUST COME FIRST
    Route::get('stock-report', [MedicinePurchaseController::class, 'stockReport'])->name('stock-report');
    Route::get('expiry-report', [MedicinePurchaseController::class, 'expiryReport'])->name('expiry-report');
    Route::get('medicine-purchases/{medicine}/transactions', [MedicinePurchaseController::class, 'medicineTransactions'])->name('medicine-purchases.transactions');
    Route::get('medicine-purchases/transactions/{medicine}/print', [MedicinePurchaseController::class, 'medicineTransactionsPrint'])
        ->name('medicine-purchases.medicine-transactions.print');
    // Medicine Purchase Routes - SPECIFIC ROUTES FIRST
    Route::get('medicine-purchases/create', [MedicinePurchaseController::class, 'create'])->name('medicine-purchases.create');

    // PARAMETERIZED ROUTES LAST
    Route::get('medicine-purchases/{medicinePurchase}', [MedicinePurchaseController::class, 'show'])->name('medicine-purchases.show');
    Route::get('medicine-purchases/{medicinePurchase}/edit', [MedicinePurchaseController::class, 'edit'])->name('medicine-purchases.edit');

    // REST OF THE ROUTES
    Route::get('medicine-purchases', [MedicinePurchaseController::class, 'index'])->name('medicine-purchases.index');
    Route::post('medicine-purchases', [MedicinePurchaseController::class, 'store'])->name('medicine-purchases.store');
    Route::put('medicine-purchases/{medicinePurchase}', [MedicinePurchaseController::class, 'update'])->name('medicine-purchases.update');
    Route::delete('medicine-purchases/{medicinePurchase}', [MedicinePurchaseController::class, 'destroy'])->name('medicine-purchases.destroy');
    // In your routes file
    Route::get('medicine-purchases/{medicinePurchase}/print', [MedicinePurchaseController::class, 'print'])->name('medicine-purchases.print');
    Route::get('/medicine-purchases/supplier/{supplier}/medicines', [MedicinePurchaseController::class, 'getMedicinesBySupplier'])
        ->name('medicine-purchases.get-medicines-by-supplier');
});

// Bulk Order Routes
Route::middleware(['auth', 'role:admin,reception,pharmacy,doctor'])->group(function () {
    // Bulk Order Management
    Route::get('/medicines/bulk-order', [MedicineController::class, 'bulkOrder'])->name('medicines.bulk-order');
    Route::get('/medicines/bulk-order/{supplier}/create', [MedicineController::class, 'createBulkOrder'])->name('medicines.create-bulk-order');
    Route::post('/medicines/bulk-order', [MedicineController::class, 'storeBulkOrder'])->name('medicines.store-bulk-order');
    Route::get('/medicines/bulk-order/report', [MedicineController::class, 'bulkOrderReport'])->name('medicines.bulk-order-report');
    Route::get('/medicines/bulk-order/{id}/edit', [MedicineController::class, 'editBulkOrder'])->name('medicines.edit-bulk-order');
    Route::put('/medicines/bulk-order/{id}', [MedicineController::class, 'updateBulkOrder'])->name('medicines.update-bulk-order');
    Route::delete('/medicines/bulk-order/{id}', [MedicineController::class, 'deleteBulkOrder'])->name('medicines.delete-bulk-order');
    Route::get('/medicines/bulk-order/{id}/print', [MedicineController::class, 'printBulkOrder'])->name('medicines.print-bulk-order');
});

// Medicine Sales CRUD Routes
Route::prefix('medicine-sales')->name('medicine-sales.')->middleware(['auth', 'role:admin,reception,pharmacy'])->group(function () {
    Route::get('/', [MedicineSaleController::class, 'index'])->name('index');
    Route::get('/create', [MedicineSaleController::class, 'create'])->name('create');
    Route::post('/', [MedicineSaleController::class, 'store'])->name('store');
    Route::get('/{medicineSale}', [MedicineSaleController::class, 'show'])->name('show');
    Route::get('/{medicineSale}/edit', [MedicineSaleController::class, 'edit'])->name('edit');
    Route::put('/{medicineSale}', [MedicineSaleController::class, 'update'])->name('update');
    Route::patch('/{medicineSale}', [MedicineSaleController::class, 'update']);
    Route::delete('/{medicineSale}', [MedicineSaleController::class, 'destroy'])->name('destroy');
    Route::get('/{medicineSale}/print', [MedicineSaleController::class, 'print'])->name('print');
});


// Add these routes to your web.php
Route::prefix('op-registers')->name('op-registers.')->middleware(['auth', 'role:admin,reception,doctor'])->group(function () {
    // Prescription Routes
    Route::get('/{opRegister}/prescription/create', [OpRegisterController::class, 'createPrescription'])->name('prescription.create');
    Route::get('/{opRegister}/prescription/edit', [OpRegisterController::class, 'editPrescription'])->name('prescription.edit');
    Route::post('/{opRegister}/prescription', [OpRegisterController::class, 'storePrescription'])->name('prescription.store');
    Route::put('/{opRegister}/prescription', [OpRegisterController::class, 'updatePrescription'])->name('prescription.update');
});
Route::prefix('op-registers')->name('op-registers.')->middleware(['auth', 'role:admin,doctor'])->group(function () {

    // Doctor OP Routes
    Route::get('/doctor-op', [OpRegisterController::class, 'doctorOp'])->name('doctor-op');
    Route::get('/{opRegister}/doctor-print', [OpRegisterController::class, 'doctorPrint'])->name('doctor-print');
    Route::get('/{opRegister}/details', [OpRegisterController::class, 'getOpDetails'])->name('details');
    Route::post('/{opRegister}/doctor-update', [OpRegisterController::class, 'updateByDoctor'])->name('doctor-update');
    Route::get('/{opRegister}/prescription-view', [OpRegisterController::class, 'prescriptionView'])->name('prescription-view');
});

// Pharmacy Routes
Route::prefix('pharmacy')->name('pharmacy.')->middleware(['auth', 'role:admin,pharmacy'])->group(function () {
    // Dashboard (shows both OP and IP)
    Route::get('/', [PharmacyController::class, 'index'])->name('index');

    // OP Pharmacy Routes
    Route::prefix('op')->name('op.')->group(function () {
        Route::get('/{opRegister}', [PharmacyController::class, 'showOp'])->name('show');
        Route::post('/{opRegister}/issue', [PharmacyController::class, 'issueOp'])->name('issue');
        Route::get('/bill/{opRegister}', [PharmacyController::class, 'billOp'])->name('bill');
    });

    // IP Pharmacy Routes
    Route::prefix('ip')->name('ip.')->group(function () {
        Route::get('/{inpatientRegister}', [PharmacyController::class, 'showIp'])->name('show');
        Route::post('/{inpatientRegister}/issue', [PharmacyController::class, 'issueIp'])->name('issue');
        Route::get('/bill/{inpatientRegister}', [PharmacyController::class, 'billIp'])->name('bill');
    });

    // Stock Management
    Route::get('/stock', [PharmacyController::class, 'stock'])->name('stock');
    Route::post('/stock/{medicine}/update', [PharmacyController::class, 'updateStock'])->name('update-stock');
    // Pharmacy Reports
    Route::get('/reports/op', [PharmacyController::class, 'opReport'])->name('reports.op');
    Route::get('/reports/op/print', [PharmacyController::class, 'opReportPrint'])->name('reports.op.print');
    Route::get('/reports/ip', [PharmacyController::class, 'ipReport'])->name('reports.ip');
    Route::get('/reports/ip/print', [PharmacyController::class, 'ipReportPrint'])->name('reports.ip.print');
    // Route for AJAX OP details in modal
    Route::get('/op/{opRegister}/details', [PharmacyController::class, 'getOpDetails'])->name('op.details');

    // Route for AJAX IP details in modal
    Route::get('/ip/{inpatientRegister}/details', [PharmacyController::class, 'getIpDetails'])->name('ip.details');
});

// routes/web.php
Route::prefix('radiology')->name('radiology.')->middleware(['auth', 'role:admin,radiology'])->group(function () {
    // Dashboard (shows both OP and IP)
    Route::get('/', [RadiologyController::class, 'index'])->name('index');

    // OP Radiology Routes
    Route::prefix('op')->name('op.')->group(function () {
        Route::get('/{opRegister}', [RadiologyController::class, 'showOp'])->name('show');
        Route::get('/result/{opRadiology}/edit', [RadiologyController::class, 'editOp'])->name('edit');
        Route::put('/result/{opRadiology}', [RadiologyController::class, 'updateOp'])->name('update');
        Route::get('/print/{opRegister}', [RadiologyController::class, 'printOpReport'])->name('print');
    });

    // IP Radiology Routes
    Route::prefix('ip')->name('ip.')->group(function () {
        Route::get('/{inpatientRegister}', [RadiologyController::class, 'showIp'])->name('show');
        Route::get('/result/{ipRadiology}/edit', [RadiologyController::class, 'editIp'])->name('edit');
        Route::put('/result/{ipRadiology}', [RadiologyController::class, 'updateIp'])->name('update');
        Route::get('/print/{inpatientRegister}', [RadiologyController::class, 'printIpReport'])->name('print');
    });
    // Radiology Reports
    Route::get('/reports', [RadiologyController::class, 'reports'])->name('reports');
});

// Lab Routes
Route::prefix('lab')->name('lab.')->middleware(['auth', 'role:admin,lab,doctor'])->group(function () {
    // Dashboard (OP Lab - keeping existing route)
    Route::get('/', [OpLabTestController::class, 'index'])->name('index');

    // OP Lab Routes (renamed to match new structure)
    Route::prefix('op')->name('op.')->group(function () {
        Route::get('/{opRegister}', [OpLabTestController::class, 'show'])->name('show');
        Route::get('/result/{opLabTest}/edit', [OpLabTestController::class, 'edit'])->name('edit');
        Route::get('/result/{opLabTest}/bill', [OpLabTestController::class, 'bill'])->name('bill');
        Route::put('/result/{opLabTest}', [OpLabTestController::class, 'update'])->name('update');
        Route::get('/print/{opRegister}', [OpLabTestController::class, 'printReport'])->name('print');
        Route::get('/{opRegister}/print-all', [OpLabTestController::class, 'printAllReports'])->name('print-all');
    });

    // IP Lab Routes (new routes)
    Route::prefix('ip')->name('ip.')->group(function () {
        Route::get('/{inpatientRegister}', [OpLabTestController::class, 'showIp'])->name('show');
        Route::get('/result/{ipLabTest}/edit', [OpLabTestController::class, 'editIp'])->name('edit');
        Route::put('/result/{ipLabTest}', [OpLabTestController::class, 'updateIp'])->name('update');
        Route::get('/result/{ipLabTest}/bill', [OpLabTestController::class, 'billIp'])->name('bill');
        Route::get('/print/{inpatientRegister}', [OpLabTestController::class, 'printIpReport'])->name('print');
    });

    // Lab Reports
    Route::get('/reports', [OpLabTestController::class, 'reports'])->name('reports');
});

// Manual Lab Tests Routes

Route::middleware(['auth', 'role:admin,lab,doctor,pharmacy'])->group(function () {
    Route::get('/manual-lab-tests', [ManualLabTestController::class, 'index'])->name('manual-lab-tests.index');
    Route::get('/manual-lab-tests/create', [ManualLabTestController::class, 'create'])->name('manual-lab-tests.create');
    Route::post('/manual-lab-tests', [ManualLabTestController::class, 'store'])->name('manual-lab-tests.store');
    Route::get('/manual-lab-tests/{manualLabTest}', [ManualLabTestController::class, 'show'])->name('manual-lab-tests.show');
    Route::get('/manual-lab-tests/{manualLabTest}/edit', [ManualLabTestController::class, 'edit'])->name('manual-lab-tests.edit');
    Route::put('/manual-lab-tests/{manualLabTest}', [ManualLabTestController::class, 'update'])->name('manual-lab-tests.update');
    Route::delete('/manual-lab-tests/{manualLabTest}', [ManualLabTestController::class, 'destroy'])->name('manual-lab-tests.destroy');

    // Result Update Routes - Use distinct pattern for ManualLabTestItem
    Route::get('/manual-lab-test-items/{item}/edit-result', [ManualLabTestController::class, 'editResult'])
        ->name('manual-lab-tests.edit-result');
    Route::put('/manual-lab-test-items/{item}/update-result', [ManualLabTestController::class, 'updateResult'])
        ->name('manual-lab-test-items.update-result');

    Route::post('/manual-lab-tests/{manualLabTest}/update-payment', [ManualLabTestController::class, 'updatePayment'])
        ->name('manual-lab-tests.update-payment');
    Route::get('/manual-lab-tests/{manualLabTest}/print', [ManualLabTestController::class, 'print'])
        ->name('manual-lab-tests.print');
    Route::get('/manual-lab-tests/items/{item}/print-result', [ManualLabTestController::class, 'printItemResult'])
        ->name('manual-lab-tests.print-item-result');
    // In your routes/web.php, inside the manual lab tests group
    Route::get('/manual-lab-tests/{manualLabTest}/print-all-results', [ManualLabTestController::class, 'printAllResults'])
        ->name('manual-lab-tests.print-all-results');
});

// Manual Radiology Tests Routes
Route::middleware(['auth', 'role:admin,radiology'])->group(function () {
    Route::get('/manual-radiology-tests', [ManualRadiologyTestController::class, 'index'])->name('manual-radiology-tests.index');
    Route::get('/manual-radiology-tests/create', [ManualRadiologyTestController::class, 'create'])->name('manual-radiology-tests.create');
    Route::post('/manual-radiology-tests', [ManualRadiologyTestController::class, 'store'])->name('manual-radiology-tests.store');
    Route::get('/manual-radiology-tests/{manualRadiologyTest}', [ManualRadiologyTestController::class, 'show'])->name('manual-radiology-tests.show');
    Route::get('/manual-radiology-tests/{manualRadiologyTest}/edit', [ManualRadiologyTestController::class, 'edit'])->name('manual-radiology-tests.edit');
    Route::put('/manual-radiology-tests/{manualRadiologyTest}', [ManualRadiologyTestController::class, 'update'])->name('manual-radiology-tests.update');
    Route::delete('/manual-radiology-tests/{manualRadiologyTest}', [ManualRadiologyTestController::class, 'destroy'])->name('manual-radiology-tests.destroy');

    // Result Update Routes
    Route::get('/manual-radiology-test-items/{item}/edit-result', [ManualRadiologyTestController::class, 'editResult'])->name('manual-radiology-tests.edit-result');
    Route::put('/manual-radiology-test-items/{item}/update-result', [ManualRadiologyTestController::class, 'updateResult'])->name('manual-radiology-tests.update-result');

    Route::post('/manual-radiology-tests/{manualRadiologyTest}/update-payment', [ManualRadiologyTestController::class, 'updatePayment'])->name('manual-radiology-tests.update-payment');
    Route::get('/manual-radiology-tests/{manualRadiologyTest}/print', [ManualRadiologyTestController::class, 'print'])->name('manual-radiology-tests.print');
});

Route::middleware(['auth', 'role:admin,lab'])->group(function () {

    /* ----------------------------------------------
     | OP Lab Tests Routes
     ---------------------------------------------- */
    Route::get('op-lab-tests', [OpLabTestController::class, 'index'])->name('op-lab-tests.index');
    Route::get('op-lab-tests/{opRegister}', [OpLabTestController::class, 'show'])->name('op-lab-tests.show');
    Route::get('op-lab-tests/{opLabTest}/edit', [OpLabTestController::class, 'edit'])->name('op-lab-tests.edit');
    Route::put('op-lab-tests/{opLabTest}', [OpLabTestController::class, 'update'])->name('op-lab-tests.update');
    Route::get('op-lab-tests/{opLabTest}/download', [OpLabTestController::class, 'download'])->name('op-lab-tests.download');
    Route::get('op-lab-tests/{opRegister}/print', [OpLabTestController::class, 'printReport'])->name('op-lab-tests.print');
});

Route::middleware(['auth', 'role:admin,reception,doctor'])->group(function () {
    /* ----------------------------------------------
     | Inpatient Register Routes
     ---------------------------------------------- */

    // IMPORTANT: doctor-ip MUST come BEFORE wildcard {inpatientRegister}
    Route::get('inpatient-register/doctor-ip', [InpatientRegisterController::class, 'doctorIp'])
        ->name('inpatient-register.doctor-ip');

    Route::get('inpatient-register', [InpatientRegisterController::class, 'index'])->name('inpatient-register.index');
    Route::get('inpatient-register/create', [InpatientRegisterController::class, 'create'])->name('inpatient-register.create');
    Route::post('inpatient-register', [InpatientRegisterController::class, 'store'])->name('inpatient-register.store');

    // Wildcard routes (must be placed AFTER doctor-ip)
    Route::get('inpatient-register/{inpatientRegister}', [InpatientRegisterController::class, 'show'])
        ->name('inpatient-register.show');
    Route::get('inpatient-register/{inpatientRegister}/edit', [InpatientRegisterController::class, 'edit'])
        ->name('inpatient-register.edit');
    Route::put('inpatient-register/{inpatientRegister}', [InpatientRegisterController::class, 'update'])
        ->name('inpatient-register.update');
    Route::delete('inpatient-register/{inpatientRegister}', [InpatientRegisterController::class, 'destroy'])
        ->name('inpatient-register.destroy');

    /* ----------------------------------------------
     | Inpatient Prescription Routes
     ---------------------------------------------- */
    Route::get('inpatient-register/{inpatientRegister}/prescription/create', [InpatientRegisterController::class, 'createPrescription'])
        ->name('inpatient-register.prescription.create');
    Route::post('inpatient-register/{inpatientRegister}/prescription', [InpatientRegisterController::class, 'storePrescription'])
        ->name('inpatient-register.prescription.store');
    Route::get('inpatient-register/{inpatientRegister}/prescription/edit', [InpatientRegisterController::class, 'editPrescription'])
        ->name('inpatient-register.prescription.edit');
    Route::put('inpatient-register/{inpatientRegister}/prescription', [InpatientRegisterController::class, 'updatePrescription'])
        ->name('inpatient-register.prescription.update');
    Route::get('inpatient-register/{inpatientRegister}/prescription/view', [InpatientRegisterController::class, 'prescriptionView'])
        ->name('inpatient-register.prescription.view');

    Route::post('inpatient-register/{inpatientRegister}/discharge', [InpatientRegisterController::class, 'discharge'])
        ->name('inpatient-register.discharge');


    // IP Report Routes
    Route::get('ip-report', [InpatientRegisterController::class, 'report'])->name('ip-report');
    // Inpatient report print route
    Route::get('/ip-report/{inpatient}/print', [InpatientRegisterController::class, 'printReport'])->name('ip-report.print');
    Route::get('ip-registers/{inpatientRegister}/preview', [InpatientRegisterController::class, 'preview'])->name('ip-registers.preview');
    Route::get('inpatient-registers/{inpatientRegister}/print', [InpatientRegisterController::class, 'print'])->name('inpatient-registers.print');
});

// routes/web.php (admin section)

Route::middleware(['auth', 'role:admin,reception'])->group(function () {
    // Operation Register Routes
    Route::prefix('operation-registers')->name('operation-registers.')->group(function () {
        Route::get('/', [OperationRegisterController::class, 'index'])->name('index');
        Route::get('/create', [OperationRegisterController::class, 'create'])->name('create');
        Route::post('/', [OperationRegisterController::class, 'store'])->name('store');

        // SUCCESS ROUTE MUST COME BEFORE PARAMETERIZED ROUTES
        Route::get('/success', [OperationRegisterController::class, 'success'])->name('success');

        // Parameterized routes come AFTER
        Route::get('/{operationRegister}', [OperationRegisterController::class, 'show'])->name('show');
        Route::get('/{operationRegister}/edit', [OperationRegisterController::class, 'edit'])->name('edit');
        Route::put('/{operationRegister}', [OperationRegisterController::class, 'update'])->name('update');
        Route::delete('/{operationRegister}', [OperationRegisterController::class, 'destroy'])->name('destroy');
        Route::get('/{operationRegister}/print', [OperationRegisterController::class, 'print'])->name('print');
    });
});

// Route::resource('camp-pharmacy', CampPharmacyController::class);
// Route::get('camp-pharmacy/{id}/print', [CampPharmacyController::class, 'print'])->name('camp-pharmacy.print');
// Route::get('camp-pharmacy/{id}/print-thermal', [CampPharmacyController::class, 'printThermal'])->name('camp-pharmacy.print-thermal');


// Regular camp-pharmacy with full resource routes
Route::resource('camp-pharmacy', CampPharmacyController::class);
Route::get('camp-pharmacy/{id}/print', [CampPharmacyController::class, 'print'])->name('camp-pharmacy.print');
Route::get('camp-pharmacy/{id}/print-thermal', [CampPharmacyController::class, 'printThermal'])->name('camp-pharmacy.print-thermal');

// Free camp-pharmacy - only specific routes (no resource)
Route::prefix('free-camp-pharmacy')->name('free-camp-pharmacy.')->group(function () {
    // IMPORTANT: Place specific routes BEFORE parameter routes
    Route::get('create', [CampPharmacyController::class, 'freeCreate'])->name('create');
    Route::post('/', [CampPharmacyController::class, 'freeStore'])->name('store');

    // List all free camp pharmacies
    Route::get('/', [CampPharmacyController::class, 'freeIndex'])->name('index');

    // Routes with parameters - place these AFTER specific routes
    Route::get('{id}', [CampPharmacyController::class, 'freeShow'])->name('show');
    Route::get('{id}/edit', [CampPharmacyController::class, 'freeEdit'])->name('edit');
    Route::put('{id}', [CampPharmacyController::class, 'freeUpdate'])->name('update');
    Route::patch('{id}', [CampPharmacyController::class, 'freeUpdate'])->name('update');
    Route::delete('{id}', [CampPharmacyController::class, 'freeDestroy'])->name('destroy');
    Route::get('{id}/print', [CampPharmacyController::class, 'freePrint'])->name('print');
    Route::get('{id}/print-thermal', [CampPharmacyController::class, 'freePrintThermal'])->name('print-thermal');
});

// Regular camp-pharmacy with full resource routes
Route::resource('camp-new', CampNewController::class);
Route::get('camp-new/{id}/print', [CampNewController::class, 'print'])->name('camp-new.print');
Route::get('camp-new/{id}/print-thermal', [CampNewController::class, 'printThermal'])->name('camp-new.print-thermal');


// Patient AJAX route
Route::get('/patients/{patient}/ajax', [OpRegisterController::class, 'getPatientAjax'])->name('patients.ajax');


// ---------- OTHER ROLE DASHBOARDS ----------
Route::middleware(['auth', 'role:reception'])->group(function () {
    Route::get('/reception/dashboard', [DashboardController::class, 'receptionDashboard'])->name('reception.dashboard');
});

Route::middleware(['auth', 'role:doctor'])->group(function () {
    Route::get('/doctor/dashboard', [DashboardController::class, 'doctorDashboard'])->name('doctor.dashboard');
});

Route::middleware(['auth', 'role:pharmacy'])->group(function () {
    Route::get('/pharmacy/dashboard', [DashboardController::class, 'pharmacyDashboard'])->name('pharmacy.dashboard');
});

Route::middleware(['auth', 'role:radiology'])->group(function () {
    Route::get('/radiology/dashboard', [DashboardController::class, 'radiologyDashboard'])->name('radiology.dashboard');
});

Route::middleware(['auth', 'role:lab'])->group(function () {
    Route::get('/lab/dashboard', [DashboardController::class, 'labDashboard'])->name('lab.dashboard');
});
