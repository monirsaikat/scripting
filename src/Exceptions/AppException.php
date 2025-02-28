<?php 

namespace Src\Exceptions;

use Exception;

class AppException extends Exception
{
    protected $errorCode;
    protected $errorData;

    public function __construct($message = "An error occurred", $code = 0, Exception $previous = null, $errorCode = null, $errorData = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errorCode = $errorCode;
        $this->errorData = $errorData;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function getErrorData()
    {
        return $this->errorData;
    }
}
