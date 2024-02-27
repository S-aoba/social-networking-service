<?php

return [
  'global' => [
    \Middleware\HttpLoggingMiddleware::class,
    \Middleware\SessionsSetupMiddleware::class,
    \Middleware\MiddlewareA::class,
    \Middleware\MiddlewareB::class,
    \Middleware\MiddlewareC::class,
  ]
];
