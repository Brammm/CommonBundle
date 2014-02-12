<?php

namespace Brammm\CommonBundle\EventListener;

use Brammm\CommonBundle\Exception\UnsupportedTypeException;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     *
     * @throws \LogicException
     */
    public function onControllerResponse(GetResponseForControllerResultEvent $event)
    {
        $controller = $event->getRequest()->attributes->get('_controller');

        $responseType = $this->defaultResponse;
        foreach ($this->responses as $key => $type) {
            if (preg_match(sprintf('/%s/', $key), $controller)) {
                $responseType = $type;
                break;
            }
        }

        switch ($responseType) {
            case 'json':
                    $response = new JsonResponse($event->getControllerResult());
                break;
            case 'template':
                    $template = $this->getTemplate($event->getRequest()->attributes->get('_controller'));
                    $response = $this->templating->renderResponse($template, $event->getControllerResult());
                break;
            default:
                throw new UnsupportedTypeException(sprintf('Response type "%s" not supported', $responseType));
        }


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