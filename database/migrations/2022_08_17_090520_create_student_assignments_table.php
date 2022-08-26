<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('file_name')->nullable();
            $table->boolean('verified')->default(false);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('assignment_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_assignments');
    }
}
