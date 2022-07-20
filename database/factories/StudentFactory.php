<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use stdClass;

class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'batch_name' => 'bat_'.random_int(1, 20),
            'department_id' => function() {
                return Department::factory()->create()->id;
            },
            'batch_id' => function() use(&$department) {
                return Batch::factory()->create()->id;

            },
        ];
    }
}
