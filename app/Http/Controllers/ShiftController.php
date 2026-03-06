<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;
//laravel runs their rules() and authorizes() methods before controller methods
use App\Http\Requests\StoreShiftRequest;
use App\Http\Requests\UpdateShiftRequest;
use Illuminate\Http\JsonResponse;

class ShiftController extends Controller
{
    public function index(): JsonResponse
    {
        //eager load the staff relationship to avoid n+1 queries
        $shifts = Shift::with('staff')-> get();
        
        return response() -> json($shifts);
    }

    public function store (StoreShiftRequest $request): JsonResponse
    {
        //injects request, validates, and only calls controller if valid
        $shift = Shift::create($request->validated());
        //load staff relation to include even if null/unassigned.
        $shift->load('staff');

        return response() -> json($shift, 201);
    }

    public function update (UpdateShiftRequest $request, Shift $shift): JsonResponse
    {
        $shift->update($request->validated());
        $shift->load('staff');

        return response() -> json($shift);
    }

}
