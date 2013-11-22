<?php

namespace Brammm\CommonBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class FormCreatedEvent extends Event
{
    /** @var FormInterface */
    protected $form;
    /** @var Request */
    protected $request;

    public function __construct(FormInterface $form, Request $request)
    {
        $this->form    = $form;
        $this->request = $request;
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

}