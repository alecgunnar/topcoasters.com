<?php

/* Views/Index.tpl */
class __TwigTemplate_55235d4160ed74bac66c791e755919c4 extends Twig_Template
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
        echo "Maverick is a PHP framework, it is designed to be as flexible as possible, with easy to use features and libraries.<br />
<br />
To begin building your application, you can start by editing this page. The file which controls this page's content is located at:
<pre class=\"code\">
";
        // line 5
        echo twig_escape_filter($this->env, (isset($context["pathToController"]) ? $context["pathToController"] : null), "html", null, true);
        echo "
</pre>
You can begin by editing that page and go from there to build your whole application.";
    }

    public function getTemplateName()
    {
        return "Views/Index.tpl";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  25 => 5,  19 => 1,);
    }
}
