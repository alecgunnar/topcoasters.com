<table>
  <tr>
    <th width="60%">Amusement Park</th>
    <th width="40%">Rating</th>
  </tr>
  {% for p in topParks %}
  <tr>
    <td>
      {{ p.getLink()|raw }}
      <div class="description">
      in {{ p.getLocation() }}
      </div>
    </td>
    <td><div class="rating" rating="{{ p.get('rating') }}">{{ p.get('rating') }} / 5.0</div></td>
  </tr>
  {% else %}
  <tr>
    <td colspan="3" class="textCenter">There aren't any amusement parks to show you.</td>
  </tr>
  {% endfor %}
</table>