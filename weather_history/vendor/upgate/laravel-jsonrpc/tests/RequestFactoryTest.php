<?php
declare(strict_types=1);

use Upgate\LaravelJsonRpc\Exception\BadRequestException;
use Upgate\LaravelJsonRpc\Server\Request;
use Upgate\LaravelJsonRpc\Server\Batch;
use Upgate\LaravelJsonRpc\Server\RequestFactory;

class RequestFactoryTest extends \PHPUnit\Framework\TestCase
{

    public function testCreateRequestWithoutParameters()
    {
        $requestData = (object)[
            'jsonrpc' => '2.0',
            'method'  => 'foo'
        ];
        $requestFactory = new RequestFactory();
        $request = $requestFactory->createRequest($requestData);
        $this->assertEquals('foo', $request->getMethod());
        $this->assertEmpty($request->getParams()->getParams());
    }

    public function testCreateRequestWithoutId()
    {
        $requestData = (object)[
            'jsonrpc' => '2.0',
            'method'  => 'foo'
        ];
        $requestFactory = new RequestFactory();
        $request = $requestFactory->createRequest($requestData);
        $this->assertNull($request->getId());
    }

    public function testCreateRequestWithNumericId()
    {
        $requestData = (object)[
            'jsonrpc' => '2.0',
            'method'  => 'foo',
            'id'      => 1
        ];
        $requestFactory = new RequestFactory();
        $request = $requestFactory->createRequest($requestData);
        $this->assertSame(1, $request->getId());
    }

    public function testCreateRequestWithStringId()
    {
        $requestData = (object)[
            'jsonrpc' => '2.0',
            'method'  => 'foo',
            'id'      => '1_1'
        ];
        $requestFactory = new RequestFactory();
        $request = $requestFactory->createRequest($requestData);
        $this->assertSame('1_1', $request->getId());
    }

    public function testCreateRequestWithPositionalParameters()
    {
        $requestData = (object)[
            'jsonrpc' => '2.0',
            'method'  => 'foo',
            'params'  => [1, "bar"]
        ];
        $requestFactory = new RequestFactory();
        $request = $requestFactory->createRequest($requestData);
        $this->assertEquals('foo', $request->getMethod());
        $this->assertEquals([1, "bar"], $request->getParams()->getParams());
        $this->assertFalse($request->getParams()->areParamsNamed());
    }

    public function testCreateRequestWithNamedParameters()
    {
        $requestData = (object)[
            'jsonrpc' => '2.0',
            'method'  => 'foo',
            'params'  => (object)['a' => 1, 'b' => "bar"]
        ];
        $requestFactory = new RequestFactory();
        $request = $requestFactory->createRequest($requestData);
        $this->assertEquals('foo', $request->getMethod());
        $this->assertEquals(['a' => 1, 'b' => "bar"], $request->getParams()->getParams());
        $this->assertTrue($request->getParams()->areParamsNamed());
    }

    public function testCreateRequestFailsWithBadVersion()
    {
        $requestData = (object)[
            'jsonrpc' => '1.0',
            'method'  => 'foo'
        ];
        $requestFactory = new RequestFactory();
        $this->expectException(BadRequestException::class);
        $requestFactory->createRequest($requestData);
    }

    public function testCreateRequestFailsWithoutJsonRpcField()
    {
        $requestData = (object)[
            'method' => 'foo'
        ];
        $requestFactory = new RequestFactory();
        $this->expectException(BadRequestException::class);
        $requestFactory->createRequest($requestData);
    }

    public function testCreateRequestWithoutMethod()
    {
        $requestData = (object)[
            'jsonrpc' => '2.0'
        ];
        $requestFactory = new RequestFactory();
        $this->expectException(BadRequestException::class);
        $requestFactory->createRequest($requestData);
    }

    public function testCreateRequestFromPayload()
    {
        $requestData = (object)[
            'jsonrpc' => '2.0',
            'method'  => 'foo',
            'params'  => [1, "bar"],
            'id'      => 'foo',
        ];
        $requestFactory = new RequestFactory();
        /** @var Request $request */
        $request = $requestFactory->createFromPayload(json_encode($requestData));
        $this->assertInstanceOf(Request::class, $request);
        $this->assertEquals('foo', $request->getMethod());
        $this->assertEquals([1, "bar"], $request->getParams()->getParams());
        $this->assertFalse($request->getParams()->areParamsNamed());
        $this->assertEquals('foo', $request->getId());
    }

    public function testCreateBatchFromPayload()
    {
        $requestData = [
            (object)[
                'jsonrpc' => '2.0',
                'method'  => 'foo',
                'id'      => 'foo',
            ],
            (object)[
                'jsonrpc' => '2.0',
                'method'  => 'bar',
                'id'      => 'bar',
            ],
        ];
        $requestFactory = new RequestFactory();
        /** @var Batch $batch */
        $batch = $requestFactory->createFromPayload(json_encode($requestData));
        $this->assertInstanceOf(Batch::class, $batch);
        $this->assertCount(2, $batch->toArray());

        for ($i = 0; $i < 2; ++$i) {
            $request = $requestFactory->createRequest($batch->toArray()[$i]);
            $this->assertEquals($requestData[$i]->method, $request->getMethod());
            $this->assertEquals($requestData[$i]->id, $request->getId());
        }
    }
}
