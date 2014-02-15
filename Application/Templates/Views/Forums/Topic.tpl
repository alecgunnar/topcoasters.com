{% if member %}
<div class="postingOptions">
  {% if topic.get('closed') and not member.get('is_mod') %}
  <span class="dummyButton red">Topic Closed</span>
  {% else %}
  <a href="/forums/post/reply/{{ topic.get('topic_id') }}" class="button">Post a Reply</a>
  {% endif %}
</div>
{% endif %}
<h1>{{ pageTitle|raw }}</h1>
<div class="h1Description">
  posted in {{ topic.getForum().getLink()|raw }}
</div>
{% for number,post in posts %}
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
          <div class="clear"></div>
        </div>
      </td>
      <td class="seventy">
        <div class="postNumber right">
          <a name="{{ number }}" href="#{{ number }}">#{{ number }}</a>
        </div>
        <div class="postDate">
          Posted {{ post.getDate('post_date').getShortTime() }}.
        </div>
        {{ post.getParsedMessage()|raw|nl2br }}
        
      </td>
    </tr>
  </table>
  {% if member %}
  <div class="postOptions">
    <ul>
      {% if member.get('is_mod') or member.get('is_admin') or member.get('member_id') == post.getMember().get('member_id') %}
      <li><a href="/forums/post/edit/{{ post.get('post_id') }}">Edit Post</a>
      {% if (member.get('is_mod') or member.get('is_admin')) and post.get('is_first_post') == '0' %}
      <li><a href="/forums/post/delete/{{ post.get('post_id') }}" confirm-click="true">Delete Post</a>
      {% endif %}
      {% endif %}
      <li><a href="/forums/post/reply-to/{{ post.get('post_id') }}">Reply</a>
    </ul>
  </div>
  {% endif %}
</div>
{% endfor %}
{% if member %}
<div class="postingOptions">
  {% if member.get('is_mod') %}
  <!--<a href="/forums/moderate/delete/{{ topic.get('topic_id') }}" class="button grey redText">Delete Topic</a>
  <a href="/forums/moderate/close/{{ topic.get('topic_id') }}" class="button grey">Close Topic</a>
  <a href="/forums/moderate/move/{{ topic.get('topic_id') }}" class="button grey">Move Topic</a>
  <a href="/forums/moderate/status/{{ topic.get('topic_id') }}" class="button grey">Pin Topic</a>-->
  <a href="/forums/moderate/{{ topic.get('topic_id') }}" class="button gray">Moderation Options</a>
  {% endif %}
  {% if topic.get('closed') and not member.get('is_mod') %}
  <span class="dummyButton red">Topic Closed</span>
  {% else %}
  <a href="/forums/post/reply/{{ topic.get('topic_id') }}" class="button">Post a Reply</a>
  {% endif %}
</div>
{% endif %}

{% import 'Macros/Pagination.tpl' as pagination %}

{{ pagination.build(paginationLinks) }}