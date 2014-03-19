<?php

namespace Brammm\CommonBundle\Controller;

use Brammm\CommonBundle\Event\ControllerEvent;
use Brammm\CommonBundle\Event\FormCreatedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

abstract class Controller
{
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;
    /** @var FormFactory */
    protected $formFactory;
    /** @var Request*/
    protected $request;
    /** @var SessionInterface */
    protected $session;

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

        $event = new FormCreatedEvent($form, $this->request);
        $this->eventDispatcher->dispatch(ControllerEvent::FORM_CREATED, $event);

        return $form;
    }

    /**
     * Will tell you if it's okay to process a form or not
     * Handles the request for you as well
     *
     * @param FormInterface $form
     *
     * @return bool
     */
    public function processForm(FormInterface $form)
    {
        if (!$this->request->isMethod('POST')) {
            return false;
        }

        $form->handleRequest($this->request);

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
     * @param FormFactory $formFactory
     */
    public function setFormFactory(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param RequestStack $requestStack
     */
    public function setRequest(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @param SessionInterface $session
     */
    public function setSession(SessionInterface $session)
    {
        $this->session = $session;
    }
} 