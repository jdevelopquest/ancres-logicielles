<?php

namespace App\Application;

class Response
{
    protected array $headers = [];
    protected int $code = 200;
    protected string $body = "";

    public function __construct(bool $bigFail = false)
    {
        if ($bigFail) {
            $this->setHeaders([
                'Content-Type' => 'text/html',
            ]);
            $this->setCode(500);
            $this->setBody("Une erreur est survenue");
        }
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param int $code
     */
    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return void
     */
    public function send(): void
    {
        foreach ($this->headers as $header) {
            header($header);
        }

        http_response_code($this->code);

        echo $this->body;
    }
}