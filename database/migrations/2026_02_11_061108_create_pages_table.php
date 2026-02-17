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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('slider_id')->nullable();
            $table->json('event_ids')->nullable();
            $table->string('page_title', 191);
            $table->string('sub_title')->nullable();
            $table->string('slug', 191)->unique();
            $table->longText('body')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_navbar')->default(false);
            $table->integer('sort_order')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('pages')->onDelete('cascade');
            $table->foreign('slider_id')->references('id')->on('sliders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
