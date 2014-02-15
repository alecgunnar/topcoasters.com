{% include "Components/Flexslider.tpl" %}

<div class="left fourty">
  <div class="recentNews">
    <h2>Featured Topics</h2>
    {% for topic in recentNews %}
    <div class="article">
      {{ topic.getLink()|raw }}
      <div class="description">{{ topic.getForum().getLink()|raw }} &middot; {{ topic.getDate('post_date').format('F j, Y g:i a') }}</div>
    </div>
    {% else %}
    <em>There aren't any featured topics to display.</em>
    {% endfor %}
  </div>
</div>
<div class="right sixty">
  
</div>
<div class="clear"></div>