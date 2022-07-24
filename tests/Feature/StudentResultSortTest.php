<?php

namespace Tests\Feature;

use App\Models\Result;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class StudentResultSortTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function student_result()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $student = Student::factory()->create();

        $result = Result::factory()->create(['student_id' => $student->id, 'gpa' => 5, 'date' => '2022-07-15']);
        Result::factory()->create(['student_id' => $student->id, 'gpa' => 4.5, 'date' => '2022-07-16']);
        Result::factory()->create(['student_id' => $student->id, 'gpa' => 4, 'date' => '2022-07-17']);
        Result::factory()->create(['student_id' => $student->id, 'gpa' => 3.5, 'date' => '2022-07-18']);
        Result::factory()->create(['student_id' => $student->id, 'gpa' => 3, 'date' => '2022-07-19']);
        Result::factory()->create(['student_id' => $student->id, 'gpa' => 2.5, 'date' => '2022-07-20']);
        Result::factory()->create(['student_id' => $student->id, 'gpa' => 2, 'date' => '2022-07-21']);

        $response = $this->json('get', '/api/students/'.$student->id.'?results=true&&sort[gpa]=desc&&sort[date]=asc&&');

        $response->assertJsonFragment($result->toArray());

    }
}
