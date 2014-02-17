{% for result in results %}
<div class="padding">
  {% if result.get('post_id') > 0 %}
  <a href="{{ result.getUrl() }}">{{ result.getTopic().getName() }}</a>
  <div class="description">in {{ result.getTopic().getForum().getLink()|raw }}</div>
  {% else %}
  {{ result.getLink()|raw }}
  <div class="description">in {{ result.getForum().getLink()|raw }}</div>
  {% endif %}
</div>
{% endfor %}