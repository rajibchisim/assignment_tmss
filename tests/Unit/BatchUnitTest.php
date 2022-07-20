<?php

namespace Tests\Unit;

use App\Models\Batch;
use App\Models\Department;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BatchUnitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_many_students()
    {
        $department = Department::factory()->create();
        $batch = Batch::factory()->create([ 'department_id' => $department->id ]);

        $student = Student::factory()->create([ 'department_id' => $department->id, 'batch_name' => $batch->name, 'batch_id' => $batch->id ]);

        $this->assertInstanceOf(Student::class, $batch->fresh()->students()->first());
    }


    /** @test */
    public function it_belongs_to_department()
    {
        $this->withoutExceptionHandling();


        $department = Department::factory()->create();
        $batch = Batch::factory()->create([ 'department_id' => $department->id ]);

        $student = Student::factory()->create([ 'department_id' => $department->id, 'batch_name' => $batch->name ]);

        $this->assertInstanceOf(Department::class, $batch->fresh()->department);
    }
}
