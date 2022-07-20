<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use \App\Models\Result as StudentResult;
use App\Models\Student;

class StudentCrudTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function student_can_be_deleted()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $student = Student::factory()->create();
        $studentData = collect($student->toArray())->except(['updated_at', 'created_at'])->all();


        $this->assertDatabaseCount('students', 1);
        $this->assertDatabaseHas('students', $studentData);
        $this->json('delete', '/api/students/'.$student->id.'/delete');
        $this->assertDatabaseCount('students', 0);
        $this->assertDatabaseMissing('students', $studentData);
    }

    /** @test */
    public function deleting_student_deletes_all_related_results()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $student = Student::factory()->create();

        StudentResult::factory(10)->create(['student_id'=>$student->id]);
        $this->assertCount(10, $student->fresh()->results);
        $this->assertDatabaseCount('results', 10);

        $this->json('delete', '/api/students/'.$student->id.'/delete');
        $this->assertDatabaseCount('results', 0);

    }
}
