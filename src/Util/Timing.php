<?php

namespace Src\Util;

use DateTime;
use DateInterval;
use Exception;

class Timing
{
    private DateTime $date;

    public function __construct($time = 'now')
    {
        $this->date = new \DateTime($time, new \DateTimeZone(
            timezoneToOffset(
                app()->getConfig('app')['timezone']
            )
        ));
    }

    public static function now(): self
    {
        return new self();
    }

    public static function today(): self
    {
        return new self('today');
    }

    public static function tomorrow(): self
    {
        return new self('tomorrow');
    }

    public static function yesterday(): self
    {
        return new self('yesterday');
    }

    public function format(string $format = 'Y-m-d H:i:s'): string
    {
        return $this->date->format($format);
    }

    public function addDays(int $days): self
    {
        $this->date->add(new DateInterval("P{$days}D"));
        return $this;
    }

    public function subDays(int $days): self
    {
        $this->date->sub(new DateInterval("P{$days}D"));
        return $this;
    }

    public function diffInDays(self $otherDate): int
    {
        return (int)$this->date->diff($otherDate->date)->format('%r%a');
    }

    public function isPast(): bool
    {
        return $this->date < new DateTime();
    }

    public function isFuture(): bool
    {
        return $this->date > new DateTime();
    }

    public function getDateTime(): DateTime
    {
        return $this->date;
    }

    public function diffForHumans(): string
    {
        $now = new DateTime();
        $diff = $this->date->diff($now);

        $isFuture = $this->date > $now;
        $suffix = $isFuture ? 'from now' : 'ago';

        if ($diff->y > 0) {
            return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . " $suffix";
        }
        if ($diff->m > 0) {
            return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . " $suffix";
        }
        if ($diff->d > 0) {
            if ($diff->d === 1) {
                return $isFuture ? 'tomorrow' : 'yesterday';
            }
            return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . " $suffix";
        }
        if ($diff->h > 0) {
            return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . " $suffix";
        }
        if ($diff->i > 0) {
            return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . " $suffix";
        }
        return 'just now';
    }
}
