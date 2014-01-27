<h2>Account Overview</h2>
<div class="grayWrap">
  <div class="profilePicture">
    {{ member.getProfilePicture(true)|raw }}
  </div>
  <div class="username">{{ member.getName() }}</div>
  Member Since: {{ memberSince }}<br />
  <a href="/account/profile-picture">Change Profile Picture</a>
  <div class="clear"></div>
</div>
<div class="grayWrap textCenter">
  <a href="/track-record">Update my Track Record</a>
</div>