{% import 'Forms/Standard.tpl' as standard %}
{% import _self as this %}

{% macro renderTrackAndLayout(group) %}
{% import 'Forms/Standard.tpl' as standard %}

<div class="fieldGroup container">
  <div class="groupLabel">{{ group.label }}</div>
  {{ standard.renderFields(group.fields) }}
  <div class="description box">Please enter the launches and/or lifts in the order which they occur one the roller coaster. <strong>The speed is not required.</strong></div>
  <div class="box textCenter">
    <input type="button" name="addNewLift" id="addLiftButton" value="Add a Launch / Lift" />
  </div>
</div>
{% endmacro %}

<div class="standardForm container">
  {{ standard.renderGroup(fields.generalInfo) }}
  {{ standard.renderGroup(fields.locationInfo) }}
  {{ this.renderTrackAndLayout(fields.trackAndLayout) }}
  {{ standard.renderGroup(fields.statistics) }}
  {{ standard.renderGroup(fields.trainsAndCars) }}
  {{ standard.renderField(fields.description) }}
  {{ standard.renderField(fields.pov_videos) }}
  {{ standard.renderField(fields.submit) }}
</div>