<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\McmcDashboardController;
use App\Http\Controllers\AgencyDashboardController;
use App\Http\Controllers\AgencyFirstLoginController;
use App\Http\Middleware\EnsureAgencyStaff;
use App\Http\Middleware\EnsureMcmcStaff;
use App\Http\Middleware\AgencyFirstLogin;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\MCMC\InquiryManagementController;
use App\Http\Controllers\InquiryAssignmentController;
use App\Http\Controllers\InquiryProgressController;
use App\Exports\AssignmentReportExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\MCMC\ProgressReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| 1) Public Registration & Verification
| 2) Login & Logout
| 3) Password Reset (Forgot & Reset) 
| 4) Authenticated Routes (Profile, Dashboards, First‐Login Flows)
| 5) Fallback root → /login
|
*/

// ------------------------------------------------
// 1) Public Registration & Verification
// ------------------------------------------------
Route::get('register', [RegisterController::class, 'showRegistrationForm'])
    ->name('register');

Route::post('register', [RegisterController::class, 'register']);

Route::get('email/verify', [RegisterController::class, 'showVerificationNotice'])
    ->name('verification.notice');

Route::get('email/verify/code', [RegisterController::class, 'showVerificationForm'])
    ->name('verification.form');

Route::post('email/verify', [RegisterController::class, 'verifyCode'])
    ->name('verification.verify');


// ------------------------------------------------
// 2) Login & Logout
// ------------------------------------------------
Route::get('login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('login', [LoginController::class, 'login']);

Route::post('logout', [LoginController::class, 'logout'])
    ->name('logout');


// ------------------------------------------------
// 3) Password Reset (Forgot & Reset Flows)
// ------------------------------------------------
// Show “forgot password” form (guest only)
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->middleware('guest')
    ->name('password.request');

// Send the reset link email
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('guest')
    ->name('password.email');

// Show the “reset password” form (guest only, with token in URL)
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

// Handle the “reset password” submission
Route::post('reset-password', [ResetPasswordController::class, 'reset'])
    ->middleware('guest')
    ->name('password.update');


// ------------------------------------------------
// 4) Authenticated Routes (requires login)
// ------------------------------------------------
Route::middleware('auth')->group(function () {

    // 4.1) Dashboard Entry Point
    //     Everyone logs in → is sent here → redirect logic sends them
    //     to /agency-first-login, /dashboard/agency, /dashboard/mcmc, or /dashboard/public
    Route::get('/dashboard', [DashboardRedirectController::class, 'index'])
        ->name('dashboard');


    // 4.2) Public Profile Management
    //      (Any authenticated user—public or even MCMC if you want—visits /profile)
    Route::get('profile', [ProfileController::class, 'showProfile'])
        ->name('profile');

    Route::get('profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('profile/edit', [ProfileController::class, 'update'])
        ->name('profile.update');


    // 4.3) Settings (placeholder)
    Route::get('settings', function () {
        return view('settings');
    })->name('settings');

    // inquiry history
    Route::middleware(['auth'])->get('/inquiry/history', [\App\Http\Controllers\DashboardRedirectController::class, 'history'])
        ->name('inquiry.history');


    // 4.4) “First‐Login” flow for Agency Staff
    //       Only agency_staff with is_first_login = true may see this
    Route::middleware(['auth', AgencyFirstLogin::class])
        ->get('/agency/first-login', [AgencyFirstLoginController::class, 'showChangeForm'])
        ->name('agency.first-login');

    Route::middleware(['auth', AgencyFirstLogin::class])
        ->post('/agency/first-login', [AgencyFirstLoginController::class, 'updatePassword'])
        ->name('agency.first-login.update');


    Route::middleware(['auth', EnsureMcmcStaff::class])->group(function () {
        // Existing dashboard route:
        Route::get('/dashboard/mcmc', [McmcDashboardController::class, 'index'])
            ->name('dashboard.mcmc');

        // Existing MCMC Profile & Edit
        Route::prefix('mcmc')
            ->name('mcmc.')
            ->group(function () {

                Route::get('profile', [ProfileController::class, 'showProfile'])
                    ->name('profile');

                Route::get('profile/edit', [ProfileController::class, 'edit'])
                    ->name('profile.edit');

                Route::put('profile/edit', [ProfileController::class, 'update'])
                    ->name('profile.update');

                // ───────────────────────────────────────────────
                // 4.5.a  Create Account (agency staff) → Form + Store
                // ───────────────────────────────────────────────
                Route::get('create-account', [UserManagementController::class, 'showCreateForm'])
                    ->name('create-account');

                Route::post('create-account', [UserManagementController::class, 'storeNewAgency'])
                    ->name('store-account');

                // ───────────────────────────────────────────────
                // 4.5.b  User Directory (listing, filtering, sorting)
                // ───────────────────────────────────────────────
                Route::get('users', [UserManagementController::class, 'index'])
                    ->name('users');

                // 4.5.c  Download PDF of User Directory
                Route::get('users/pdf', [UserManagementController::class, 'downloadPdf'])
                    ->name('users.pdf');

                // 4.5.d delete users
                Route::delete('users/{id}', [UserManagementController::class, 'destroy'])
                    ->name('users.destroy');

                // NEW MCMC Inquiry Management routes:
                Route::get('inquiries', [InquiryManagementController::class, 'index'])->name('inquiries');
                Route::get('inquiries/pdf', [InquiryManagementController::class, 'downloadPdf'])->name('inquiries.pdf');
                Route::get('inquiries/{id}', [InquiryManagementController::class, 'show'])->name('inquiries.show');
                Route::put('inquiries/{id}', [InquiryManagementController::class, 'update'])->name('inquiries.update');

                Route::get('assignment-report', [InquiryAssignmentController::class, 'showReport'])
                    ->name('assignment-report');

                Route::get('assignment-report/pdf', [InquiryAssignmentController::class, 'exportPdf'])
                    ->name('assignment-report.pdf');

                // Route::get('/mcmc/assign', [App\Http\Controllers\MCMC\InquiryAssignmentController::class, 'reassignForm'])
                //     ->name('mcmc.assign-form');

                // Route::post('/mcmc/inquiries/reassign', [\App\Http\Controllers\MCMC\InquiryAssignController::class, 'update'])
                //     ->name('mcmc.assign.update');

                Route::get('assignment-report/excel', function () {
                    $month = request('month');
                    $year = request('year');
                    $agencyId = request('agency');

                    // Run your filtered DB query (same as in the controller)
                    $query = DB::table('inquiry_assignments')
                        ->join('users', 'inquiry_assignments.agency_id', '=', 'users.id')
                        ->select(
                            'users.agency_name',
                            DB::raw('MONTH(inquiry_assignments.assigned_at) as month'),
                            DB::raw('YEAR(inquiry_assignments.assigned_at) as year'),
                            DB::raw('COUNT(*) as total_inquiries')
                        )
                        ->groupBy('users.agency_name', 'month', 'year');

                    if ($month) $query->whereMonth('inquiry_assignments.assigned_at', $month);
                    if ($year) $query->whereYear('inquiry_assignments.assigned_at', $year);
                    if ($agencyId) $query->where('inquiry_assignments.agency_id', $agencyId);

                    $reportData = $query->get();

                    return Excel::download(new AssignmentReportExport($reportData), 'inquiry_assignment_report.xlsx');
                })->name('assignment-report.excel');
            });
    });

    // 4.6) Agency Staff Dashboard & Profile (only role = agency_staff && is_first_login = false)
    Route::middleware(['auth', EnsureAgencyStaff::class])->group(function () {


    
        // Dashboard
        Route::get('/dashboard/agency', [AgencyDashboardController::class, 'index'])
            ->name('dashboard.agency');

        // ✅ Profile + Inquiry Tracking go here under same prefix
        Route::prefix('agency')
            ->name('agency.')
            ->group(function () {

                // Profile routes
                Route::get('profile', [ProfileController::class, 'showProfile'])
                    ->name('profile');

                Route::get('profile/edit', [ProfileController::class, 'edit'])
                    ->name('profile.edit');

                Route::put('profile/edit', [ProfileController::class, 'update'])
                    ->name('profile.update');

                // ✅ Add this block for Inquiry Tracking
                Route::get('inquiries', [\App\Http\Controllers\Agency\InquiryTrackingController::class, 'index'])
                    ->name('inquiries');

                Route::get('inquiries/{id}', [\App\Http\Controllers\Agency\InquiryTrackingController::class, 'show'])
                    ->name('inquiries.show');

                Route::put('inquiries/{id}', [\App\Http\Controllers\Agency\InquiryTrackingController::class, 'update'])
                    ->name('inquiries.update');


                // TAMBAH BARIS NI ↓
                Route::post('inquiries/{id}/progress', [InquiryProgressController::class, 'store'])
                    ->name('inquiries.progress.store');
                    
            });
    });


    // 4.7) Public Dashboard (fallback for non‐agency, non‐MCMC)
    Route::get('/dashboard/public', [DashboardRedirectController::class, 'publicDashboard'])
        ->name('dashboard.public');

    // 4.8) Public Inquiry Form Submission (only for Public Users)
    Route::prefix('inquiry')
        ->name('inquiry.')
        ->group(function () {
            Route::get('create', [\App\Http\Controllers\InquiryController::class, 'create'])
                ->name('create');

            Route::post('store', [\App\Http\Controllers\InquiryController::class, 'store'])
                ->name('store');

            Route::get('thank-you', function () {
                return view('inquiry.thank-you');
            })->name('thank-you');
        });
});

// ------------------------------------------------
// 5) Fallback: redirect “/” to login if not already
// ------------------------------------------------
Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('/mcmc/assign', [InquiryAssignmentController::class, 'showForm'])
    ->middleware('auth')
    ->name('mcmc.assign-form');

Route::post('/assign-inquiry', [InquiryAssignmentController::class, 'assignToAgency'])
    ->name('assign.inquiry');

Route::get('inquiry/tracking', [DashboardRedirectController::class, 'history'])
    ->name('inquiry.tracking')
    ->middleware('auth');



Route::get('/inquiry/{id}/progress', [App\Http\Controllers\InquiryProgressController::class, 'show'])
    ->name('inquiry.progress.show');

Route::post('/agency/inquiry/{id}/review', [InquiryController::class, 'submitFinalReview'])->name('agency.submitReview');

//Route to Mark All as Read
Route::get('/notifications/mark-read', function () {
    auth()->user()->unreadNotifications->markAsRead();
    return redirect()->back();
})->name('notifications.markRead');

Route::put('/agency/inquiries/{id}/review', [InquiryController::class, 'submitFinalReview'])->name('agency.inquiries.review');

Route::get('inquiries', [\App\Http\Controllers\Agency\InquiryTrackingController::class, 'index'])
    ->name('agency.inquiries');

//mcmc manage progress

Route::get('/mcmc/manage-process', [McmcDashboardController::class, 'processPage'])
    ->name('mcmc.manage-process');

Route::get('/progress-report', [ProgressReportController::class, 'index'])
    ->middleware(['auth', EnsureMcmcStaff::class])
    ->name('progress.report');


//pdf & excel progress

Route::get('/mcmc/progress-report', [App\Http\Controllers\MCMC\ProgressReportController::class, 'index'])
    ->name('progress.report');

Route::get('/mcmc/progress-report/pdf', [App\Http\Controllers\MCMC\ProgressReportController::class, 'downloadPdf'])
    ->name('progress.report.pdf');

Route::get('/mcmc/progress-report/excel', [App\Http\Controllers\MCMC\ProgressReportController::class, 'downloadExcel'])
    ->name('progress.report.excel');
