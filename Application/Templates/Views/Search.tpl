<h1>Search Top Coasters</h1>

<div class="left thirty">
  <div class="verticalTabs">
    <ul>
      {% for url,data in areas %}
      <li><a href="/search{% if url is not empty %}/{{ url }}{% endif %}?q={{ query|url_encode }}"{% if url == what %} class="active"{% endif %}>{{ data.0 }}</a></li>
      {% endfor %}
    </ul>
  </div>
</div>
<div class="right seventy">
  <h2>Search results for: "{{ query }}" ({{ total_results }})</h2>
  {% if total_results %}
  {{ results|raw }}
  {% else %}
    <div class="alert">Your search has not returned any results.</div>
  {% endif %}
</div>
<div class="clear"></div>