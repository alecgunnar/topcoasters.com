<div class="field textCenter">
  {% if fields.profilePicture.getError() is not empty %}
  <div class="box textCenter">
    <div class="error">
      {{ fields.profilePicture.getError() }}
    </div>
  </div>
  {% endif %}
  <strong>{{ fields.profilePicture.getLabel() }}:</strong> {{ fields.profilePicture.render()|raw }}
</div>
<div class="submitRow">
  {{ fields.saveFile.render()|raw }}
</div>