<?php

namespace Middleware;

use Response\HTTPRenderer;

interface Middleware
{
    public function handle(callable $next): HTTPRenderer;
}
