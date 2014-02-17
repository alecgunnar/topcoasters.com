{% for result in results %}
<div class="padding">
  {% if result.getScreenshot() is not empty %}<div class="left" style="margin-right: 20px;"><img src="{{ result.getScreenshot() }}" class="small" /></div>{% endif %}{{ result.getLink()|raw }}
  <div class="description">In {{ result.getCategoryLink()|raw }}</div>
  <div class="clear"></div>
</div>
{% endfor %}