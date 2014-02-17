{% if member and active > 0 %}
<div class="postingOptions">
  <a href="/exchange/upload/{{ active }}" class="button">Upload a File</a>
</div>
{% endif %}
<h1>Track Exchange</h1>
<div class="left thirty">
  <div class="verticalTabs">
    <ul>
      {% for id,data in categories %}
        <li><a href="/exchange/{{ data.0 }}"{% if id == active %} class="active"{% endif %}>{{ data.1 }}</a></li>
      {% endfor %}
    </ul>
  </div>
</div>
<div class="right seventy">
  <div class="dataTable">
    <h2>{{ activeTitle }}</h2>
    <table>
      <tr>
        <th colspan="2" width="75%">File</th>
        <th><div class="textCenter">Total Downloads</div></th>
      </tr>
      {% for file in files %}
      <tr>
        {% if file.getScreenshot() is not empty %}
        <td width="30%"><img src="{{ file.getScreenshot() }}" class="thumbnail" /></td>
        <td>{% else %}<td colspan="2">{% endif %}
          {{ file.getLink()|raw }}{% if active == 0 %} <span class="description">({{ file.getCategoryName() }})</span>{% endif %} 
          <div class="description">
            uploaded by {{ file.getMember().getLink()|raw }} {{ file.getDate('upload_date').getShortTime() }}
          </div>
        </td>
        <td class="textCenter">{{ file.get('num_downloads') }}</td>
      </tr>
      {% else %}
      <tr>
        <td colspan="3" class="textCenter"><em>This category does not have any files in it.</em></td>
      </tr>
      {% endfor %}
    </table>
  </div>
  {% import 'Macros/Pagination.tpl' as pagination %}
  
  {{ pagination.build(paginationLinks) }}
</div>
<div class="clear"></div>