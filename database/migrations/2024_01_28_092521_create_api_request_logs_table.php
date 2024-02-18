<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('api_request_logs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->nullable();
            $table->string('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('method')->nullable();
            $table->string('path')->nullable();
            $table->boolean('show_activity')->default(0);
            $table->text('activity')->nullable();
            $table->string('header_code')->nullable();
            $table->text('request_data')->nullable();
            $table->text('response_data')->nullable();
            $table->string('third_party_header_code')->nullable();
            $table->text('third_party_request_data')->nullable();
            $table->text('third_party_response_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_request_logs');
    }
};
