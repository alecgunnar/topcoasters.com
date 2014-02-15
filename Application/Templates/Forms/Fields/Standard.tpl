<div class="field">
  {% if label is not empty %}<label>{{ label }}</label>{% endif %}{% if error %}<span class="fieldError">{% if label is not empty %} &middot; {% endif %}{{ error|raw }}</span>{% endif %}{% if label or error %}<br />{% endif %}
  {{ field|raw }}
  {% if desc is not empty %}
  <div class="description">
    {{ desc }}
  </div>
  {% endif %}
</div>