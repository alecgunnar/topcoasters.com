{% import "Macros/DataTable.tpl" as dataTable %}

{% if coaster.get('approved') == 0 %}
  <div class="alert right">This database entry has not yet been reviewed and approved.{% if member.get('is_admin') %} <a href="{{ coaster.getUrl() }}/approve">Approve</a>{% endif %}</div>
{% endif %}

<h1>{% if coaster.get('name') is empty %}<em>{{ coaster.getName() }}</em>{% else %}{{ coaster.getName() }}{% endif %}</h1>
<div class="h1Description">
  at {{ park.getLink()|raw }} {% if park.getLocation() is not empty %} in {{ park.getLocation()|raw }}{% endif %}
</div>
<div class="left seventy">
  {{ dataTable.build("Roller Coaster Information", data) }}
</div>
<div class="right thirty">
  <div class="plainBox lightBox">
    <div class="rating right" rating="{{ coaster.get('rating') }}">{% if coaster.get('rating') != 0 %}{{ coaster.get('rating') }} / 5.0 {% else %}N/A{% endif %}</div>
    <h3>Rating</h3>
    <div class="textCenter">
    {% if member is not empty %}
      {% if favorite is not empty %}
      <a href="{{ favorite.getUrl() }}">Edit Track Record</a>
      {% else %}
      <a href="/track-record/coasters/add/{{ coaster.get('coaster_id') }}">Add to Track Record</a>
      {% endif %}
    {% endif %}
    </div>
  </div>
  {% if coaster.getPovVideos() is iterable and coaster.getPovVideos()|length %}
  <div class="grayWrap">
    <h3>Point of View</h3>
    {% for videoId in coaster.getPovVideos() %}
      <iframe width="100%" height="200" src="//www.youtube.com/embed/{{ videoId }}" frameborder="0" allowfullscreen></iframe>
    {% endfor %}
  </div>
  {% endif %}
  {% if coaster.get('description') %}
  <div class="grayWrap">
    <h3>More Information</h3>
    {{ coaster.get('description')|nl2br }}
  </div>
  {% endif %}
  {% if member.get('member_id') %}
  <div class="plainBox lighterBox textCenter">
    <a href="/database/contribute/roller-coaster/{{ coaster.get('coaster_id') }}">Edit this Roller Coaster</a>
  </div>
  {% endif %}
</div>
<div class="clear"></div>