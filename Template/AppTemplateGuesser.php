<?php

namespace Brammm\CommonBundle\Template;

use Brammm\CommonBundle\Exception\UnguessableControllerException;

class AppTemplateGuesser implements TemplateGuesserInterface
{

    /**
     * {@inheritDoc}
     */
    public function guess($controller)
    {
        // Gets an array that looks like
        // [
        //     1 => 'acme_demo',
        //     2 => 'foo',
        //     3 => 'bar'
        // ]
        if (preg_match('/^(.+)\.controller\.(.+):(.+)Action$/', $controller, $matches)) {
            return ':'
            . ucfirst(preg_replace('/^[a-z]+_/', '', $matches[1])) // Strip out the vendor
            . ':'
            . ucfirst($matches[2])
            . '/' . $matches[3]
            . '.html.twig';
        }

        throw new UnguessableControllerException(sprintf(
            'The controller "%s" does not match the pattern "acme_demo.controller.foo:barAction"',
            $controller
        ));
    }
}