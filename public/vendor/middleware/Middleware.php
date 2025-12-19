<?php

namespace vendor\middleware;

/**
 * Abstract base class for all middleware components
 * Defines the contract that all middleware must implement
 * Middleware are request interceptors that can modify or redirect requests
 */
abstract class Middleware
{
    /**
     * Main middleware handler method
     * This method is called when middleware is executed during request processing
     * Each concrete middleware must implement its own logic here
     *
     * @return void
     */
    abstract public static function handle(): void;
}