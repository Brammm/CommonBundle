<?php

namespace Brammm\CommonBundle\Renderer;

use Brammm\CommonBundle\Template\TemplateGuesserInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class TemplateRenderer implements RendererInterface
{
    /** @var TemplateGuesserInterface  */
    private $guesser;
    /** @var EngineInterface */
    private $templating;


    public function __construct(TemplateGuesserInterface $guesser, EngineInterface $templating)
    {
        $this->guesser    = $guesser;
        $this->templating = $templating;
    }

    /**
     * {@inheritDoc}
     */
    public function render($controller, $data)
    {
        return $this->templating->renderResponse(
            $this->guesser->guess($controller),
            $data
        );
    }
}