<?php
require_once __DIR__ . '/vendor/autoload.php';

use RateLimiter\Limiter;

// Limit to 6 iterations per second
$limiter = new Limiter(6, 1);

for ($i = 0; $i < 50; $i++) {
    $limiter->await();

    echo "Iteration $i" . PHP_EOL;
}
