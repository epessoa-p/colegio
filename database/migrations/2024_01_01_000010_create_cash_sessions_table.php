<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cash_register_id');
            $table->unsignedBigInteger('personal_id');
            $table->decimal('opening_amount', 14, 2)->default(0);
            $table->decimal('closing_amount', 14, 2)->nullable();
            $table->decimal('expected_amount', 14, 2)->nullable();
            $table->decimal('difference', 14, 2)->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamp('opened_at')->useCurrent();
            $table->timestamp('closed_at')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('opened_by');
            $table->unsignedBigInteger('closed_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('cash_register_id', 'idx_cash_sessions_register');
            $table->index('personal_id', 'idx_cash_sessions_personal');
            $table->index('status', 'idx_cash_sessions_status');
            $table->index('opened_by');
            $table->index('closed_by');

            $table->foreign('cash_register_id')
                ->references('id')
                ->on('cash_registers')
                ->cascadeOnDelete();
            $table->foreign('personal_id')
                ->references('id')
                ->on('personals')
                ->cascadeOnDelete();
            $table->foreign('opened_by')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
            $table->foreign('closed_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_sessions');
    }
};
