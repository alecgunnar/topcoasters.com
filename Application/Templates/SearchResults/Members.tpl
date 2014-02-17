{% for result in results %}
<div class="padding">
  {% if result.getProfilePicture() is not empty %}<div class="left profilePicture small" style="margin-right: 20px;">{{ result.getProfilePicture(true)|raw }}</div>{% endif %}{{ result.getLink()|raw }}{% if result.getMemberTitle() is not empty %} &middot; {{ result.getMemberTitle() }}{% endif %}
  <div class="clear"></div>
</div>
{% endfor %}