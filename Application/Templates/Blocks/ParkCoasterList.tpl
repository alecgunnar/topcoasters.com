{% macro date(when, coaster) %}
{% if coaster.getOpenedDate() is empty and when == -1 %}<em>Unknown</em>{% else %}{{ coaster.getOpenedDate() }}{% endif %}{% if when == -1 %} - {% if coaster.getClosedDate() %}{{ coaster.getClosedDate() }}{% else %}<em>Unknown</em>{% endif %}{% endif %}
{% endmacro %}
{% import _self as opened %}
{% if coasters|length %}
<div class="dataTable borderless">
  <table>
    <tr>
      <th width="50%">Roller Coaster</th>
      <th>{% if when == -1 %}Operated{% elseif when == 0 %}Opened{% else %}Opening{% endif %}</th>
    </tr>
    {% for coaster in coasters %}
    <tr>
      <td>{{ coaster.getLink()|raw }}{% if coaster.get('status') == 'sbno' %} (SBNO){% endif %}</td>
      <td>{% if opened.date(when, coaster) %}{{ opened.date(when, coaster) }}{% else %}<em>Unknown</em>{% endif %}</td>
    </tr>
    {% endfor %}
  </table>
</div>
{% endif %}