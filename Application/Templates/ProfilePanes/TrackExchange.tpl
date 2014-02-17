<div class="dataTable">
  <h2>Exchange Files</h2>
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
        {{ file.getLink()|raw }}
        <div class="description">
          uploaded in {{ file.getCategoryLink()|raw }} {{ file.getDate('upload_date').getShortTime() }}
        </div>
      </td>
      <td class="textCenter">{{ file.get('num_downloads') }}</td>
    </tr>
    {% else %}
    <tr>
      <td colspan="3" class="textCenter"><em>{{ showMember.getName() }} has not uploaded any exchange files.</em></td>
    </tr>
    {% endfor %}
  </table>
</div>
{% import 'Macros/Pagination.tpl' as pagination %}

{{ pagination.build(paginationLinks) }}