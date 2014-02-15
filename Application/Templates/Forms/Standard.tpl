{% macro renderFormField(field) %}
{% if field.getPrepended() is not empty %}{{ field.getPrepended()|raw }} {% endif %}{{ field.render()|raw }}{% if field.getAppended() is not empty %} {{ field.getAppended()|raw}}{% endif %}
{% endmacro %}

{% macro renderAttachedFields(fields) %}
{% import _self as form %}
{% for f in fields %}
{{ form.renderFormField(f) }}
{% endfor %}
{% endmacro %}

{% macro renderField(field) %}
{% import _self as form %}
<div class="field">
  {% if field.getLabel() is not empty %}<span class="label">{{ field.getLabel() }}</span>{% endif %}{% if field.getError() %}<span class="error">{% if field.getLabel() is not empty %} &middot; {% endif %}{{ field.getError()|raw }}</span>{% elseif field.isRequired() %} <span style="color: #A00;">*</span>{% endif %}{% if field.getLabel() or field.getError() %}{% endif %}
  <div>{{ form.renderFormField(field) }}{% if field.getAttachedFields()|length %}{{ form.renderAttachedFields(field.getAttachedFields()) }}{% endif %}</div>
  {% if field.getDescription() is not empty %}
  <div class="description">
    {{ field.getDescription()|raw }}
  </div>
  {% endif %}
</div>
{% endmacro %}

{% macro renderGroup(group) %}
{% import _self as form %}
<div class="fieldGroup container">
  {% if group.label is not empty %}<div class="groupLabel">{{ group.label }}</div>{% endif %}
  {{ form.renderFields(group.fields) }}
</div>
{% endmacro %}

{% macro renderFields(fields) %}
  {% import _self as form %}
  
  {% for f in fields %}
    {% if f is iterable %}
      {{ form.renderGroup(f) }}
    {% else %}
      {{ form.renderField(f) }}
    {% endif %}
  {% endfor %}
{% endmacro %}

{% import _self as form %}

<div class="standardForm container">
  {{ form.renderFields(fields) }}
</div>