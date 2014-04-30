<h1>Search Top Coasters</h1>

<h2>Search results for: "{{ search_query }}" ({{ total_results }})</h2>
{% for result in results %}

{% else %}
<div class="alert">Your search has not returned any results.</div>
{% endfor %}