<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('employees')) {
            Schema::create('employees', function (Blueprint $table) {
                $table->id();
                $table->string('employee_number')->unique();
                $table->string('first_name');
                $table->string('second_name')->nullable();
                $table->string('third_name')->nullable();
                $table->string('fourth_name')->nullable();
                $table->string('civil_id')->unique();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
                $table->string('job_title')->nullable();
                $table->string('rank')->nullable();
                $table->date('hire_date')->nullable();
                $table->date('birth_date')->nullable();
                $table->enum('gender', ['male', 'female'])->default('male');
                $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->default('single');
                $table->text('address')->nullable();
                $table->decimal('salary', 10, 3)->nullable();
                $table->integer('annual_leave_balance')->default(30);
                $table->integer('sick_leave_balance')->default(15);
                $table->enum('status', ['active', 'inactive', 'on_leave', 'terminated'])->default('active');
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
