<?php

namespace Tests\Feature;

use App\Models\Result;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use AssertionError;

class StudentResultSortTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function student_sort_result()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $student = Student::factory()->create();

        $expected = [
            [ 'student_id' => $student->id, 'date' => '2022-07-18', 'gpa' => 5 ],
            [ 'student_id' => $student->id, 'date' => '2022-07-25', 'gpa' => 4.5 ],
            [ 'student_id' => $student->id, 'date' => '2022-07-15', 'gpa' => 4 ],
            [ 'student_id' => $student->id, 'date' => '2022-07-20', 'gpa' => 3.5 ],
            [ 'student_id' => $student->id, 'date' => '2022-07-21', 'gpa' => 3 ],
            [ 'student_id' => $student->id, 'date' => '2022-07-22', 'gpa' => 2.5 ],
            [ 'student_id' => $student->id, 'date' => '2022-07-30', 'gpa' => 2 ],
        ];

        foreach ($expected as $result) {
            Result::factory()->create($result);
        }


        $response = $this->json('get', '/api/students/'.$student->id.'?results=true');

        // $response->assertJsonFragment($studentsResultData);

        $array = collect(
            $response->json()['student']['results']['data'])
            ->transform(function($item) {
                return collect($item)->except(['id', 'created_at', 'updated_at'])->all();
            })
            ->all();

            foreach ($expected as $item) {
                $this->assertTrue(collect($array)->contains($item));
            }

            $response = $this->json('get', '/api/students/'.$student->id.'?results=true');
            // &&sort[gpa]=desc&&sort[date]=asc&&
            collect($expected)->sortBy('date', SORT_REGULAR, true)->sortBy('gpa')->dd();
        // $this->assert

    }


    // works only for associative array
    public function assertArraysAreEqualIgnoringKeyOrder(array $expected, array $array)
    {
        $this->assertEquals([], array_diff_key($expected, $array));
        $this->assertEquals([], array_diff_key($array, $expected));

        foreach ($expected as $key => $value) {
            if (is_array($value)) {
                $this->assertArraysAreEqualIgnoringKeyOrder($value, $array[$key]);
            } else {
                $this->assertEquals($value, $array[$key], "Array key '$key'");
            }
        }
    }


    public function assertArraysAreEqualIgnoringIndexOrder(array $expected, array $array)
    {
        $this->assertEquals([], array_diff_key($expected, $array));
        $this->assertEquals([], array_diff_key($array, $expected));

        foreach ($expected as $key => $value) {
            if (is_array($value)) {
                $this->assertArraysAreEqualIgnoringIndexOrder($value, $array[$key]);
            } else {
                $this->assertEquals($value, $array[$key], "Array key '$key'");
            }
        }
    }
}
