{% macro renderField(label, field) %}
<div class="field">
  <label>{{ field.render()|raw }} {{ label }}</label>
</div>
{% endmacro %}

{% macro renderFields(fields) %}
  {% import _self as form %}
  
  {% for f in fields %}
    {% if f is iterable %}
      <div class="fieldGroup container">
        <div class="groupLabel">{{ f.label }}</div>
        {{ form.renderFields(f.fields) }}
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