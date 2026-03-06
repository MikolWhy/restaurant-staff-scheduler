<?php

namespace Tests\Unit;

use App\Http\Requests\StoreStaffRequest;
use PHPUnit\Framework\TestCase;

class StoreStaffRequestTest extends TestCase
{
    public function test_validation_rules_exist_for_all_fields(): void
    {
        $request = new StoreStaffRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('name', $rules);
        $this->assertArrayHasKey('role', $rules);
        $this->assertArrayHasKey('phone_number', $rules);

        $this->assertStringContainsString('required', $rules['name']);
        $this->assertStringContainsString('required', $rules['role']);
        $this->assertStringContainsString('required', $rules['phone_number']);
    }

    public function test_authorize_returns_true(): void
    {
        $request = new StoreStaffRequest();
        $this->assertTrue($request->authorize());
    }
}