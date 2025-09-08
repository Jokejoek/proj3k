<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('content_views', function (Blueprint $table) {
            $table->id();
            $table->string('viewable_type', 120);  // เช่น App\Models\Cve หรือ App\Models\Tool
            $table->string('viewable_id', 64);     // รองรับทั้ง cve_id (string) และ tool_id (int→string)
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip', 64)->nullable();
            $table->string('ua', 255)->nullable();
            $table->timestamps();

            $table->index(['viewable_type', 'viewable_id']);
            $table->index('created_at');
        });
    }

    public function down(): void {
        Schema::dropIfExists('content_views');
    }
};

