<?php

use App\Http\Controllers\SurveyResponseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/r/{token}', [SurveyResponseController::class, 'show'])
    ->name('survey.respond');

Route::post('/r/{token}', [SurveyResponseController::class, 'store'])
    ->name('survey.submit');

