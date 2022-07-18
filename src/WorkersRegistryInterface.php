<?php

declare(strict_types=1);

namespace Spiral\TemporalBridge;

use Spiral\TemporalBridge\Exception\WorkersRegistryException;
use Temporal\Worker\WorkerInterface;
use Temporal\Worker\WorkerOptions;

interface WorkersRegistryInterface
{
    /**
     * Register a new temporal worker with given options
     * @throws WorkersRegistryException
     */
    public function register(string $name, ?WorkerOptions $options): void;

    /**
     * Get or create worker by name
     */
    public function get(string $name): WorkerInterface;

    /**
     * Check if a worker with given name registered
     */
    public function has(string $name): bool;
}
