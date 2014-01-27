{% macro renderField(label, field) %}
<div class="field{% if field.getAttribute('checked') is not null %} noHide{% endif %}">
  <label>{{ field.render()|raw }} {{ label|raw }}</label>
</div>
{% endmacro %}

{% macro renderFields(fields) %}
  {% import _self as form %}
  
  {% for f in fields %}
    {% if f is iterable %}
      <div class="fieldGroup container">
        <div class="groupLabel">
          <a class="minimax right">Show</a>
          {{ f.label }}
        </div>
        <div class="locations">
          {{ form.renderFields(f.fields) }}
        </div>
      </div>
    {% else %}
      {{ form.renderField(f.getLabel(), f) }}
    {% endif %}
  {% endfor %}
{% endmacro %}

{% import _self as form %}

<div class="filterForm">
  {{ form.renderFields(fields) }}
</div>