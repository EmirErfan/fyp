<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\TestScheduleController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\TestSessionController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StressTestController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\AccessRequestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Models\Participant;
use App\Models\TestSession;
use App\Models\AccessRequest;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Password Reset Routes
Route::get('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'request'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'email'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [\App\Http\Controllers\PasswordResetController::class, 'reset'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [\App\Http\Controllers\PasswordResetController::class, 'update'])->middleware('guest')->name('password.update');

Route::post('/request-access', [AccessRequestController::class, 'store']);

/*
|--------------------------------------------------------------------------
| SECURE ROUTES (MUST BE LOGGED IN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // DASHBOARD
    Route::get('/dashboard', function () {
        $user = auth()->user();

        // 1. DATA ACCESS CONTROL (Admin vs Researcher)
        if ($user->role === 'admin') {
            // Admin sees everything
            $totalParticipants = \App\Models\Participant::count();
            $totalSessions = \App\Models\TestSession::count();
            
            $participants = \App\Models\Participant::all()->keyBy('id');
            $sessions = \App\Models\TestSession::all()->keyBy('id');
        } else {
            // Researcher only sees their own assigned participants and sessions
            $totalParticipants = \App\Models\Participant::where('user_id', $user->id)->count();
            
            $participants = \App\Models\Participant::where('user_id', $user->id)->get()->keyBy('id');
            
            // Get sessions only for this researcher's participants
            $participantIds = $participants->pluck('id');
            $totalSessions = \App\Models\TestSession::whereIn('participant_id', $participantIds)->count();
            $sessions = \App\Models\TestSession::whereIn('participant_id', $participantIds)->get()->keyBy('id');
        }

        $pendingRequests = \App\Models\AccessRequest::where('status', 'pending')->latest()->get(); 

        // 2. FETCH ASSESSMENTS (Filtered securely by the allowed sessions)
        $sessionIds = $sessions->pluck('id');
        $assessments = \Illuminate\Support\Facades\DB::table('assessments')
                        ->whereIn('test_session_id', $sessionIds)
                        ->get()
                        ->groupBy('test_session_id');

        $data = [
            'gender' => ['Male' => ['pre'=>[],'post'=>[]], 'Female' => ['pre'=>[],'post'=>[]]],
            'age' => ['18-24' => ['pre'=>[],'post'=>[]], '25-34' => ['pre'=>[],'post'=>[]], '35-44' => ['pre'=>[],'post'=>[]]],
            'education' => ['High School' => ['pre'=>[],'post'=>[]], 'Bachelor' => ['pre'=>[],'post'=>[]], 'Master+' => ['pre'=>[],'post'=>[]]]
        ];

        // 3. CALCULATE ANALYTICS
        foreach ($assessments as $sessionId => $sessionAssessments) {
            $pre = $sessionAssessments->where('type', 'pre')->first();
            $post = $sessionAssessments->where('type', 'post')->first();
            $session = $sessions->get($sessionId);
            
            if ($pre && $post && $session) {
                $participant = $participants->get($session->participant_id);
                if ($participant) {
                    
                    // Using Distress as the primary stress metric
                    $preScore = $pre->distress_item_01 + $pre->distress_item_02 + $pre->distress_item_03 + $pre->distress_item_04 + $pre->distress_item_05;
                    $postScore = $post->distress_item_01 + $post->distress_item_02 + $post->distress_item_03 + $post->distress_item_04 + $post->distress_item_05;

                    if (isset($data['gender'][$participant->gender])) {
                        $data['gender'][$participant->gender]['pre'][] = $preScore;
                        $data['gender'][$participant->gender]['post'][] = $postScore;
                    }

                    $age = \Carbon\Carbon::parse($participant->dob)->age;
                    $ageGroup = null;
                    if ($age >= 18 && $age <= 24) $ageGroup = '18-24';
                    elseif ($age >= 25 && $age <= 34) $ageGroup = '25-34';
                    elseif ($age >= 35) $ageGroup = '35-44';
                    if ($ageGroup) {
                        $data['age'][$ageGroup]['pre'][] = $preScore;
                        $data['age'][$ageGroup]['post'][] = $postScore;
                    }

                    $edu = $participant->education_level;
                    $eduGroup = 'High School';
                    if (stripos($edu, 'Degree') !== false || stripos($edu, 'Bachelor') !== false) $eduGroup = 'Bachelor';
                    elseif (stripos($edu, 'Master') !== false || stripos($edu, 'PhD') !== false) $eduGroup = 'Master+';
                    $data['education'][$eduGroup]['pre'][] = $preScore;
                    $data['education'][$eduGroup]['post'][] = $postScore;
                }
            }
        }

        $avg = function($arr) {
            return count($arr) > 0 ? round(array_sum($arr) / count($arr), 2) : 0;
        };

        // 4. FORMAT FOR CHART.JS
        $chartData = [
            'gender' => [
                'labels' => ['Male', 'Female'],
                'pre' => [$avg($data['gender']['Male']['pre']), $avg($data['gender']['Female']['pre'])],
                'post' => [$avg($data['gender']['Male']['post']), $avg($data['gender']['Female']['post'])],
            ],
            'age' => [
                'labels' => ['18-24', '25-34', '35-44'],
                'pre' => [$avg($data['age']['18-24']['pre']), $avg($data['age']['25-34']['pre']), $avg($data['age']['35-44']['pre'])],
                'post' => [$avg($data['age']['18-24']['post']), $avg($data['age']['25-34']['post']), $avg($data['age']['35-44']['post'])],
            ],
            'education' => [
                'labels' => ['High School', 'Bachelor', 'Master+'],
                'pre' => [$avg($data['education']['High School']['pre']), $avg($data['education']['Bachelor']['pre']), $avg($data['education']['Master+']['pre'])],
                'post' => [$avg($data['education']['High School']['post']), $avg($data['education']['Bachelor']['post']), $avg($data['education']['Master+']['post'])],
            ]
        ];
        
        return view('dashboard', compact('totalParticipants', 'totalSessions', 'pendingRequests', 'chartData'));
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
    Route::get('/participants/{id}', [ParticipantController::class, 'show'])->name('participants.show');
    Route::get('/participants/{id}/edit', [ParticipantController::class, 'edit']);
    Route::put('/participants/{id}', [ParticipantController::class, 'update']);
    Route::delete('/participants/{id}', [ParticipantController::class, 'destroy']);

    // DATA EXPORT
    Route::get('/export', [ExportController::class, 'index']);
    Route::get('/export/csv', [ExportController::class, 'downloadCsv']);

    // ADMIN ONLY USER MANAGEMENT
    Route::middleware([\App\Http\Middleware\IsAdmin::class])->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/create', [UserController::class, 'create']);
        Route::post('/users', [UserController::class, 'store']);
        
        Route::get('/admin/staff/{id}/sessions', [UserController::class, 'viewStaffSessions'])->name('users.sessions');
        
        Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/admin/users/{id}', [UserController::class, 'update']);
        
        Route::delete('/admin/users/{id}', [UserController::class, 'destroy']);

        Route::get('/admin/requests', [UserController::class, 'accessRequests']);
        Route::post('/admin/requests/{id}/approve', [UserController::class, 'approveRequest']);
        Route::get('/admin/requests/{id}', [\App\Http\Controllers\UserController::class, 'showRequest']);
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
    Route::get('/test-sessions/{id}/launch', [AssessmentController::class, 'launchTest']);

    // INTERACTIVE TESTS
    Route::get('/test-sessions/{id}/stroop', [StressTestController::class, 'stroop']);
    Route::get('/test-sessions/{id}/mist', [StressTestController::class, 'mist']);
    Route::get('/test-sessions/{id}/tsst', [StressTestController::class, 'tsst']);

    // VIDEO RECORDING UPLOAD API (AUTO-SAVES RESULTS TOO)
    Route::post('/test-sessions/{id}/recordings', [TestSessionController::class, 'storeRecordings']);

    // FINAL RESULT SCREEN
    Route::get('/test-sessions/{id}/result-summary', [AssessmentController::class, 'showResultSummary']);

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit']);
    Route::post('/profile', [ProfileController::class, 'update']);

    // PASSWORD
    Route::get('/password/change', [ProfileController::class, 'changePassword']);
    Route::post('/password/change', [ProfileController::class, 'updatePassword']);

});