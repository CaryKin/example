<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;

Route::get('/order', [IndexController::class, 'index'])->name('index');
