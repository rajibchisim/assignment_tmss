<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\Department;
use App\Models\Student;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DepartmentCrudTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function only_department_all_paginated()
    {
        $this->withExceptionHandling();

        $this->actingAs(User::factory()->create());

        $departments = Department::factory(2)->create();

        $response = $this->json('get', '/api/departments');

        // partial match. checking paginator elements are present. And departments are present
        $response->assertJson(
            [
                'status' => 200,
                'departments_paginated' => [
                    'current_page' => 1,
                    'data' => $departments->toArray()
                ]
            ]
        );
    }

    /** @test */
    public function department_all_optional_load_batches()
    {
        $this->withExceptionHandling();

        $this->actingAs(User::factory()->create());

        $batch = Batch::factory()->create();

        $response = $this->json('get', '/api/departments?batches=true');

        // partial match. checking batch are present
        $response->assertJsonFragment(
            $batch->toArray()
        );
    }


    /** @test */
    public function count_all_student_and_batches()
    {
        $this->withExceptionHandling();

        $department = Department::factory()->create();
        $batches = Batch::factory(15)->create(['department_id' => $department->id]);

        $batches->each(function($batch) use($department) {
            $students = Student::factory(50)->create(['department_id' => $department->id, 'batch_id' => $batch->id]);
        });

        $this->actingAs(User::factory()->create());
        $response = $this->json('get', '/api/departments/'.$department->id.'?batches=true');

        $response->assertJson([
            'status' => 200,
            'department' => [
                'batches' => [
                    'data' => []
                ],
                'students_count' => 750,
                'batches_count' => 15,
            ]
        ]);
    }
}
