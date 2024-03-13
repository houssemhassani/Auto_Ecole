<?php

use App\Http\Controllers\admin\CandidatController;
use App\Http\Controllers\admin\CourController;
use App\Http\Controllers\Api\GestionUserController;
use App\Http\Controllers\admin\AutoEcoleController;
use App\Http\Controllers\admin\MonitorController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/gestionUser/AllUsers', [GestionUserController::class, 'index'])->name('index');
    Route::get('/gestionUser/{email}', [GestionUserController::class, 'show'])->name('show');

    Route::resource('roles', RoleController::class);
    //Route::resource('/gestionmonitor', MonitorController::class);
    Route::delete('/gestionUser/destroy/{userId}', [GestionUserController::class, 'destroy'])->name('destroy');
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::prefix('/gestioncour')->group(function () {
        Route::get('/allCours', [CourController::class, 'index'])->name('cours.index');
        Route::get('/create', [CourController::class, 'create'])->name('cours.create');
        Route::post('/addCour', [CourController::class, 'store'])->name('cours.store');
        Route::get('/showCour/{id}', [CourController::class, 'show'])->name('cours.show');
        Route::get('/{id}/edit', [CourController::class, 'edit'])->name('cour.edit');
        Route::put('/update/{id}', [CourController::class, 'update'])->name('cour.update');
        Route::delete('/destroy/{id}', [CourController::class, 'destroy'])->name('cour.destroy');
    });

    Route::prefix('/gestionmonitor')->group(function () {
        Route::post('/store', [MonitorController::class, 'store'])->name('monitor.store');
        Route::get('/autoecoles', [MonitorController::class, 'create'])->name('monitor.create');
        Route::get('/show/{id}', [MonitorController::class, 'show'])->name('monitor.show');
        Route::delete('/destroy/{id}', [MonitorController::class, 'destroy'])->name('monitor.destroy');
        Route::get('/allmonitors', [MonitorController::class, 'index'])->name('monitor.index');
        Route::post('/updateProfile', [MonitorController::class, 'update'])->name('monitor.updateProfile');

    });
    Route::prefix('/conversations')->group(function () {
        Route::get('/', [ChatController::class, 'index']);

        // Route pour récupérer la conversation avec un utilisateur spécifique
        Route::get('/conversation/{userId}', [ChatController::class, 'conversation']);

        // Route pour envoyer un message
        Route::post('/send', [ChatController::class, 'send']);

        // Route pour supprimer un message
        Route::delete('/delete/{id}', [ChatController::class, 'delete']);
    });
    Route::prefix('/gestioncandidat')->group(function () {
        Route::post('/store', [CandidatController::class, 'store'])->name('candidat.store');
        // Route::get('/autoecoles', [CandidatController::class, 'create'])->name('monitor.create');
        Route::get('/show/{id}', [CandidatController::class, 'show'])->name('candidat.show');
        Route::delete('/destroy/{id}', [CandidatController::class, 'destroy'])->name('candidat.destroy');
        Route::get('/allcandidats', [CandidatController::class, 'index'])->name('candidat.index');
        Route::post('/updateProfile', [CandidatController::class, 'update'])->name('candidat.updateProfile');
        Route::get('/courses/without-candidates', [CandidatController::class, 'getCoursesWithoutCandidates']);
        Route::get('/{candidatId}/courses', [CandidatController::class, 'getCoursesByCandidateId']);
        Route::post('/assign-course/{candidatId}', [CandidatController::class, 'assignCourse'])->name('candidat.assignCourse');
    });

    Route::post('/gestionautoecole/store', [AutoEcoleController::class, 'store'])->name('autoecole.store');
    Route::post('/users/{userId}/assign-roles', 'App\Http\Controllers\Api\UserController@assignRoles');

});

Route::get('demo', [GestionUserController::class, 'getOnlineUsers'])->name('');
Route::post('/user/store', [UserController::class, 'store'])->name('store');
Route::post('user/login', [UserController::class, 'login'])->name('login');
Route::post('/user/forgot-password', [UserController::class, 'forgotPassword'])->name('password.email');
Route::post('/user/check-code', [UserController::class, 'checkCode'])->name('user.checkCode');
Route::post('/user/reset-password', [UserController::class, 'resetPassword'])->name('password.reset');
/* Route::post('password/email',  ForgotPasswordController::class);
Route::post('password/code/check', CodeCheckController::class);
Route::post('password/reset', ResetPasswordController::class); */
