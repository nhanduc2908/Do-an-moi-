<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mobile_devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('device_name');
            $table->string('device_type');
            $table->string('os_version');
            $table->string('device_id')->unique();
            $table->string('imei')->nullable();
            $table->boolean('is_jailbroken')->default(false);
            $table->boolean('is_compliant')->default(true);
            $table->timestamp('last_compliance_check')->nullable();
            $table->timestamp('last_seen_at');
            $table->string('status')->default('active');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('mobile_devices');
    }
};