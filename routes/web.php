<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangController;

Route::get('/', function () {
    return view('content.dashboard');
});