<!Doctype html>
<html lang="en">
  <head lang="en">
    <meta charset="utf-8">
    <title>{{ title|raw }}{{ title_suffix|raw }}</title>
    {% for file in cssFiles %}
    <link rel="stylesheet" href="{{ file }}?{{ build_id }}" />
    {% endfor %}
    {% for file in jsFiles %}
    <script src="{{ file }}?{{ build_id }}"></script>
    {% endfor %}
  </head>
  <body id="{{ page_id }}">
    <div id="fb-root"></div>
    {{ body|raw }}
  </body>
</html>