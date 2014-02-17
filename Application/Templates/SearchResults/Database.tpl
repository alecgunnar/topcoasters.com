{% for result in results %}
<div class="padding">
  {{ result.getLink()|raw }}
  <div class="description">
    {% if result.get('coaster_id') > 0 %}
    at {{ result.getPark().getLink()|raw }}
    {% else %}
    in {{ result.getLocation() }}
    {% endif %}
  </div>
</div>
{% endfor %}