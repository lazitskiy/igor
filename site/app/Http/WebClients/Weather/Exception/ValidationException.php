<?php
declare(strict_types=1);

namespace App\Http\WebClients\Weather\Exception;

class ValidationException extends RuntimeException
{
    /**
     * @var string
     */
    protected $message = 'Weather. Parameters incorrect';

    protected string $request;

    protected string $response;

    /**
     * @var mixed
     */
    protected $errorCode;

    public function __construct(string $request, string $response, $errorCode = null, $violations = [])
    {
        $this->request = $request;
        $this->response = $response;
        $this->errorCode = $errorCode;
    }

    public function getRequest(): string
    {
        return $this->request;
    }

    public function getResponse(): string

    {
        return $this->response;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }
}
