<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestScheduleController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\TestSessionController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StressTestController;
use App\Http\Controllers\ExportController;
use App\Models\Participant;
use App\Models\TestSession;

// --- PUBLIC ROUTES ---
Route::get('/', function () {
    return view('welcome'); // Landing page
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// --- SECURE RESEARCHER ROUTES (MUST BE LOGGED IN) ---
Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // DASHBOARD
    Route::get('/dashboard', function () {
        $totalParticipants = Participant::count();
        $totalSessions = TestSession::count();
        return view('dashboard', compact('totalParticipants', 'totalSessions'));
    })->name('dashboard');

    // SCHEDULES
    Route::get('/schedules', [TestScheduleController::class, 'index']);
    Route::get('/schedules/create', [TestScheduleController::class, 'create']);
    Route::post('/schedules', [TestScheduleController::class, 'store']);
    Route::get('/schedules/{id}', [TestScheduleController::class, 'show']);

    // PARTICIPANTS
    Route::get('/participants', [ParticipantController::class, 'index']);
    Route::get('/participants/create', [ParticipantController::class, 'create']);
    Route::post('/participants', [ParticipantController::class, 'store']);
    Route::get('/participants/{id}/edit', [ParticipantController::class, 'edit']);
    Route::put('/participants/{id}', [ParticipantController::class, 'update']);
    Route::delete('/participants/{id}', [ParticipantController::class, 'destroy']);

    // DATA EXPORT
    Route::get('/export', [ExportController::class, 'index']);
    Route::get('/export/csv', [ExportController::class, 'downloadCsv']);

    // ADMIN ONLY USER MANAGEMENT
    Route::middleware([\App\Http\Middleware\IsAdmin::class])->group(function () {
        Route::get('/users', [\App\Http\Controllers\UserController::class, 'index']);
        Route::get('/users/create', [\App\Http\Controllers\UserController::class, 'create']);
        Route::post('/users', [\App\Http\Controllers\UserController::class, 'store']);
        Route::get('/admin/staff/{id}/sessions', [\App\Http\Controllers\UserController::class, 'viewStaffSessions']);
    });

    // TEST SESSIONS & ASSIGNMENTS
    Route::get('/test-sessions', [TestSessionController::class, 'index']);
    Route::get('/test-sessions/create', [TestSessionController::class, 'create']);
    Route::post('/test-sessions', [TestSessionController::class, 'store']);
    Route::get('/test-sessions/{id}', [TestSessionController::class, 'show']);

    // ASSESSMENTS & CONSENT
    Route::get('/test-sessions/{id}/consent', [AssessmentController::class, 'showConsent']);
    Route::get('/test-sessions/{id}/pre-test', [AssessmentController::class, 'createPreTest']);
    Route::post('/test-sessions/{id}/pre-test', [AssessmentController::class, 'storePreTest']);
    Route::get('/test-sessions/{id}/post-test', [AssessmentController::class, 'createPostTest']);
    Route::post('/test-sessions/{id}/post-test', [AssessmentController::class, 'storePostTest']);

    // LAUNCH PAD SCREEN
    Route::get('/test-sessions/{id}/launch', [App\Http\Controllers\AssessmentController::class, 'launchTest']);

    // INTERACTIVE TESTS
    Route::get('/test-sessions/{id}/stroop', [StressTestController::class, 'stroop']);
    Route::get('/test-sessions/{id}/mist', [StressTestController::class, 'mist']);
    Route::get('/test-sessions/{id}/tsst', [StressTestController::class, 'tsst']);

    // VIDEO RECORDING UPLOAD API (AUTO-SAVES RESULTS TOO)
    Route::post('/test-sessions/{id}/recordings', [\App\Http\Controllers\TestSessionController::class, 'storeRecordings']);

    // FINAL RESULT SCREEN
    Route::get('/test-sessions/{id}/result-summary', [AssessmentController::class, 'showResultSummary']);

    // PROFILE
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit']);
    Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update']);

    // PASSWORD
    Route::get('/password/change', [App\Http\Controllers\ProfileController::class, 'changePassword']);
    Route::post('/password/change', [App\Http\Controllers\ProfileController::class, 'updatePassword']);

    
});