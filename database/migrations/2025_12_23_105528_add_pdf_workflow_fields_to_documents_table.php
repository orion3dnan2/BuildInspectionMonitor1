<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (!Schema::hasColumn('documents', 'original_file_path')) {
                $table->string('original_file_path')->nullable()->after('file_path');
            }
            if (!Schema::hasColumn('documents', 'pdf_path')) {
                $table->string('pdf_path')->nullable()->after('original_file_path');
            }
            if (!Schema::hasColumn('documents', 'signed_pdf_path')) {
                $table->string('signed_pdf_path')->nullable()->after('pdf_path');
            }
            if (!Schema::hasColumn('documents', 'is_signed')) {
                $table->boolean('is_signed')->default(false)->after('signed_pdf_path');
            }
            if (!Schema::hasColumn('documents', 'signed_at')) {
                $table->timestamp('signed_at')->nullable()->after('is_signed');
            }
            if (!Schema::hasColumn('documents', 'signed_by')) {
                $table->foreignId('signed_by')->nullable()->after('signed_at')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('documents', 'archived_at')) {
                $table->timestamp('archived_at')->nullable()->after('signed_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn([
                'original_file_path',
                'pdf_path',
                'signed_pdf_path',
                'is_signed',
                'signed_at',
                'signed_by',
                'archived_at'
            ]);
        });
    }
};
