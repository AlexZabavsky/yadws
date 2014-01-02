//TODO: Implement AJAX requests handling

( function( $ ) {
  
    $.fn.yadws = function( option, settings ) {

        if ( typeof option === 'object' ) {
            settings = option;
        }
        else if ( typeof option === 'string' ) {
            var values = [];
            var elements = this.each( function() {
                var data = $( this ).data( '_yawds' );
                if ( data ) {
                    if ( $.fn.yadws.defaultSettings[option] !== undefined )
                    {
                        if ( settings !== undefined ) { 
                            data.settings[option] = settings; 
                        } else { 
                            values.push( data.settings[option] ); 
                        }
                    }
                }
            });
            
            if ( values.length === 1 ) { 
                return values[0]; 
            }
            
            if ( values.length > 0) { 
                return values; 
            } else { 
                return elements; 
            }
        }

        return this.each( function() {
            var $element = $(this);
            var $settings = $.extend({}, $.fn.yadws.defaultSettings, settings || {});          
            var carousel = new YAWDSlider($settings, $element);
            carousel.init();
            $element.data('_yawds', carousel);
        });
    };

    $.fn.yadws.defaultSettings = {
        layout: [3],
        type: 'slider',
        navigation: 'bullets'
    };
    
    function YAWDSlider(settings, $element) {
        this.carousel = null;
        this.counter = 0;
        this.settings = settings;
        this.$element = $element;
        return this;
    }

    YAWDSlider.prototype = {
      
        init: function() {
            this.$container = this.$element.find('.yadws-container');
            this.$inner = this.$element.find('.yadws-inner');
            this.$slides = this.$element.find('.yadws-slide');
            
            this.$inner.width(this.$container.width() * this.$slides.length);
            this.$inner.height(this.$container.height());

            this.$slides.removeClass('yadws-hidden');            
            
            var $this = this;

            if(this.$slides.length > 1) {
                if(this.settings.navigation.indexOf('arrows') >= 0) {
                    this.$element.find('.yadws-next,.yadws-prev').bind('click', function(obj){ $this.carouselControls($this, obj.target); }).show();
                }
                if(this.settings.navigation.indexOf('bullets') >= 0) {
                    //this.$element.find('.yadws-bullet').bind('click', function(obj){ $this.carouselControls($this, obj.target); });
                    this.$element.find('.yadws-bullet').show();
                }
            }
            
            console.log(this.settings);
        },

        /**
         * Performs sliding animation, should be bound to click event of controlling elements
         *
         * TODO: Implement "linear" navigation, that doesn't loop.
         * TODO: If linear navigation is used, disable "Previous" control in the beginning and "Next" control at the end
         */
        carouselControls: function($this, obj) {
            var data = $this.$element.data('_yawds'),
                slide_width = data.$container.width();
            
            if($(obj).hasClass('yadws-next')) {
                if(data.counter == $this.$inner.find('.yadws-slide').length-1) {
                    $this.$inner.find('.yadws-slide:last').after($this.$inner.find('.yadws-slide:first'));
                    $this.$inner.css('marginLeft', (slide_width*(-data.counter) + slide_width));
                } else {
                    data.counter++;
                }
            } else {
                if(data.counter == 0) {
                    $this.$inner.find('.yadws-slide:first').before($this.$inner.find('.yadws-slide:last'));
                    $this.$inner.css('marginLeft', (-slide_width));
                } else {
                    data.counter--;
                }
            }
            
            $this.$inner.animate({
              'marginLeft' : slide_width*(-data.counter)
            });
        },

        /**
         * Performs sliding animation, should be bound to click event of controlling elements
         *
         * TODO: Implement "linear" navigation, that doesn't loop.
         * TODO: If linear navigation is used, disable "Previous" control in the beginning and "Next" control at the end
         */
        carouselControls: function($this, obj) {
            var data = $this.$element.data('_yawds');
            var slide_width = data.$container.width();
            
            if($(obj).hasClass('yadws-next')) {
                if(data.counter == $this.$inner.find('.yadws-slide').length-1) {
                    $this.$inner.find('.yadws-slide:last').after($this.$inner.find('.yadws-slide:first'));
                    $this.$inner.css('marginLeft', (slide_width*(-data.counter) + slide_width));
                } else {
                    data.counter++;
                }
            } else {
                if(data.counter == 0) {
                    $this.$inner.find('.yadws-slide:first').before($this.$inner.find('.yadws-slide:last'));
                    $this.$inner.css('marginLeft', (-slide_width));
                } else {
                    data.counter--;
                }
            }
            
            $this.$inner.animate({
              'marginLeft' : slide_width*(-data.counter)
            });
        },
        
        /**
         * Makes AJAX request
         * @deprecated
         */
        makeAJAXRequest: function($this) {
            var url_params = '';

            if($this.settings.category) {
                url_params += '?byCategories='+$this.settings.category;
            }
          
            $.getJSON($this.settings.url+url_params, function(data) {
                var i = 0;
                $this.$inner.empty();

                $.each($this.settings.layout, function(slide_key, slide_data) {
                                        
                    var $slide = $('<div class="yadws-slide" />');
                    $this.$inner.append($slide);
                    
                    $.each(slide_data, function(row_key, items_amount) {
                        
                        var $row = $('<div class="yadws-row" />');
                        $slide.append($row);

                        for(i=0; i<items_amount; i++) {
                            var item_width = parseInt($this.$container.width() / items_amount);
                            var $item = $('<div class="yadws-item" />').width(item_width);
                            $row.append($item);
                            
                            var itemOuterWidth = $item.outerWidth();
                            if(itemOuterWidth > item_width) $item.width(item_width - (itemOuterWidth - item_width));
                            $row.width(item_width * items_amount);
                            $slide.width(item_width * items_amount);
                            var _i = i;
                            var $image = $('<img/>').attr('src',data.entries[i]['url'])
                                                    .width($item.width());
                            $item.append($image);
                        }
                    });
                });
          
                $this.$inner.width($this.$container.width() * $this.settings.layout.length);
            });
        },
    };
})(jQuery);

