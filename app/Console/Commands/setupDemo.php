<?php

namespace App\Console\Commands;

use App\Models\Batch;
use App\Models\Department;
use App\Models\Result;
use App\Models\Student;
use App\Models\User;
use Illuminate\Console\Command;

class setupDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Does initial migration seeding';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->call('key:generate');
        $this->call('jwt:secret');
        $this->call('jwt:generate-certs');

        $this->call('migrate:fresh');

        $departments = [
            'Mathematics Department',
            // 'Philosophy Department',
            'Computer Science & Engineering',
            'Computing & Information System (CIS)',
            'Software Engineering',
            // 'Environmental Science and Disaster Management',
            'Multimedia & Creative Technology (MCT)',
            // 'General Educational Development (GED)',
            'Information Technology and Management',
            // 'Electronics & Telecommunication Engineering',
            // 'Textile Engineering',
            // 'Electrical & Electronic Engineering',
            // 'Architecture',
            // 'Civil Engineering',
            // 'Pharmacy',
            // 'Public Health',
            // 'Nutrition & Food Engineering',
            // 'English',
            // 'Law',
            // 'Journalism & Mass Communication',
            // 'Development Studies',
            // 'Information Science and Library Management',
        ];





        $batches = ['summer', 'spring', 'fall', 'winter'];
        $batchCount = 15;
        $studentCount = 12;
        $resultCountMax = 6;


        $this->info('Seeding Model: Department, Batch, Student, Result');
        $barProgress = $this->output->createProgressBar(count($departments) * $batchCount * $studentCount);
        $barProgress->start();

        foreach($departments as $departmentName) {

            $department = Department::factory()->create(['name' => $departmentName]);


            $createBatchCount = $batchCount;
            $batchIndex = 0;
            $set = 1;




            while($createBatchCount != 0) {
                $barProgress->advance();

                $batch = $this->createbatch($department->id, $batches[$batchIndex].$set);
                $batchIndex++;
                if($batchIndex == 4) {
                    $batchIndex = 0;
                    $set++;
                }


                for($i = 0; $i < $studentCount; $i++) {
                    $barProgress->advance();

                    $student = Student::factory()->create([
                                'department_id' => $department->id,
                                'batch_id' => $batch->id,
                                'batch_name' => $batch->name
                            ]);

                    $randCount = rand(3, $resultCountMax);
                    Result::factory($randCount)->create(['student_id' => $student->id]);
                }

                $createBatchCount--;
            }

        };

        $barProgress->finish();
        $this->info('');
        $this->info('Database seeding completed');

        $this->newLine(1);
        $this->warn('Generating admin user ...');
        $username = 'admin@admin.com';
        User::factory()->create(['email' => $username, 'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi']);

        $this->table(
            ['Email', 'Password'],
            [[$username, 'password']]
        );
        $this->info('Setup complete.');

        return 0;
    }

    public function createbatch($departmentId, $name)
    {
        return Batch::factory()->create(['department_id' => $departmentId, 'name' => $name]);
    }
}
