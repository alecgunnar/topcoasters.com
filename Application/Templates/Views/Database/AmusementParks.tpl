{% if amusementParks|length %}
<div class="alphabetNavigation">
  <ul>
  {% for key, parks in amusementParks %}
    <li><a href="#{{ key }}">{% if key == 26 %}0-9{% elseif key == 27 %}No Name{% else %}{{ key|upper }}{% endif %}</a></li>
  {% endfor %}
  </ul>
</div>
{% endif %}
<div class="left thirty">
  <div class="plainBox darkBox">
    {% if filtered_results %}
    <div class="right">
      <a href="/database/amusement-parks">Clear Filters</a>
    </div>
    {% endif %}
    <h3>Filter by Location</h3>
    {{ filter_form|raw }}
  </div>
</div>
<div class="right seventy">
  {% for key, parks in amusementParks %}
  <div class="dataTable">
    <a name="{{ key }}"></a>
    <h2>{% if key == 26 %}0-9{% elseif key == 27 %}Unknown Names{% else %}{{ key|upper }}{% endif %}</h2>
    <table>
      <tr>
        <th width="50%">Amusement Park</th>
        <th width="50%">Location</th>
      </tr>
      {% for p in parks %}
      <tr>
        <td>{{ p.getLink()|raw }}</td>
        <td>{{ p.getLocation()|raw }}</td>
      </tr>
      {% endfor %}
    </table>
  </div>
  {% else %}
  <div class="alert">
    There aren't any amusement parks which match your chosen filters.
  </div>
  {% endfor %}
</div>
<div class="clear"></div>