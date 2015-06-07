(function($) {
	$.fn.slideshow = function(opts) {
		var $container   = null,
				$current     = null,
				$next        = null,
				count        = 1,
				timeout      = 0,
				slides       = [],
				style        = '',
				valueCurrent = 0,
				valueNext    = 0,
				valueStage   = 0,
				options      = {
					slideClass:  'slideshow__slide',
					activeClass: 'slideshow__slide--active',
					waitTime:    5000,
					t: {
						type:      'slide',
						direction: 'left',
						duration:  500,
					}
				};

		var methods = {
			init: function() {
				$container.find('.' + options.slideClass).each(function(i, e) {
					var $s = slides[slides.length] = $(this);

					if($current && !$next)
						$next = $s;

					if($s.hasClass(options.activeClass))
						$current = $s;
				});

				if(!$next)
					return;

				methods.configureTransition();
				methods.setupEventHandlers();
				methods.waitToTransition();
			},
			configureTransition: function() {
				style        = options.t.direction,
				valueCurrent = $container.height();
				valueNext    = 0;

				if(options.t.type == 'fade') {
					style        = 'opacity';
					valueCurrent = valueStage = 0;
					valueNext    = 100;
					return;
				}

				switch(options.t.direction) {
					case 'up':
						style = 'top';
						break;
					case 'down':
						style = 'bottom';
						break;
					default:
						valueCurrent = $container.width();
				}

				valueStage    = valueCurrent;
				valueCurrent *= -1;
			},
			setupEventHandlers: function() {
				$container.on('mouseout', methods.waitToTransition);
				$container.on('mouseover', methods.stopWaitingToTransition);
			},
			waitToTransition: function() {
				if(timeout)
					return;

				if(!methods.stageNext())
					return;

				timeout = setTimeout(function() {
					methods.transitionToNext();
				}, options.waitTime);
			},
			stageNext: function() {
				if(!$next)
					return false;

				if(options.t.type == 'fade')
					return true;

				$next.css(style, valueStage + 'px').addClass(options.activeClass);

				return true;
			},
			stopWaitingToTransition: function() {
				if(!timeout)
					return;

				clearTimeout(timeout);
				timeout = 0;
			},
			transitionToNext: function() {
				methods.stopWaitingToTransition();

				var animateCurrent = {},
					  animateNext    = {};

				animateCurrent[style] = valueCurrent;
				$current.animate(animateCurrent, options.t.duration, function() {
					this.removeClass(options.activeClass);
				}.bind($current));

				animateNext[style] = valueNext;
				$next.animate(animateNext, options.t.duration);

				$current = $next;
				count    = ++count % slides.length;
				$next    = slides[count];

				methods.waitToTransition();
			}
		};

		options    = $.extend({}, options, opts);
		$container = $(this);

		methods.init();
	};
})(jQuery);