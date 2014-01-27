{% if member %}
<div class="postingOptions">
  <a href="/forums/post/topic/{{ forum.get('forum_id') }}" class="button">Post a Topic</a>
</div>
{% endif %}
<h1>{{ forum.getName() }}</h1>
<div class="dataTable">
  <table>
    <tr>
      <th width="50%">Topic</th>
      <th width="20%"><div class="textCenter">Replies</div></th>
      <th width="30%">Last Post</th>
    </tr>
    {% for topic in topics %}
    <tr>
      <td>
        {{ topic.getLink()|raw }}
        <div class="description">
          Started by {{ topic.getMember().getLink()|raw }} on {{ topic.getDate('post_date').getStandardDateTime() }}.
        </div>
      </td>
      <td class="textCenter">{{ topic.get('num_replies') }}</td>
      <td>
        {% if topic.getLastPost() %}
          By {{ topic.getLastPost().getMember().getLink()|raw }} {{ topic.getLastPost().getDate('post_date').getShortTime() }}.
        {% endif %}
      </td>
    </tr>
    {% else %}
    <tr>
      <td colspan="3" class="textCenter"><em>No topics have been posted in this forum.{% if member %} Do you want to <a href="/forums/post/topic/{{ forum.get('forum_id') }}">start one</a>?{% endif %}</em></td>
    </tr>
    {% endfor %}
  </table>
</div>

{% import 'Macros/Pagination.tpl' as pagination %}

{{ pagination.build(paginationLinks) }}