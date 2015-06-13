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

var whenReady = function() {
  /*
   * Mobile navigation switches
   */
  $(mobileNavTogglers).on('tap', function() {
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

  /*
   * Desktop/tablet member nav pop down setup
   */
  var $memberPopDown = $('.member-nav__member__pop-down');

  $('.member-nav__member').on('click tap', function(e) {
    e.stopPropagation();
    console.log('click');
    $memberPopDown.toggle();
  });

  /*
   * Document events
   */
  $(document).on('click tap', function() {
    $memberPopDown.hide();
  });
};

$(document).ready(whenReady);
$(document).on('page:load', whenReady);