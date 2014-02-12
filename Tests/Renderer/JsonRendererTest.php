<?php

namespace Brammm\CommonBundle\Tests\Renderer;

use Brammm\CommonBundle\Renderer\JsonRenderer;

class JsonRendererTest extends \PHPUnit_Framework_TestCase
{
    /** @var JsonRenderer */
    private $SUT;

    public function setUp()
    {
        $this->SUT = new JsonRenderer();
    }

    public function testReturnsJsonResponse()
    {
        $response = $this->SUT->render('foo', ['bar' => 'baz']);

        $this->assertEquals(
            '{"bar":"baz"}',
            $response->getContent()
        );

    }
} 