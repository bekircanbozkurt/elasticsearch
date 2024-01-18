<?php

use App\Http\Controllers\ElasticSearch;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ElasticSearch::class, 'index'])->name('home');
// Route::any('/elastic', [ElasticSearch::class, 'showChart']);
Route::get('/fetch-data', [ElasticSearch::class, 'fetchData'])->name('fetchData');
Route::get('counter/{id}', [ElasticSearch::class, 'index'])->name('counter');



