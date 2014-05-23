<?php

namespace Brammm\CommonBundle\Request\ParamConverter;

use Brammm\CommonBundle\Form\Handler\AbstractFormHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class FormHandlerConverter implements ParamConverterInterface
{
    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var AbstractFormHandler */
    private $instance;

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $handler = $this->getFormHandler($configuration->getClass());
        $form    = $this->formFactory->create($handler->getFormName());

        $handler
            ->setForm($form)
            ->setRequest($request);

        $request->attributes->set($configuration->getName(), $handler);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration)
    {
        return $this->getFormHandler($configuration->getClass()) instanceof AbstractFormHandler
            ? true
            : false;
    }

    /**
     * @param string  $class
     *
     * @return AbstractFormHandler
     */
    private function getFormHandler($class)
    {
        if (null === $this->instance) {
            return $this->instance = new $class;
        }

        return $this->instance;
    }
}