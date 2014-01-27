{% if rollerCoasters|length %}
<div class="alphabetNavigation">
  <ul>
  {% for key, coasters in rollerCoasters %}
    <li><a href="#{{ key }}">{% if key == 26 %}0-9{% elseif key == 27 %}No Name{% else %}{{ key|upper }}{% endif %}</a></li>
  {% endfor %}
  </ul>
</div>
{% endif %}
<div class="left thirty">
  <div class="plainBox darkBox">
  {% if filtered_results %}
    <div class="right">
      <a href="/database/roller-coasters">Clear Filters</a>
    </div>
    {% endif %}
    <h3>Filters</h3>
    {% if filter_park is not empty %}
    <div class="plainBox lighterBox">
      <strong>Amusement Park:</strong><br />
      {{ filter_park.getLink()|raw }}
    </div>
    {% endif %}
    {{ filterForm|raw }}
  </div>
</div>
<div class="right seventy">
  {% for key, coasters in rollerCoasters %}
  <div class="dataTable">
    <a name="{{ key }}"></a>
    <h2>{% if key == 26 %}0-9{% elseif key == 27 %}Unknown Names{% else %}{{ key|upper }}{% endif %}</h2>
    <table>
      <tr>
        <th width="50%">Roller Coaster</th>
        <th width="50%">Amusement Park</th>
      </tr>
      {% for c in coasters %}
      <tr>
        <td>{{ c.getLink()|raw }}</td>
        <td>{{ c.getPark().getLink()|raw }}</td>
      </tr>
      {% endfor %}
    </table>
  </div>
  {% else %}
  <div class="alert">
    There aren't any roller coasters which match your chosen filters.
  </div>
  {% endfor %}
</div>
<div class="clear"></div>