<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('records', function (Blueprint $table) {
            $table->string('tracking_number')->unique()->nullable()->after('id');
        });

        $records = DB::table('records')->whereNull('tracking_number')->get();
        foreach ($records as $record) {
            $trackingNumber = 'TRK-' . date('Y') . '-' . str_pad($record->id, 6, '0', STR_PAD_LEFT);
            DB::table('records')->where('id', $record->id)->update(['tracking_number' => $trackingNumber]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('records', function (Blueprint $table) {
            $table->dropColumn('tracking_number');
        });
    }
};
