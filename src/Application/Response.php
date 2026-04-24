<?php
declare(strict_types=1);

namespace App\Application;

use App\Application\Utils\Logger;

class Response
{
    protected array $headers = [];
    protected array $cookies = [];
    protected int $code = 200;
    protected ?string $body = null;

    /**
     * Adds a header to the header list.
     *
     * @param string $header The header string to be added.
     * @param bool $replace Indicates whether this header should replace a previous one with the same name. Defaults to true.
     * @param int $responseCode The HTTP response code associated with the header. Defaults to 0.
     * @return self Returns the current instance.
     */
    public function addHeader(string $header, bool $replace = true, int $responseCode = 0): self
    {
        $this->headers[] = [
            "header" => $header,
            "replace" => $replace,
            "response_code" => $responseCode,
        ];
        return $this;
    }

    /**
     * Adds a cookie to the response.
     *
     * @param string $name The name of the cookie.
     * @param string $value The value of the cookie. Defaults to an empty string.
     * @param int $expiresOrOptions The expiration time or options for the cookie. Defaults to 0.
     * @param string $path The path on the server in which the cookie will be available. Defaults to "/".
     * @param string $domain The domain that the cookie is available to. Defaults to "/".
     * @param bool $secure Whether the cookie should only be transmitted over a secure HTTPS connection. Defaults to false.
     * @param bool $httpOnly Whether the cookie is accessible only through the HTTP protocol. Defaults to false.
     * @return self
     */
    public function addCookie(string $name,
                              string $value = "",
                              int    $expiresOrOptions = 0,
                              string $path = "/",
                              string $domain = "/",
                              bool   $secure = false,
                              bool   $httpOnly = false): self
    {
        $this->cookies[] = [
            "name" => $name,
            "value" => $value,
            "expires_or_options" => $expiresOrOptions,
            "path" => $path,
            "domain" => $domain,
            "secure" => $secure,
            "httponly" => $httpOnly
        ];
        return $this;
    }

    /**
     * Sets the code value.
     *
     * @param int $code The code to be set.
     * @return self
     */
    public function setCode(int $code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Sets the body content.
     *
     * @param string $body The body content to be set.
     * @return self
     */
    public function setBody(?string $body): self
    {
        if ($body !== null) {
            $this->body = $body;
        }
        return $this;
    }

    /**
     * Sends the HTTP response, including headers, cookies, status code, and body.
     *
     * This method checks if headers have already been sent and logs a warning if so.
     * It then proceeds to send the headers, cookies, status code, and response body.
     *
     * @return void
     */
    public function send(): void
    {
        if (headers_sent($file, $line)) {
            $logger = new Logger();
            $logger->warning('Headers already sent before Response::send()', ['file' => $file, 'line' => $line]);
        }

        $this->sendHeaders();
        $this->sendCookies();
        $this->sendStatusCode();
        $this->sendBody();
    }

    /**
     * Sends the headers to the output buffer.
     *
     * Iterates through the list of headers and sends each header using the `header` function.
     *
     * @return void
     */
    private function sendHeaders(): void
    {
        foreach ($this->headers as $header) {
            header(
                $header["header"],
                $header["replace"],
                $header["response_code"]
            );
        }
    }

    /**
     * Sends cookies to the client.
     *
     * Iterates through the list of cookies and attempts to send each one.
     * Logs a debug message if a cookie fails to be set.
     *
     * @return void
     */
    private function sendCookies(): void
    {
        foreach ($this->cookies as $cookie) {
            $success = setcookie(
                $cookie["name"],
                $cookie["value"],
                $cookie["expires_or_options"],
                $cookie["path"],
                $cookie["domain"],
                $cookie["secure"],
                $cookie["httponly"]
            );

            if (!$success) {
                $logger = new Logger();
                $logger->debug("Fail to set cookie", ["cookie" => $cookie]);
            }
        }
    }

    /**
     * Sends the status code as an HTTP response.
     *
     * @return void
     */
    private function sendStatusCode(): void
    {
        http_response_code($this->code);
    }

    /**
     * Sends the body content if it is not empty.
     *
     * @return void
     */
    private function sendBody(): void
    {
        if ($this->body !== null) {
            echo $this->body;
        }
    }
}