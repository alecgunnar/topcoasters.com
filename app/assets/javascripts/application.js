//= require jquery
//= require jquery_ujs
//= require turbolinks
//= require_tree .

'use strict';

var mobileNavTogglers   = '.header__main-nav-drop, .header__member-nav-drop',
    mobileNavOpen       = false,
    whichMobileNav      = null,
    mobileNavContainers = {
      main:   '.nav__main',
      member: '.header__member-nav'
    };

var whenReady = function() {
  /*
   * Show the notice flash
   */
  var $flashContainer = $('#flash__message__container');

  if(typeof $flashContainer !== 'undefined')
    $flashContainer.fadeIn(function() {
      setTimeout(function() {
        $flashContainer.fadeOut();
      }, 5000);
    });

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
  var $memberPopDown   = $('.member-nav__member__pop-down'),
      clickedMemberNav = false;

  $('.member-nav__member').on('click tap', function(e) {
    clickedMemberNav = true;
    $memberPopDown.toggle();
  });

  /*
   * Document events
   */
  $(document).on('click tap', function() {
    if(!clickedMemberNav)
      $memberPopDown.hide();

    clickedMemberNav = false
  });
};

$(document).ready(whenReady);
$(document).on('page:load', whenReady);