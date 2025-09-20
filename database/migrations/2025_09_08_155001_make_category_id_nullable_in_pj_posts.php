<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // หากมี foreign key เดิม ให้ถอดก่อน (ชื่อคีย์อาจต่าง ตรวจชื่อใน DB)
        Schema::table('pj_posts', function (Blueprint $table) {
            if (Schema::hasColumn('pj_posts', 'category_id')) {
                try { $table->dropForeign(['category_id']); } catch (\Throwable $e) {}
            }
        });

        // แก้เป็น nullable
        Schema::table('pj_posts', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->change();
        });

        // ใส่ FK กลับแบบ on delete set null (ถ้ามีตาราง categories)
        Schema::table('pj_posts', function (Blueprint $table) {
            if (Schema::hasColumn('pj_posts', 'category_id')) {
                $table->foreign('category_id')
                      ->references('category_id')->on('pj_categories')
                      ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pj_posts', function (Blueprint $table) {
            try { $table->dropForeign(['category_id']); } catch (\Throwable $e) {}
            $table->unsignedBigInteger('category_id')->nullable(false)->default(0)->change();
        });
    }
};
