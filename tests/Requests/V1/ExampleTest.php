<?php

namespace Tests\Requests\V1;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->get('/v4/example');
    }

    public function testStatusCode()
    {
        $this->assertResponseOk();
    }

    public function testContentType()
    {
        $this->seeHeader('Content-Type', 'application/vnd.api+json');
    }
}
