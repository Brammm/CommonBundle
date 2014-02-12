<?php

namespace Brammm\CommonBundle\Tests\EventListener;


use Brammm\CommonBundle\EventListener\ViewListener;
use Symfony\Component\HttpFoundation\Response;

class ViewListenerTest extends \PHPUnit_Framework_TestCase
{

    /** @var ViewListener */
    private $SUT;

    public function setUp()
    {
        $this->SUT = new ViewListener('template', [
            'bar' => 'json',
            'baz' => 'barf',
        ]);
    }

    /**
     * @expectedException \Brammm\CommonBundle\Exception\UnsupportedTypeException
     */
    public function testThrowsExceptionIfUnknownType()
    {
        $event = $this->getEventWithRequestWithController('baz');

        $this->SUT->onControllerResponse($event);
    }

    /**
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testCanOnlyAddRendererInterfaces()
    {
        $renderers = [
            'bar' => $this->getMock('\Brammm\CommonBundle\Renderer\RendererInterface'),
            'baz' => new \StdClass(),
        ];

        $this->SUT->setRenderers($renderers);
    }

    public function testRendersResponse()
    {
        $renderer = $this->getMock('\Brammm\CommonBundle\Renderer\RendererInterface');
        $renderer
            ->expects($this->once())
            ->method('render')
            ->with(
                $this->equalTo('bar'),
                $this->equalTo(['foo'=>'bar'])
            )
            ->will($this->returnValue(new Response()));

        $renderers = [
            'json' => $renderer,
        ];

        $this->SUT->setRenderers($renderers);

        $event = $this->getEventWithRequestWithController('bar');
        $event
            ->expects($this->once())
            ->method('getControllerResult')
            ->will($this->returnValue(['foo'=>'bar']));
        $event
            ->expects($this->once())
            ->method('setResponse');

        $this->SUT->onControllerResponse($event);
    }

    private function getEventWithRequestWithController($controller)
    {
        $request = $this->getMockBuilder('\Symfony\Component\HttpFoundation\Request')
            ->enableArgumentCloning()
            ->getMock();

        $paramBag = $this->getMock('\Symfony\Component\HttpFoundation\ParameterBag');
        $paramBag->expects($this->once())
            ->method('get')
            ->with($this->equalTo('_controller'))
            ->will($this->returnValue($controller));

        $request->attributes = $paramBag;

        $event = $this->getMockBuilder('\Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent')
            ->disableOriginalConstructor()
            ->getMock();
        $event
            ->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($request));

        return $event;
    }
}