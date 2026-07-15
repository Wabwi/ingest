<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Meal;
use App\Models\BowelMovement;

class DeviceSetupController extends Controller
{
    /**
     * Display the device setup page on the web app.
     */
    public function index()
    {
        $user = auth()->user();
        return view('device_setup', compact('user'));
    }

    /**
     * Generate a new 6-digit numeric setup code for the user.
     */
    public function generateCode()
    {
        $user = auth()->user();
        
        // Generate a simple 6-digit numeric code for easy mobile entry
        $code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        
        $user->update([
            'device_setup_code' => $code,
            'device_setup_expires_at' => Carbon::now()->addHours(2), // Valid for 2 hours
        ]);

        return redirect()->route('device-setup.index')->with('success', 'Setup code generated successfully!');
    }

    /**
     * API: Verify the device setup code entered on the mobile app.
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = User::where('device_setup_code', $request->code)
            ->where('device_setup_expires_at', '>', Carbon::now())
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'The code entered is invalid or has expired. Please generate a new code on the web app.'
            ], 400);
        }

        // Generate an API token if the user doesn't have one
        if (!$user->api_token) {
            $user->api_token = Str::random(80);
        }

        // Consume the code so it cannot be used again
        $user->device_setup_code = null;
        $user->device_setup_expires_at = null;
        $user->save();

        return response()->json([
            'success' => true,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'password_hash' => $user->password, // Sent securely so local mobile DB can authenticate the user offline
                'api_token' => $user->api_token,
            ]
        ]);
    }

    /**
     * API: Sync/backup local logs from the mobile app to the web server.
     */
    public function syncData(Request $request)
    {
        $user = auth()->user();

        $meals = $request->input('meals', []);
        $bowelMovements = $request->input('bowel_movements', []);

        $syncedMealUuids = [];
        $syncedBmUuids = [];

        // Sync Meals
        foreach ($meals as $mealData) {
            if (empty($mealData['uuid'])) {
                continue;
            }

            $meal = $user->meals()->withTrashed()->where('uuid', $mealData['uuid'])->first();

            if ($meal) {
                if (!empty($mealData['deleted_at'])) {
                    $meal->update(['deleted_at' => Carbon::parse($mealData['deleted_at'])]);
                } else {
                    $meal->update([
                        'meal_type' => $mealData['meal_type'],
                        'description' => $mealData['description'],
                        'eaten_at' => Carbon::parse($mealData['eaten_at']),
                        'deleted_at' => null, // restore if soft-deleted
                    ]);
                }
            } else {
                $user->meals()->create([
                    'uuid' => $mealData['uuid'],
                    'meal_type' => $mealData['meal_type'],
                    'description' => $mealData['description'],
                    'eaten_at' => Carbon::parse($mealData['eaten_at']),
                    'deleted_at' => !empty($mealData['deleted_at']) ? Carbon::parse($mealData['deleted_at']) : null,
                ]);
            }
            $syncedMealUuids[] = $mealData['uuid'];
        }

        // Sync Bowel Movements
        foreach ($bowelMovements as $bmData) {
            if (empty($bmData['uuid'])) {
                continue;
            }

            $bm = $user->bowelMovements()->withTrashed()->where('uuid', $bmData['uuid'])->first();

            if ($bm) {
                if (!empty($bmData['deleted_at'])) {
                    $bm->update(['deleted_at' => Carbon::parse($bmData['deleted_at'])]);
                } else {
                    $bm->update([
                        'bristol_type' => $bmData['bristol_type'],
                        'notes' => $bmData['notes'] ?? null,
                        'logged_at' => Carbon::parse($bmData['logged_at']),
                        'deleted_at' => null, // restore if soft-deleted
                    ]);
                }
            } else {
                $user->bowelMovements()->create([
                    'uuid' => $bmData['uuid'],
                    'bristol_type' => $bmData['bristol_type'],
                    'notes' => $bmData['notes'] ?? null,
                    'logged_at' => Carbon::parse($bmData['logged_at']),
                    'deleted_at' => !empty($bmData['deleted_at']) ? Carbon::parse($bmData['deleted_at']) : null,
                ]);
            }
            $syncedBmUuids[] = $bmData['uuid'];
        }

        // Fetch all of the user's active meals and bowel movements
        $allUserMeals = $user->meals()->get()->map(function ($meal) {
            return [
                'uuid' => $meal->uuid,
                'meal_type' => $meal->meal_type,
                'description' => $meal->description,
                'eaten_at' => $meal->eaten_at->toIso8601String(),
            ];
        });

        $allUserBm = $user->bowelMovements()->get()->map(function ($bm) {
            return [
                'uuid' => $bm->uuid,
                'logged_at' => $bm->logged_at->toIso8601String(),
                'bristol_type' => $bm->bristol_type,
                'notes' => $bm->notes,
            ];
        });

        // Fetch all deleted UUIDs
        $deletedMeals = $user->meals()->onlyTrashed()->pluck('uuid')->toArray();
        $deletedBm = $user->bowelMovements()->onlyTrashed()->pluck('uuid')->toArray();

        return response()->json([
            'success' => true,
            'message' => 'Synchronization complete.',
            'synced_meals' => $syncedMealUuids,
            'synced_bowel_movements' => $syncedBmUuids,
            'all_meals' => $allUserMeals,
            'all_bowel_movements' => $allUserBm,
            'deleted_meals' => $deletedMeals,
            'deleted_bowel_movements' => $deletedBm,
        ]);
    }
}
