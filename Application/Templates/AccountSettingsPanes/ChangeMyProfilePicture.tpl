<h2>Profile Picture Options</h2>
{% if picUnavailable is not empty %}
<div class="alert">
  That picture is not available
</div>
{% endif %}
<div class="grayWrap box">
  <div class="profilePictureViewer">
    {% if gravatar_url is not empty %}
      <img src="{{ gravatar_url }}" />
    {% else %}
      Not Available
    {% endif %}
  </div>
  <h3>Gravatar{% if gravatar_url is not empty and member.get('profile_picture_type') != 'gravatar' %} (<a href="/account/profile-picture/switch-to/gravatar">Use this Picture</a>){% endif %}</h3>
  Use your Gravatar as your profile picture. The email address you used on Gravatar, must match the email address for your account on Top Coasters. (<a href="http://www.gravatar.com" target="_blank">Learn about Gravatar</a>)
  <div class="clear"></div>
</div>
<div class="grayWrap box">
  <div class="profilePictureViewer">
    {% if facebook_url is not empty %}
      <img src="{{ facebook_url }}" />
    {% else %}
      Not Available
    {% endif %}
  </div>
  <h3>Facebook{% if facebook_url is not empty and member.get('profile_picture_type') != 'facebook' %} (<a href="/account/profile-picture/switch-to/facebook">Use this Picture</a>){% endif %}</h3>
  Use your Facebook profile picture as your profile picture. This requires that you have signed into Top Coasters with Facebook.
  <div class="clear"></div>
</div>
<div class="grayWrap box">
  <div class="profilePictureViewer">
    {% if topcoasters_url is not empty %}
      <img src="{{ topcoasters_url }}" />
    {% else %}
      <img src="/assets/images/default_profile_picture.png" />
    {% endif %}
  </div>
  <h3>Upload a Profile Picture{% if topcoasters_url is not empty and member.get('profile_picture_type') != 'uploaded' %} (<a href="/account/profile-picture/switch-to/uploaded">Use this Picture</a>){% endif %}</h3>
  Choose an image file from your computer to use as your profile picture. It cannot exceed 200 pixels in width or height, and must be either a: png, gif, or a jpg (jpeg).
  <div class="clear"></div>
  {{ upload_form|raw }}
  <div class="clear"></div>
</div>
{% if member.get('profile_picture_type') is not empty %}
<div class="grayWrap textCenter">
  <a href="/account/profile-picture/remove" class="redLink">Remove my Profile Picture</a>
</div>
{% endif %}