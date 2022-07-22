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
        $min = 2;
        $max = 16;
        $dateOffset = random_int($min, $max);
        return [
            'student_id' => function() {
                return Student::factory()->create()->id;
            },
            'gpa' => $this->faker->randomFloat(1, 2, 5),
            'date' => Carbon::now()->subDay($max/2)->addDay($dateOffset)->format('Y-m-d')
        ];
    }
}
