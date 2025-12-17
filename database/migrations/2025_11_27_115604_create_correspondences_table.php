<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('correspondences')) {
            Schema::create('correspondences', function (Blueprint $table) {
                $table->id();
                $table->string('document_number')->unique();
                $table->string('title');
                $table->enum('type', ['incoming', 'outgoing'])->default('incoming');
                $table->string('from_department')->nullable();
                $table->string('to_department')->nullable();
                $table->date('document_date');
                $table->string('subject');
                $table->text('description')->nullable();
                $table->string('file_path')->nullable();
                $table->string('file_name')->nullable();
                $table->string('file_type')->nullable();
                $table->bigInteger('file_size')->nullable();
                $table->enum('status', ['new', 'reviewed', 'completed', 'archived'])->default('new');
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
                $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
                $table->softDeletes();
                
                $table->index(['type', 'status']);
                $table->index('document_date');
                $table->index('document_number');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('correspondences');
    }
};
