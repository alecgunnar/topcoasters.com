<div id="redirectWrapper">
  <div id="redirectMessage">{{ redirect_msg }}</div>
</div>
<div id="memberBar">
  <div class="wrapper">
    {% if member.get('member_id') is not empty %}
    <div class="options">
      <div class="right">
        <ul>
          {% if member.get('activation_key') == 'reg' %}
          <li><strong><a href="/account/activation-email">Resend Activation Email</a></strong></li>
          <li><a href="http://local.topcoasters.dev/account/change-email">Change Email Address</a></li>
          {% else %}
          {% endif %}
          <li><a href="/account">Account Settings</a></li>
          {% if member.get('is_admin') %}
          <li><a href="/admin">Admin CP</a></li>
          {% endif %}
          <li><a href="/sign-out">Sign Out</a></li>
        </ul>
      </div>
      <ul>
        <li><a href="{{ member.getUrl() }}"><div class="profilePicture"><img src='{{ member.getProfilePicture() }}' /></div>{{ member.getName() }}</a></li>
      </ul>
    </div>
    {% else %}
    <div class="options">
      <div class="right">
        <ul>
          <li><a href="/sign-in" id="signIn">Sign In</a></li>
          <li><a href="/create-an-account">Create an Account</a></li>
        </ul>
      </div>
      &nbsp;
    </div>
    {% endif %}
  </div>
</div>
<div id="header">
  <div class="wrapper">
    <div id="search">
      {{ searchForm|raw }}
    </div>
    <div id="mainNavigation">
      <ul>
        {% for label, url in navigationLinks %}
        <li><a href="{{ url.0 }}"{% if url.1 %} class="active"{% endif %}>{{ label }}</a></li>
        {% endfor %}
      </ul>
    </div>
    <a href="/" id="logo">Top Coasters</a>
  </div>
</div>
<div class="wrapper">
  <div id="content">
    {{ content|raw }}
  </div>
  <div id="footer">
    <div class="menu">
      <ul>
        <li><a href="/terms-of-use">Terms of Use & Privacy Policy</a></li>
        <li><a href="/contact">Contact</a></li>
        <li><a href="/about">About</a></li>
      </ul>
    </div>
    Top Coasters &copy; 2009 - {{ "now"|date('Y') }} <a href="http://www.aleccarpenter.me/" target="_blank">Alec Carpenter</a>
  </div>
</div>
<div id="scrollToTop" title="Jump to Top">Top</div>