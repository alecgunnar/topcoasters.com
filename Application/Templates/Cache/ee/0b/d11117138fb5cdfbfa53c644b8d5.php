<?php

/* Layouts/Default.tpl */
class __TwigTemplate_ee0bd11117138fb5cdfbfa53c644b8d5 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div id=\"wrapper\">
  <h1>";
        // line 2
        echo twig_escape_filter($this->env, (isset($context["pageTitle"]) ? $context["pageTitle"] : null), "html", null, true);
        echo "</h1>
  <div id=\"content\">
    ";
        // line 4
        echo (isset($context["content"]) ? $context["content"] : null);
        echo "
  </div>
</div>";
    }

    public function getTemplateName()
    {
        return "Layouts/Default.tpl";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  27 => 4,  22 => 2,  19 => 1,);
    }
}
