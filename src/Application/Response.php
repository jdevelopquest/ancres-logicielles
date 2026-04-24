<?php

namespace App\Application;

class Response
{
    protected array $headers = [];
    protected array $cookies = [];
    protected int $code = 200;
    protected string $body = "";

    /**
     * Sets the code value.
     *
     * @param int $code The code to set.
     * @return void
     */
    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    public function addHeader(string $header, bool $replace = true, int $response_code = 0): void
    {
        $this->headers[] = [
            "header" => $header,
            "replace" => $replace,
            "response_code" => $response_code,
        ];
    }

    /**
     * Adds a cookie to the HTTP response.
     *
     * @param string $name The name of the cookie.
     * @param string $value The value of the cookie. Default is an empty string.
     * @param int $expires_or_options The expiration time as a Unix timestamp or an array of options. Default is 0.
     * @param string $path The path on the server where the cookie will be available. Default is "/".
     * @param string $domain The (sub)domain that the cookie is available to. Default is "/".
     * @param bool $secure Indicates whether the cookie should only be transmitted over a secure HTTPS connection. Default is false.
     * @param bool $httponly Indicates whether the cookie is accessible only through the HTTP protocol and not via scripting languages. Default is false.
     * @return void
     */
    public function addCookie(string $name,
                              string $value = "",
                              int    $expires_or_options = 0,
                              string $path = "/",
                              string $domain = "/",
                              bool   $secure = false,
                              bool   $httponly = false): void
    {
        $this->cookies[] = [
            "name" => $name,
            "value" => $value,
            "expires_or_options" => $expires_or_options,
            "path" => $path,
            "domain" => $domain,
            "secure" => $secure,
            "httponly" => $httponly
        ];
    }

    /**
     * Sets the body content.
     *
     * @param string $body The body content to set.
     * @return void
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * Sends the prepared HTTP response, setting headers, status code, and body.
     *
     * @return void
     */
    public function send(): void
    {
        foreach ($this->headers as $header) {
            header(
                $header["header"],
                $header["replace"],
                $header["response_code"]
            );
        }

        foreach ($this->cookies as $cookie) {
            setcookie(
                $cookie["name"],
                $cookie["value"],
                $cookie["expires_or_options"],
                $cookie["path"],
                $cookie["domain"],
                $cookie["secure"],
                $cookie["httponly"]
            );
        }

        http_response_code($this->code);

        if (!empty($this->body)) {
            echo $this->body;
        }
    }
}