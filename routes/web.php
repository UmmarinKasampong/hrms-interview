<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthManager;
use App\Http\Controllers\FormManager;
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

Route::get('/', [AuthManager::class , 'checkPath'])->name('home');


Route::get('/login' , [AuthManager::class , 'login'])->name('login');
Route::post('/login' , [AuthManager::class , 'loginPost'])->name('login.post');

Route::get('/logout' , [AuthManager::class , 'logout'])->name('logout');

Route::get('/registration' , [AuthManager::class , 'registration'])->name('registration');
Route::post('/registration' , [AuthManager::class , 'registrationPost'])->name('registration.post');


// show table request

// Route::get('/overviewEmp' , [FormManager::class , 'OverviewEmp'])->name('overEmp');
Route::get('/overview' , [FormManager::class , 'Overview'])->name('overM');

Route::post('/loadMenuinfo' , [FormManager::class , 'LoadMenu'])->name('loadMenu.post');
Route::post('/filterTable' , [FormManager::class , 'FillterTable'])->name('filTable.post');



// Table Detail

// View / Manager Update
Route::get('/docForm/{id}' , [FormManager::class , 'ViewdocForm']);
Route::get('/leaveForm/{id}' , [FormManager::class , 'ViewleaveForm']);
Route::get('/outForm/{id}' , [FormManager::class , 'ViewoutForm']);

Route::post('/docMA/post' , [FormManager::class , 'ManagerAction'])->name('docMA.post');

// Emp Update
Route::get('/docEdit/{id}' , [FormManager::class , 'UpdatedocForm']);
Route::post('/docEdit/post' , [FormManager::class , 'UpdatedocPost'])->name('docE.post');

Route::get('/leaveEdit/{id}' , [FormManager::class , 'UpdateleaveForm']);
Route::post('/leaveEdit/post' , [FormManager::class , 'UpdateleavePost'])->name('leaveE.post');

Route::get('/outEdit/{id}' , [FormManager::class , 'UpdateoutForm']);
Route::post('/outEdit/post' , [FormManager::class , 'UpdateoutPost'])->name('outE.post');

// Create

Route::get('/docCreate' , [FormManager::class , 'CreatedocForm']);
Route::post('/docCreate/post' , [FormManager::class , 'CreatedocPost'])->name('docC.post');

Route::get('/leaveCreate' , [FormManager::class , 'CreateleaveForm']);
Route::post('/leaveCreate/post' , [FormManager::class , 'CreateleavePost'])->name('leaveC.post');

Route::get('/outCreate' , [FormManager::class , 'CreateoutForm']);
Route::post('/outCreate/post' , [FormManager::class , 'CreateoutPost'])->name('outC.post');

// Route::post('/leaveCreate' , [FormManager::class , 'CreateleaveForm']);
// Route::post('/outCreate' , [FormManager::class , 'CreateoutForm']);



// delete Form

Route::delete('/docDel/{id}' , [FormManager::class , 'DeleteDoc']);

Route::delete('/leaveDel/{id}' , [FormManager::class , 'DeleteLeave']);

Route::delete('/outDel/{id}' , [FormManager::class , 'DeleteOut']);