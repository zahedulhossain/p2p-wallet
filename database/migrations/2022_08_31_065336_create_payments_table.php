<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_wallet_id')->constrained('wallets');
            $table->foreignId('to_wallet_id')->constrained('wallets');
            $table->enum('status', ['requested', 'approved', 'declined']);
            $table->double('amount');
            $table->double('conversion_rate')->nullable();
            $table->double('converted_amount')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
