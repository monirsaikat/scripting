<?php

namespace Src\Middleware;

use Src\Exceptions\AppException;

class CsrfMiddleware
{
    public function handle()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken    = $_POST['csrf_token'] ?? null;
            $sessionToken = $_SESSION['csrf_token'] ?? null;

            if ($csrfToken !== $sessionToken) {
                throw new AppException(
                    "CSRF token mismatch. Access denied.", 403, null, 403,
                    [
                        'request_method' => $_SERVER['REQUEST_METHOD'],
                        'uri' => $_SERVER['REQUEST_URI']
                    ]
                );
            }
        }
    }
}
