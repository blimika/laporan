<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$key = config('services.gemini.key');
$response = Illuminate\Support\Facades\Http::get('https://generativelanguage.googleapis.com/v1beta/models?key='.$key);
print_r($response->json());
