<?php
namespace ZCFram;

/**
 *
 */
class viewController
{

    protected $vars = [];
    protected $content;
    protected $view;
    protected $twig;

    public function __construct()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__.'/../../app/views');
        $this->twig = new \Twig_Environment($loader, []);
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setVars(string $vars)
    {
        $this->vars = $vars;
    }

    public function getView(): string
    {
        return $this->twig->render($this->content, $this->vars);
    }
}
