<?php

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';

$classes = [
    Illuminate\Support\Collection::class,
    Illuminate\Database\Eloquent\Model::class,
    Illuminate\Routing\Router::class,
    Illuminate\Foundation\Application::class,
    Illuminate\Support\Facades\DB::class,
    Illuminate\Support\Facades\Cache::class,
];

foreach ($classes as $class) {
    class_exists($class);
}
