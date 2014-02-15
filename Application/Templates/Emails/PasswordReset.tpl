{% include 'Emails/Header.tpl' %}

You are receiving this email because a password reset request was made on <a href="http://www.topcoasters.com/">Top Coasters</a> for an account using this email address. If you did not request this a password reset, please ignore this email. Otherwise, please click the link below to reset your password:<br />
<br />
<a href="{{ url }}forgot-password/reset?token={{ code }}">Reset my Password</a>

{% include 'Emails/Footer.tpl' %}