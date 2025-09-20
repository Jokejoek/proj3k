<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pj_posts', function (Blueprint $table) {
            if (!Schema::hasColumn('pj_posts', 'image_url')) {
                $table->string('image_url', 255)->nullable()->after('content');
            }
        });
    }
    public function down(): void
    {
        Schema::table('pj_posts', function (Blueprint $table) {
            if (Schema::hasColumn('pj_posts', 'image_url')) {
                $table->dropColumn('image_url');
            }
        });
    }
};
