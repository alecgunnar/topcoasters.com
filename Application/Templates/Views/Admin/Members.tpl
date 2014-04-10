<div class="dataTable">
  <table>
    <tr>
      <th width="40%">Username</th>
      <th width="40%">Joined</th>
      <th width="20%"></th>
    </tr>
    {% for member in members %}
    <tr>
      <td>{{ member.getLink()|raw }}</td>
      <td>{{ member.getDate('reg_date').getShortTime() }}</td>
      <td></td>
    </tr>
    {% endfor %}
  </table>
</div>