<?php

namespace Database\Seeders;


use App\Models\Subject;
use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        User::create([
            'name'=>'admin',
            'email'=>'superadmin@mail.ru',
            'password'=>'$2a$12$HEtD2CggESmYr5Zf5fdXe.am0CaS14YjEoMYVnFQT8QW7KLR5fRWe',
            'mobile'=>'099998',
            'reg_token'=>'Laravel',
            'role'=>'admin'
        ]);
//        User::create([
//            'name'=>'teacher1',
//            'email'=>'teacher1@mail.ru',
//            'password'=>'$2a$12$HEtD2CggESmYr5Zf5fdXe.am0CaS14YjEoMYVnFQT8QW7KLR5fRWe',
//            'mobile'=>'0111111',
//            'reg_token'=>'Laravel',
//            'role'=>'teacher'
//        ]);
//        User::create([
//            'name'=>'student1',
//            'email'=>'student1@mail.ru',
//            'password'=>'$2a$12$HEtD2CggESmYr5Zf5fdXe.am0CaS14YjEoMYVnFQT8QW7KLR5fRWe',
//            'mobile'=>'0111111',
//            'reg_token'=>'Laravel',
//            'role'=>'student'
//        ]);
        Course::create([
            'name'=>'Course 1'
        ]);
        Course::create([
            'name'=>'Course 2'
        ]);
        Course::create([
            'name'=>'Course 3'
        ]);
        Course::create([
            'name'=>'Course 4'
        ]);

        Subject::create([
            'name'=>'Mathematics'
        ]);
        Subject::create([
            'name'=>'Physics'
        ]);
        Subject::create([
            'name'=>'Chemistry'
        ]);
        Subject::create([
            'name'=>'History'
        ]);
    }

}
