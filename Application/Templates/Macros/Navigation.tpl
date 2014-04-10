{% macro menu(links) %}
<ul>
  {% for num, data in links %}
  <li><a href="{{ data.1 }}"{% if data.2 %} class="active"{% endif %}>{{ data.0 }}</a></li>
  {% endfor %}
</ul>
{% endmacro %}