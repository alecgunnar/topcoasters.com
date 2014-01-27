<div class="dataTable borderless">
  <table>
    <tr>
      <th width="21%">Roller Coaster</th>
      <th width="21%">Amusement Park</th>
      <th width="21%">Times Ridden</th>
      <th width="21%">Rating</th>
      <th width="16%"></th>
    </tr>
    {% for fav in favorites %}
    <tr favorite="{{ fav.get('favorite_id') }}" favorite-type="coaster">
      <td><a name="{{ fav.get('favorite_id') }}"></a>{{ fav.getCoaster().getLink()|raw }}</td>
      <td>{{ fav.getCoaster().getPark().getLink()|raw }}</td>
      <td class="fav_ridden">{{ fav.get('times_ridden') }}</td>
      <td class="fav_rating"><div class="rating" rating="{{ fav.get('rating') }}"></div></td>
      <td class="editRecord"><a href="#">Edit</a> <a href="#">Remove</a></td>
    </tr>
    {% else %}
    <tr>
      <td colspan="5" class="textCenter"><em>You have not added any roller coasters to your track record.</em></td>
    </tr>
    {% endfor %}
  </table>
</div>