<h2>Log in with Facebook</h2>
{% if accountConnected %}
  <div class="alert">
    Your Facebook account is already connected to another Top Coasters account. You must disconnect that account before you can connect this one.
  </div>
{% endif %}
{% if connected %}
<div class="grayWrap">
  You have granted Top Coasters permission to access your public profile information and email address from your Facebook account.
</div>
<div class="grayWrap">
  <h3 style="color: #A00;">Disabling "Log in with Facebook"</h3>
  If you no longer wish to allow Top Coasters to access your public profile information and email address from your Facebook account, click the link below to disable the "Log in with Facebook" feature. This will cause all permissions you have granted Top Coasters to be revoked.<br />
  <br />
  <div class="textCenter">
    <a href="/account/log-in-with-facebook/disable" class="redLink">Disable Log in with Facebook</a>
  </div>
</div>
{% else %}
<div class="grayWrap">
  <div class="right">
    <a href="/account/log-in-with-facebook" class="facebookLogin"></a>
  </div>
  <div class="left fourty">
  Use "Log in with Facebook" to sign into Top Coasters quicker, and easier. All it takes is one click!
  </div>
  <div class="clear"></div>
</div>
{% endif %}