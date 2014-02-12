<?php

namespace Brammm\CommonBundle\Tests\Renderer;

use Brammm\CommonBundle\Renderer\TemplateRenderer;

class TemplateRendererTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|\Brammm\CommonBundle\Template\TemplateGuesserInterface */
    private $guesser;
    /** @var \PHPUnit_Framework_MockObject_MockObject|'\Symfony\Bundle\FrameworkBundle\Templating\EngineInterface' */
    private $templating;
    /** @var TemplateRenderer */
    private $SUT;

    public function setUp()
    {
        $this->guesser = $this->getMock('\Brammm\CommonBundle\Template\TemplateGuesserInterface');
        $this->templating = $this->getMockBuilder('\Symfony\Bundle\FrameworkBundle\Templating\EngineInterface')
            ->getMock();

        $this->SUT = new TemplateRenderer($this->guesser, $this->templating);
    }

    public function testRender()
    {
        $this->guesser
            ->expects($this->once())
            ->method('guess')
            ->with($this->equalTo('foo'))
            ->will($this->returnValue('bar'));

        $this->templating
            ->expects($this->once())
            ->method('renderResponse')
            ->with(
                $this->equalTo('bar'),
                $this->equalTo(['foo'=>'baz'])
            );

        $this->SUT->render('foo', ['foo'=>'baz']);
    }
} 