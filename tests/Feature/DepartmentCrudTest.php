<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\Department;
use App\Models\Student;
use App\Models\User;
use \App\Models\Result as StudentResult;
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


    /** @test */
    public function department_can_be_deleted()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $department = Department::factory()->create();
        $departmentData = collect($department->toArray())->except(['updated_at', 'created_at'])->all();

        $otherDepartment = Department::factory()->create();
        $otherDepartmentData = collect($otherDepartment->toArray())->except(['updated_at', 'created_at'])->all();


        $this->assertDatabaseCount('departments', 2);
        $this->assertDatabaseHas('departments', $departmentData);
        $this->assertDatabaseHas('departments', $otherDepartmentData);


        $this->json('delete', '/api/departments/'.$department->id.'/delete');

        $this->assertDatabaseCount('departments', 1);
        $this->assertDatabaseHas('departments', $otherDepartmentData);
    }


    /** @test */
    public function deleting_department_deletes_all_related_batch_student_result_models()
    {
        $this->withoutExceptionHandling();


        $user = User::factory()->create();
        $this->actingAs($user);

        $department = Department::factory()->create();
        $batch = Batch::factory()->create(['department_id' => $department->id]);
        $student = Student::factory()->create(['batch_id' => $batch->id, 'department_id' => $department->id]);
        $result = StudentResult::factory()->create(['student_id' => $student->id]);


        $otherDepartment = Department::factory()->create();
        $otherBatch = Batch::factory()->create(['department_id' => $otherDepartment->id]);
        $otherStudent = Student::factory()->create(['batch_id' => $otherBatch->id, 'department_id' => $otherDepartment->id]);
        $otherResult = StudentResult::factory()->create(['student_id' => $otherStudent->id]);



        // StudentResult::factory(10)->create(['student_id'=>$student->id]);
        $this->assertDatabaseCount('departments', 2);
        $this->assertDatabaseCount('batches', 2);
        $this->assertDatabaseCount('students', 2);
        $this->assertDatabaseCount('results', 2);

        $this->assertDatabaseHas('departments', collect($department->toArray())->except(['updated_at', 'created_at'])->all());
        $this->assertDatabaseHas('batches', collect($batch->toArray())->except(['updated_at', 'created_at'])->all());
        $this->assertDatabaseHas('students', collect($student->toArray())->except(['updated_at', 'created_at'])->all());
        $this->assertDatabaseHas('results', collect($result->toArray())->except(['updated_at', 'created_at'])->all());

        $this->json('delete', '/api/departments/'.$department->id.'/delete');

        $this->assertDatabaseCount('departments', 1);
        $this->assertDatabaseCount('batches', 1);
        $this->assertDatabaseCount('students', 1);
        $this->assertDatabaseCount('results', 1);

        $this->assertDatabaseHas('departments', collect($otherDepartment->toArray())->except(['updated_at', 'created_at'])->all());
        $this->assertDatabaseHas('batches', collect($otherBatch->toArray())->except(['updated_at', 'created_at'])->all());
        $this->assertDatabaseHas('students', collect($otherStudent->toArray())->except(['updated_at', 'created_at'])->all());
        $this->assertDatabaseHas('results', collect($otherResult->toArray())->except(['updated_at', 'created_at'])->all());

    }

}
