{% macro field(label, field, error, desc) %}
<div class="field">
  <label>{{ label }}</label>{% if error %}<span class="error"> &middot; {{ error|raw }}</span>{% endif %}<br />
  {{ field|raw }}
  {% if desc is not empty %}
    <br />
    <div class="description">{{ desc }}</div>
  {% endif %}
</div>
{% endmacro %}

{% import _self as form %}

{{ form.field(fields.username.getLabel(), fields.username.render(), fields.username.getError(), fields.username.getDescription()) }}
{{ form.field(fields.email.getLabel(), fields.email.render(), fields.email.getError(), fields.email.getDescription()) }}

{% set passwordField = fields.password.render() ~ fields.password_confirm.render() %}

<div id="password">
{{ form.field(fields.password.getLabel(), passwordField, fields.password.getError(), fields.password.getDescription()) }}
</div>

<div id="terms">
  By completing and submitting this form, you acknowledge that you have read and agree to the <a href="/terms-of-use" target="_blank">Terms of Use and Privacy Policy</a>.
</div>

<div class="submitRow">
  {{ fields.submit.render()|raw }}
</div>