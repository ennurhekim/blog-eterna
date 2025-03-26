<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('app'); // Vue.js uygulamanızın çalışacağı sayfa
})->where('any', '.*');