<?php

namespace Brammm\CommonBundle\Form\Handler;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractFormHandler
{
    /** @var Request */
    private $request;
    /** @var FormInterface */
    private $form;

    /**
     * Returns the alias of the form.type service
     *
     * @return string
     */
    abstract public function getFormName();

    /**
     * Handles a form
     *
     * @param callable $handleAction
     *
     * @return bool
     */
    public function handle(callable $handleAction)
    {
        $this->getForm()->handleRequest($this->getRequest());

        if ($this->getForm()->isValid()) {
            try {
                $handleAction($this->getForm());
                return true;
            } catch (\Exception $e) {
                $error = new FormError($e->getMessage());
                $this->getForm()->addError($error);
            }
        }

        return false;
    }

    /**
     * @param FormInterface $form
     *
     * @return $this
     */
    final public function setForm($form)
    {
        $this->form = $form;
        return $this;
    }

    /**
     * @return FormInterface
     */
    final public function getForm()
    {
        return $this->form;
    }

    /**
     * @param Request $request
     *
     * @return $this
     */
    final public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return Request
     */
    final public function getRequest()
    {
        return $this->request;
    }


}