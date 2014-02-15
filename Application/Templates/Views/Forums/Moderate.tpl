<h1>Moderation Options</h1>
<div class="h1Description">for topic {{ topic.getLink()|raw }}</div>
<div class="left thirty">
  <div class="grayWrap textCenter">
    <h2>General Options</h2>
    <div style="margin: 20px;">
      <a href="/forums/moderate/{{ topic.get('topic_id') }}/stick-it" style="min-width: 170px; display: inline-block;" {% if topic.get('pinned') %}class="button red">Pull it Down{% else %}class="button">Stick it Up{% endif %}</a>
    </div>
    <div style="margin: 20px 0;">
      <a href="/forums/moderate/{{ topic.get('topic_id') }}/close-it" style="min-width: 170px; display: inline-block;" {% if topic.get('closed') %}class="button">Open the Topic{% else %}class="button red">Close the Topic{% endif %}</a>
    </div>
    <div style="margin: 20px 0;">
      <a href="/forums/moderate/{{ topic.get('topic_id') }}/feature-it" style="min-width: 170px; display: inline-block;" {% if topic.get('featured') %}class="button red">Un-Feature the Topic{% else %}class="button">Feature the Topic{% endif %}</a>
    </div>
    <div style="margin: 20px 0;">
      <a href="/forums/moderate/{{ topic.get('topic_id') }}/delete-moved-to" style="min-width: 170px; display: inline-block;" class="button red">Delete Moved-To Links</a>
    </div>
  </div>
</div>
<div class="right seventy">
  <h2>Move Topic</h2>
  {{ moveTopic.render()|raw }}
  <h2>Delete Topic</h2>
  {{ deleteTopic.render()|raw }}
</div>
<div class="clear"></div>