<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Recreates student_tuitions_fees, which was accidentally dropped.
     * Schema matches the table as left by
     * 2026_07_31_040000_split_student_tuitions_into_orders_and_fee_lines.php.
     */
    public function up(): void
    {
        Schema::create('student_tuitions_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tuition_id')->constrained('student_tuitions')->cascadeOnDelete();
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->enum('type', ['class_fee', 'late_pickup', 'absence_deduction', 'adjustment'])->default('adjustment');
            $table->bigInteger('amount');
            $table->bigInteger('original_amount')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('adjusted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('adjusted_at')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_tuitions_fees');
    }
};
