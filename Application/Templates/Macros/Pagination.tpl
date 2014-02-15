{% macro build(data) %}
{% if data.pages %}
<div class="pagination">
  <div class="label">
    {% if data.pages == 1 %}
    Single Page
    {% else %}
    Page {{ data.page }} of {{ data.pages }}
    {% endif %}
  </div>
  {% if data.links|length %}
  <ul>
    {% for link in data.links %}
    <li><a href="{{ link.0 }}">{{ link.1|raw }}</a></li>
    {% endfor %}
  </ul>
  {% endif %}
</div>
{% endif %}
{% endmacro %}