// ----------------------------------------------------------------------------
// markItUp!
// ----------------------------------------------------------------------------
// Copyright (C) 2011 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// Html tags
// http://en.wikipedia.org/wiki/html
// ----------------------------------------------------------------------------
// Basic set. Feel free to add more tags
// ----------------------------------------------------------------------------
var markItUpSettings = {
	markupSet:  [ 	
		{name:'Bold', key:'', openWith:'[b]', closeWith:'[/b]' },
		{name:'Italic', key:'', openWith:'[i]', closeWith:'[/i]'  },
		{name:'Underline', key:'', openWith:'[u]', closeWith:'[/u]' },
		{name:'Strike Through', key:'', openWith:'[s]', closeWith:'[/s]' },
		{separator:'---------------' },
		{name:'Align Left', key:'', openWith:'[left]', closeWith:'[/left]' },
		{name:'Align Center', key:'', openWith:'[center]', closeWith:'[/center]' },
		{name:'Align Right', key:'', openWith:'[right]', closeWith:'[/right]' },
		{separator:'---------------' },
		{name:'Superscript', key:'', openWith:'[sup]', closeWith:'[/sup]' },
		{name:'Subscript', key:'', openWith:'[sub]', closeWith:'[/sub]' },
		{separator:'---------------' },
		{name:'Bulleted List', openWith:'  [*]', closeWith:'', multiline:true, openBlockWith:'[list]\n', closeBlockWith:'\n[/list]'},
		//{name:'Numeric List', openWith:'   [*]', closeWith:'', multiline:true, openBlockWith:'[list=num]\n', closeBlockWith:'\n[/list]'},
		{separator:'---------------' },
		{name:'Picture', key:'', replaceWith:'[img][![URL to Picture:!:http://]!][/img]' },
		{name:'YouTube Video', key:'', replaceWith:'[youtube][![Video ID:!:]!][/youtube]' },
		{name:'Link', key:'', openWith:'[url=[![Link:!:http://]!]]', closeWith:'[/url]', placeHolder:'Your text to link...' },
		//{name:'Quote', key:'Q', openWith:'[quote]', closeWith:'[/quote]' },
		//{name:'Spoiler', key:'', openWith:'[spoiler]', closeWith:'[/spoiler]' },
		//{separator:'---------------' },
		//{name:'Clean', className:"clean", replaceWith:function(h) { return h.selection.replace('/\[(.*?)\]/g', "") } },
		//{separator:'---------------' },
		//{name:'Preview', className:'preview',  call:'preview' },
		//{name:'Save Draft', className:'saveDraft',  call:'saveDraft' }
	]
};