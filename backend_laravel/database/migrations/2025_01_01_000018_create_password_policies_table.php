<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('password_policies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('min_length')->default(12);
            $table->boolean('require_uppercase')->default(true);
            $table->boolean('require_lowercase')->default(true);
            $table->boolean('require_numbers')->default(true);
            $table->boolean('require_special')->default(true);
            $table->integer('expiry_days')->default(90);
            $table->integer('history_count')->default(5);
            $table->integer('max_attempts')->default(5);
            $table->integer('lockout_minutes')->default(15);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('password_policies');
    }
};