<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'student_id' => function() {
                return Student::factory()->create()->id;
            },
            'gpa' => $this->faker->randomFloat(1, 2, 5),
            'date' => Carbon::now()->format('Y-m-d')
        ];
    }
}
