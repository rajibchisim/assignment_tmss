<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $department = Department::factory()->create();
        return [
            'name' => $this->faker->name(),
            'batch_name' => 'bat_'.random_int(1, 20),
            'batch_id' => function() use($department) {
                return Batch::factory()->create(['department_id' => $department->id])->id;
            },
            'department_id' => function() use($department){
                return $department->id;
            }
        ];
    }
}
