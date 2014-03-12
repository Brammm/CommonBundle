<?php

namespace Brammm\CommonBundle\Controller;

use Brammm\CommonBundle\Event\ControllerEvent;
use Brammm\CommonBundle\Event\FormCreatedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     */
    public function setRequestStack(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param SessionInterface $session
     */
    public function setSession(SessionInterface $session)
    {
        $this->session = $session;
    }
} 