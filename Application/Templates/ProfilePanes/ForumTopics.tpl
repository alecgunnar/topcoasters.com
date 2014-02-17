<div class="dataTable">
  <h2>Topics Started by {{ showMember.getName() }}</h2>
  <table>
    <tr>
      <th width="75%">Topic and Forum</th>
      <th><div class="textCenter">Replies</div></th>
    </tr>
    {% for topic in topics %}
    <tr>
      <td>
        {{ topic.getLink()|raw }}
        <div class="description">posted in {{ topic.getForum().getLink()|raw }} {{ topic.getDate('post_date').getShortTime() }}</div>
      </td>
      <td class="textCenter">{{ topic.get('num_replies') }}</td>
    </tr>
    {% else %}
    <tr>
      <td colspan="2" class="textCenter"><em>{{ showMember.getName() }} has not started any topics.</em></td>
    </tr>
    {% endfor %}
  </table>
</div>

{% import 'Macros/Pagination.tpl' as pagination %}
{{ pagination.build(paginationLinks) }}