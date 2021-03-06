<div class="grayWrap">
  <div class="left fifty">
    <h1>{{ park.getName()|raw }}</h1>
    {% if park.getLocation() is not empty %}
    <div id="locationInfo">
      in {{ park.getLocation()|raw }}
    </div>
    {% endif %}
  </div>
  <div class="right fourty textRight">
    <div class="plainBox borderless darkBox textRight" style="margin-bottom: 10px;">
      <div class="rating"rating="{{ park.get('rating') }}">{{ park.get('rating') }} / 5.0</div>
    </div>
    {{ park.get('tag_line')|raw }}
  </div>
  <div class="clear"></div>
</div>

{% if parkIsClosed %}
  <div class="alert">
    {{ park.get('name') }} is closed for the off-season{% if park.getDaysUntilOpen() >= 1 %}, it will reopen in {{ park.getDaysUntilOpen() }} days on {{ park.getDate('season_start').format('F jS') }}.{% elseif park.getDaysUntilOpen() == -1 %}, it will reopen tomorrow.{% else %}.{% endif %}
  </div>
{% endif %}
{% if park.get('approved') == 0 %}
  <div class="alert">This database entry has not yet been reviewed and approved.{% if member.get('is_admin') %} <a href="{{ park.getUrl() }}/approve">Approve</a>{% endif %}</div>
{% endif %}

{% import 'Macros/DataTable.tpl' as dataTable %}

<div class="left fourty">
  {{ dataTable.build('About the Park', data) }}
</div>

{% import 'Macros/Tabs.tpl' as tabs %}

<div class="right sixty">
  {% if coasterTabs|length %}
  {{ tabs.build('parkCoasters', 'Roller Coasters at ' ~ park.get('name'), coasterTabs) }}
  {% else %}
  <div class="grayWrap textCenter">
    <em>We do not have any roller coasters to show for this park.</em>
  </div>
  {% endif %}
</div>
<div class="clear"></div>
{% if member %}
<div class="padding textCenter" style="margin-top: 10px;">
  {% if member.get('is_mod') %}<a href="/database/contribute/amusement-park/{{ park.get('park_id') }}" class="button">Edit this Amusement Park</a> <a href="/database/contribute/roller-coaster/park/  {{ park.get('park_id') }}" class="button">Add a Roller Coaster</a>{% else %}{% endif %}
  {% if favorite is not null %}<a href="{{ favorite.getUrl() }}" class="button">Edit Track Record</a>{% else %}<a href="/track-record/parks/add/{{ park.get('park_id') }}" class="button">Add Park to Track Record</a>{% endif %}
</div>
{% endif %}