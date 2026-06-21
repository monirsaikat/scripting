<?php

namespace Src;

class Unpoly
{
    public const VERSION = '3.14.3';
    public const SHELL_TARGET = '#page';
    public const MAIN_TARGET = '#page';
    public const CONTENT_TARGET = '#app';

    public function isRequest(): bool
    {
        return $this->header('X-Up-Version') !== null;
    }

    public function target(): ?string
    {
        return $this->header('X-Up-Target');
    }

    public function failTarget(): ?string
    {
        return $this->header('X-Up-Fail-Target');
    }

    public function mode(): ?string
    {
        return $this->header('X-Up-Mode');
    }

    public function isLayer(): bool
    {
        $mode = $this->mode();

        return $mode !== null && $mode !== 'root';
    }

    public function validateFields(): array
    {
        $fields = $this->header('X-Up-Validate');

        if (!$fields) {
            return [];
        }

        return array_filter(array_map('trim', explode(',', $fields)));
    }

    public function setTarget(string $selector): void
    {
        header("X-Up-Target: {$selector}");
    }

    public function setTitle(string $title): void
    {
        header('X-Up-Title: ' . addcslashes($title, "\r\n"));
    }

    public function setLocation(string $location): void
    {
        header('X-Up-Location: ' . url($location));
        header('X-Up-Method: GET');
    }

    public function acceptLayer(array $value = []): void
    {
        header('X-Up-Accept-Layer: ' . json_encode((object) $value));
    }

    public function dismissLayer(array $value = []): void
    {
        header('X-Up-Dismiss-Layer: ' . json_encode((object) $value));
    }

    public function emit(string $event, array $payload = []): void
    {
        $payload['type'] = $event;
        header('X-Up-Events: ' . json_encode([$payload]));
    }

    public function expireCache(string $pattern = '*'): void
    {
        header("X-Up-Expire-Cache: {$pattern}");
    }

    public function evictCache(string $pattern = '*'): void
    {
        header("X-Up-Evict-Cache: {$pattern}");
    }

    public function vary(string ...$headers): void
    {
        $headers = array_filter($headers);

        if (!$headers) {
            return;
        }

        header('Vary: ' . implode(', ', $headers), false);
    }

    public function attrs(array $attributes = []): string
    {
        $html = [];

        foreach ($attributes as $name => $value) {
            if ($value === false || $value === null) {
                continue;
            }

            if ($value === true) {
                $html[] = htmlspecialchars((string) $name, ENT_QUOTES, 'UTF-8');
                continue;
            }

            $html[] = sprintf(
                '%s="%s"',
                htmlspecialchars((string) $name, ENT_QUOTES, 'UTF-8'),
                htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8')
            );
        }

        return implode(' ', $html);
    }

    private function header(string $name): ?string
    {
        $serverKey = 'HTTP_' . strtoupper(str_replace('-', '_', $name));

        return $_SERVER[$serverKey] ?? null;
    }
}
