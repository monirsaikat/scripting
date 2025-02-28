<?php 

namespace Src\Exceptions;

use Src\Exceptions\AppException;

class ErrorHandler
{
    /**
     * Handle the error and show a custom error page.
     *
     * @param \Exception $exception
     * @return void
     */
    public static function handleException($exception)
    { 
        $errorCode = 500;
        $errorMessage = "An unexpected error occurred.";
        $errorData = null;

        if ($exception instanceof AppException) {
            $errorCode = $exception->getCode() ?: 500;
            $errorMessage = $exception->getMessage();
            $errorData = $exception->getErrorData();
        }

        http_response_code($errorCode);

        extract([
            'errorMessage' => $errorMessage,
            'errorCode' => $errorCode,
            'errorData' => $errorData
        ]);

        $errorViewFile = 'error';
        
        if($errorCode == 404) $errorViewFile = '404';

        if($errorCode == 500 && app()->getConfig('app')['app_mode'] == 'production') $errorViewFile = '500';
        
        require app()->getConfig('app')['view_folder'] . '/errors/' . $errorViewFile .'.php';
        exit;
    }
}
