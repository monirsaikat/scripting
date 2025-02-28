<?php

namespace Src\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Route
{
    public string $method;
    public string $path;
    public ?string $name;

    public function __construct(string $method, string $path, ?string $name = null)
    {
        $this->method = $method;
        $this->path   = $path;
        $this->name   = $name;
    }
}
