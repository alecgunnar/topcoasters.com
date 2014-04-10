{% import 'Macros/Navigation.tpl' as nav %}

<div id="header">
  <div id="navigation">
    {{ nav.menu(mainNavigationLinks) }}
  </div>
  <a href="/" id="logo">Top Coasters</a>
</div>
<div id="main">
  <div id="sidebar">
    {{ nav.menu(sideNavigationLinks) }}
  </div>
  <div id="content">
    <h1 id="pageTitle">{{ pageTitle }}</h1>
    {{ content|raw }}
  </div>
  <div class="clear"></div>
</div>