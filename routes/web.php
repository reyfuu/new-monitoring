<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use App\Http\Controllers\BimbinganController;
use Telegram\Bot\Laravel\Facades\Telegram;


Route::get('/', function () {
    return view('welcome');
});

// Redirect root ke dashboard
Route::get('/', function () {
    return redirect('/dashboard');
});

// Redirect /admin ke dashboard (jaga-jaga)
Route::get('/admin', function () {
    return redirect('/dashboard');
});

Route::get("/get-updates", function () {
  $updates = Telegram::getUpdates();
  return $updates;
});

Route::get('/view-pdf/{filename)',function ($filename){
    $path = "private/". $filename;

    if(!Storage::disk('local')->exists($path)){
        abort(404);
    }

return Storage::disk('local')->response($path);

})->name('pdf.view')->middleware('auth');

Route::get('/bimbingans/{bimbingan}', [BimbinganController::class, 'show'])->name('bimbingans.show');



