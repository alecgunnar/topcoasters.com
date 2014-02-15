{% macro build(name, title, tabs) %}
<div class="tabs boxBorder" id="{{ name }}">
  <h2>{{ title }}</h2>
  <div class="tabMenu">
   <ul>
      {% for label,data in tabs %}
        {% if data.1 is not empty %}
      <li><a href="#"{% if data.0 %} class="active"{% endif %}>{{ label }}</a></li>
        {% endif %}
      {% endfor %}
    </ul>
  </div>
  <div class="tabContent">
    {% for content in tabs %}
      {% if content.1 is not empty %}
      <div class="tabBody{% if content.0 %} active{% endif %}">
        {{ content.1|raw }}
      </div>
      {% endif %}
    {% endfor %}
  </div>
</div>
<script>
$('#{{ name }}').tabs();
</script>
{% endmacro %}