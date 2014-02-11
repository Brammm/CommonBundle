<?php

namespace Brammm\CommonBundle\Tests\EventListener;

use Brammm\CommonBundle\EventListener\ViewListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ViewListenerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent */
    private $eventMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|\Symfony\Bundle\TwigBundle\TwigEngine */
    private $twigMock;

    public function setUp()
    {
        $this->twigMock = $this->getMockBuilder('\Symfony\Bundle\TwigBundle\TwigEngine')
            ->disableOriginalConstructor()
            ->getMock();

        $this->eventMock = $this->getMockBuilder(
            '\Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent'
        )
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testonControllerResponse()
    {
        $request = $this->getRequestMock('acme_demo.controller.foo:barAction');

        $this->eventMock->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($request));

        $this->eventMock->expects($this->once())
            ->method('getControllerResult')
            ->will($this->returnValue(['foo' => 'bar'])); // the foo from the templating mock

        $this->twigMock->expects($this->once())
            ->method('renderResponse')
            ->with($this->equalTo(':Demo:Foo/bar.html.twig'), $this->equalTo(['foo' => 'bar']))
            ->will($this->returnValue(new Response()));

        $listener = new ViewListener($this->twigMock);
        $listener->onControllerResponse($this->eventMock);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIncorrectController()
    {
        $request = $this->getRequestMock('AcmeDemoBundle:Foo:bar');

        $this->eventMock->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($request));

        $listener = new ViewListener($this->twigMock);
        $listener->onControllerResponse($this->eventMock);
    }

    /**
     * @param string $forController
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\HttpFoundation\Request
     */
    private function getRequestMock($forController)
    {
        $paramBag = $this->getMockBuilder('\Symfony\Component\HttpFoundation\ParameterBag')
                ->getMock();
        $paramBag->expects($this->once())
            ->method('get')
            ->will($this->returnValue($forController));

        $request = $this->getMockBuilder('\Symfony\Component\HttpFoundation\Request')
            ->enableArgumentCloning()
            ->getMock();
        $request->attributes = $paramBag;

        return $request;
    }
}