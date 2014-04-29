<div class="box">
  <h2>Amount of Data Stored</h2>
  Cached Content: {{ cached_content }} Mb<br />
  Database Size: {{ database_size }} Mb

  <div class="dataSizeBar">
    <div class="blue" style="width: {{ cached_percent }}%;">Cached ({{ cached_percent }}%)</div>
    <div class="green right" style="width: {{ database_percent }}%;">Database ({{ database_percent }}%)</div>
  </div>
</div>