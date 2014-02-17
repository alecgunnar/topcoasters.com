{% if member %}
<div class="postingOptions">
  <a href="/forums/post/topic/{{ forum.get('forum_id') }}" class="button">Post a Topic</a>
</div>
{% endif %}
<h1>{{ forum.getName() }}</h1>
{% if forum.get('description') %}
<div class="h1Description">{{ forum.get('description') }}</div>
{% endif %}
<div class="dataTable">
  <table>
    <tr>
      <th width="50%" colspan="2">Topic</th>
      <th width="15%"><div class="textCenter">Replies</div></th>
      <th width="35%">Last Post</th>
    </tr>
    {% for topic in topics %}
    {% if topic.get('moved_to') %}
    <tr>
      <td colspan="4">Moved: {{ topic.getLink()|raw }}</td>
    {% else %}
    <tr class="{% if topic.get('pinned') %}pinned{% endif %}{% if topic.get('closed') %} closed{% endif %}{% if topic.get('featured') %} featured{% endif %}">
      <td width="5%" class="status"></td>
      <td>
        {{ topic.getLink()|raw }}
        <div class="description">
          started by {{ topic.getMember().getLink()|raw }} on {{ topic.getDate('post_date').getStandardDateTime() }}.
        </div>
      </td>
      <td class="textCenter">{{ topic.get('num_replies') }}</td>
      <td>
        {% if topic.getLastPost() %}
          By {{ topic.getLastPost().getMember().getLink()|raw }} {{ topic.getLastPost().getDate('post_date').getShortTime() }}.
        {% endif %}
      </td>
    {% endif %}
    </tr>
    {% else %}
    <tr>
      <td colspan="4" class="textCenter"><em>No topics have been posted in this forum.{% if member %} Do you want to <a href="/forums/post/topic/{{ forum.get('forum_id') }}">start one</a>?{% endif %}</em></td>
    </tr>
    {% endfor %}
  </table>
</div>

{% import 'Macros/Pagination.tpl' as pagination %}

{{ pagination.build(paginationLinks) }}