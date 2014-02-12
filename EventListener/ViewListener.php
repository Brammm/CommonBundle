<?php

namespace Brammm\CommonBundle\EventListener;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

class ViewListener
{

    /** @var string */
    private $defaultResponse;
    /** @var EngineInterface */
    private $templating;

    public function __construct($defaultResponse, array $responses, EngineInterface $templating)
    {
        $this->defaultResponse = $defaultResponse;
        $this->responses       = $responses;
        $this->templating      = $templating;
    }

    /**
     * Looks at the current controller and finds a matching template
     * Controller must be called very specifically (as a service)
     * It then renders the template and sets it as the Response
     *
     * @param GetResponseForControllerResultEvent $event
     */
    public function onControllerResponse(GetResponseForControllerResultEvent $event)
    {
        $template = $this->getTemplate($event->getRequest()->attributes->get('_controller'));
        $response = $this->templating->renderResponse($template, $event->getControllerResult());

        $event->setResponse($response);
    }

    /**
     * Converts a string that matches to pattern "acme_demo.controller.foo:barAction"
     * to ":Demo:Foo/bar.html.twig"
     *
     * @param string $controllerPath
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function getTemplate($controllerPath)
    {
        // Gets an array that looks like
        // [
        //     1 => 'acme_demo',
        //     2 => 'foo',
        //     3 => 'bar'
        // ]
        if (preg_match('/^(.+)\.controller\.(.+):(.+)Action$/', $controllerPath, $matches)) {
            return ':'
            . ucfirst(preg_replace('/^[a-z]+_/', '', $matches[1])) // Strip out the vendor
            . ':'
            . ucfirst($matches[2])
            . '/' . $matches[3]
            . '.html.twig';
        } else {
            throw new \InvalidArgumentException(sprintf(
                'The controller "%s" does not match the pattern "acme_demo.controller.foo:barAction"',
                $controllerPath
            ));
        }

    }
}