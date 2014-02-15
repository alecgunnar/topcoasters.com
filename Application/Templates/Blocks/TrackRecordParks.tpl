<div class="dataTable borderless">
  <table>
    <tr>
      <th width="30%">Amusement Park</th>
      <th width="30%">Location</th>
      <th width="20%">Rating</th>
      <th width="20%"></th>
    </tr>
    {% for fav in favorites %}
    <tr favorite="{{ fav.get('favorite_id') }}" favorite-type="park">
      <td><a name="{{ fav.get('favorite_id') }}"></a>{{ fav.getPark().getLink()|raw }}</td>
      <td>{{ fav.getPark().getLocation()|raw }}</td>
      <td class="fav_rating"><div class="rating" rating="{{ fav.get('rating') }}"></div></td>
      <td class="editRecord"><a href="#">Edit</a> <a href="#">Remove</a></td>
    </tr>
    {% else %}
    <tr>
      <td colspan="5" class="textCenter"><em>You have not added any amusement parks to your track record.</em></td>
    </tr>
    {% endfor %}
  </table>
</div>