<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('phishing_urls', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('url');
            $table->string('domain');
            $table->integer('report_count')->default(0);
            $table->timestamp('first_reported_at');
            $table->timestamp('last_seen_at');
            $table->string('status')->default('active');
            $table->string('targeted_brand')->nullable();
            $table->timestamps();
            
            $table->unique('url');
            $table->index('domain');
        });
    }

    public function down()
    {
        Schema::dropIfExists('phishing_urls');
    }
};