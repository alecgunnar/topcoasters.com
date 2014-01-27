<?php

/* Layouts/Base.tpl */
class __TwigTemplate_0c7718af9c18d7aa3604a3aff8a1be91 extends Twig_Template
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
        echo "<!Doctype html>
<html>
  <head>
    <title>";
        // line 4
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
        echo "</title>
    ";
        // line 5
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["cssFiles"]) ? $context["cssFiles"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["file"]) {
            // line 6
            echo "    <link rel=\"stylesheet\" href=\"";
            echo twig_escape_filter($this->env, (isset($context["file"]) ? $context["file"] : null), "html", null, true);
            echo "\" />
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['file'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 8
        echo "    ";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["jsFiles"]) ? $context["jsFiles"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["file"]) {
            // line 9
            echo "    <script src=\"";
            echo twig_escape_filter($this->env, (isset($context["file"]) ? $context["file"] : null), "html", null, true);
            echo "\"></script>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['file'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 11
        echo "  </head>
  <body>
    ";
        // line 13
        echo (isset($context["body"]) ? $context["body"] : null);
        echo "
  </body>
</html>";
    }

    public function getTemplateName()
    {
        return "Layouts/Base.tpl";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  59 => 13,  55 => 11,  46 => 9,  41 => 8,  32 => 6,  28 => 5,  24 => 4,  27 => 4,  22 => 2,  19 => 1,);
    }
}
