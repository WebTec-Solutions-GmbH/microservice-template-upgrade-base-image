<?php

namespace Tests\Exceptions;

use App\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class HandlerTest extends TestCase
{
    public function testRender()
    {
        $exception = new \Exception('test exception', 123);
        $request = new Request();
        $handler = new Handler();

        $result = $handler->render($request, $exception);
        $this->assertEquals([
            'errors' => [
                [
                    'status' => 500,
                    'code' => 123,
                    'title' => 'test exception'
                ]
            ]
        ], $result->getData(true));
    }

    public function testRenderValidationException()
    {
        $validator = \Mockery::mock()
            ->shouldReceive('errors')
            ->andReturn(new MessageBag(['message 1', 'message 2']))
            ->once()
            ->getMock();
        $exception = new ValidationException($validator);
        $request = new Request();
        $handler = new Handler();

        $result = $handler->render($request, $exception);
        $this->assertEquals([
            'errors' => [
                [
                    'status' => 422,
                    'title' => 'Invalid Parameter',
                    'source' => [
                        'parameter' => 0
                    ],
                    'detail' => 'message 1'
                ],
                [
                    'status' => 422,
                    'title' => 'Invalid Parameter',
                    'source' => [
                        'parameter' => 1
                    ],
                    'detail' => 'message 2'
                ]
            ]
        ], $result->getData(true));
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown(); // TODO: Change the autogenerated stub
    }
}
