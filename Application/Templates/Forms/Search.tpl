<form name="search" action="/search{% if search_box_what is not empty %}/{{ search_box_what }}{% endif %}">
  <div class="clearField"></div><input type="text" placeholder="Search Top Coasters" name="q" value="{{ search_query }}" />
</form>