{% if flexImages|length %}
<div class="flexslider">
  <ul class="slides">
  {% for img in flexImages %}
    <li style="background: url('{{ urlToImage }}{{ img.0 }}') no-repeat;">
    {% if img.1 %}
      <span>{{ img.1 }}</span>
    {% endif %}
    </li>
  {% endfor %}
  </ul>
</div>
<script type='text/javascript'>
$(window).load(function() {
    $('.flexslider').flexslider({
        pauseOnHover: true,
        animation: "slide",
        slideshowSpeed: 5000,
        controlNav: false,
        directionNav: false,
    });
});
</script>
{% endif %}