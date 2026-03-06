<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStaffRequest;
use App\Models\Staff;
// for Type hinting
use Illuminate\Http\JsonResponse;

class StaffController extends Controller
{
    public function index(): JsonResponse {

       # get all staff members
        $staff = Staff::all();

        return response() -> json($staff);
    }

    // "store" a RESTful convention for creating a new object in the database
    public function store (StoreStaffRequest $request): JsonResponse {

        $staff = Staff::create($request->validated());

        return response() -> json($staff, 201 );
    }

}
