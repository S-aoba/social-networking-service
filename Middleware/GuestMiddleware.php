<?php

namespace Middleware;

use Helpers\Authenticate;
use Response\HTTPRenderer;
use Response\Render\RedirectRenderer;

class GuestMiddleware implements Middleware
{
  public function handle(callable $next): HTTPRenderer
  {
    error_log('Running authentication check...');
    if (Authenticate::isLoggedIn()) {
      return new RedirectRenderer('login');
    }

    return $next();
  }
}
