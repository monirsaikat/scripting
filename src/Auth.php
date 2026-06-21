<?php

namespace Src;

class Auth
{
    private string $guard;

    public function __construct(string $guard = 'user')
    {
        $this->guard = $guard;
    }

    public function guard(): string
    {
        return $this->guard;
    }

    public function id()
    {
        return $_SESSION['auth'][$this->guard]['id'] ?? $this->legacyDefaultGuardId();
    }

    public function check(): bool
    {
        return (bool) $this->id();
    }

    public function guest(): bool
    {
        return !$this->check();
    }

    public function user()
    {
        $id = $this->id();

        if (!$id) {
            return null;
        }

        return Cache::get($this->cacheKey($id)) ?? $this->legacyDefaultGuardUser($id);
    }

    public function login($user): void
    {
        if (!isset($user->id)) {
            throw new \InvalidArgumentException('Authenticated user object must have an id property.');
        }

        $_SESSION['auth'][$this->guard] = ['id' => $user->id];
        Cache::set($this->cacheKey($user->id), $user);

        if ($this->guard === 'user') {
            $_SESSION['user_id'] = $user->id;
            Cache::set("user_{$user->id}", $user);
        }
    }

    public function logout(): void
    {
        $id = $this->id();

        if ($id) {
            Cache::delete($this->cacheKey($id));

            if ($this->guard === 'user') {
                Cache::delete("user_{$id}");
            }
        }

        unset($_SESSION['auth'][$this->guard]);

        if ($this->guard === 'user') {
            unset($_SESSION['user_id']);
        }
    }

    public static function logoutAll(): void
    {
        if (!empty($_SESSION['auth'])) {
            foreach (array_keys($_SESSION['auth']) as $guard) {
                (new self($guard))->logout();
            }
        }

        if (isset($_SESSION['user_id'])) {
            (new self('user'))->logout();
        }
    }

    private function cacheKey($id): string
    {
        return "auth_{$this->guard}_{$id}";
    }

    private function legacyDefaultGuardId()
    {
        if ($this->guard !== 'user') {
            return null;
        }

        return $_SESSION['user_id'] ?? null;
    }

    private function legacyDefaultGuardUser($id)
    {
        if ($this->guard !== 'user') {
            return null;
        }

        return Cache::get("user_{$id}");
    }
}
