<?php

namespace App\Application;

class Request
{
    protected mixed $method;
    protected string|array|int|null|false $path;
    protected string|array|int|null|false $query;
    protected string|false $body;

    public function __construct()
    {
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $this->query = parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY);
        $this->body = file_get_contents("php://input");
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
     * @return false|string
     */
    public function getBody(): false|string
    {
        return $this->body;
    }
}