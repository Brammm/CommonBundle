<?php

namespace Brammm\CommonBundle\Template;

interface TemplateGuesserInterface
{

    /**
     * Receives a _controller string (e.g. acme_demo.controller.foo:barAction)
     * Transforms it into a location where the corresponding view might be (e.g. AcmeDemoBundle:Foo:bar.html.twig)
     *
     * @param string $controller
     *
     * @return string
     * @throws \Brammm\CommonBundle\Exception\UnguessableControllerException
     */
    public function guess($controller);
} 