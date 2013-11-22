<?php

namespace Brammm\CommonBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactory;
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

    /**
     * Creates a form
     *
     * @param       $type
     * @param null  $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    public function createForm($type, $data = null, array $options = array())
    {
        return $this->formFactory->create($type, $data, $options);
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
} 