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
    <h3>Contribute</h3>
    <div class="verticalMenu">
      <ul>
        <li><a href="/database/contribute/roller-coaster">Add a Roller Coaster</a></li>
        <li><a href="/database/contribute/amusement-park">Add an Amusement Park</a></li>
      </ul>
    </div>
  </div>
</div>
<div class="right seventy">
  <div class="dataTable">
    <h2>Highest Rated Roller Coasters</h2>
    <table>
      <tr>
        <th width="35%">Roller Coaster</th>
        <th width="35%">Amusement Park</th>
        <th width="30%">Rating</th>
      </tr>
      {% for c in topCoasters %}
      <tr>
        <td>{{ c.getLink()|raw }}</td>
        <td>{{ c.getPark().getLink()|raw }}</td>
        <td><div class="rating" rating="{{ c.get('rating') }}">{{ c.get('rating') }} / 5.0</div></td>
      </tr>
      {% else %}
      <tr>
        <td colspan="3" class="textCenter">There aren't any roller coasters to show you.</td>
      </tr>
      {% endfor %}
    </table>
  </div>
  <div class="dataTable">
    <h2>Highest Rated Amusement Parks</h2>
    <table>
      <tr>
        <th width="35%">Amusement Park</th>
        <th width="35%">Location</th>
        <th width="30%">Rating</th>
      </tr>
      {% for p in topParks %}
      <tr>
        <td>{{ p.getLink()|raw }}</td>
        <td>{{ p.getLocation() }}</td>
        <td><div class="rating" rating="{{ p.get('rating') }}">{{ p.get('rating') }} / 5.0</div></td>
      </tr>
      {% else %}
      <tr>
        <td colspan="3" class="textCenter">There aren't any amusement parks to show you.</td>
      </tr>
      {% endfor %}
    </table>
  </div>
</div>
<div class="clear"></div>