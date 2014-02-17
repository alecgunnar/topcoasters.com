<div class="postingOptions">
  {% if member %}
  {% if member.get('is_mod') or member.get('member_id') == file.getMember().get('member_id') %}
  <a href="/exchange/edit/{{ file.get('file_id') }}" class="button">Edit File</a>
  {% if member.get('is_mod') %}
  <a href="/exchange/delete/{{ file.get('file_id') }}" class="button red" confirm-click="true">Delete File</a>
  {% endif %}
  {% endif %}
  {% endif %}
</div>
<h1>{{ file.getName() }}</h1>
<div class="h1Description">uploaded in {{ file.getCategoryLink()|raw }}</div>
<div class="grayWrap">
  <table class="invisible" style="height: 275px;">
    <tr>
      <td class="fifty textCenter">
        {% if file.getScreenshot() %}
        <img src="{{ file.getScreenshot() }}" class="fit">
        {% else %}
        <em>No Screenshot &#9785;</em>
        {% endif %}
      </td>
      <td class="fifty">
        <h2>About</h2>
        <div class="dataTable">
          <table>
            <tr>
              <td class="twenty">Uploaded By</td>
              <td>{{ file.getMember().getLink()|raw }}</td>
            </tr>
            <tr>
              <td class="twenty">Uploaded On</td>
              <td>{{ file.getDate('upload_date').format('F j, Y g:i a') }}</td>
            </tr>
            <tr>
              <td class="twenty">Category</td>
              <td>{{ file.getCategoryLink()|raw }}</td>
            </tr>
            <tr>
              <td class="twenty">Total Downloads</td>
              <td>{{ file.get('num_downloads') }}</td>
            </tr>
          </table>
        </div>
        <div class="textCenter" style="margin-top: 30px;">
          {% if member %}
          <a href="{{ file.getDownloadUrl() }}" class="button green">Download this File &#8595;</a>
          {% endif %}
        </div>
      </td>
    </tr>
  </table>
  <div class="plainBox borderless darkBox" style="margin-top: 10px;">
    {{ file.getParsed('description')|raw|nl2br }}
  </div>
</div>