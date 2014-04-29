<div class="dataTable">
  <table>
    <tr>
      <th width="60%">Cache Key</th>
      <th width="20%"><div class="textCenter">Size</div></th>
      <th width="20%"><div class="textRight">Options</div></th>
    </tr>
    {% for cache in caches %}
    <tr>
      <td>{{ cache.key }}</td>
      <td class="textCenter">{{ cache.size }} Kb</td>
      <td class="textRight">{% if cache.recache %}<a href="/admin/content/cached/recache/{{ cache.key }}">Recache</a>{% endif %}</td>
    </tr>
    {% else %}
    <tr>
      <td colspan="2" class="center"><em>There aren't any caches.</em></td>
    </tr>
    {% endfor %}
  </table>
</div>