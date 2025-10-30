<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\InvoicesController;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');
})->name('/');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('clients', ClientsController::class);
    Route::resource('invoices', InvoicesController::class);
});



// email test
// Route::get('/test-mail', function () {
//     Mail::raw('Hello from Laravel', function($m){
//         $m->to('test@inbox.mailtrap.io')->subject('Test');
//     });
//     return 'Sent';
// });


require __DIR__.'/auth.php';
