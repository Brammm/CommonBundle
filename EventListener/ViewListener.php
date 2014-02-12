<?php

namespace Brammm\CommonBundle\EventListener;

use Brammm\CommonBundle\Exception\UnsupportedTypeException;
use Brammm\CommonBundle\Template\TemplateGuesserInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

class ViewListener
{
    /** @var string */
    private $defaultType;
    /** @var array */
    private $types;
    /** @var TemplateGuesserInterface */
    private $guesser;
    /** @var EngineInterface */
    private $templating;

    public function __construct($defaultType, array $types, TemplateGuesserInterface $guesser, EngineInterface $templating)
    {
        $this->defaultType = $defaultType;
        $this->types       = $types;
        $this->guesser     = $guesser;
        $this->templating  = $templating;
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

        $responseType = $this->defaultType;
        foreach ($this->types as $key => $type) {
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
                $template = $this->guesser->guess($controller);
                $response = $this->templating->renderResponse($template, $event->getControllerResult());
                break;
            default:
                throw new UnsupportedTypeException(sprintf('Response type "%s" not supported', $responseType));
        }


        $event->setResponse($response);
    }
}