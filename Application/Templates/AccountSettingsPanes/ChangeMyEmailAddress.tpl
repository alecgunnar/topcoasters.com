<h2>Change my Email Address</h2>
<div class="grayWrap">
  Enter a new email address into the form below. Once you have submitted the form, an email will be sent to the new email address with instructions to activate it.<br />
  <br />
  Current Email Address: <strong>{{ member.get('email_address') }}</strong>
  {% if newEmailAddress is not empty %}
  <br /><br />
  Changing to: <strong>{{ newEmailAddress }}</strong> (<a href="/account/change-email/resend">Resend Activation Email</a> or <a href="/account/change-email/cancel" class="redLink">Cancel</a>)
  {% endif %}
</div>
{{ form|raw }}