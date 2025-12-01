<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('user_permissions');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'rank')) {
                $table->string('rank')->nullable()->after('password');
            }
            if (!Schema::hasColumn('users', 'office')) {
                $table->string('office')->nullable()->after('rank');
            }
        });

        if (Schema::hasColumn('users', 'permissions')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('permissions');
            });
        }
        if (Schema::hasColumn('users', 'system_access')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('system_access');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'rank')) {
                $table->dropColumn('rank');
            }
            if (Schema::hasColumn('users', 'office')) {
                $table->dropColumn('office');
            }
        });
    }
};
