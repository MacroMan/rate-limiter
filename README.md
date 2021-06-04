General framework agnostic rate limiter

Useful when consuming a rate limited API

## Installation

```
composer require macroman/rate-limiter
```

## Usage

Initialize Limiter with a `frequecy` and `duration`

```php
// example.php
use RateLimiter\Limiter;

// Limit to 6 iterations per second
$limiter = new Limiter(6, 1);

for ($i = 0; $i < 50; $i++) {
    $limiter->await();

    // Make your rate limited call here
    echo "Iteration $i" . PHP_EOL;
}
```

## License

See LICENSE
