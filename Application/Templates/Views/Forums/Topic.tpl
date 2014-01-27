{% if member %}
<div class="postingOptions">
  <a href="/forums/post/reply/{{ topic.get('topic_id') }}" class="button">Post a Reply</a>
</div>
{% endif %}
<h1>{{ pageTitle|raw }}</h1>
{% for post in posts %}
<div class="postWrap">
  <table class="plainTable">
    <tr>
      <td class="thirty">
        <div class="plainBox lighterBox">
          <div class="left profilePicture extraSmall bordered" style="margin-right: 10px;">
            {{ post.getMember().getProfilePicture(true)|raw }}
          </div>
          <h3 style="margin-bottom: 3px;">{{ post.getMember().getLink()|raw }}</h3>
          {% if post.getMember().getMemberTitle() is not empty %}
          {{ post.getMember().getMemberTitle() }}
          {% endif %}
          <div style="margin-top: 20px;">
            Posted <span style="font-weight: 400;">{{ post.getDate('post_date').getShortTime()|lower }}</span>.
          </div>
          <div class="clear"></div>
        </div>
        {% if member %}
        <div class="postOptions">
          <ul>
            {% if member.get('is_mod') or member.get('is_admin') or member.get('member_id') == post.getMember().get('member_id') %}
            <li><a href="/forums/post/edit/{{ post.get('post_id') }}">Edit Post</a>
            {% if (member.get('is_mod') or member.get('is_admin')) and post.get('is_first_post') == '0' %}
            <li><a href="/forums/delete/post/{{ post.get('post_id') }}">Delete Post</a>
            {% endif %}
            {% endif %}
            <li><a href="/forums/post/reply-to/{{ post.get('post_id') }}">Reply</a>
          </ul>
        </div>
        {% endif %}
      </td>
      <td class="seventy">{{ post.getParsedMessage()|raw|nl2br }}</td>
    </tr>
  </table>
</div>
{% endfor %}
{% if member %}
<div class="postingOptions">
  <a href="/forums/post/reply/{{ topic.get('topic_id') }}" class="button">Post a Reply</a>
</div>
{% endif %}

{% import 'Macros/Pagination.tpl' as pagination %}

{{ pagination.build(paginationLinks) }}