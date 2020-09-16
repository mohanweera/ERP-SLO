<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\Modules\Slo\Entities\CourseRequirement::class, function (Faker $faker) {
    return [
        'course_id' => function(){
         return \Modules\Academic\Entities\Course::all()->unique();
        }
    ];
});
