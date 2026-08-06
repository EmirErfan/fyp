<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

try {
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    echo "<h1>Database Fix Executed!</h1>";
    echo "<p>Migration Output:</p><pre>" . \Illuminate\Support\Facades\Artisan::output() . "</pre>";
    
    // Also do it manually here just in case migration doesn't work!
    $user = \App\Models\User::where('email', 'abdulwahab@system.com')->first();
    if ($user) {
        echo "<p>Found user: " . $user->name . "</p>";
        if (!$user->team_id) {
            $teamName = trim($user->name);
            $team = \App\Models\Team::create(['name' => $teamName]);
            $user->update(['team_id' => $team->id]);
            echo "<p>Team created manually and assigned: " . $teamName . "</p>";
        } else {
            echo "<p>User already has a team (ID: " . $user->team_id . ")</p>";
        }
    } else {
        echo "<p>User not found with email abdulwahab@system.com</p>";
    }

    echo "<p><a href='/users'>Go back to Users page</a></p>";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
