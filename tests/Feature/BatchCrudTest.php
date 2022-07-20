<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\Department;
use App\Models\Student;
use App\Models\User;
use \App\Models\Result as StudentResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BatchCrudTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function batch_can_be_deleted()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $batch = Batch::factory()->create();
        $batchData = collect($batch->toArray())->except(['updated_at', 'created_at'])->all();


        $this->assertDatabaseCount('batches', 1);
        $this->assertDatabaseHas('batches', $batchData);
        $this->json('delete', '/api/batches/'.$batch->id.'/delete');
        $this->assertDatabaseCount('batches', 0);
        $this->assertDatabaseMissing('batches', $batchData);
    }

    /** @test */
    public function deleting_student_deletes_all_related_results()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $department = Department::factory()->create();


        $batch = Batch::factory()->create(['department_id' => $department->id]);
        $student = Student::factory()->create(['batch_id' => $batch->id, 'department_id' => $department->id]);
        $result = StudentResult::factory()->create(['student_id' => $student->id]);



        $otherBatch = Batch::factory()->create(['department_id' => $department->id]);
        $otherStudent = Student::factory()->create(['batch_id' => $otherBatch->id, 'department_id' => $department->id]);
        $otherResult = StudentResult::factory()->create(['student_id' => $otherStudent->id]);



        // StudentResult::factory(10)->create(['student_id'=>$student->id]);
        $this->assertDatabaseCount('students', 2);
        $this->assertDatabaseCount('results', 2);

        $this->json('delete', '/api/batches/'.$batch->id.'/delete');

        $this->assertDatabaseCount('results', 1);
        $this->assertDatabaseCount('students', 1);

        $this->assertDatabaseHas('batches', collect($otherBatch->toArray())->except(['updated_at', 'created_at'])->all());
        $this->assertDatabaseHas('students', collect($otherStudent->toArray())->except(['updated_at', 'created_at'])->all());
        $this->assertDatabaseHas('results', collect($otherResult->toArray())->except(['updated_at', 'created_at'])->all());

    }
}
