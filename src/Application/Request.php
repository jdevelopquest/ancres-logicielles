<?php

namespace App\Application;

class Request
{
    protected string $method;
    protected string $path;
    protected string $query;
    protected array $params;
    protected mixed $body;
    protected mixed $files;
    protected array $headers;
    protected array $cookies;

    public function __construct()
    {
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $this->query = parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY);
        $this->params = array_merge($_GET, $_POST);
        $this->body = file_get_contents("php://input");
        $this->files = $_FILES;
        $this->headers = getallheaders();
        $this->cookies = $_COOKIE;
    }

    /**
     * @return mixed
     */
    public function getMethod(): mixed
    {
        return $this->method;
    }

    /**
     * @return array|false|int|string|null
     */
    public function getPath(): false|array|int|string|null
    {
        return $this->path;
    }

    /**
     * @return array|false|int|string|null
     */
    public function getQuery(): false|array|int|string|null
    {
        return $this->query;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @return false|string
     */
    public function getBody(): false|string
    {
        return $this->body;
    }

    /**
     * @return mixed
     */
    public function getFiles(): mixed
    {
        return $this->files;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getCookies(): array
    {
        return $this->cookies;
    }

    public function isAjax(): bool
    {
        return isset($this->headers["X-Requested-With"]) && $this->headers["X-Requested-With"] === 'XMLHttpRequest';
    }

    public function isPost(): bool
    {
        return $this->method === "POST";
    }

    public function isGet(): bool
    {
        return $this->method === "GET";
    }
}