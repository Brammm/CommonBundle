<?php

namespace Brammm\CommonBundle\Manager;

use Doctrine\ORM\EntityManager;

abstract class Manager
{
    /** @var EntityManager */
    protected $em;

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }
} 