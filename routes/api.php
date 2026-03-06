<?php

#use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ShiftController;

// Staff routes. Make sure to use Route not "route"
Route::get('/staff', [StaffController::class, 'index']); //gets all staff members
Route::post('/staff', [StaffController::class, 'store']); //creates a new staff member

// Adding shift routes 
Route::get('/shifts', [ShiftController::class, 'index']); //gets all shifts
Route::post('/shifts', [ShiftController::class, 'store']); //creates a new shift

//Could use POST or PUT but PATCH is more specific for updating a single field.
Route::patch('/shifts/{shift}', [ShiftController::class, 'update']); //updates a shift