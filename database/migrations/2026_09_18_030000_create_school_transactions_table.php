<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Append-only ledger of every credit/debit against a school's balance.
     * Rows are never updated or deleted; corrections are new rows with
     * type = adjustment. schools.balance is a cached running total kept in
     * sync with this table inside the same DB transaction as each insert.
     */
    public function up(): void
    {
        Schema::create('school_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->enum('type', [
                'tuition_payment',
                'topup',
                'billing_charge',
                'ticket_charge',
                'payout',
                'adjustment',
                'refund',
            ]);
            $table->enum('direction', ['credit', 'debit']);
            $table->bigInteger('amount');
            $table->bigInteger('balance_after');
            $table->string('reference_type', 50)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('idempotency_key')->nullable()->unique();
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->index(['school_id', 'created_at']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_transactions');
    }
};
