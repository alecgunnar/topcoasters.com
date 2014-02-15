<div class="sixty center">
  <h1>Sign In</h1>
  <div class="grayWrap box connect">
    <div class="right">
      <a href="/connect" class="facebookLogin">Login with Facebook</a>
    </div>
    Want to <em>speed</em> up this process?<br />
    Sign in with Facebook!
  </div>
  <div class="box">
    Enter your email address and password into the form below to sign in. If you do not have an account, you can <a href="/create-an-account">create an account</a>.
  </div>
  {% if loginError == true %}
    <div class="alert" style="margin-top: 10px;">
      Your email address or password was invalid!
    </div>
  {% endif %}
  {{ form|raw }}
</div>