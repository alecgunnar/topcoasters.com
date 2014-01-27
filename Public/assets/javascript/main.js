$(document).ready(function() {
    var parent     = $('#addLiftButton').parent().parent(),
        liftsGroup = parent.children('.fieldGroup'),
        lift       = liftsGroup.children('.field:first'),
        numLifts   = liftsGroup.children('.field').length + 1,
        removeLink = $('<div class="right"><a href="#" class="redLink">Remove</a></div>');

    liftsGroup.children('.field').each(function(index) {
        if(!index) {
            return;
        }

        var $this      = $(this),
            clonedLink = removeLink.clone();

        clonedLink.on('click', function(e) {
            e.preventDefault();

            removeLiftRow($this);
        });

        clonedLink.prependTo($this);
    });

    function removeLiftRow(row) {
        var confirmRemove = confirm('Are you sure you would like to remove this launch/lift?');

        if(confirmRemove) {
            row.slideUp(function() {
                $(this).remove();
            });
        }
    }

    $('#database-contribute-rollercoaster').find('#addLiftButton').on('click', function() {
        var $this    = $(this),
            newLift  = lift.clone();

        newLift.children('select:first').attr('name', 'rollerCoaster[trackAndLayout][transports][transport_type_' + numLifts + ']').val(0);
        newLift.children('input[type="number"]').attr('name', 'rollerCoaster[trackAndLayout][transports][transport_speed_' + numLifts + ']').val('');
        newLift.children('select:last').attr('name', 'rollerCoaster[trackAndLayout][transports][transport_reverse_' + numLifts + ']');

        var clonedLink = removeLink.clone();
        
        clonedLink.children('a').on('click', function(e) {
            e.preventDefault();

            removeLiftRow(newLift);
        });

        clonedLink.prependTo(newLift);

        numLifts++;

        newLift.appendTo(liftsGroup);
    });
});$.standardAjax = function(data, done, always) {
    data.type        = 'POST';
    data.dataType    = 'json';

    if(typeof data.data != 'undefined') {
        data.data.ajaxRequest = true;
    } else {
        data.data = {ajaxRequest: true};
    }

    return $.ajax(data).done(function(data, textStatus, jqXHR) {
        var resp = {status: 'ok'};

        if(typeof jqXHR.responseText != 'undefined') {
            resp = JSON.parse(jqXHR.responseText);
        }

        if(resp.status == 'ok') {
            done(data, textStatus, jqXHR);
        } else if(resp.status == 'signin') {
            alert('You must sign in to continue!');

            window.location = '/sign-in';
        } else {
            alert('Something went wrong, please try again! (' + resp.status + ')');
        }
    }).fail(function(data, textStatus, jqXHR) {
        alert('Something went wrong, please try again!');
    }).always(always);
};function lookForMiniMaxes() {
    $('#database-amusementparks').find('a.minimax').each(function() {
        var $this  = $(this),
            $group = $this.parent().parent();

        $group.children('.locations').children('.field').children('label').children('input[type="checkbox"]').each(function() {
            $(this).on('click', function() {
                $(this).parent().parent().toggleClass('noHide').toggleClass('dontHide');
            });
        });

        $this.parent().on('click', function() {
            if($this.text() == 'Show') {
                $this.text('Hide');
                $group.children('.locations').children('.field').show();
            } else {
                $this.text('Show');
                $group.children('.locations').children('.field').not('.noHide').hide();
            }
        }).css('cursor', 'pointer');
    });
}/*
 * jQuery FlexSlider v2.0
 * http://www.woothemes.com/flexslider/
 *
 * Copyright 2012 WooThemes
 * Free to use under the GPLv2 license.
 * http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Contributing author: Tyler Smith (@mbmufffin)
 */

;(function ($) {

  //FlexSlider: Object Instance
  $.flexslider = function(el, options) {
    var slider = $(el),
        vars = $.extend({}, $.flexslider.defaults, options),
        namespace = vars.namespace,
        touch = ("ontouchstart" in window) || window.DocumentTouch && document instanceof DocumentTouch,
        eventType = (touch) ? "touchend" : "click",
        vertical = vars.direction === "vertical",
        reverse = vars.reverse,
        carousel = (vars.itemWidth > 0),
        fade = vars.animation === "fade",
        asNav = vars.asNavFor !== "",
        methods = {};
    
    // Store a reference to the slider object
    $.data(el, "flexslider", slider);
    
    // Privat slider methods
    methods = {
      init: function() {
        slider.animating = false;
        slider.currentSlide = vars.startAt;
        slider.animatingTo = slider.currentSlide;
        slider.atEnd = (slider.currentSlide === 0 || slider.currentSlide === slider.last);
        slider.containerSelector = vars.selector.substr(0,vars.selector.search(' '));
        slider.slides = $(vars.selector, slider);
        slider.container = $(slider.containerSelector, slider);
        slider.count = slider.slides.length;
        // SYNC:
        slider.syncExists = $(vars.sync).length > 0;
        // SLIDE:
        if (vars.animation === "slide") vars.animation = "swing";
        slider.prop = (vertical) ? "top" : "marginLeft";
        slider.args = {};
        // SLIDESHOW:
        slider.manualPause = false;
        // TOUCH/USECSS:
        slider.transitions = !vars.video && !fade && vars.useCSS && (function() {
          var obj = document.createElement('div'),
              props = ['perspectiveProperty', 'WebkitPerspective', 'MozPerspective', 'OPerspective', 'msPerspective'];
          for (var i in props) {
            if ( obj.style[ props[i] ] !== undefined ) {
              slider.pfx = props[i].replace('Perspective','').toLowerCase();
              slider.prop = "-" + slider.pfx + "-transform";
              return true;
            }
          }
          return false;
        }());
        // CONTROLSCONTAINER:
        if (vars.controlsContainer !== "") slider.controlsContainer = $(vars.controlsContainer).length > 0 && $(vars.controlsContainer);
        // MANUAL:
        if (vars.manualControls !== "") slider.manualControls = $(vars.manualControls).length > 0 && $(vars.manualControls);
        
        // RANDOMIZE:
        if (vars.randomize) {
          slider.slides.sort(function() { return (Math.round(Math.random())-0.5); });
          slider.container.empty().append(slider.slides);
        }
        
        slider.doMath();
        
        // ASNAV:
        if (asNav) methods.asNav.setup();
        
        // INIT
        slider.setup("init");
        
        // CONTROLNAV:
        if (vars.controlNav) methods.controlNav.setup();
        
        // DIRECTIONNAV:
        if (vars.directionNav) methods.directionNav.setup();
        
        // KEYBOARD:
        if (vars.keyboard && ($(slider.containerSelector).length === 1 || vars.multipleKeyboard)) {
          $(document).bind('keyup', function(event) {
            var keycode = event.keyCode;
            if (!slider.animating && (keycode === 39 || keycode === 37)) {
              var target = (keycode === 39) ? slider.getTarget('next') :
                           (keycode === 37) ? slider.getTarget('prev') : false;
              slider.flexAnimate(target, vars.pauseOnAction);
            }
          });
        }
        // MOUSEWHEEL:
        if (vars.mousewheel) {
          slider.bind('mousewheel', function(event, delta, deltaX, deltaY) {
            event.preventDefault();
            var target = (delta < 0) ? slider.getTarget('next') : slider.getTarget('prev');
            slider.flexAnimate(target, vars.pauseOnAction);
          });
        }
        
        // PAUSEPLAY
        if (vars.pausePlay) methods.pausePlay.setup();
        
        // SLIDSESHOW
        if (vars.slideshow) {
          if (vars.pauseOnHover) {
            slider.hover(function() {
              slider.pause();
            }, function() {
              if (!slider.manualPause) slider.play();
            });
          }
          // initialize animation
          (vars.initDelay > 0) ? setTimeout(slider.play, vars.initDelay) : slider.play();
        }
        
        // TOUCH
        if (touch && vars.touch) methods.touch();
        
        // FADE&&SMOOTHHEIGHT || SLIDE:
        if (!fade || (fade && vars.smoothHeight)) $(window).bind("resize focus", methods.resize);
        
        
        // API: start() Callback
        setTimeout(function(){
          vars.start(slider);
        }, 200);
      },
      asNav: {
        setup: function() {
          slider.asNav = true;
          slider.animatingTo = Math.floor(slider.currentSlide/slider.move);
          slider.currentItem = slider.currentSlide;
          slider.slides.removeClass(namespace + "active-slide").eq(slider.currentItem).addClass(namespace + "active-slide");
          slider.slides.click(function(e){
            e.preventDefault();
            var $slide = $(this),
                target = $slide.index();
            if (!$(vars.asNavFor).data('flexslider').animating && !$slide.hasClass('active')) {
              slider.direction = (slider.currentItem < target) ? "next" : "prev";
              slider.flexAnimate(target, vars.pauseOnAction, false, true, true);
            }
          });
        }
      },
      controlNav: {
        setup: function() {
          if (!slider.manualControls) {
            methods.controlNav.setupPaging();
          } else { // MANUALCONTROLS:
            methods.controlNav.setupManual();
          }
        },
        setupPaging: function() {
          var type = (vars.controlNav === "thumbnails") ? 'control-thumbs' : 'control-paging',
              j = 1,
              item;
          
          slider.controlNavScaffold = $('<ol class="'+ namespace + 'control-nav ' + namespace + type + '"></ol>');
          
          if (slider.pagingCount > 1) {
            for (var i = 0; i < slider.pagingCount; i++) {
              item = (vars.controlNav === "thumbnails") ? '<img src="' + slider.slides.eq(i).attr("data-thumb") + '"/>' : '<a>' + j + '</a>';
              slider.controlNavScaffold.append('<li>' + item + '</li>');
              j++;
            }
          }
          
          // CONTROLSCONTAINER:
          (slider.controlsContainer) ? $(slider.controlsContainer).append(slider.controlNavScaffold) : slider.append(slider.controlNavScaffold);
          methods.controlNav.set();
          
          methods.controlNav.active();
        
          slider.controlNavScaffold.delegate('a, img', eventType, function(event) {
            event.preventDefault();
            var $this = $(this),
                target = slider.controlNav.index($this);

            if (!$this.hasClass(namespace + 'active')) {
              slider.direction = (target > slider.currentSlide) ? "next" : "prev";
              slider.flexAnimate(target, vars.pauseOnAction);
            }
          });
          // Prevent iOS click event bug
          if (touch) {
            slider.controlNavScaffold.delegate('a', "click touchstart", function(event) {
              event.preventDefault();
            });
          }
        },
        setupManual: function() {
          slider.controlNav = slider.manualControls;
          methods.controlNav.active();
          
          slider.controlNav.live(eventType, function(event) {
            event.preventDefault();
            var $this = $(this),
                target = slider.controlNav.index($this);
                
            if (!$this.hasClass(namespace + 'active')) {
              (target > slider.currentSlide) ? slider.direction = "next" : slider.direction = "prev";
              slider.flexAnimate(target, vars.pauseOnAction);
            }
          });
          // Prevent iOS click event bug
          if (touch) {
            slider.controlNav.live("click touchstart", function(event) {
              event.preventDefault();
            });
          }
        },
        set: function() {
          var selector = (vars.controlNav === "thumbnails") ? 'img' : 'a';
          slider.controlNav = $('.' + namespace + 'control-nav li ' + selector, (slider.controlsContainer) ? slider.controlsContainer : slider);
        },
        active: function() {
          slider.controlNav.removeClass(namespace + "active").eq(slider.animatingTo).addClass(namespace + "active");
        },
        update: function(action, pos) {
          if (slider.pagingCount > 1 && action === "add") {
            slider.controlNavScaffold.append($('<li><a>' + slider.count + '</a></li>'));
          } else if (slider.pagingCount === 1) {
            slider.controlNavScaffold.find('li').remove();
          } else {
            slider.controlNav.eq(pos).closest('li').remove();
          }
          methods.controlNav.set();
          (slider.pagingCount > 1 && slider.pagingCount !== slider.controlNav.length) ? slider.update(pos, action) : methods.controlNav.active();
        }
      },
      directionNav: {
        setup: function() {
          var directionNavScaffold = $('<ul class="' + namespace + 'direction-nav"><li><a class="' + namespace + 'prev" href="#">' + vars.prevText + '</a></li><li><a class="' + namespace + 'next" href="#">' + vars.nextText + '</a></li></ul>');
        
          // CONTROLSCONTAINER:
          if (slider.controlsContainer) {
            $(slider.controlsContainer).append(directionNavScaffold);
            slider.directionNav = $('.' + namespace + 'direction-nav li a', slider.controlsContainer);
          } else {
            slider.append(directionNavScaffold);
            slider.directionNav = $('.' + namespace + 'direction-nav li a', slider);
          }
        
          methods.directionNav.update();
        
          slider.directionNav.bind(eventType, function(event) {
            event.preventDefault();
            var target = ($(this).hasClass(namespace + 'next')) ? slider.getTarget('next') : slider.getTarget('prev');
            slider.flexAnimate(target, vars.pauseOnAction);
          });
          // Prevent iOS click event bug
          if (touch) {
            slider.directionNav.bind("click touchstart", function(event) {
              event.preventDefault();
            });
          }
        },
        update: function() {
          var disabledClass = namespace + 'disabled';
          if (!vars.animationLoop) {
            if (slider.pagingCount === 1) {
             slider.directionNav.addClass(disabledClass);
            } else if (slider.animatingTo === 0) {
              slider.directionNav.removeClass(disabledClass).filter('.' + namespace + "prev").addClass(disabledClass);
            } else if (slider.animatingTo === slider.last) {
              slider.directionNav.removeClass(disabledClass).filter('.' + namespace + "next").addClass(disabledClass);
            } else {
              slider.directionNav.removeClass(disabledClass);
            }
          }
        }
      },
      pausePlay: {
        setup: function() {
          var pausePlayScaffold = $('<div class="' + namespace + 'pauseplay"><a></a></div>');
        
          // CONTROLSCONTAINER:
          if (slider.controlsContainer) {
            slider.controlsContainer.append(pausePlayScaffold);
            slider.pausePlay = $('.' + namespace + 'pauseplay a', slider.controlsContainer);
          } else {
            slider.append(pausePlayScaffold);
            slider.pausePlay = $('.' + namespace + 'pauseplay a', slider);
          }
        
          // slider.pausePlay.addClass(pausePlayState).text((pausePlayState == 'pause') ? vars.pauseText : vars.playText);
          methods.pausePlay.update((vars.slideshow) ? namespace + 'pause' : namespace + 'play');
        
          slider.pausePlay.bind(eventType, function(event) {
            event.preventDefault();
            if ($(this).hasClass(namespace + 'pause')) {
              slider.pause();
              slider.manualPause = true;
            } else {
              slider.play();
              slider.manualPause = false;
            }
          });
          // Prevent iOS click event bug
          if (touch) {
            slider.pausePlay.bind("click touchstart", function(event) {
              event.preventDefault();
            });
          }
        },
        update: function(state) {
          (state === "play") ? slider.pausePlay.removeClass(namespace + 'pause').addClass(namespace + 'play').text(vars.playText) : slider.pausePlay.removeClass(namespace + 'play').addClass(namespace + 'pause').text(vars.pauseText);
        }
      },
      touch: function() {
        var startX,
          startY,
          offset,
          cwidth,
          dx,
          startT,
          scrolling = false;
              
        el.addEventListener('touchstart', onTouchStart, false);
        function onTouchStart(e) {
          if (slider.animating) {
            e.preventDefault();
          } else if (e.touches.length === 1) {
            slider.pause();
            // CAROUSEL: 
            cwidth = (vertical) ? slider.h : slider. w;
            startT = Number(new Date());
            // CAROUSEL:
            offset = (carousel && reverse && slider.animatingTo === slider.last) ? 0 :
                     (carousel && reverse) ? slider.limit - (((slider.itemW + vars.itemMargin) * slider.move) * slider.animatingTo) :
                     (carousel && slider.currentSlide === slider.last) ? slider.limit :
                     (carousel) ? ((slider.itemW + vars.itemMargin) * slider.move) * slider.currentSlide : 
                     (reverse) ? (slider.last - slider.currentSlide + slider.cloneOffset) * cwidth : (slider.currentSlide + slider.cloneOffset) * cwidth;
            startX = (vertical) ? e.touches[0].pageY : e.touches[0].pageX;
            startY = (vertical) ? e.touches[0].pageX : e.touches[0].pageY;

            el.addEventListener('touchmove', onTouchMove, false);
            el.addEventListener('touchend', onTouchEnd, false);
          }
        }

        function onTouchMove(e) {
          dx = (vertical) ? startX - e.touches[0].pageY : startX - e.touches[0].pageX;
          scrolling = (vertical) ? (Math.abs(dx) < Math.abs(e.touches[0].pageX - startY)) : (Math.abs(dx) < Math.abs(e.touches[0].pageY - startY));
          
          if (!scrolling || Number(new Date()) - startT > 500) {
            e.preventDefault();
            if (!fade && slider.transitions) {
              if (!vars.animationLoop) {
                dx = dx/((slider.currentSlide === 0 && dx < 0 || slider.currentSlide === slider.last && dx > 0) ? (Math.abs(dx)/cwidth+2) : 1);
              }
              slider.setProps(offset + dx, "setTouch");
            }
          }
        }
        
        function onTouchEnd(e) {
          if (slider.animatingTo === slider.currentSlide && !scrolling && !(dx === null)) {
            var updateDx = (reverse) ? -dx : dx,
                target = (updateDx > 0) ? slider.getTarget('next') : slider.getTarget('prev');
            
            if (slider.canAdvance(target) && (Number(new Date()) - startT < 550 && Math.abs(updateDx) > 20 || Math.abs(updateDx) > cwidth/2)) {
              slider.flexAnimate(target, vars.pauseOnAction);
            } else {
              slider.flexAnimate(slider.currentSlide, vars.pauseOnAction, true);
            }
          }
          // finish the touch by undoing the touch session
          el.removeEventListener('touchmove', onTouchMove, false);
          el.removeEventListener('touchend', onTouchEnd, false);
          startX = null;
          startY = null;
          dx = null;
          offset = null;
        }
      },
      resize: function() {
        if (!slider.animating && slider.is(':visible')) {
          if (!carousel) slider.doMath();
          
          if (fade) {
            // SMOOTH HEIGHT:
            methods.smoothHeight();
          } else if (carousel) { //CAROUSEL:
            slider.slides.width(slider.computedW);
            slider.update(slider.pagingCount);
            slider.setProps();
          }
          else if (vertical) { //VERTICAL:
            slider.viewport.height(slider.h);
            slider.setProps(slider.h, "setTotal");
          } else {
            // SMOOTH HEIGHT:
            if (vars.smoothHeight) methods.smoothHeight();
            slider.newSlides.width(slider.computedW);
            slider.setProps(slider.computedW, "setTotal");
          }
        }
      },
      smoothHeight: function(dur) {
        if (!vertical || fade) {
          var $obj = (fade) ? slider : slider.viewport;
          (dur) ? $obj.animate({"height": slider.slides.eq(slider.animatingTo).height()}, dur) : $obj.height(slider.slides.eq(slider.animatingTo).height());
        }
      },
      sync: function(action) {
        var $obj = $(vars.sync).data("flexslider"),
            target = slider.animatingTo;
        
        switch (action) {
          case "animate": $obj.flexAnimate(target, vars.pauseOnAction, false, true); break;
          case "play": if (!$obj.playing && !$obj.asNav) { $obj.play(); } break;
          case "pause": $obj.pause(); break;
        }
      }
    }
    
    // public methods
    slider.flexAnimate = function(target, pause, override, withSync, fromNav) {
      if (!slider.animating && (slider.canAdvance(target) || override) && slider.is(":visible")) {
        if (asNav && withSync) {
          var master = $(vars.asNavFor).data('flexslider');
          slider.atEnd = target === 0 || target === slider.count - 1;
          master.flexAnimate(target, true, false, true, fromNav);
          slider.direction = (slider.currentItem < target) ? "next" : "prev";
          master.direction = slider.direction;
          
          if (Math.ceil((target + 1)/slider.visible) - 1 !== slider.currentSlide && target !== 0) {
            slider.currentItem = target;
            slider.slides.removeClass(namespace + "active-slide").eq(target).addClass(namespace + "active-slide");
            target = Math.floor(target/slider.visible);
          } else {
            slider.currentItem = target;
            slider.slides.removeClass(namespace + "active-slide").eq(target).addClass(namespace + "active-slide");
            return false;
          }
        }
        
        slider.animating = true;
        slider.animatingTo = target;
        // API: before() animation Callback
        vars.before(slider);
        
        // SLIDESHOW:
        if (pause) slider.pause();
        
        // SYNC:
        if (slider.syncExists && !fromNav) methods.sync("animate");
        
        // CONTROLNAV
        if (vars.controlNav) methods.controlNav.active();
        
        // !CAROUSEL:
        // CANDIDATE: slide active class (for add/remove slide)
        if (!carousel) slider.slides.removeClass(namespace + 'active-slide').eq(target).addClass(namespace + 'active-slide');
        
        // INFINITE LOOP:
        // CANDIDATE: atEnd
        slider.atEnd = target === 0 || target === slider.last;
        
        // DIRECTIONNAV:
        if (vars.directionNav) methods.directionNav.update();
        
        if (target === slider.last) {
          // API: end() of cycle Callback
          vars.end(slider);
          // SLIDESHOW && !INFINITE LOOP:
          if (!vars.animationLoop) slider.pause();
        }
        
        // SLIDE:
        if (!fade) {
          var dimension = (vertical) ? slider.slides.filter(':first').height() : slider.computedW,
              margin, slideString, calcNext;
          
          // INFINITE LOOP / REVERSE:
          if (carousel) {
            margin = (vars.itemWidth > slider.w) ? vars.itemMargin * 2 : vars.itemMargin;
            calcNext = ((slider.itemW + margin) * slider.move) * slider.animatingTo;
            slideString = (calcNext > slider.limit && slider.visible !== 1) ? slider.limit : calcNext;
          } else if (slider.currentSlide === 0 && target === slider.count - 1 && vars.animationLoop && slider.direction !== "next") {
            slideString = (reverse) ? (slider.count + slider.cloneOffset) * dimension : 0;
          } else if (slider.currentSlide === slider.last && target === 0 && vars.animationLoop && slider.direction !== "prev") {
            slideString = (reverse) ? 0 : (slider.count + 1) * dimension;
          } else {
            slideString = (reverse) ? ((slider.count - 1) - target + slider.cloneOffset) * dimension : (target + slider.cloneOffset) * dimension;
          }
          slider.setProps(slideString, "", vars.animationSpeed);
          if (slider.transitions) {
            if (!vars.animationLoop || !slider.atEnd) {
              slider.animating = false;
              slider.currentSlide = slider.animatingTo;
            }
            slider.container.unbind("webkitTransitionEnd transitionend");
            slider.container.bind("webkitTransitionEnd transitionend", function() {
              slider.wrapup(dimension);
            });
          } else {
            slider.container.animate(slider.args, vars.animationSpeed, vars.easing, function(){
              slider.wrapup(dimension);
            });
          }
        } else { // FADE:
          slider.slides.eq(slider.currentSlide).fadeOut(vars.animationSpeed, vars.easing);
          slider.slides.eq(target).fadeIn(vars.animationSpeed, vars.easing, slider.wrapup);
        }
        // SMOOTH HEIGHT:
        if (vars.smoothHeight) methods.smoothHeight(vars.animationSpeed);
      }
    } 
    slider.wrapup = function(dimension) {
      // SLIDE:
      if (!fade && !carousel) {
        if (slider.currentSlide === 0 && slider.animatingTo === slider.last && vars.animationLoop) {
          slider.setProps(dimension, "jumpEnd");
        } else if (slider.currentSlide === slider.last && slider.animatingTo === 0 && vars.animationLoop) {
          slider.setProps(dimension, "jumpStart");
        }
      }
      slider.animating = false;
      slider.currentSlide = slider.animatingTo;
      // API: after() animation Callback
      vars.after(slider);
    }
    
    // SLIDESHOW:
    slider.animateSlides = function() {
      if (!slider.animating) slider.flexAnimate(slider.getTarget("next"));
    }
    // SLIDESHOW:
    slider.pause = function() {
      clearInterval(slider.animatedSlides);
      slider.playing = false;
      // PAUSEPLAY:
      if (vars.pausePlay) methods.pausePlay.update("play");
      // SYNC:
      if (slider.syncExists) methods.sync("pause");
    }
    // SLIDESHOW:
    slider.play = function() {
      slider.animatedSlides = setInterval(slider.animateSlides, vars.slideshowSpeed);
      slider.playing = true;
      // PAUSEPLAY:
      if (vars.pausePlay) methods.pausePlay.update("pause");
      // SYNC:
      if (slider.syncExists) methods.sync("play");
    }
    slider.canAdvance = function(target) {
      // ASNAV:
      var last = (asNav) ? slider.pagingCount - 1 : slider.last;
      return (asNav && slider.currentItem === 0 && target === slider.pagingCount - 1 && slider.direction !== "next") ? false :
             (target === slider.currentSlide && !asNav) ? false :
             (vars.animationLoop) ? true :
             (slider.atEnd && slider.currentSlide === 0 && target === last && slider.direction !== "next") ? false :
             (slider.atEnd && slider.currentSlide === last && target === 0 && slider.direction === "next") ? false :
             true;
    }
    slider.getTarget = function(dir) {
      slider.direction = dir; 
      if (dir === "next") {
        return (slider.currentSlide === slider.last) ? 0 : slider.currentSlide + 1;
      } else {
        return (slider.currentSlide === 0) ? slider.last : slider.currentSlide - 1;
      }
    }
    
    // SLIDE:
    slider.setProps = function(pos, special, dur) {
      var target = (function() {
        var posCheck = (pos) ? pos : ((slider.itemW + vars.itemMargin) * slider.move) * slider.animatingTo,
            posCalc = (function() {
              if (carousel) {
                return (special === "setTouch") ? pos :
                       (reverse && slider.animatingTo === slider.last) ? 0 :
                       (reverse) ? slider.limit - (((slider.itemW + vars.itemMargin) * slider.move) * slider.animatingTo) :
                       (slider.animatingTo === slider.last) ? slider.limit : posCheck;
              } else {
                switch (special) {
                  case "setTotal": return (reverse) ? ((slider.count - 1) - slider.currentSlide + slider.cloneOffset) * pos : (slider.currentSlide + slider.cloneOffset) * pos;
                  case "setTouch": return (reverse) ? pos : pos;
                  case "jumpEnd": return (reverse) ? pos : slider.count * pos;
                  case "jumpStart": return (reverse) ? slider.count * pos : pos;
                  default: return pos;
                }
              }
            }());
            return (posCalc * -1) + "px";
          }());

      if (slider.transitions) {
        target = (vertical) ? "translate3d(0," + target + ",0)" : "translate3d(" + target + ",0,0)";
        dur = (dur !== undefined) ? (dur/1000) + "s" : "0s";
        slider.container.css("-" + slider.pfx + "-transition-duration", dur);
      }
      
      slider.args[slider.prop] = target;
      if (slider.transitions || dur === undefined) slider.container.css(slider.args);
    }
    
    slider.setup = function(type) {
      // SLIDE:
      if (!fade) {
        var sliderOffset, arr;
            
        if (type === "init") {
          slider.viewport = $('<div class="flex-viewport"></div>').css({"overflow": "hidden", "position": "relative"}).appendTo(slider).append(slider.container);
          // INFINITE LOOP:
          slider.cloneCount = 0;
          slider.cloneOffset = 0;
          // REVERSE:
          if (reverse) {
            arr = $.makeArray(slider.slides).reverse();
            slider.slides = $(arr);
            slider.container.empty().append(slider.slides);
          }
        }
        // INFINITE LOOP && !CAROUSEL:
        if (vars.animationLoop && !carousel) {
          slider.cloneCount = 2;
          slider.cloneOffset = 1;
          // clear out old clones
          if (type !== "init") slider.container.find('.clone').remove();
          slider.container.append(slider.slides.first().clone().addClass('clone')).prepend(slider.slides.last().clone().addClass('clone'));
        }
        slider.newSlides = $(vars.selector, slider);
        
        sliderOffset = (reverse) ? slider.count - 1 - slider.currentSlide + slider.cloneOffset : slider.currentSlide + slider.cloneOffset;
        // VERTICAL:
        if (vertical && !carousel) {
          slider.container.height((slider.count + slider.cloneCount) * 200 + "%").css("position", "absolute").width("100%");
          setTimeout(function(){
            slider.newSlides.css({"display": "block"});
            slider.doMath();
            slider.viewport.height(slider.h);
            slider.setProps(sliderOffset * slider.h, "init");
          }, (type === "init") ? 100 : 0);
        } else {
          slider.container.width((slider.count + slider.cloneCount) * 200 + "%");
          slider.setProps(sliderOffset * slider.computedW, "init");
          setTimeout(function(){
            slider.doMath();
            slider.newSlides.css({"width": slider.computedW, "float": "left", "display": "block"});
            // SMOOTH HEIGHT:
            if (vars.smoothHeight) methods.smoothHeight();
          }, (type === "init") ? 100 : 0);
        }
      } else { // FADE: 
        slider.slides.css({"width": "100%", "float": "left", "marginRight": "-100%", "position": "relative"});
        if (type === "init") slider.slides.eq(slider.currentSlide).fadeIn(vars.animationSpeed, vars.easing);
        // SMOOTH HEIGHT:
        if (vars.smoothHeight) methods.smoothHeight();
      }
      // !CAROUSEL:
      // CANDIDATE: active slide
      if (!carousel) slider.slides.removeClass(namespace + "active-slide").eq(slider.currentSlide).addClass(namespace + "active-slide");
    }
    
    slider.doMath = function() {
      var slide = slider.slides.first(),
          slideMargin = vars.itemMargin,
          minItems = vars.minItems,
          maxItems = vars.maxItems;
      
      slider.w = slider.width();
      slider.h = slide.height();
      slider.boxPadding = slide.outerWidth() - slide.width();

      // CAROUSEL:
      if (carousel) {
        slider.itemT = vars.itemWidth + slideMargin;
        slider.minW = (minItems) ? minItems * slider.itemT : slider.w;
        slider.maxW = (maxItems) ? maxItems * slider.itemT : slider.w;
        slider.itemW = (slider.minW > slider.w) ? (slider.w - (slideMargin * minItems))/minItems :
                       (slider.maxW < slider.w) ? (slider.w - (slideMargin * maxItems))/maxItems :
                       (vars.itemWidth > slider.w) ? slider.w : vars.itemWidth;
        slider.visible = Math.floor(slider.w/(slider.itemW + slideMargin));
        slider.move = (vars.move > 0 && vars.move < slider.visible ) ? vars.move : slider.visible;
        slider.pagingCount = Math.ceil(((slider.count - slider.visible)/slider.move) + 1);
        slider.last =  slider.pagingCount - 1;
        slider.limit = (slider.pagingCount === 1) ? 0 :
                       (vars.itemWidth > slider.w) ? ((slider.itemW + (slideMargin * 2)) * slider.count) - slider.w - slideMargin : ((slider.itemW + slideMargin) * slider.count) - slider.w;
      } else {
        slider.itemW = slider.w;
        slider.pagingCount = slider.count;
        slider.last = slider.count - 1;
      }
      slider.computedW = slider.itemW - slider.boxPadding;
    }
    
    slider.update = function(pos, action) {
      slider.doMath();
      
      // update currentSlide and slider.animatingTo if necessary
      if (!carousel) {
        if (pos < slider.currentSlide) {
          slider.currentSlide += 1;
        } else if (pos <= slider.currentSlide && pos !== 0) {
          slider.currentSlide -= 1;
        }
        slider.animatingTo = slider.currentSlide;
      }
      
      // update controlNav
      if (vars.controlNav && !slider.manualControls) {
        if ((action === "add" && !carousel) || slider.pagingCount > slider.controlNav.length) {
          methods.controlNav.update("add");
        } else if ((action === "remove" && !carousel) || slider.pagingCount < slider.controlNav.length) {
          if (carousel && slider.currentSlide > slider.last) {
            slider.currentSlide -= 1;
            slider.animatingTo -= 1;
          }
          methods.controlNav.update("remove", slider.last);
        }
      }
      // update directionNav
      if (vars.directionNav) methods.directionNav.update();
      
    }
    
    slider.addSlide = function(obj, pos) {
      var $obj = $(obj);
      
      slider.count += 1;
      slider.last = slider.count - 1;
      
      // append new slide
      if (vertical && reverse) {
        (pos !== undefined) ? slider.slides.eq(slider.count - pos).after($obj) : slider.container.prepend($obj);
      } else {
        (pos !== undefined) ? slider.slides.eq(pos).before($obj) : slider.container.append($obj);
      }
      
      // update currentSlide, animatingTo, controlNav, and directionNav
      slider.update(pos, "add");
      
      // update slider.slides
      slider.slides = $(vars.selector + ':not(.clone)', slider);
      // re-setup the slider to accomdate new slide
      slider.setup();
      
      //FlexSlider: added() Callback
      vars.added(slider);
    }
    slider.removeSlide = function(obj) {
      var pos = (isNaN(obj)) ? slider.slides.index($(obj)) : obj;
      
      // update count
      slider.count -= 1;
      slider.last = slider.count - 1;
      
      // remove slide
      if (isNaN(obj)) {
        $(obj, slider.slides).remove();
      } else {
        (vertical && reverse) ? slider.slides.eq(slider.last).remove() : slider.slides.eq(obj).remove();
      }
      
      // update currentSlide, animatingTo, controlNav, and directionNav
      slider.doMath();
      slider.update(pos, "remove");
      
      // update slider.slides
      slider.slides = $(vars.selector + ':not(.clone)', slider);
      // re-setup the slider to accomdate new slide
      slider.setup();
      
      // FlexSlider: removed() Callback
      vars.removed(slider);
    }
    
    //FlexSlider: Initialize
    methods.init();
  }
  
  //FlexSlider: Default Settings
  $.flexslider.defaults = {
    namespace: "flex-",             //{NEW} String: Prefix string attached to the class of every element generated by the plugin
    selector: ".slides > li",       //{NEW} Selector: Must match a simple pattern. '{container} > {slide}' -- Ignore pattern at your own peril
    animation: "fade",              //String: Select your animation type, "fade" or "slide"
    easing: "swing",               //{NEW} String: Determines the easing method used in jQuery transitions. jQuery easing plugin is supported!
    direction: "horizontal",        //String: Select the sliding direction, "horizontal" or "vertical"
    reverse: false,                 //{NEW} Boolean: Reverse the animation direction
    animationLoop: true,             //Boolean: Should the animation loop? If false, directionNav will received "disable" classes at either end
    smoothHeight: false,            //{NEW} Boolean: Allow height of the slider to animate smoothly in horizontal mode  
    startAt: 0,                     //Integer: The slide that the slider should start on. Array notation (0 = first slide)
    slideshow: true,                //Boolean: Animate slider automatically
    slideshowSpeed: 7000,           //Integer: Set the speed of the slideshow cycling, in milliseconds
    animationSpeed: 600,            //Integer: Set the speed of animations, in milliseconds
    initDelay: 0,                   //{NEW} Integer: Set an initialization delay, in milliseconds
    randomize: false,               //Boolean: Randomize slide order
    
    // Usability features
    pauseOnAction: true,            //Boolean: Pause the slideshow when interacting with control elements, highly recommended.
    pauseOnHover: false,            //Boolean: Pause the slideshow when hovering over slider, then resume when no longer hovering
    useCSS: true,                   //{NEW} Boolean: Slider will use CSS3 transitions if available
    touch: true,                    //{NEW} Boolean: Allow touch swipe navigation of the slider on touch-enabled devices
    video: false,                   //{NEW} Boolean: If using video in the slider, will prevent CSS3 3D Transforms to avoid graphical glitches
    
    // Primary Controls
    controlNav: true,               //Boolean: Create navigation for paging control of each clide? Note: Leave true for manualControls usage
    directionNav: true,             //Boolean: Create navigation for previous/next navigation? (true/false)
    prevText: "Previous",           //String: Set the text for the "previous" directionNav item
    nextText: "Next",               //String: Set the text for the "next" directionNav item
    
    // Secondary Navigation
    keyboard: true,                 //Boolean: Allow slider navigating via keyboard left/right keys
    multipleKeyboard: false,        //{NEW} Boolean: Allow keyboard navigation to affect multiple sliders. Default behavior cuts out keyboard navigation with more than one slider present.
    mousewheel: false,              //{UPDATED} Boolean: Requires jquery.mousewheel.js (https://github.com/brandonaaron/jquery-mousewheel) - Allows slider navigating via mousewheel
    pausePlay: false,               //Boolean: Create pause/play dynamic element
    pauseText: "Pause",             //String: Set the text for the "pause" pausePlay item
    playText: "Play",               //String: Set the text for the "play" pausePlay item
    
    // Special properties
    controlsContainer: "",          //{UPDATED} jQuery Object/Selector: Declare which container the navigation elements should be appended too. Default container is the FlexSlider element. Example use would be $(".flexslider-container"). Property is ignored if given element is not found.
    manualControls: "",             //{UPDATED} jQuery Object/Selector: Declare custom control navigation. Examples would be $(".flex-control-nav li") or "#tabs-nav li img", etc. The number of elements in your controlNav should match the number of slides/tabs.
    sync: "",                       //{NEW} Selector: Mirror the actions performed on this slider with another slider. Use with care.
    asNavFor: "",                   //{NEW} Selector: Internal property exposed for turning the slider into a thumbnail navigation for another slider
    
    // Carousel Options
    itemWidth: 0,                   //{NEW} Integer: Box-model width of individual carousel items, including horizontal borders and padding.
    itemMargin: 0,                  //{NEW} Integer: Margin between carousel items.
    minItems: 0,                    //{NEW} Integer: Minimum number of carousel items that should be visible. Items will resize fluidly when below this.
    maxItems: 0,                    //{NEW} Integer: Maxmimum number of carousel items that should be visible. Items will resize fluidly when above this limit.
    move: 0,                        //{NEW} Integer: Number of carousel items that should move on animation. If 0, slider will move all visible items.
                                    
    // Callback API
    start: function(){},            //Callback: function(slider) - Fires when the slider loads the first slide
    before: function(){},           //Callback: function(slider) - Fires asynchronously with each slider animation
    after: function(){},            //Callback: function(slider) - Fires after each slider animation completes
    end: function(){},              //Callback: function(slider) - Fires when the slider reaches the last slide (asynchronous)
    added: function(){},            //{NEW} Callback: function(slider) - Fires after a slide is added
    removed: function(){}           //{NEW} Callback: function(slider) - Fires after a slide is removed
  }


  //FlexSlider: Plugin Function
  $.fn.flexslider = function(options) {
    options = options || {};
    if (typeof options === "object") {
      return this.each(function() {
        var $this = $(this),
            selector = (options.selector) ? options.selector : ".slides > li",
            $slides = $this.find(selector);

        if ($slides.length === 1) {
          $slides.fadeIn(400);
          if (options.start) options.start($this);
        } else if ($this.data('flexslider') === undefined) {
          new $.flexslider(this, options);
        }
      });
    } else {
      // Helper strings to quickly perform functions on the slider
      var $slider = $(this).data('flexslider');
      switch (options) {
        case "play": $slider.play(); break;
        case "pause": $slider.pause(); break;
        case "next": $slider.flexAnimate($slider.getTarget("next"), true); break;
        case "prev":
        case "previous": $slider.flexAnimate($slider.getTarget("prev"), true); break;
        default: if (typeof options === "number") $slider.flexAnimate(options, true);
      }
    }
  }  

})(jQuery);var ratings = {
    five: 5,
    fourHalf: 4.5,
    four: 4,
    threeHalf: 3.5,
    three: 3,
    twoHalf: 2.5,
    two: 2,
    oneHalf: 1.5,
    one: 1,
    half: .5,
    zero: 0
};

function setupSearchField() {
    var $clearField       = $('#header #search .clearField'),
        showingClearField = false,
        clickedClear      = false,
        $searchField      = $('#header #search input'),
        searchStartWidth  = $searchField.css('width'),
        searchMaxWidth    = $searchField.css('max-width');

    $clearField.on('mousedown', function() {
        clickedClear = true;
    }).on('click', function(e) {
        $searchField.val('').focus().keyup();
    });

    $searchField.on('click', function(e) {
        if($searchField.attr('expanded') != 'true') {
            $searchField.css('width', searchMaxWidth).attr('expanded', 'true');
        }

        if($searchField.val()) {
            $searchField.keyup();
        }
    }).on('keyup', function(e) {
        if(!showingClearField) {
            $clearField.fadeIn(200);

            showingClearField = true;
        } else if(!$searchField.val()) {
            $clearField.fadeOut(200);

            showingClearField = false;
        }
    });

    $searchField.on('blur', function(e) {
        if($searchField.attr('expanded') != 'false' && !clickedClear) {
            if(showingClearField) {
                $clearField.fadeOut(200);

                showingClearField = false;
            }

            $searchField.css('width', searchStartWidth).attr('expanded', 'false');
        }

        clickedClear = false;
    });
}

function hideMessage() {
    if($('#redirectWrapper #redirectMessage').text() != '') {
        setTimeout(function() {
            $('#redirectWrapper').fadeOut();
        }, 5000);
    } else {
        $('#redirectWrapper').hide();
    }
}

function showMessage(msg) {
    $('#redirectWrapper #redirectMessage').text(msg);

    $('#redirectWrapper').fadeIn();
    
    hideMessage();
}

function lookForFacebookLoginButtons() {
    $('.facebookLogin, .facebookLoginMini a').on('click', function(e) {
        e.preventDefault();

        doFacebookLogin($(this).attr('href'));
    });
}

function lookForRatings() {
    $('.rating').showRatingStars();
}

$(document).ready(function() {
    setupSearchField();
    hideMessage();
    lookForFacebookLoginButtons();
    lookForRatings();
    lookForMiniMaxes();

    $(window).scrollToTop('#scrollToTop');
});// ----------------------------------------------------------------------------
// markItUp! Universal MarkUp Engine, JQuery plugin
// v 1.1.x
// Dual licensed under the MIT and GPL licenses.
// ----------------------------------------------------------------------------
// Copyright (C) 2007-2012 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
// 
// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.
// 
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
// THE SOFTWARE.
// ----------------------------------------------------------------------------
(function($) {
	$.fn.markItUp = function(settings, extraSettings) {
		var method, params, options, ctrlKey, shiftKey, altKey; ctrlKey = shiftKey = altKey = false;

		if (typeof settings == 'string') {
			method = settings;
			params = extraSettings;
		} 

		options = {	id:						'',
					nameSpace:				'',
					root:					'',
					previewHandler:			false,
					previewInWindow:		'', // 'width=800, height=600, resizable=yes, scrollbars=yes'
					previewInElement:		'',
					previewAutoRefresh:		true,
					previewPosition:		'after',
					previewTemplatePath:	'~/templates/preview.html',
					previewParser:			false,
					previewParserPath:		'',
					previewParserVar:		'data',
					resizeHandle:			true,
					beforeInsert:			'',
					afterInsert:			'',
					onEnter:				{},
					onShiftEnter:			{},
					onCtrlEnter:			{},
					onTab:					{},
					markupSet:			[	{ /* set */ } ]
				};
		$.extend(options, settings, extraSettings);

		// compute markItUp! path
		if (!options.root) {
			$('script').each(function(a, tag) {
				miuScript = $(tag).get(0).src.match(/(.*)jquery\.markitup(\.pack)?\.js$/);
				if (miuScript !== null) {
					options.root = miuScript[1];
				}
			});
		}

		// Quick patch to keep compatibility with jQuery 1.9
		var uaMatch = function(ua) {
			ua = ua.toLowerCase();

			var match = /(chrome)[ \/]([\w.]+)/.exec(ua) ||
				/(webkit)[ \/]([\w.]+)/.exec(ua) ||
				/(opera)(?:.*version|)[ \/]([\w.]+)/.exec(ua) ||
				/(msie) ([\w.]+)/.exec(ua) ||
				ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(ua) ||
				[];

			return {
				browser: match[ 1 ] || "",
				version: match[ 2 ] || "0"
			};
		};
		var matched = uaMatch( navigator.userAgent );
		var browser = {};

		if (matched.browser) {
			browser[matched.browser] = true;
			browser.version = matched.version;
		}
		if (browser.chrome) {
			browser.webkit = true;
		} else if (browser.webkit) {
			browser.safari = true;
		}

		return this.each(function() {
			var $$, textarea, levels, scrollPosition, caretPosition, caretOffset,
				clicked, hash, header, footer, previewWindow, template, iFrame, abort;
			$$ = $(this);
			textarea = this;
			levels = [];
			abort = false;
			scrollPosition = caretPosition = 0;
			caretOffset = -1;

			options.previewParserPath = localize(options.previewParserPath);
			options.previewTemplatePath = localize(options.previewTemplatePath);

			if (method) {
				switch(method) {
					case 'remove':
						remove();
					break;
					case 'insert':
						markup(params);
					break;
					default: 
						$.error('Method ' +  method + ' does not exist on jQuery.markItUp');
				}
				return;
			}

			// apply the computed path to ~/
			function localize(data, inText) {
				if (inText) {
					return 	data.replace(/("|')~\//g, "$1"+options.root);
				}
				return 	data.replace(/^~\//, options.root);
			}

			// init and build editor
			function init() {
				id = ''; nameSpace = '';
				if (options.id) {
					id = 'id="'+options.id+'"';
				} else if ($$.attr("id")) {
					id = 'id="markItUp'+($$.attr("id").substr(0, 1).toUpperCase())+($$.attr("id").substr(1))+'"';

				}
				if (options.nameSpace) {
					nameSpace = 'class="'+options.nameSpace+'"';
				}
				$$.wrap('<div '+nameSpace+'></div>');
				$$.wrap('<div '+id+' class="markItUp"></div>');
				$$.wrap('<div class="markItUpContainer"></div>');
				$$.addClass("markItUpEditor");

				// add the header before the textarea
				header = $('<div class="markItUpHeader"></div>').insertBefore($$);
				$(dropMenus(options.markupSet)).appendTo(header);

				// add the footer after the textarea
				footer = $('<div class="markItUpFooter"></div>').insertAfter($$);

				// add the resize handle after textarea
				if (options.resizeHandle === true && browser.safari !== true) {
					resizeHandle = $('<div class="markItUpResizeHandle"></div>')
						.insertAfter($$)
						.bind("mousedown.markItUp", function(e) {
							var h = $$.height(), y = e.clientY, mouseMove, mouseUp;
							mouseMove = function(e) {
								$$.css("height", Math.max(20, e.clientY+h-y)+"px");
								return false;
							};
							mouseUp = function(e) {
								$("html").unbind("mousemove.markItUp", mouseMove).unbind("mouseup.markItUp", mouseUp);
								return false;
							};
							$("html").bind("mousemove.markItUp", mouseMove).bind("mouseup.markItUp", mouseUp);
					});
					footer.append(resizeHandle);
				}

				// listen key events
				$$.bind('keydown.markItUp', keyPressed).bind('keyup', keyPressed);
				
				// bind an event to catch external calls
				$$.bind("insertion.markItUp", function(e, settings) {
					if (settings.target !== false) {
						get();
					}
					if (textarea === $.markItUp.focused) {
						markup(settings);
					}
				});

				// remember the last focus
				$$.bind('focus.markItUp', function() {
					$.markItUp.focused = this;
				});

				if (options.previewInElement) {
					refreshPreview();
				}
			}

			// recursively build header with dropMenus from markupset
			function dropMenus(markupSet) {
				var ul = $('<ul></ul>'), i = 0;
				$('li:hover > ul', ul).css('display', 'block');
				$.each(markupSet, function() {
					var button = this, t = '', title, li, j;
					title = (button.key) ? (button.name||'')+' [Ctrl+'+button.key+']' : (button.name||'');
					key   = (button.key) ? 'accesskey="'+button.key+'"' : '';
					if (button.separator) {
						li = $('<li class="markItUpSeparator">'+(button.separator||'')+'</li>').appendTo(ul);
					} else {
						i++;
						for (j = levels.length -1; j >= 0; j--) {
							t += levels[j]+"-";
						}
						li = $('<li class="markItUpButton markItUpButton'+t+(i)+' '+(button.className||'')+'"><a href="" '+key+' title="'+title+'">'+(button.name||'')+'</a></li>')
						.bind("contextmenu.markItUp", function() { // prevent contextmenu on mac and allow ctrl+click
							return false;
						}).bind('click.markItUp', function(e) {
							e.preventDefault();
						}).bind("focusin.markItUp", function(){
                            $$.focus();
						}).bind('mouseup', function() {
							if (button.call) {
								eval(button.call)();
							}
							setTimeout(function() { markup(button) },1);
							return false;
						}).bind('mouseenter.markItUp', function() {
								$('> ul', this).show();
								$(document).one('click', function() { // close dropmenu if click outside
										$('ul ul', header).hide();
									}
								);
						}).bind('mouseleave.markItUp', function() {
								$('> ul', this).hide();
						}).appendTo(ul);
						if (button.dropMenu) {
							levels.push(i);
							$(li).addClass('markItUpDropMenu').append(dropMenus(button.dropMenu));
						}
					}
				}); 
				levels.pop();
				return ul;
			}

			// markItUp! markups
			function magicMarkups(string) {
				if (string) {
					string = string.toString();
					string = string.replace(/\(\!\(([\s\S]*?)\)\!\)/g,
						function(x, a) {
							var b = a.split('|!|');
							if (altKey === true) {
								return (b[1] !== undefined) ? b[1] : b[0];
							} else {
								return (b[1] === undefined) ? "" : b[0];
							}
						}
					);
					// [![prompt]!], [![prompt:!:value]!]
					string = string.replace(/\[\!\[([\s\S]*?)\]\!\]/g,
						function(x, a) {
							var b = a.split(':!:');
							if (abort === true) {
								return false;
							}
							value = prompt(b[0], (b[1]) ? b[1] : '');
							if (value === null) {
								abort = true;
							}
							return value;
						}
					);
					return string;
				}
				return "";
			}

			// prepare action
			function prepare(action) {
				if ($.isFunction(action)) {
					action = action(hash);
				}
				return magicMarkups(action);
			}

			// build block to insert
			function build(string) {
				var openWith 			= prepare(clicked.openWith);
				var placeHolder 		= prepare(clicked.placeHolder);
				var replaceWith 		= prepare(clicked.replaceWith);
				var closeWith 			= prepare(clicked.closeWith);
				var openBlockWith 		= prepare(clicked.openBlockWith);
				var closeBlockWith 		= prepare(clicked.closeBlockWith);
				var multiline 			= clicked.multiline;
				
				if (replaceWith !== "") {
					block = openWith + replaceWith + closeWith;
				} else if (selection === '' && placeHolder !== '') {
					block = openWith + placeHolder + closeWith;
				} else {
					string = string || selection;

					var lines = [string], blocks = [];
					
					if (multiline === true) {
						lines = string.split(/\r?\n/);
					}
					
					for (var l = 0; l < lines.length; l++) {
						line = lines[l];
						var trailingSpaces;
						if (trailingSpaces = line.match(/ *$/)) {
							blocks.push(openWith + line.replace(/ *$/g, '') + closeWith + trailingSpaces);
						} else {
							blocks.push(openWith + line + closeWith);
						}
					}
					
					block = blocks.join("\n");
				}

				block = openBlockWith + block + closeBlockWith;

				return {	block:block, 
							openBlockWith:openBlockWith,
							openWith:openWith, 
							replaceWith:replaceWith, 
							placeHolder:placeHolder,
							closeWith:closeWith,
							closeBlockWith:closeBlockWith
					};
			}

			// define markup to insert
			function markup(button) {
				var len, j, n, i;
				hash = clicked = button;
				get();
				$.extend(hash, {	line:"", 
						 			root:options.root,
									textarea:textarea, 
									selection:(selection||''), 
									caretPosition:caretPosition,
									ctrlKey:ctrlKey, 
									shiftKey:shiftKey, 
									altKey:altKey
								}
							);
				// callbacks before insertion
				prepare(options.beforeInsert);
				prepare(clicked.beforeInsert);
				if ((ctrlKey === true && shiftKey === true) || button.multiline === true) {
					prepare(clicked.beforeMultiInsert);
				}			
				$.extend(hash, { line:1 });

				if ((ctrlKey === true && shiftKey === true)) {
					lines = selection.split(/\r?\n/);
					for (j = 0, n = lines.length, i = 0; i < n; i++) {
						if ($.trim(lines[i]) !== '') {
							$.extend(hash, { line:++j, selection:lines[i] } );
							lines[i] = build(lines[i]).block;
						} else {
							lines[i] = "";
						}
					}

					string = { block:lines.join('\n')};
					start = caretPosition;
					len = string.block.length + ((browser.opera) ? n-1 : 0);
				} else if (ctrlKey === true) {
					string = build(selection);
					start = caretPosition + string.openWith.length;
					len = string.block.length - string.openWith.length - string.closeWith.length;
					len = len - (string.block.match(/ $/) ? 1 : 0);
					len -= fixIeBug(string.block);
				} else if (shiftKey === true) {
					string = build(selection);
					start = caretPosition;
					len = string.block.length;
					len -= fixIeBug(string.block);
				} else {
					string = build(selection);
					start = caretPosition + string.block.length ;
					len = 0;
					start -= fixIeBug(string.block);
				}
				if ((selection === '' && string.replaceWith === '')) {
					caretOffset += fixOperaBug(string.block);
					
					start = caretPosition + string.openBlockWith.length + string.openWith.length;
					len = string.block.length - string.openBlockWith.length - string.openWith.length - string.closeWith.length - string.closeBlockWith.length;

					caretOffset = $$.val().substring(caretPosition,  $$.val().length).length;
					caretOffset -= fixOperaBug($$.val().substring(0, caretPosition));
				}
				$.extend(hash, { caretPosition:caretPosition, scrollPosition:scrollPosition } );

				if (string.block !== selection && abort === false) {
					insert(string.block);
					set(start, len);
				} else {
					caretOffset = -1;
				}
				get();

				$.extend(hash, { line:'', selection:selection });

				// callbacks after insertion
				if ((ctrlKey === true && shiftKey === true) || button.multiline === true) {
					prepare(clicked.afterMultiInsert);
				}
				prepare(clicked.afterInsert);
				prepare(options.afterInsert);

				// refresh preview if opened
				if (previewWindow && options.previewAutoRefresh) {
					refreshPreview(); 
				}
																									
				// reinit keyevent
				shiftKey = altKey = ctrlKey = abort = false;
			}

			// Substract linefeed in Opera
			function fixOperaBug(string) {
				if (browser.opera) {
					return string.length - string.replace(/\n*/g, '').length;
				}
				return 0;
			}
			// Substract linefeed in IE
			function fixIeBug(string) {
				if (browser.msie) {
					return string.length - string.replace(/\r*/g, '').length;
				}
				return 0;
			}
				
			// add markup
			function insert(block) {	
				if (document.selection) {
					var newSelection = document.selection.createRange();
					newSelection.text = block;
				} else {
					textarea.value =  textarea.value.substring(0, caretPosition)  + block + textarea.value.substring(caretPosition + selection.length, textarea.value.length);
				}
			}

			// set a selection
			function set(start, len) {
				if (textarea.createTextRange){
					// quick fix to make it work on Opera 9.5
					if (browser.opera && browser.version >= 9.5 && len == 0) {
						return false;
					}
					range = textarea.createTextRange();
					range.collapse(true);
					range.moveStart('character', start); 
					range.moveEnd('character', len); 
					range.select();
				} else if (textarea.setSelectionRange ){
					textarea.setSelectionRange(start, start + len);
				}
				textarea.scrollTop = scrollPosition;
				textarea.focus();
			}

			// get the selection
			function get() {
				textarea.focus();

				scrollPosition = textarea.scrollTop;
				if (document.selection) {
					selection = document.selection.createRange().text;
					if (browser.msie) { // ie
						var range = document.selection.createRange(), rangeCopy = range.duplicate();
						rangeCopy.moveToElementText(textarea);
						caretPosition = -1;
						while(rangeCopy.inRange(range)) {
							rangeCopy.moveStart('character');
							caretPosition ++;
						}
					} else { // opera
						caretPosition = textarea.selectionStart;
					}
				} else { // gecko & webkit
					caretPosition = textarea.selectionStart;

					selection = textarea.value.substring(caretPosition, textarea.selectionEnd);
				} 
				return selection;
			}

			// open preview window
			function preview() {
				if (typeof options.previewHandler === 'function') {
					previewWindow = true;
				} else if (options.previewInElement) {
					previewWindow = $(options.previewInElement);
				} else if (!previewWindow || previewWindow.closed) {
					if (options.previewInWindow) {
						previewWindow = window.open('', 'preview', options.previewInWindow);
						$(window).unload(function() {
							previewWindow.close();
						});
					} else {
						iFrame = $('<iframe class="markItUpPreviewFrame"></iframe>');
						if (options.previewPosition == 'after') {
							iFrame.insertAfter(footer);
						} else {
							iFrame.insertBefore(header);
						}	
						previewWindow = iFrame[iFrame.length - 1].contentWindow || frame[iFrame.length - 1];
					}
				} else if (altKey === true) {
					if (iFrame) {
						iFrame.remove();
					} else {
						previewWindow.close();
					}
					previewWindow = iFrame = false;
				}
				if (!options.previewAutoRefresh) {
					refreshPreview(); 
				}
				if (options.previewInWindow) {
					previewWindow.focus();
				}
			}

			// refresh Preview window
			function refreshPreview() {
 				renderPreview();
			}

			function renderPreview() {
				var phtml;
				if (options.previewHandler && typeof options.previewHandler === 'function') {
					options.previewHandler( $$.val() );
				} else if (options.previewParser && typeof options.previewParser === 'function') {
					var data = options.previewParser( $$.val() );
					writeInPreview(localize(data, 1) ); 
				} else if (options.previewParserPath !== '') {
					$.ajax({
						type: 'POST',
						dataType: 'text',
						global: false,
						url: options.previewParserPath,
						data: options.previewParserVar+'='+encodeURIComponent($$.val()),
						success: function(data) {
							writeInPreview( localize(data, 1) ); 
						}
					});
				} else {
					if (!template) {
						$.ajax({
							url: options.previewTemplatePath,
							dataType: 'text',
							global: false,
							success: function(data) {
								writeInPreview( localize(data, 1).replace(/<!-- content -->/g, $$.val()) );
							}
						});
					}
				}
				return false;
			}
			
			function writeInPreview(data) {
				if (options.previewInElement) {
					$(options.previewInElement).html(data);
				} else if (previewWindow && previewWindow.document) {			
					try {
						sp = previewWindow.document.documentElement.scrollTop
					} catch(e) {
						sp = 0;
					}	
					previewWindow.document.open();
					previewWindow.document.write(data);
					previewWindow.document.close();
					previewWindow.document.documentElement.scrollTop = sp;
				}
			}
			
			// set keys pressed
			function keyPressed(e) { 
				shiftKey = e.shiftKey;
				altKey = e.altKey;
				ctrlKey = (!(e.altKey && e.ctrlKey)) ? (e.ctrlKey || e.metaKey) : false;

				if (e.type === 'keydown') {
					if (ctrlKey === true) {
						li = $('a[accesskey="'+((e.keyCode == 13) ? '\\n' : String.fromCharCode(e.keyCode))+'"]', header).parent('li');
						if (li.length !== 0) {
							ctrlKey = false;
							setTimeout(function() {
								li.triggerHandler('mouseup');
							},1);
							return false;
						}
					}
					if (e.keyCode === 13 || e.keyCode === 10) { // Enter key
						if (ctrlKey === true) {  // Enter + Ctrl
							ctrlKey = false;
							markup(options.onCtrlEnter);
							return options.onCtrlEnter.keepDefault;
						} else if (shiftKey === true) { // Enter + Shift
							shiftKey = false;
							markup(options.onShiftEnter);
							return options.onShiftEnter.keepDefault;
						} else { // only Enter
							markup(options.onEnter);
							return options.onEnter.keepDefault;
						}
					}
					if (e.keyCode === 9) { // Tab key
						if (shiftKey == true || ctrlKey == true || altKey == true) {
							return false; 
						}
						if (caretOffset !== -1) {
							get();
							caretOffset = $$.val().length - caretOffset;
							set(caretOffset, 0);
							caretOffset = -1;
							return false;
						} else {
							markup(options.onTab);
							return options.onTab.keepDefault;
						}
					}
				}
			}

			function remove() {
				$$.unbind(".markItUp").removeClass('markItUpEditor');
				$$.parent('div').parent('div.markItUp').parent('div').replaceWith($$);
				$$.data('markItUp', null);
			}

			init();
		});
	};

	$.fn.markItUpRemove = function() {
		return this.each(function() {
				$(this).markItUp('remove');
			}
		);
	};

	$.markItUp = function(settings) {
		var options = { target:false };
		$.extend(options, settings);
		if (options.target) {
			return $(options.target).each(function() {
				$(this).focus();
				$(this).trigger('insertion', [options]);
			});
		} else {
			$('textarea').trigger('insertion', [options]);
		}
	};
})(jQuery);
(function($) {
    var timer;

    $.fn.neverDisappear = function() {
        var $this        = this,
            windowWidth  = $(window).width(),
            contentWidth = $('#content').width(),
            leftWidth    = (windowWidth - contentWidth) / 2,
            margin       = (leftWidth - $this.width()) / 5;

        if(margin >= 15) {
            $this.toggleClass('neverDisappear');
            $this.css('margin-left', margin + 'px');
        } else {
            $this.toggleClass('neverDisappear');
        }

        $(window).resize(function() {
            clearTimeout(timer);
            timer = setTimeout(function() {
                $this.neverDisappear();
            }, 100);
        });
    };
}(jQuery));

$(document).ready(function() {
    $('.neverDisappear').neverDisappear();
});(function($) {
    $.fn.showRatingStars = function() {
        return $(this).each(function() {
            var rating = $(this).attr('rating');
    
            for(var minRating in ratings) {
                if(ratings[minRating] <= rating) {
                    $(this).addClass(minRating);
                    break;
                }
            }
        });
    };
}(jQuery));(function($) {
    $.fn.scrollToTop = function(icon) {
        var $this = $(this),
            $icon = $(icon);

        $icon.on('click', function() {
            $this.goToTop();
        });

        $this.scroll(function() {
            var windowHeight  = $(this).height(),
                showIconAfter = windowHeight * .75;

            if($this.scrollTop() > showIconAfter) {
                $icon.fadeIn();
            } else {
                $icon.fadeOut();
            }
        });
    };
    
    $.fn.goToTop = function() {
        var $this        = $(this),
            windowHeight = $(this).height(),
            scrollPos    = $this.scrollTop(),
            amount       = scrollPos / 20,
            interval     = setInterval(function() { doScroll(); }, 1),
            time         = 0;

        var eqn = function() {
            return scrollPos - (time * amount);
        };

        var doScroll = function() {
            var position = eqn();

            if(position <= 0) {
                position = 0;

                clearInterval(interval)
            }

            $this.scrollTop(position);

            time++;
        };
    };
}(jQuery));// ----------------------------------------------------------------------------
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
};(function ($) {
    var tabGroups = new Array(),
        activeRe  = /( )?active/;

    $.fn.tabs = function() {
        var $this = this,
            id    = $this.attr('id');

        if(typeof tabGroups[id] != 'undefined') {
            return;
        }

        tabGroups[id] = new Array();

        tabGroups[id]['current'] = new Array(null, null);
        tabGroups[id]['bodies']  = new Array();

        $this.children('.tabContent').children('.tabBody').each(function(index) {
            tabGroups[id]['bodies'][index] = $(this);

            if(activeRe.exec($(this).attr('class'))) {
                tabGroups[id]['current'][1] = $(this);
            }
        });

        $this.children('.tabMenu').children('ul').children('li').each(function(index) {
            if(activeRe.exec($(this).children('a').attr('class'))) {
                tabGroups[id]['current'][0] = $(this).children('a');
            }
        });

        if(tabGroups[id]['current'][0] == null || tabGroups[id]['current'][1] == null) {
            tabGroups[id]['current'][0] = $this.children('.tabMenu').children('ul').children('li:first-child').children('a').toggleClass('active');
            tabGroups[id]['current'][1] = $this.children('.tabContent').children('.tabBody:first-child').toggleClass('active');
        }

        $this.children('.tabMenu').children('ul').children('li').each(function(index) {
            $(this).children('a').on('click', function(e) {
                e.preventDefault();

                tabGroups[id]['current'][0].toggleClass('active');
                tabGroups[id]['current'][1].toggleClass('active');

                $(this).toggleClass('active');
                tabGroups[id]['current'][0] = $(this);

                tabGroups[id]['bodies'][index].toggleClass('active');
                tabGroups[id]['current'][1] = tabGroups[id]['bodies'][index];
            });
        });
    };
}(jQuery));var working     = false,
    workingOn   = 0,
    saving      = false,
    timesRidden = 0,
    rating      = 0;

var riddenField = '<input type="number" id="riddenEntry" style="width: 100px" />',
    ratingField = '<select id="ratingEntry"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select>';

$(document).ready(function() {
    $('#trackrecord').find('.editRecord').each(function() {
        var $this = $(this);

        $this.children('a').each(function(index) {
            var a  = $(this),
                tr = a.parent().parent();

            if(workingOn != tr.attr('favorite')) {
                if(index) {
                    a.on('click', function(e) {
                        e.preventDefault();
                        removeRecord(tr);
                    });
                } else {
                    a.on('click', function(e) {
                        e.preventDefault();
                        editRecord(tr);
                    });
                }
            }
        });
    });
});

function editRecord(tr) {
    if(working === true) {
        alert('You are still editing another record, please save before editing another!');

        return;
    }

    working   = true;
    workingOn = tr.attr('favorite');

    tr.children('.editRecord').children('a').css('display', 'inline');

    timesRidden = tr.children('.fav_ridden').text();
    rating      = tr.children('.fav_rating').children('.rating').attr('rating');

    tr.children('.fav_ridden').html($(riddenField).val(timesRidden));
    tr.children('.fav_rating').html($(ratingField).val(rating));

    getForwardButton(tr).text('Save').unbind('click').on('click', function(e) {
        e.preventDefault();
        saveRecord(tr);
    });

    getBackwardButton(tr).text('Cancel').unbind('click').on('click', function(e) {
        e.preventDefault();
        finish(tr, timesRidden, rating);
    });
}

function removeRecord(tr) {
    var confirmation = confirm('Are you sure you want to remove this roller coaster from your track record?');

    if(!confirmation) {
        return;
    }

    $.standardAjax({
        url: '/track-record/' + getWhat(tr) + '/remove/' + tr.attr('favorite')
    }, function() {
        showMessage('Your track record has been updated.');
        tr.remove();
    });
}

function saveRecord(tr) {
    working = false;

    var riddenEntryValue = $('#riddenEntry').val(),
        ratingEntryValue = $('#ratingEntry').val();

    if((riddenEntryValue != timesRidden && typeof riddenEntryValue != 'undefined') || ratingEntryValue != rating) {
        if(saving) {
            return;
        }

        saving = true;

        if(riddenEntryValue <= 0) {
            alert('Your value for the number of times ridden is invalid.');

            return;
        }

        if(riddenEntryValue < timesRidden) {
            var confirmLess = confirm('You entered a value for the times ridden field which was less then what was there before. Is that correct?');

            if(!confirmLess) {
                return false;
            }
        }

        var saveData = {rating: ratingEntryValue};

        if(typeof riddenEntryValue != 'undefined') {
            saveData.timesRidden = riddenEntryValue;
        }

        $.standardAjax({
            url:  '/track-record/' + getWhat(tr) + '/save/' + tr.attr('favorite'),
            data: saveData
        }, function() {
            showMessage('Your track record has been updated.');
            finish(tr, riddenEntryValue, ratingEntryValue);
        }, function() {
            release();
        });
    } else {
        alert('You have not made any changes.');
    }
}

function finish(tr, riddenVal, ratingVal) {
    tr.children('.editRecord').children('a').css('display', '');
    tr.children('.fav_ridden').html(riddenVal);
    tr.children('.fav_rating').html($('<div class="rating" rating="' + ratingVal + '"></div>').showRatingStars());

    getForwardButton(tr).text('Edit').unbind('click').on('click', function(e) {
        e.preventDefault();
        editRecord(tr);
    });

    getBackwardButton(tr).text('Remove').unbind('click').on('click', function(e) {
        e.preventDefault();
        removeRecord(tr);
    });

    working = false;
}

function release() {
    saving = false;
}

function editRecordOnLoad(id) {
    var tr = $('tr[favorite="' + id + '"]'),
        a  = tr.children('.editRecord').children('a:first');

    editRecord(tr);
}

function getForwardButton(tr) {
    return tr.children('.editRecord').children('a:first');
}

function getBackwardButton(tr) {
    return tr.children('.editRecord').children('a:last');
}

function getWhat(tr) {
    if(tr.attr('favorite-type') == 'coaster') {
        return 'coasters';
    } else {
        return 'parks';
    }
}