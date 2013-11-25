<?php

namespace Brammm\CommonBundle\Tests\EventListener;

use Brammm\CommonBundle\EventListener\ViewListener;
use Symfony\Component\HttpFoundation\Response;

class ViewListenerTest extends \PHPUnit_Framework_TestCase
{
    /** @var ViewListener */
    private $listener;

    protected function setUp()
    {
        $templating = $this->getMockBuilder('\Symfony\Bundle\TwigBundle\TwigEngine')
            ->disableOriginalConstructor()
            ->getMock();

        $templating->expects($this->any())
            ->method('renderResponse')
            ->with($this->equalTo('AcmeDemoBundle:Foo:bar.html.twig'), $this->equalTo(['foo' => 'bar']))
            ->will($this->returnValue(new Response()));

        $this->listener = new ViewListener($templating);
    }

    public function testonControllerResponse()
    {
        // mocking away
        $paramBag = $this->getMockBuilder('\Symfony\Component\HttpFoundation\ParameterBag')
                ->getMock();
        $paramBag->expects($this->once())
            ->method('get')
            ->will($this->returnValue('acme_demo.controller.foo:barAction'));

        $this->doTest($paramBag);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIncorrectController()
    {
        // mocking away
        $paramBag = $this->getMockBuilder('\Symfony\Component\HttpFoundation\ParameterBag')
                ->getMock();
        $paramBag->expects($this->once())
            ->method('get')
            ->will($this->returnValue('AcmeDemoBundle:Foo:bar'));

        $this->doTest($paramBag);
    }

    protected function doTest($paramBag)
    {
        $request = $this->getMockBuilder('\Symfony\Component\HttpFoundation\Request')
            ->enableArgumentCloning()
            ->getMock();
        $request->attributes = $paramBag;

        $event = $this->getMockBuilder('\Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent')
            ->disableOriginalConstructor()
            ->getMock();
        $event->expects($this->any())
            ->method('getControllerResult')
            ->will($this->returnValue(['foo' => 'bar'])); // the foo from the templating mock

        $event->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request));

        $this->listener->onControllerResponse($event);
    }
}