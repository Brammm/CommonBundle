<?php

namespace Brammm\CommonBundle\Controller;

use Brammm\CommonBundle\Event\ControllerEvent;
use Brammm\CommonBundle\Event\FormCreatedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class Controller
{
    /** @var RequestStack */
    protected $requestStack;
    /** @var FormFactory */
    protected $formFactory;
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /**
     * Creates a form
     *
     * @param         $type
     * @param null    $data
     * @param array   $options
     *
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    public function createForm($type, $data = null, array $options = array())
    {
        $form = $this->formFactory->create($type, $data, $options);

        $event = new FormCreatedEvent($form, $this->requestStack);
        $this->eventDispatcher->dispatch(ControllerEvent::FORM_CREATED, $event);

        return $form;
    }

    ########################
    ## Dependency Setters ##
    ########################

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param FormFactory $formFactory
     */
    public function setFormFactory(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param RequestStack $requestStack
     */
    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
} 