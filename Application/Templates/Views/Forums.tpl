{% for category in forums %}
  <div class="dataTable">
    <h2>{{ category.0.getName() }}</h2>
    <table>
      <tr>
        <th width="50%">Forum</th>
        <th width="10%"><div class="textCenter">Topics</div></th>
        <th width="10%"><div class="textCenter">Replies</div></th>
        <th width="40%">Last Post</th>
      </tr>
      {% for forum in category.1 %}
      <tr>
        <td>
          <div style="font-weight: 400;">{{ forum.getLink()|raw }}</div>
          {% if forum.get('description') is not empty %}
          <div class="description">
            {{ forum.get('description') }}
          </div>
          {% endif %}
        </td>
        <td class="textCenter">{{ forum.get('num_topics') }}</td>
        <td class="textCenter">{{ forum.get('num_posts') }}</td>
        <td>
          {% if forum.getLastPost() %}
          In {{ forum.getLastPost.getTopic.getLink()|raw }} by {{ forum.getLastPost().getMember.getLink()|raw }}
          {% else %}
          <em>No recent post.</em>
          {% endif %}
        </td>
      </tr>
      {% endfor %}
    </table>
  </div>
{% else %}
<div class="grayWrap">
  This cannot be right.... we don't have any forums.
</div>
{% endfor %}