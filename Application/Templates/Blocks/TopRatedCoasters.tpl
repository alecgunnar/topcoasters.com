<table>
  <tr>
    <th width="65%">Roller Coaster & Amusement Park</th>
    <th width="30%">Rating</th>
  </tr>
  {% for c in topCoasters %}
  <tr>
    <td>
      {{ c.getLink()|raw }}
      <div class="description">
        at {{ c.getPark().getLink()|raw }}
      </div>
    </td>
    <td><div class="rating" rating="{{ c.get('rating') }}">{{ c.get('rating') }} / 5.0</div></td>
  </tr>
  {% else %}
  <tr>
    <td colspan="3" class="textCenter">There aren't any roller coasters to show you.</td>
  </tr>
  {% endfor %}
</table>