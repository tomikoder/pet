<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pet/{id}', [PetController::class, 'findPetById'])
    ->where('id', '[0-9]+')
    ->name('pet.get');

Route::get('/update/pet/{id}', [PetController::class, 'updatePetForm'])
    ->where('id', '[0-9]+')
    ->name('pet.updateForm');

Route::post('/update/pet/{id}', [PetController::class, 'updatePetAction'])
    ->where('id', '[0-9]+')
    ->name('pet.updateAction');

Route::get('/pet', [PetController::class, 'addPetForm']);

Route::post('/pet', [PetController::class, 'addPetActions']);