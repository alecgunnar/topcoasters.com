<div class="left thirty">
  <div id="panes">
    <h2>Settings Navigation</h2>
    <ul>
      {% for link,label in panes %}
        <li><a href="/account/{{ link }}"{% if activePane == link %} class="active"{% endif %}>{{ label }}</a></li>
      {% endfor %}
    </ul>
  </div>
</div>
<div class="right seventy">
  <div id="pane">
    <div id="{{paneId}}">
      {{ paneView|raw }}
    </div>
  </div>
</div>
<div class="clear"></div>