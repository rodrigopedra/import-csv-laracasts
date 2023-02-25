<?php

use App\Http\Controllers\ContactsController;
use App\Http\Controllers\ImportContactsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ContactsController::class, 'index'])->name('contacts.index');

Route::resource('import', ImportContactsController::class)
    ->whereUuid('import')
    ->except(['index', 'show']);
