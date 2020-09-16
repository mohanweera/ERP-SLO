<?php

namespace Modules\Academic\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Academic\Entities\Course;
use Modules\Academic\Entities\Department;
use Modules\Academic\Entities\Faculty;

class AcademicDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        factory(Faculty::class, 2)->create();
        factory(Department::class,4)->create();

        factory(Course::class,10)->create();
        // $this->call("OthersTableSeeder");
    }
}
