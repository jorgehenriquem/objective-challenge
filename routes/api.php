<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;


Route::post('/conta', [AccountController::class, 'createAccountWithBalance']);
