{% include "Components/Flexslider.tpl" %}
{% import 'Macros/Tabs.tpl' as tabs %}

<div class="recentNews borderless">
  <span class="label"><a href="/featured-topics" title="View all featured topics.">Featured Topics</a></span><div id="newsreel">We don't have any recent news or updates to show you... &#9785;</div>
</div>
<script>
var recentNewsTopics = new Array({% for topic in recentNews %}{'title': '{{ topic.getName()|raw }}', 'url': '{{ topic.getUrl() }}'}{% if loop.last == false %},{% endif %}{% endfor %});
$('#newsreel').composeNewsreel(recentNewsTopics);
</script>
<div class="left fifty">
  <div class="dataTable borderless">
    {{ tabs.build('topRated', 'Top Rated', topRatedTabs) }}
  </div>
</div>
<div class="right fifty">
  <div class="dataTable">
    <h2>Recent Track Exchange Files</h2>
    <table>
      {% for file in exchangeFiles %}
      <tr>
        {% if file.getScreenshot() is not empty %}
        <td width="20%"><img src="{{ file.getScreenshot() }}" class="medium" show-lightbox="true" /></td>
        <td>{% else %}<td colspan="2">{% endif %}
          {{ file.getLink()|raw }} by {{ file.getMember().getLink()|raw }}
          <div class="description">uploaded {{ file.getDate('upload_date').getShortTime() }}</div>
        </td>
      </tr>
      {% else %}
      <tr>
        <td class="textCenter"><em>There aren't any exchange files to show you.</em></td>
      </tr>
      {% endfor %}
    </table>
  </div>
</div>
<div class="clear"></div>
