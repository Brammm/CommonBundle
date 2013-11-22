<?php

namespace Brammm\CommonBundle\Controller;

use Brammm\CommonBundle\Event\ControllerEvent;
use Brammm\CommonBundle\Event\FormCreatedEvent;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

abstract class Controller
{
    /** @var ObjectManager */
    protected $em;
    /** @var SecurityContextInterface */
    protected $security;
    /** @var SessionInterface */
    protected $session;
    /** @var FormFactory */
    protected $formFactory;
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /**
     * Creates a form
     *
     * @param       $type
     * @param null  $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    public function createForm($type, $data = null, Request $request = null, array $options = array())
    {
        $form = $this->formFactory->create($type, $data, $options);

        if (null === $request) {
            $form->handleRequest($request);
        }

        $event = new FormCreatedEvent($form, $request);
        $this->eventDispatcher->dispatch(ControllerEvent::FORM_CREATED, $event);

        return $form;
    }

    ########################
    ## Dependency Setters ##
    ########################

    /**
     * @param ObjectManager $em
     */
    public function setEntityManager(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param SecurityContextInterface $security
     */
    public function setSecurity(SecurityContextInterface $security)
    {
        $this->security = $security;
    }

    /**
     * @param SessionInterface $session
     */
    public function setSession(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param FormFactory $formFactory
     */
    public function setFormFactory(FormFactory $formFactory) {
        $this->formFactory = $formFactory;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher) {
        $this->eventDispatcher = $eventDispatcher;
    }
} 