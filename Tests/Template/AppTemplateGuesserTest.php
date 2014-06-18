<?php

namespace Brammm\CommonBundle\Tests\Template;

use Brammm\CommonBundle\Template\AppTemplateGuesser;

class AppTemplateGuesserTest extends \PHPUnit_Framework_TestCase
{
    /** @var AppTemplateGuesser */
    private $SUT;

    public function setUp()
    {
        $this->SUT = new AppTemplateGuesser();
    }

    public function testGuess()
    {
        $this->assertEquals(
            ':Demo:Foo/bar.html.twig',
            $this->SUT->guess('acme_demo.controller.foo:barAction')
        );
    }

    public function testSubnamespace()
    {
        $this->assertEquals(
            ':Demo:Baz/Foo/bar.html.twig',
            $this->SUT->guess('acme_demo.controller.baz.foo:barAction')
        );
    }

    /**
     * @expectedException \Brammm\CommonBundle\Exception\UnguessableControllerException
     */
    public function testException()
    {
        $this->SUT->guess('foo');
    }
} 