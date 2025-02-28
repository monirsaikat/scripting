<?php

namespace Src;

class Request
{
    private $method;
    private $uri;
    private $headers;
    private $params;
    private $body;

    public function __construct()
    {
        $this->method  = $_SERVER['REQUEST_METHOD'];
        $this->uri     = $_SERVER['REQUEST_URI'];
        $this->headers = getallheaders();
        $this->params  = $_GET;
        $this->body    = json_decode(file_get_contents('php://input'), true);
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getHeader($name)
    {
        return $this->headers[$name] ?? null;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getBody()
    {
        return $this->body;
    }
}
