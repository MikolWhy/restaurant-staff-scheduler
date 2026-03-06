<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShiftRequest extends FormRequest
{
    /**
     * No authorization required for this project.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Rules aligned with shifts table: staff_id nullable, date/time types, role enum.
     * day: remove after_or_equal:today if you need to allow past dates (e.g. backfilling missed shifts).
     * end_time after start_time: valid for same-day only; overnight shifts would need different logic.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            /**
             * Optional staff_id, if null/unassigned. but must be a valid staff member id.
             * Required date, must be today or in the future. Could change for backfill
             * Required Start and end time, must be in 24 hour format (HH:MM).
             * Required Role, must be server, cook, or manager.
             */
            'staff_id' => 'nullable|exists:staff,id',
            'day' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            // 'end_time' => 'required|date_format:H:i',
            'role' => 'required|in:server,cook,manager',
        ];
    }

    public function messages(): array
    {
        return [
            // could allow past dates in the future if we need to backfill missed shifts.
            'day.after_or_equal' => 'Shift date cannot be in the past.',

            // could allow overnight shifts in the future if need by removing this rule.
            'end_time.after' => 'End time must be after start time.',
            'staff_id.exists' => 'Selected staff member does not exist.',
            'role.in' => 'Role must be server, cook, or manager.',
        ];
    }
}