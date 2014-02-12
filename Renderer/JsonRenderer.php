<?php

namespace Brammm\CommonBundle\Renderer;

use Symfony\Component\HttpFoundation\JsonResponse;

class JsonRenderer implements RendererInterface
{

    /**
     * {@inheritDoc}
     */
    public function render($controller, $data)
    {
        return new JsonResponse($data);
    }
}