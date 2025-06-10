<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\Request;

class Validator
{
    public static function make(array $data, array $rules, array $customMessages = []): ValidatorResult
    {
        $errors = [];

        foreach ($rules as $field => $ruleString) {
            $value = $data[$field] ?? null;
            $ruleList = explode('|', $ruleString);

            foreach ($ruleList as $rule) {
                $params = [];

                if (str_contains($rule, ':')) {
                    [$rule, $paramString] = explode(':', $rule, 2);
                    $params = explode(',', $paramString);
                }

                $messageKey = "{$field}.{$rule}";
                $customMessage = $customMessages[$messageKey] ?? null;

                switch (strtolower($rule)) {
                    case 'required':
                        if ($value === null || $value === '') {
                            $errors[$field][] = $customMessage ?? "The {$field} field is required.";
                        }
                        break;

                    case 'email':
                        if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$field][] = $customMessage ?? "The {$field} must be a valid email address.";
                        }
                        break;

                    case 'numeric':
                        if ($value !== null && !is_numeric($value)) {
                            $errors[$field][] = $customMessage ?? "The {$field} must be numeric.";
                        }
                        break;

                    case 'min':
                        $min = (int)($params[0] ?? 0);
                        if (is_numeric($value) && $value < $min) {
                            $errors[$field][] = $customMessage ?? "The {$field} must be at least {$min}.";
                        } elseif (is_string($value) && mb_strlen($value) < $min) {
                            $errors[$field][] = $customMessage ?? "The {$field} must be at least {$min} characters.";
                        }
                        break;

                    case 'max':
                        $max = (int)($params[0] ?? 0);
                        if (is_numeric($value) && $value > $max) {
                            $errors[$field][] = $customMessage ?? "The {$field} may not be greater than {$max}.";
                        } elseif (is_string($value) && mb_strlen($value) > $max) {
                            $errors[$field][] = $customMessage ?? "The {$field} may not be greater than {$max} characters.";
                        }
                        break;

                    case 'confirmed':
                        if (!isset($data["{$field}_confirmation"]) || $value !== $data["{$field}_confirmation"]) {
                            $errors[$field][] = $customMessage ?? "The {$field} confirmation does not match.";
                        }
                        break;

                    case 'same':
                        $other = $params[0] ?? '';
                        if (!isset($data[$other]) || $value !== $data[$other]) {
                            $errors[$field][] = $customMessage ?? "The {$field} must match {$other}.";
                        }
                        break;

                    case 'in':
                        if (!in_array($value, $params)) {
                            $errors[$field][] = $customMessage ?? "The {$field} must be one of: " . implode(', ', $params);
                        }
                        break;

                    case 'regex':
                        $pattern = $params[0] ?? '';
                        if ($pattern && !preg_match($pattern, $value)) {
                            $errors[$field][] = $customMessage ?? "The {$field} format is invalid.";
                        }
                        break;

                    // Extend further as needed
                }
            }
        }

        return new ValidatorResult($errors);
    }
}