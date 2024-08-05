<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_settings', function (Blueprint $table) {
            $table->id();
            $table->text('languages');
            $table->string('default_language');
            $table->boolean('youtube_feature')->nullable()->default(0);
            $table->string('youtube_api')->nullable();
            $table->boolean('youtube_feature_free_tier')->nullable()->default(0);
            $table->boolean('rss_feature')->nullable()->default(0);
            $table->boolean('rss_feature_free_tier')->nullable()->default(0);
            $table->integer('gpt_4o_mini_credits')->default(0);
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
        Schema::dropIfExists('main_settings');
    }
};
