<?php
declare(strict_types=1);

namespace Upgate\LaravelJsonRpc\Server;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Upgate\LaravelJsonRpc\Exception\JsonRpcException;
use Illuminate\Http\JsonResponse;

final class RequestResponse implements Jsonable, Arrayable
{

    private $id;
    private $result;
    private $isError = false;

    /**
     * @param string|int|null $id
     * @param mixed $result
     * @param bool $isError
     */
    public function __construct($id, $result, $isError = false)
    {
        $this->id = null === $id ? null : (is_numeric($id) ? $id : (string)$id);
        $this->result = $result instanceof JsonResponse && method_exists($result, 'getData')
            ? $result->getData() : $result;
        $this->isError = (bool)$isError;
    }

    /**
     * @param string|int|null $id
     * @param \Exception $exception
     * @return RequestResponse
     */
    public static function constructExceptionErrorResponse($id, \Exception $exception): RequestResponse
    {
        if ($exception instanceof JsonRpcException) {
            $extras = $exception->getExtras();
        } else {
            $extras = [];
        }

        return self::constructErrorResponse($id, $exception->getMessage(), $exception->getCode(), $extras);
    }

    /**
     * @param string|int|null $id
     * @param string $message
     * @param int $code
     * @param array $extras
     * @return RequestResponse
     */
    public static function constructErrorResponse(
        $id,
        string $message,
        int $code = ErrorCode::INTERNAL_ERROR,
        array $extras = []
    ): RequestResponse {
        return new self(
            $id,
            [
                'code'    => $code,
                'message' => (string)$message
            ] + $extras,
            true
        );
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode((object)$this->toArray(), $options);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $result = ['jsonrpc' => '2.0'];
        if ($this->id !== null) {
            $result['id'] = $this->id;
        }
        if ($this->isError) {
            $result['error'] = $this->result;
        } else {
            $result['result'] = $this->result;
        }

        return $result;
    }

}
