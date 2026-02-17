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
        // Menus table
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            // Note: The user's code had page_id here which seems to imply a menu belongs to a page?
            // Or maybe it's a default page? The user's code: $table->foreignId('page_id')->constrained('pages')->onDelete('cascade');
            // But they ALSO have a pivot table menu_page. This seems redundant or specific to their logic.
            // I will strictly follow their provided code.
            $table->foreignId('page_id')->constrained('pages')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Pivot table: menu_page
        Schema::create('menu_page', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->foreignId('page_id')->constrained('pages')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['menu_id', 'page_id']); // prevent duplicates
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_page');
        Schema::dropIfExists('menus');
    }
};
