<?php

namespace Src;

class Response
{
    private $statusCode = 200;
    private $headers = [];
    private $body;

    public function setStatusCode($code)
    {
        $this->statusCode = $code;
        return $this;
    }

    public function setHeader($name, $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    public function send()
    {
        // Set headers
        http_response_code($this->statusCode);
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        // Send body
        echo $this->body;
    }
}
