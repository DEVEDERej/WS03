<?php

/**
 * Get the base path
 * 
 * @param string $path
 * @return string
 */
if (!function_exists('basePath')) {
    function basePath($path = '')
    {
        return __DIR__ . '/' . $path;
    }
}

/**
 * Load a view
 * 
 * @param string $name
 * @return void
 */
if (!function_exists('loadView')) {
    function loadView($name, $data = [])
    {
        $viewPath = basePath("App/views/{$name}.view.php");

        if (file_exists($viewPath)) {
            extract($data, EXTR_OVERWRITE);
            require $viewPath;
        } else {
            echo "View '{$name}' not found";
        }
    }
}

/**
 * Load a partial
 * 
 * @param string $name
 * @param array $data
 * @return void
 */
if (!function_exists('loadPartial')) {
    function loadPartial($name, $data = [])
    {
        $partialPath = basePath("App/views/partials/{$name}.php");

        if (file_exists($partialPath)) {
            extract($data);
            require $partialPath;
        } else {
            echo "Partial '{$name}' not found";
        }
    }
}

/**
 * Inspect a value(s)
 * 
 * @param mixed $value
 * @return void
 */
if (!function_exists('inspect')) {
    function inspect($value)
    {
        echo '<pre>';
        var_dump($value);
        echo '</pre>';
    }
}

/**
 * Inspect a value(s) and die
 * 
 * @param mixed $value
 * @return void
 */
if (!function_exists('inspectAndDie')) {
    function inspectAndDie($value)
    {
        echo '<pre>';
        die(var_dump($value));
        echo '</pre>';
    }
}

/**
 * Format salary with dollar sign and commas
 * 
 * @param string $salary
 * @return string
 */
if (!function_exists('formatSalary')) {
    function formatSalary($salary)
    {
        return '₱' . number_format(floatval($salary));
    }
}

/**
 * Sanitize data
 * 
 * @param string $dirty
 * @return string
 */
if (!function_exists('sanitize')) {
    function sanitize($dirty)
    {
        return filter_var(trim($dirty), FILTER_SANITIZE_SPECIAL_CHARS);
    }
}

/**
 * Redirect to a given url
 * 
 * @param string $url
 * @return void
 */
if (!function_exists('redirect')) {
    function redirect($url)
    {
        header("Location: " . url($url));
        exit;
    }
}

/**
 * Get the URL with base path
 * 
 * @param string $path
 * @return string
 */
if (!function_exists('url')) {
    function url($path = '')
    {
        $basePath = '/WS03/public';
        return $basePath . $path;
    }
}
