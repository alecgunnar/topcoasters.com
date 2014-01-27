<div class="textCenter">
  {% if fields.profilePicture.getError() is not empty %}
  <div class="box fieldError textCenter">
    {{ fields.profilePicture.getError() }}
  </div>
  {% endif %}
  <strong>{{ fields.profilePicture.getLabel() }}:</strong> {{ fields.profilePicture.render()|raw }}
</div>
<div class="submitRow">
  {{ fields.saveFile.render()|raw }}
</div>