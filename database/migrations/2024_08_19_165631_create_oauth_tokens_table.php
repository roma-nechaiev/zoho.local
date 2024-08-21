<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('oauth_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('client_id')->nullable();
            $table->string('client_secret')->nullable();
            $table->string('refresh_token')->nullable();
            $table->string('access_token')->nullable();
            $table->string('grant_token')->nullable();
            $table->bigInteger('expiry_time');
            $table->string('redirect_url')->nullable();
            $table->string('api_domain')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oauth_tokens');
    }
};
