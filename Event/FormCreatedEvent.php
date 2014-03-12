<?php

namespace Brammm\CommonBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class FormCreatedEvent extends Event
{
    /** @var FormInterface */
    protected $form;
    /** @var RequestStack */
    protected $requestStack;

    public function __construct(FormInterface $form, RequestStack $requestStack)
    {
        $this->form         = $form;
        $this->requestStack = $requestStack;
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return RequestStack
     */
    public function getRequestStack()
    {
        return $this->requestStack;
    }

}