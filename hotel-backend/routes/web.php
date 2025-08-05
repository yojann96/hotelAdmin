<?php

use App\Http\Controllers\API\HotelController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HotelController::class, 'index']);
