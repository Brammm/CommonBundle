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
        //     1 => 'acme_demo', // vendor_bundle
        //     2 => 'foo',       // namespace of controller with possible subnamespace (bar.foo)
        //     3 => 'bar'        // name of action
        // ]
        if (preg_match('/^(.+)\.controller\.(.+):(.+)Action$/', $controller, $matches)) {

            $bundle   = ucfirst(preg_replace('/^[a-z]+_/', '', $matches[1])); // get name of bundle without vendor
            $folders  = explode('.', $matches[2]);                            // get folder/subfolder of controller
            $folders  = array_map('ucfirst', $folders);                       // uppercase folder names
            $folder   = implode('/', $folders);                               // put back together
            $template = $matches[3];                                          // name of template is name of action

            return sprintf(':%s:%s/%s.html.twig', $bundle, $folder, $template);
        }

        throw new UnguessableControllerException(sprintf(
            'The controller "%s" does not match the pattern "acme_demo.controller.foo:barAction"',
            $controller
        ));
    }
}