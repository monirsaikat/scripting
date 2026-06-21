<?php

namespace Src\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Guest
{
    private string $guard;
    private string $redirectTo;

    public function __construct(string $guard = 'user', string $redirectTo = '/') {
        $this->guard = $guard;
        $this->redirectTo = $redirectTo;
    }

    public function handle()
    {
        if (auth($this->guard)->check()) {
            redirect($this->redirectTo);
            exit();
        }
    }
}
