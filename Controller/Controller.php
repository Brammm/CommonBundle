<?php

namespace Brammm\CommonBundle\Controller;

use Brammm\CommonBundle\Event\ControllerEvent;
use Brammm\CommonBundle\Event\FormCreatedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class Controller
{
    /** @var EventDispatcherInterface */
    private $eventDispatcher;
    /** @var FormFactory */
    private $formFactory;

    /**
     * Creates a form
     *
     * @param Request $request
     * @param         $type
     * @param null    $data
     * @param array   $options
     *
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    public function createForm(Request $request, $type, $data = null, array $options = array())
    {
        $form = $this->formFactory->create($type, $data, $options);

        $event = new FormCreatedEvent($form, $request);
        $this->eventDispatcher->dispatch(ControllerEvent::FORM_CREATED, $event);

        return $form;
    }

    /**
     * Will tell you if it's okay to process a form or not
     * Handles the request for you as well
     *
     * @param FormInterface $form
     * @param Request       $request
     *
     * @return bool
     */
    public function processForm(FormInterface $form, Request $request)
    {
        if (!$request->isMethod('POST')) {
            return false;
        }

        $form->handleRequest($request);

        return $form->isValid();
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
     * @return EventDispatcherInterface
     */
    final public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * @param FormFactory $formFactory
     */
    public function setFormFactory(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @return FormFactory
     */
    final public function getFormFactory()
    {
        return $this->formFactory;
    }


} 