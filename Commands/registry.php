<?php
require_once __DIR__ . '/Programs/Migrate.php';
require_once __DIR__ . '/Programs/CodeGeneration.php';
require_once __DIR__ . '/Programs/Seed.php';

return [
    Commands\Programs\Migrate::class,
    Commands\Programs\CodeGeneration::class,
    Commands\Programs\Seed::class,
];
