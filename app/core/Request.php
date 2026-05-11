<?php
declare(strict_types=1);

namespace App\Core;

class Request
{
    public readonly string $method;
    public readonly string $path;
    public readonly string $query;
    public readonly array $params;
    public readonly mixed $body;
    public readonly array $cookies;
    protected readonly array $files;
    protected readonly array $headers;
    private const string HDR_X_AJAX_REQUEST = "X-Ajax-Request";

    public function __construct()
    {
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) ?? "/";
        $this->query = parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY) ?? "";
        $this->params = array_merge($_GET, $_POST);
        $this->body = file_get_contents("php://input");
        $this->files = $_FILES;
        $this->headers = getallheaders();
        $this->cookies = $_COOKIE;
    }

    /**
     * Retrieves the value of a specified parameter by its name.
     *
     * @param string $paramName The name of the parameter to retrieve.
     * @return string The value of the parameter if it exists, or an empty string if it does not.
     */
    public function getParam(string $paramName): string
    {
        return key_exists($paramName, $this->params)
            ? $this->params[$paramName]
            : "";
    }

    /**
     * Determines if the current request is an AJAX request.
     *
     * @return bool
     */
    public function isAjax(): bool
    {
        return isset($this->headers[self::HDR_X_AJAX_REQUEST]) &&
            $this->headers[self::HDR_X_AJAX_REQUEST] === "true";
    }

    /**
     * Determines if the current request method is POST.
     *
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->method === "POST";
    }
}
