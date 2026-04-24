<?php

namespace App\Application;

class Request
{
    public string $method {
        get {
            return $this->method;
        }
    }
    public string $path {
        get {
            return $this->path;
        }
    }
    public string $query {
        get {
            return $this->query;
        }
    }
    public array $params {
        get {
            return $this->params;
        }
    }
    public mixed $body {
        get {
            return $this->body;
        }
    }
    protected mixed $files {
        get {
            return $this->files;
        }
    }
    protected array $headers {
        get {
            return $this->headers;
        }
    }
    public array $cookies {
        get {
            return $this->cookies;
        }
    }

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

        if (preg_match("#/id=\d+$#", $this->path)) {
            $id = explode("=", $this->path)[1];
            $this->params = array_merge($this->params, ["id" => $id]);
        }
    }

    /**
     * Retrieves the value of a specified parameter by its name.
     *
     * @param string $paramName The name of the parameter to retrieve.
     * @return string The value of the parameter if it exists, or an empty string if it does not.
     */
    public function getParam(string $paramName): string
    {
        return key_exists($paramName, $this->params) ? $this->params[$paramName] : "";
    }

    /**
     * Determines if the current request is an AJAX request.
     *
     * @return bool
     */
    public function isAjax(): bool
    {
        return (
            // Vérification classique XMLHttpRequest
            (isset($this->headers["X-Requested-With"]) && $this->headers["X-Requested-With"] === 'XMLHttpRequest') ||
            // Vérification d'un en-tête personnalisé plus explicite
            (isset($this->headers["X-Ajax-Request"]) && $this->headers["X-Ajax-Request"] === 'true') ||
            // Vérification du type de contenu Accept
            (isset($this->headers["Accept"]) && str_contains($this->headers["Accept"], 'application/json'))
        );
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

    /**
     * @return bool
     */
    public function isGet(): bool
    {
        return $this->method === "GET";
    }
}