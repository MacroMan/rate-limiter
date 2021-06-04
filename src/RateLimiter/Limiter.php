<?php

namespace RateLimiter;

/**
 * Class RateLimiter
 *
 * @package App\Components
 */
class Limiter
{
    /**
     * Limit to this many requests
     *
     * @var int
     */
    private int $frequency = 0;

    /**
     * Limit for this duration
     *
     * @var int
     */
    private int $duration = 0;

    /**
     * Current instances
     *
     * @var array
     */
    private array $instances = [];

    /**
     * RateLimiter constructor.
     *
     * @param int $frequency
     * @param int $duration #
     */
    public function __construct(int $frequency, int $duration)
    {
        $this->frequency = $frequency;
        $this->duration = $duration;
    }

    /**
     * Sleep if the bucket is full
     */
    public function await(): void
    {
        $this->purge();
        $this->instances[] = microtime(true);

        if (!$this->is_free()) {
            $wait_duration = $this->duration_until_free();
            usleep($wait_duration);
        }
    }

    /**
     * Remove expired instances
     */
    private function purge(): void
    {
        $cutoff = microtime(true) - $this->duration;

        $this->instances = array_filter($this->instances, function ($a) use ($cutoff) {
            return $a >= $cutoff;
        });
    }

    /**
     * Can we run now?
     *
     * @return bool
     */
    private function is_free(): bool
    {
        return count($this->instances) < $this->frequency;
    }

    /**
     * Get the number of microseconds until we can run the next instance
     *
     * @return float
     */
    private function duration_until_free(): float
    {
        $oldest = $this->instances[0];
        $free_at = $oldest + $this->duration * 1000000;
        $now = microtime(true);

        return ($free_at < $now) ? 0 : $free_at - $now;
    }
}
