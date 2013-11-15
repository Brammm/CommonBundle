<?php

namespace Brammm\CommonBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
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

    public function __construct(
        ObjectManager $em,
        SecurityContextInterface $security,
        SessionInterface $session
    ) {
        $this->em       = $em;
        $this->security = $security;
        $this->session  = $session;
    }
} 