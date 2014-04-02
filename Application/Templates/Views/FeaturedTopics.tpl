<div class="dataTable">
  <h2>Featured Topics</h2>
  <table>
    <tr>
      <th width="60%">Topic</th>
      <th>Forum</th>
    </tr>
    {% for article in articles %}
    <tr>
      <td width="60%">
        {{ article.getLink()|raw }}
        <div class="description">posted by {{ article.getMember().getLink()|raw }} {{ article.getDate('post_date').getShortTime() }}</div>
      </td>
      <td>{{ article.getForum().getLink()|raw }}</td>
    </tr>
    {% else %}
    <tr>
      <td colspan="2" class="textCenter"><em>There aren't any news or updates to show you...</em></td>
    </tr>
    {% endfor %}
  </table>
</div>

{% import 'Macros/Pagination.tpl' as pagination %}

{{ pagination.build(paginationLinks) }}