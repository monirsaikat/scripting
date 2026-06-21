<?php

namespace Src\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Auth
{
    private string $guard;
    private string $redirectTo;

    public function __construct(string $guard = 'user', string $redirectTo = 'login') {
        $this->guard = $guard;
        $this->redirectTo = $redirectTo;
    }

    public function handle()
    {
        if (auth($this->guard)->guest()) {
            redirect($this->redirectTo);
            exit();
        }
    }
}
