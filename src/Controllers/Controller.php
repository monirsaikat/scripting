<?php

namespace Src\Controllers;

use Pimple\Container;
use Src\Database;
use Src\Session;

class Controller
{
    private $templates;

    private $postData;

    private $getData;

    protected $container;

    private $errors = [];

    public function db()
    {
        return $this->container['db'];
    }

    public function up(): \Src\Unpoly
    {
        return up();
    }

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->templates = new \League\Plates\Engine(
            app()->getConfig('app')['view_folder']
        );
        $this->postData  = $_POST;
    }

    public function render($view, $data = [], int $statusCode = 200, array $headers = [])
    {
        http_response_code($statusCode);

        foreach ($headers as $name => $value) {
            header("$name: $value");
        }

        echo $this->templates->render($view, $data);
    }

    public function renderUnprocessable($view, $data = [], ?string $target = null)
    {
        if ($target) {
            up()->setTarget($target);
        }

        return $this->render($view, $data, 422);
    }

    public function post($key = null, $validationRules = null)
    {
        $this->postData = arrayToObject($_POST);

        if (!$key) {
            return $this->postData;
        }

        if ($validationRules) {
            $this->validate($key, $this->postData->$key, $validationRules);
        }

        Session::setOldValues($_POST);

        return @$this->postData->$key ?? null;
    }

    public function get($key = null, $validationRules = null)
    {
        $this->getData = arrayToObject($_GET);

        if (!$key) {
            return $this->getData;
        }

        if ($validationRules) {
            $this->validate($key, $this->getData->$key, $validationRules);
        }

        Session::setOldValues($_GET);

        return @$this->getData->$key ?? null;
    }

    private function validate($field, $value, $validationRules)
    {
        $validator = new \Src\Util\Validation();
        $rules = explode('|', $validationRules);

        foreach ($rules as $rule) {
            if (strpos($rule, ':') !== false) {
                list($ruleName, $param) = explode(':', $rule);
                if (method_exists($validator, $ruleName)) {
                    $validator->{$ruleName}($value, $field, $param);
                }
            } else {
                if (method_exists($validator, $rule)) {
                    $validator->{$rule}($value, $field);
                }
            }
        }

        if ($validator->hasErrors()) {
            $fieldErrors = $validator->getErrors();

            if (!isset($this->errors[$field])) {
                $this->errors[$field] = $fieldErrors;
            } else {
                foreach ($fieldErrors as $error) {
                    if (!in_array($error, $this->errors[$field])) {
                        $this->errors[$field][] = $error;
                    }
                }
            }
        }
    }


    protected function validation()
    {
        return $this->errors ?? [];
    }

    protected function renderErrors()
    {
        if (empty($this->errors)) {
            return '';
        }

        $errorHtml = '<ul class="list-unstyled m-0">';

        foreach ($this->errors as $field => $messages) {
            if (is_array($messages)) {
                foreach ($messages as $message) {
                    $errorHtml .= "<li><strong>" . ucfirst($field) . ":</strong> $message[0]</li>";
                }
            } else {
                $errorHtml .= "<li><strong>" . ucfirst($field) . ":</strong> $messages</li>";
            }
        }

        $errorHtml .= '</ul>';

        return $errorHtml;
    }


    public function json($data = [], $statusCode = 200, $headers = [])
    {
        header('Content-Type: application/json', true);

        foreach ($headers as $key => $value) {
            header("$key: $value");
        }

        http_response_code($statusCode);

        echo json_encode($data);
        exit();
    }
}
