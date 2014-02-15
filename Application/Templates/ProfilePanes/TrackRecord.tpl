<div class="dataTable">
  <h2>Track Record</h2>
  <table>
    <tr>
      <th width="30%">Roller Coaster</th>
      <th width="40%"><div class="textCenter">Times Ridden</div></th>
      <th width="30%">Rating</th>
    </tr>
    {% for fav in favorites %}
    <tr>
      <td>
        {{ fav.getCoaster().getLink()|raw }}
        <div class="description">at {{ fav.getCoaster().getPark().getLink()|raw }}</div>
      </td>
      <td class="textCenter">{{ fav.get('times_ridden') }}</td>
      <td><div class="rating" rating="{{ fav.get('rating') }}"></div></td>
    </tr>
    {% else %}
    <tr>
      <td colspan="3" class="textCenter"><em>{{ showMember.getName() }} has not added any roller coasters to their track record.</em></td>
    </tr>
    {% endfor %}
  </table>
</div>

{% import 'Macros/Pagination.tpl' as pagination %}
<div class="textCenter">
  {{ pagination.build(paginationLinks) }}
</div>