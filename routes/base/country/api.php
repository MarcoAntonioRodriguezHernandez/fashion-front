<?php

use App\Http\Controllers\Base\Country\CountryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for Country module.
|
*/

Route::post('countries', [CountryController::class, 'createRecord']);
