<?php
declare(strict_types=1);

namespace App\Http\WebClients\Weather\Exception;

class OperationException extends RuntimeException
{
    /**
     * @var string
     */
    protected $message = 'Weather. Unable to execute operation. Error errorCode';

    protected string $request;

    protected string $response;

    /**
     * @var mixed
     */
    protected $errorCode;

    public function __construct(string $request, string $response, $errorCode = null)
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
