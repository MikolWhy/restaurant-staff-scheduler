<?php

#use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;

// Staff routes
route::get('/staff', [StaffController::class, 'index']); //gets all staff members
route::post('/staff', [StaffController::class, 'store']); //creates a new staff member


