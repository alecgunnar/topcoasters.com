<h1>{{ pageTitle }}</h1>
{% if obj.getName() != 'Unknown' %}
<div class="box">
  in {{ obj.getName() }}
</div>
{% endif %}
{% if postingError %}
<div class="alert">
  There was an error posting your message, please try again.
</div>
{% endif %}
{{ form|raw }}