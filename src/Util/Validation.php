<?php

namespace Src\Util;

class Validation
{
    private $errors = [];


    public function required($value, $field)
    {
        if (empty($value)) {
            $this->errors[$field][] = ucfirst($field) . ' is required.';
        }
    }

    public function email($value, $field)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = 'Invalid email format.';
        }
    }

    public function min($value, $field, $minLength)
    {
        if (strlen($value) < $minLength) {
            $this->errors[$field][] = ucfirst($field) . ' must be at least ' . $minLength . ' characters.';
        }
    }

    public function hasErrors()
    {
        return !empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
