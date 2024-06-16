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
        Schema::table("dishes", function (Blueprint $table) {
            $table->float("multiplier")->default(1);
            $table->string("type")->default("pcs");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns("dishes", ["type", "multiplier"]);
    }
};
