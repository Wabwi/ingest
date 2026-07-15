<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;
use App\Models\Meal;
use App\Models\BowelMovement;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Populate missing UUIDs for meals
        Meal::whereNull('uuid')
            ->orWhere('uuid', '')
            ->chunkById(100, function ($meals) {
                foreach ($meals as $meal) {
                    $meal->update(['uuid' => (string) Str::uuid()]);
                }
            });

        // Populate missing UUIDs for bowel movements
        BowelMovement::whereNull('uuid')
            ->orWhere('uuid', '')
            ->chunkById(100, function ($poops) {
                foreach ($poops as $poop) {
                    $poop->update(['uuid' => (string) Str::uuid()]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse action needed
    }
};
