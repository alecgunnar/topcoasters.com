<h1>Managing my Track Record</h1>
<div class="grayWrap">
  You track record is a list you can create of the roller coasters you have ridden. To add roller coasters to your track record, find it in the database, and then click the "Add to Track Record" link.
</div>
{% if favoriteCoasterExists %}
<div class="alert">
  You have already added that roller coaster to your track record.
</div>
{% endif %}

{% if favoriteParkExists %}
<div class="alert">
  You have already added that amusement park to your track record.
</div>
{% endif %}

{% if coasterCannotBeAdded %}
<div class="alert">
  You cannot add that roller coaster to your track record.
</div>
{% endif %}

{% import 'Macros/Tabs.tpl' as tabBuilder %}

{{ tabBuilder.build('trackRecord', 'My Track Record', tabs) }}

{% if edit_id is not empty %}
<script>
editRecordOnLoad({{ edit_id }});
</script>
{% endif %}