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
    echo "<p>Output:</p><pre>" . \Illuminate\Support\Facades\Artisan::output() . "</pre>";
    echo "<p><a href='/users'>Go back to Users page</a></p>";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
