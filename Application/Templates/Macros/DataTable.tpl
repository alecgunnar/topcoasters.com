{% macro row(label, value) %}
{% if value is not empty %}
<tr>
  <td width="35%">{{ label }}</td>
  <td>{{ value|raw }}</td>
</tr>
{% endif %}
{% endmacro %}

{% macro build(title, data) %}
{% import _self as dt %}

<div class="dataTable">
  <h2>{{ title }}</h2>
  <table>
  {% for header,rows in data %}
    {% set dataRows = '' %}
    {% if rows is iterable %}
      {% for label, value in rows %}
        {% set dataRows = dataRows ~ dt.row(label, value) %}
      {% endfor %}
      {% if dataRows is not empty %}
      <tr>
        <th colspan="2">{{ header }}</th>
      </tr>
      {{ dataRows|raw }}
      {% endif %}
    {% else %}
    {{ dt.row(header, rows) }}
    {% endif %}
  {% endfor %}
  </table>
</div>
{% endmacro %}