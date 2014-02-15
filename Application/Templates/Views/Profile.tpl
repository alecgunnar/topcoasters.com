<div class="left thirty">
<div class="verticalTabs">
  <div class="textCenter" style="margin-bottom: 20px;">
    <div class="profilePicture large">
      <img src='{{ showMember.getProfilePicture() }}' />
    </div>
    <h2>{{ showMember.getName() }}</h2>
    {% if showMember.getMemberTitle() is not empty %}{{ showMember.getMemberTitle() }} &middot; {% endif %}{% if activeSession %}<span style="color:green;">Online</span>{% else %}<span style="color:red;">Offline</span>{% endif %}<br />
    {% if member.get('member_id') and 0 %}
      <div class="menu" style="margin-top: 5px;">
        <ul>
          <li><a href="/messenger/compose/{{ showMember.get('member_id') }}">Send a Message</a></li>
        </ul>
      </div>
    {% endif %}
    </div>
    <ul>
      {% for link,label in panes %}
        <li><a href="{{ showMember.getUrl() }}/{{ link }}"{% if activePane == link %} class="active"{% endif %}>{{ label }}</a></li>
      {% endfor %}
    </ul>
  </div>
</div>
<a name="tabs"></a>
<div class="right seventy">
  {{ paneTpl|raw }}
</div>
<div class="clear"></div>