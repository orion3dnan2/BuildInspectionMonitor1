<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('book_entries')) {
            Schema::create('book_entries', function (Blueprint $table) {
                $table->id();
                $table->string('book_number')->unique();
                $table->string('book_title');
                $table->enum('book_type', ['incoming', 'outgoing', 'internal', 'circular', 'decision'])->default('incoming');
                $table->date('date_written');
                $table->text('description')->nullable();
                $table->string('writer_name');
                $table->string('writer_rank')->nullable();
                $table->string('writer_office')->nullable();
                $table->enum('status', ['draft', 'submitted', 'approved', 'rejected', 'needs_modification'])->default('draft');
                $table->text('manager_comment')->nullable();
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('book_entries');
    }
};
