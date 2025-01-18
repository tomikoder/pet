<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;

Route::get('/pet/{id}', [PetController::class, 'findPetById'])
    ->where('id', '[0-9]+')
    ->name('pet.get');

Route::get('/update/pet/{id}', [PetController::class, 'updatePetForm'])
    ->where('id', '[0-9]+')
    ->name('pet.updateForm');

Route::put('/update/pet/{id}', [PetController::class, 'updatePetAction'])
    ->where('id', '[0-9]+')
    ->name('pet.updateAction');

Route::get('/add/pet', [PetController::class, 'addPetForm']);

Route::post('/add/pet', [PetController::class, 'addPetAction'])
    ->name('pet.addAction');

Route::get('/delete/pet', [PetController::class, 'deletePetForm']);

Route::delete('/delete/pet', [PetController::class, 'deletePetAction'])
    ->name('pet.deleteAction');

Route::get('/status', [PetController::class, 'searchByStatusForm']);

Route::get('list/status', [PetController::class, 'searchByStatusAction'])
    ->name('pet.SearchByStatus');

Route::get('/tags', [PetController::class, 'searchByTagsForm']);

Route::get('list/tags', [PetController::class, 'searchByStatusTags'])
    ->name('pet.SearchByTags');

