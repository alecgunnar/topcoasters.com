{% include 'Emails/Header.tpl' %}

You are receiving this email because a password change request was made on Top Coasters for an account with the username: {{ username }}. The request was to have the account's email address switched to this email address. If you would like to proceed, please click the link below.<br />
<br />
<a href="http://www.topcoasters.com/account/activate?token={{ code }}">Activate my new Email Address</a>

{% include 'Emails/Footer.tpl' %}