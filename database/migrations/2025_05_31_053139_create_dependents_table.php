<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dependents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('full_name'); // Họ và tên người phụ thuộc
            $table->string('date_of_birth')->nullable(); // Ngày sinh (có thể là string hoặc date)
            $table->string('citizen_id')->nullable(); // Số CCCD/CMND của người phụ thuộc (nếu có)
            $table->string('relationship'); // Mối quan hệ (con, cha, mẹ, vợ, chồng,...)
            $table->integer('months_registered')->default(12); // Số tháng được tính giảm trừ trong năm
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dependents');
    }
};