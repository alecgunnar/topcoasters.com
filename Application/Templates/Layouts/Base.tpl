<!Doctype html>
<html lang="en">
  <head lang="en">
    <meta charset="utf-8">
    <title>{{ title|raw }}</title>
    {% for file in cssFiles %}
    <link rel="stylesheet" href="{{ file }}?{{ build_id }}" />
    {% endfor %}
    {% for file in jsFiles %}
    <script src="{{ file }}?{{ build_id }}"></script>
    {% endfor %}
    <meta name="description" content="Top Coasters is a fun and exciting, roller coaster and amusement park enthusiast website. There's a forum, a track exchange and a database of coasters and parks.">
  </head>
  <body id="{{ page_id }}">
    <div id="fb-root"></div>
    {{ body|raw }}
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    
      ga('create', 'UA-28064918-1', 'topcoasters.com');
      ga('send', 'pageview');
    </script>
  </body>
</html>