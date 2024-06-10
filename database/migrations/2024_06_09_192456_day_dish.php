<?php

use App\Models\Day;
use App\Models\Dish;
use App\Models\Tag;
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
        Schema::create('day_dish', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Day::class);
            $table->foreignIdFor(Dish::class);
            $table->foreignIdFor(Tag::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('day_dish');
    }
};
