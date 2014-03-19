<?php

namespace Brammm\CommonBundle\EventListener;

use Brammm\CommonBundle\Exception\UnsupportedTypeException;
use Brammm\CommonBundle\Renderer\RendererInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

class ViewListener
{
    /** @var string */
    private $defaultType;
    /** @var array */
    private $types;
    /** @var RendererInterface[] */
    private $renderers;

    public function __construct($defaultType, array $types)
    {
        $this->defaultType = $defaultType;
        $this->types       = $types;
    }

    /**
     * Looks at the current controller and finds a matching template
     * Controller must be called very specifically (as a service)
     * It then renders the template and sets it as the Response
     *
     * @param GetResponseForControllerResultEvent $event
     *
     * @throws UnsupportedTypeException
     */
    public function onControllerResponse(GetResponseForControllerResultEvent $event)
    {
        $controller = $event->getRequest()->attributes->get('_controller');

        $type = $this->defaultType;
        // see if we configured a type for a regex that matches the $controller
        foreach ($this->types as $key => $keyType) {
            if (preg_match(sprintf('/%s/', $key), $controller)) {
                $type = $keyType;
                break;
            }
        }

        if (!isset($this->renderers[$type])) {
            throw new UnsupportedTypeException(sprintf('Response type "%s" not supported', $type));
        }

        $event->setResponse(
            $this->renderers[$type]->render(
                $controller,
                $event->getControllerResult() ?: []
            )
        );
    }

    /**
     * @param RendererInterface[] $renderers
     */
    public function setRenderers(array $renderers)
    {
        foreach ($renderers as $type => $renderer) {
            $this->addRenderer($type, $renderer);
        }

    }

    /**
     * @param string            $type
     * @param RendererInterface $renderer
     *
     * @return $this
     */
    public function addRenderer($type, RendererInterface $renderer)
    {
        $this->renderers[$type] = $renderer;
        return $this;
    }


}