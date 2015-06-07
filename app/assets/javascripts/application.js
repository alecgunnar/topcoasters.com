//= require jquery
//= require jquery_ujs
//= require turbolinks
//= require_tree .

var mobileNavTogglers   = '.header__main-nav-drop, .header__member-nav-drop',
		mobileNavOpen       = false,
		whichMobileNav      = null,
		mobileNavContainers = {
			main:   '.nav__main',
			member: '.header__member-nav'
		};

$(document).ready(function() {
	$(mobileNavTogglers).on('click', function() {
		var $this    = $(this),
				navClass = mobileNavContainers[$this.attr('data-nav')];

		if(mobileNavOpen) {
			if(navClass != whichMobileNav)
				return;

			$(navClass).slideUp(function() {
				mobileNavOpen  = false;
				whichMobileNav = null;
			});
		} else {
			$(navClass).slideDown(function() {
				mobileNavOpen  = true;
				whichMobileNav = navClass;
			});
		}
	});
});