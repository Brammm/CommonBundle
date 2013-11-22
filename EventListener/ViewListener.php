<?php

namespace Brammm\CommonBundle\EventListener;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

class ViewListener {

    /** @var EngineInterface */
    protected $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
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
     * to "AcmeDemoBundle:Foo:bar.html.twig"
     *
     * @param string $controllerPath
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function getTemplate($controllerPath)
    {
        if(preg_match('/^(.+)\.controller\.(.+):(.+)Action$/', $controllerPath, $matches)) {
            $bundle = preg_replace('/(?:^|_)(.?)/e',"strtoupper('$1')", $matches[1]) . 'Bundle';
            $controller = ucfirst($matches[2]);
            $action = $matches[3];

            return $bundle . ':' . $controller . ':' . $action . '.html.twig';
        } else {
            throw new \InvalidArgumentException(sprintf('The controller "%s" does not match the pattern "acme_demo.controller.foo:barAction"', $controllerPath));
        }

    }
}