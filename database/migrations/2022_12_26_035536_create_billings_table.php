<?php

use App\Models\Debt;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('debt_id');
            $table->string('bar_code');
            $table->string('our_number');
            $table->date('due_date');
            $table->enum('status', ['awaiting', 'paid'])->default('awaiting');
            $table->decimal('paid_amount', 10, 2)->nullable();
            $table->date('paid_at')->nullable();
            $table->string('paid_by')->nullable();
            $table->timestamps();
            $table->foreign('debt_id')->references('id')->on('debts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billings');
    }
};
