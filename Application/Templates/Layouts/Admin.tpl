<div id="header">
  <div id="mainNavigation">
    <ul>
      {% for label, url in navigationLinks %}
      <li><a href="{{ url.0 }}"{% if url.1 %} class="active"{% endif %}>{{ label }}</a></li>
      {% endfor %}
    </ul>
  </div>
  <a href="/" id="logo">Top Coasters</a>
</div>