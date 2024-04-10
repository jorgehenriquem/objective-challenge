<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;

Route::post('/conta', [AccountController::class, 'createAccountWithBalance']);

Route::get('/conta', [AccountController::class, 'showAccount']);

Route::post('/transacao', [TransactionController::class, 'createTransaction']);