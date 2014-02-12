<?php

namespace Brammm\CommonBundle\Renderer;

interface RendererInterface
{
    /**
     * @param string $controller
     * @param array  $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($controller, $data);
} 