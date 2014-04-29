<div class="dataTable">
  <table>
    <tr>
      <th width="75%">Task</th>
      <th width="25%"><div class="textRight">Options</div></th>
    </tr>
    {% for task in tasks %}
    <tr>
      <td>
        <strong>{{ task.title }}</strong>
        <div class="description">{{ task.description }}</div>
      </td>
      <td class="textRight"><a href="/admin/tasks/run/{{ task.key }}">Run Task</a></td>
    </tr>
    {% else %}
    <tr>
      <td colspan="2" class="center"><em>There aren't any tasks available.</em></td>
    </tr>
    {% endfor %}
  </table>
</div>