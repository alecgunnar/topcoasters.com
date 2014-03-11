<h1>Roller Coaster & Amusement Park Database</h1>
<div class="left thirty">
  <div class="grayWrap">
    <h3>Useful Links</h3>
    <div class="verticalMenu">
      <ul>
        <li><a href="/database/roller-coasters">View Roller Coasters</a></li>
        <li><a href="/database/amusement-parks">View Amusement Parks</a></li>
        <li><strong><a href="/track-record">Update my Track Record</a></strong></li>
      </ul>
    </div>
    {% if member.get('is_mod') %}
    <h3>Contribute</h3>
    <div class="verticalMenu">
      <ul>
        <li><a href="/database/contribute/roller-coaster">Add a Roller Coaster</a></li>
        <li><a href="/database/contribute/amusement-park">Add an Amusement Park</a></li>
      </ul>
    </div>
    {% endif %}
  </div>
</div>
<div class="right seventy">
  <div class="dataTable">
    <h2>Highest Rated Roller Coasters</h2>
    {% include "Blocks/TopRatedCoasters.tpl" %}
  </div>
  <div class="dataTable">
    <h2>Highest Rated Amusement Parks</h2>
    {% include "Blocks/TopRatedParks.tpl" %}
  </div>
</div>
<div class="clear"></div>