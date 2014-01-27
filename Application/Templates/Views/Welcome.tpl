<h1>Welcome to Top Coasters, {{ member.get('username') }}!</h1>
Before you can begin fully using Top Coasters, you must activate your account. An email will have been sent to your email address: <strong>{{ member.get('email') }}</strong>, which you provided during registration, with instruction to activate your account. This email should arrive within 10 minutes, usually it will show up instantly. If you do not receive this email, click the link below to have a new activation email sent.

<div class="textCenter" style="margin: 50px">
  <a href="/account/activation-email" class="button">Send a New Activation Email</a>
</div>