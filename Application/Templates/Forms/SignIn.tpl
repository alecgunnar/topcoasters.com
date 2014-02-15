{% for f in fields %}
  {% if f.getLabel() is not empty %}
<div class="field">
  <label>{{ f.getLabel() }}</label>
  {{ f.render()|raw }}
</div>
  {% else %}
<div class="submitRow">
  {{ f.render()|raw }} or <a href="/forgot-password">I forgot my password &#9785;</a>
</div>
  {% endif %}
{% endfor %}