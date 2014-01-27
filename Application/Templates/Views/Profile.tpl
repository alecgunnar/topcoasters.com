<div class="grayWrap">
  <div class="right plainBox darkerBox">
    <strong>
      {% if activeSession %}
      <span style="color:green;">Online</span>
      {% else %}
      <span style="color:red;">Offline</span>
      {% endif %}
    </strong>
  </div>
  <div class="left profilePicture medium">
    <img src='{{ showMember.getProfilePicture() }}' />
  </div>
  <h1>{{ showMember.getName() }}</h1>
  {% if showMember.getMemberTitle() is not empty %}
  {{ showMember.getMemberTitle() }}
  {% endif %}
  {% if member.get('member_id') and 0 %}
    <div class="menu" style="margin-top: 5px;">
      <ul>
        <li><a href="/messenger/compose/{{ showMember.get('member_id') }}">Send a Message</a></li>
      </ul>
    </div>
  {% endif %}
  <div class="clear"></div>
</div>
<div class="left thirty">
  <div class="verticalTabs">
    <ul>
      {% for link,label in panes %}
        <li><a href="{{ showMember.getUrl() }}/{{ link }}"{% if activePane == link %} class="active"{% endif %}>{{ label }}</a></li>
      {% endfor %}
    </ul>
  </div>
  <div class="grayWrap">
    Member since:<br />
    {{ showMember.getDate('reg_date').getShortTime()|capitalize }}
  </div>
</div>
<a name="tabs"></a>
<div class="right seventy">
  {{ paneTpl|raw }}
</div>
<div class="clear"></div>