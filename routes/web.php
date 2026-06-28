<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubAgentController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StudentLoginController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\WorldDataController;
use App\Http\Controllers\Admin\NotificationSettingsController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Sales\SalesDashboardController;
use App\Http\Controllers\Employee\EmployeeDashboardController;
use App\Http\Controllers\Register\RegisterDashboardController;

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
Route::middleware('guest')->group(function () {

Route::get('/', function () {
    return view('welcome');
});
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php'
;
Route::middleware(['auth'])->group(function () {
    // Admin Routes
    Route::middleware(['role:Admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        // Add more admin routes as needed
    });

    // Sales Routes
    Route::middleware(['role:Sales'])->prefix('sales')->name('sales.')->group(function () {
        Route::get('/dashboard', [SalesDashboardController::class, 'index'])->name('dashboard');
        // Add more sales routes as needed
    });

    // Register Routes
    Route::middleware(['role:Register'])->prefix('register')->name('register.')->group(function () {
        
        Route::get('/dashboard', [RegisterDashboardController::class, 'index'])->name('dashboard');
     
    });

    // Employee Routes
    Route::middleware(['role:Employee'])->prefix('employee')->name('employee.')->group(function () {
        Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
        // Add more employee routes as needed
    });

    
});

Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    // World Data Routes
    Route::get('/world', [WorldDataController::class, 'dashboard'])->name('world.dashboard');
    Route::get('/world/countries', [WorldDataController::class, 'countries'])->name('countries.index');
    Route::get('/world/countries/{countryId}/states', [WorldDataController::class, 'states'])->name('states.index');
    Route::get('/world/countries/{countryId}/states/{stateId}/cities', [WorldDataController::class, 'cities'])->name('cities.index');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // University Routes
    Route::get('universities/export-excel', [UniversityController::class, 'exportExcel'])->name('universities.export-excel');
    Route::get('universities/export-pdf', [UniversityController::class, 'exportPdf'])->name('universities.export-pdf');
    Route::get('universities/get-cities', [UniversityController::class, 'getCities'])->name('universities.get-cities');
    Route::get('universities/toggle-status/{university}', [UniversityController::class, 'toggleStatus'])->name('universities.toggle-status');
    Route::resource('universities', UniversityController::class);

    // Program export/import routes
    Route::get('programs/export-excel', [ProgramController::class, 'exportExcel'])->name('programs.export-excel');
    Route::get('programs/export-pdf', [ProgramController::class, 'exportPdf'])->name('programs.export-pdf');
    Route::post('programs/import', [ProgramController::class, 'importExcel'])->name('programs.import');
    Route::get('programs/import-template', [ProgramController::class, 'downloadTemplate'])->name('programs.import-template');

    // Announcement export routes
    Route::get('announcements/export-excel', [AnnouncementController::class, 'exportExcel'])->name('announcements.export-excel');
    Route::get('announcements/export-pdf', [AnnouncementController::class, 'exportPdf'])->name('announcements.export-pdf');

    // Student & application export routes
    Route::get('students/export-excel', [StudentController::class, 'exportExcel'])->name('students.export-excel');
    Route::get('students/export-pdf', [StudentController::class, 'exportPdf'])->name('students.export-pdf');
    Route::get('applications/export-excel', [ApplicationController::class, 'exportExcel'])->name('applications.export-excel');
    Route::get('applications/export-pdf', [ApplicationController::class, 'exportPdf'])->name('applications.export-pdf');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Announcement Routes cities/by-country
    Route::get('announcements/get-cities/{country}', [AnnouncementController::class, 'getCities'])->name('announcements.get-cities');
    Route::get('announcements/toggle-status/{announcement}', [AnnouncementController::class, 'toggleStatus'])->name('announcements.toggle-status');
    Route::get('announcements/publish/{announcement}', [AnnouncementController::class, 'publish'])->name('announcements.publish');
    Route::get('announcements/unpublish/{announcement}', [AnnouncementController::class, 'unpublish'])->name('announcements.unpublish');
    Route::resource('announcements', AnnouncementController::class);
});


Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Program AJAX routes — must be before resource() to avoid {program} wildcard swallowing them
    Route::get('programs/toggle-status/{program}', [ProgramController::class, 'toggleStatus'])->name('programs.toggle-status');
    Route::delete('programs/delete-all', [ProgramController::class, 'destroyAll'])->name('programs.destroy-all');
    Route::delete('programs/delete-selected', [ProgramController::class, 'destroySelected'])->name('programs.destroy-selected');
    Route::get('programs/all-ids', [ProgramController::class, 'allFilteredIds'])->name('programs.all-ids');
    Route::get('programs/by-university/{universityId}', function ($universityId) {
        $base = \App\Models\Program::where('university_id', $universityId);
        // optional language filter — used by application create to narrow degrees per language
        if (request()->filled('language')) {
            $filtered = $base->clone()->where('language', request('language'));
            return response()->json([
                'departments' => $filtered->clone()->distinct()->orderBy('department')->pluck('department'),
                'languages'   => $base->clone()->distinct()->orderBy('language')->pluck('language'),
                'degrees'     => $filtered->clone()->distinct()->orderBy('degree')->pluck('degree'),
            ]);
        }
        return response()->json([
            'departments' => $base->clone()->distinct()->orderBy('department')->pluck('department'),
            'languages'   => $base->clone()->distinct()->orderBy('language')->pluck('language'),
            'degrees'     => $base->clone()->distinct()->orderBy('degree')->pluck('degree'),
        ]);
    })->name('programs.by-university');
    Route::get('programs/departments-by-language/{universityId}/{language}', function ($universityId, $language) {
        $departments = \App\Models\Program::where('university_id', $universityId)
            ->where('language', $language)
            ->distinct()->orderBy('department')->pluck('department');
        return response()->json(['departments' => $departments]);
    })->name('programs.departments-by-language');

    Route::resource('programs', ProgramController::class);
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Student management
    Route::get('students', [StudentController::class, 'index'])->name('students.index');
    Route::get('students/pending-documents', [StudentController::class, 'pendingDocuments'])->name('students.pending-documents');
    Route::resource('applications', ApplicationController::class);
    Route::put('/applications/{application}/status', [ApplicationController::class ,'updateStatus'])
    ->name('applications.update-status'); 
    // Student creation steps
    Route::get('students/create/step1', [StudentController::class, 'createStep1'])->name('students.create.step1');
    Route::post('students/create/step1', [StudentController::class, 'storeStep1'])->name('students.store.step1');
    Route::get('students/create/step2', [StudentController::class, 'createStep2'])->name('students.create.step2');
    Route::post('students/create/step2', [StudentController::class, 'storeStep2'])->name('students.store.step2');
    Route::get('students/create/step3', [StudentController::class, 'createStep3'])->name('students.create.step3');
    Route::post('students/create/step3', [StudentController::class, 'storeStep3'])->name('students.store.step3');
    Route::get('students/create/step4', [StudentController::class, 'createStep4'])->name('students.create.step4');
    Route::post('students/create/step4', [StudentController::class, 'storeStep4'])->name('students.store.step4');
    Route::get('students/create/step5', [StudentController::class, 'createStep5'])->name('students.create.step5');
    Route::post('students/create/step5', [StudentController::class, 'storeStep5'])->name('students.store.step5');
    
    // CRUD routes
    Route::get('students/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::get('students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
    
    // Status management
    Route::patch('students/{student}/change-status', [StudentController::class, 'changeStatus'])->name('students.change-status');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// In your routes/web.php
// Student Auth Routes
Route::prefix('student')->group(function() {

    Route::get('/register', [StudentAuthController::class, 'create'])->name('student.create');
    Route::post('/register', [StudentAuthController::class, 'register'])->name('student.register');
    Route::get('/registration-success', [StudentAuthController::class, 'registrationSuccess'])->name('student.registration.success');

});
 
Route::get('students/login', [StudentLoginController::class, 'showLoginForm'])->name('student.login.view');
Route::post('students/login', [StudentLoginController::class, 'login'])->name('student.login');

Route::prefix('student')->middleware('auth.student')->group(function() {
    Route::post('/logout', [StudentAuthController::class, 'logout'])->name('student.logout');
    
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
    Route::get('/profile', [StudentDashboardController::class, 'profile'])->name('student.profile');
    Route::get('/documents', [StudentDashboardController::class, 'documents'])->name('student.documents');
    Route::get('/applications', [StudentDashboardController::class, 'applications'])->name('student.applications');
    Route::get('/timeline', [StudentDashboardController::class, 'timeline'])->name('student.timeline');
    
    // Update profile and upload documents
    Route::post('/update-profile', [StudentDashboardController::class, 'updateProfile'])->name('student.update.profile');
    Route::post('/upload-documents', [StudentDashboardController::class, 'uploadDocuments'])->name('student.documents.upload');
    
    // Application management
    Route::post('/applications/store', [StudentDashboardController::class, 'storeApplication'])->name('student.applications.store');
    Route::get('/applications/{application}', [StudentDashboardController::class, 'showApplication'])->name('student.applications.show');

    // Programs API (for dynamic dropdowns in application modal)
    Route::get('/programs/by-university/{universityId}', function ($universityId) {
        $base = \App\Models\Program::where('university_id', $universityId);
        return response()->json([
            'languages'   => $base->clone()->distinct()->orderBy('language')->pluck('language'),
            'departments' => $base->clone()->distinct()->orderBy('department')->pluck('department'),
            'degrees'     => $base->clone()->distinct()->orderBy('degree')->pluck('degree'),
        ]);
    })->name('student.programs.by-university');
    Route::get('/programs/departments-by-language/{universityId}/{language}', function ($universityId, $language) {
        $departments = \App\Models\Program::where('university_id', $universityId)
            ->where('language', $language)
            ->distinct()->orderBy('department')->pluck('department');
        $degrees = \App\Models\Program::where('university_id', $universityId)
            ->where('language', $language)
            ->distinct()->orderBy('degree')->pluck('degree');
        return response()->json(['departments' => $departments, 'degrees' => $degrees]);
    })->name('student.programs.departments-by-language');
    
    // Notification routes
    Route::get('/notifications', [StudentDashboardController::class, 'notifications'])->name('student.notifications');
    Route::post('/notifications/mark-read', [StudentDashboardController::class, 'markNotificationsAsRead'])->name('student.notifications.mark-read');    
    // Add more protected student routes here
});

// For uploading files to an application
Route::get('admin/applications/{application}/files/upload', [App\Http\Controllers\ApplicationFileController::class, 'create'])
    ->name('admin.applications.files.upload');

// For storing uploaded files
Route::post('admin/applications/{application}/files', [App\Http\Controllers\ApplicationFileController::class, 'store'])
    ->name('admin.applications.files.store');

// For listing files
Route::get('admin/applications/{application}/files', [App\Http\Controllers\ApplicationFileController::class, 'index'])
    ->name('admin.applications.files.index');

// For downloading files
Route::get('admin/applications/{application}/files/{file}/download', [App\Http\Controllers\ApplicationFileController::class, 'download'])
    ->name('admin.applications.files.download');

// For deleting files



    Route::get('/chat', [App\Http\Controllers\ChatController::class, 'index'])
    ->middleware(['auth:web,student'])
    ->name('chat');

    
    Route::get('/studentIndex', [App\Http\Controllers\ChatController::class, 'studentIndex'])
    ->middleware(['auth:web,student'])
    ->name('studentIndex');
   
// Chat AJAX routes
Route::prefix('chat')->name('chat.')->middleware(['auth:web,student'])->group(function () {
    Route::get('/conversations',                             [App\Http\Controllers\ChatController::class, 'getConversations'])->name('conversations');
    Route::post('/conversations',                            [App\Http\Controllers\ChatController::class, 'createConversation'])->name('create');
    Route::post('/conversations/group',                      [App\Http\Controllers\ChatController::class, 'createGroupConversation'])->name('create-group');
    Route::get('/conversations/{conversationId}',            [App\Http\Controllers\ChatController::class, 'getConversation'])->name('conversation');
    Route::get('/conversations/{conversationId}/messages',   [App\Http\Controllers\ChatController::class, 'getMessages'])->name('messages');
    Route::post('/messages',                                 [App\Http\Controllers\ChatController::class, 'sendMessage'])->name('send');
    Route::post('/status',                                   [App\Http\Controllers\ChatController::class, 'updateUserStatus'])->name('status');
    Route::get('/conversations/{conversationId}/group',      [App\Http\Controllers\ChatController::class, 'getGroupInfo'])->name('group-info');
    Route::post('/conversations/{conversationId}/group',     [App\Http\Controllers\ChatController::class, 'updateGroupInfo'])->name('group-update');
    Route::post('/conversations/{conversationId}/add-users', [App\Http\Controllers\ChatController::class, 'addUsersToGroup'])->name('group-add');
    Route::post('/conversations/{conversationId}/remove-user',[App\Http\Controllers\ChatController::class, 'removeUserFromGroup'])->name('group-remove');
    Route::post('/conversations/{conversationId}/leave',     [App\Http\Controllers\ChatController::class, 'leaveGroup'])->name('group-leave');
    Route::get('/users/available/{conversationId}',          [App\Http\Controllers\ChatController::class, 'getAvailableUsers'])->name('available-users');
    // Floating widget extras
    Route::get('/users', function () {
        $users    = \App\Models\User::where('id', '!=', auth()->id())->get(['id','name']);
        $isAdmin  = auth()->check() && strtolower(auth()->user()->role) === 'admin';
        $students = $isAdmin ? \App\Models\Student::get(['id','first_name','last_name']) : collect();
        return response()->json(['users' => $users, 'students' => $students]);
    })->name('users');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('subagents', SubAgentController::class);
// Add these routes to your routes/web.php file inside the admin middleware group

// Notification Settings (Admin only)
Route::get('notification-settings', [NotificationSettingsController::class, 'index'])->name('admin.notification-settings.index');
Route::put('notification-settings', [NotificationSettingsController::class, 'update'])->name('admin.notification-settings.update');

// Profile Settings
Route::prefix('profile')->name('admin.profile.')->group(function () {
    Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
    Route::put('/update-email', [ProfileController::class, 'updateEmail'])->name('updateEmail');
    Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('updatePassword');
    Route::put('/update-photo', [ProfileController::class, 'updatePhoto'])->name('updatePhoto');
    Route::delete('/remove-photo', [ProfileController::class, 'removePhoto'])->name('removePhoto');
});

// File upload routes
Route::post('admin/applications/{application}/upload-missing-files', [App\Http\Controllers\ApplicationController::class, 'uploadMissingFiles'])->name('admin.applications.upload-missing-files');
Route::post('admin/applications/{application}/upload-file', [App\Http\Controllers\ApplicationController::class, 'uploadFile'])->name('admin.applications.upload-file');


Route::prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('markAsRead');
    Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
    Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    Route::post('/delete-all', [NotificationController::class, 'destroyAll'])->name('destroyAll');
});


// File management routes
Route::get('admin/applications/{application}/files/{file}/download', [App\Http\Controllers\ApplicationController::class, 'downloadFile'])->name('admin.applications.files.download');

});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Department Routes
    Route::get('/departments', [App\Http\Controllers\DepartmentController::class, 'index'])->name('departments.index');
    Route::post('/departments', [App\Http\Controllers\DepartmentController::class, 'store'])->name('departments.store');
    Route::delete('/departments/{department}', [App\Http\Controllers\DepartmentController::class, 'destroy'])->name('departments.destroy');
});
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Department Routes
    Route::get('/degrees', [App\Http\Controllers\DegreeController::class, 'index'])->name('degrees.index');
    Route::post('/degrees', [App\Http\Controllers\DegreeController::class, 'store'])->name('degrees.store');
    Route::delete('/degrees/{degree}', [App\Http\Controllers\DegreeController::class, 'destroy'])->name('degrees.destroy');
});

// Student document management
Route::post('students/{student}/upload-document', [StudentController::class, 'uploadDocument'])
    ->name('admin.students.upload-document');
Route::delete('students/{student}/delete-document', [StudentController::class, 'deleteDocument'])
    ->name('admin.students.delete-document');

    Route::delete('/admin/applications/{application}/files/{file}/delete', [ApplicationController::class, 'deleteFile'])
    ->name('admin.applications.files.delete');