<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('iot_devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('device_id')->unique();
            $table->string('device_type');
            $table->string('manufacturer');
            $table->string('model');
            $table->string('firmware_version');
            $table->string('ip_address', 45)->nullable();
            $table->string('mac_address')->nullable();
            $table->string('location')->nullable();
            $table->string('status')->default('active');
            $table->timestamp('last_seen_at');
            $table->boolean('is_compromised')->default(false);
            $table->timestamps();
            
            $table->index(['device_type', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('iot_devices');
    }
};