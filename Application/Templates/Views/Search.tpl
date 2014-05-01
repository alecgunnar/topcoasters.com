<h1>Search Top Coasters</h1>

<h2>Search results for: "{{ search_query }}" ({{ total_results }})</h2>
{% for result in results %}
<div div class="result">
  <a href="{{ result.link }}">{{ result.htmlTitle|raw }}</a>
  <div class="description">{{ result.htmlSnippet|raw }}</div>
</div>
{% else %}
<div class="alert">Your search has not returned any results.</div>
{% endfor %}