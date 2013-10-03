(function($) {
  
    $.fn.yadws = function(option, settings) {

        if(typeof option === 'object') {
            settings = option;
        }
        else if(typeof option === 'string') {
            var values = [];
            var elements = this.each(function() {
                var data = $(this).data('_yawds');
                if(data) {
                    if($.fn.yadws.defaultSettings[option] !== undefined)
                    {
                        if(settings !== undefined) { data.settings[option] = settings; }
                        else { values.push(data.settings[option]); }
                    }
                }
            });
            if(values.length === 1) { return values[0]; }
            if(values.length > 0) { return values; }
            else { return elements; }
        }

        return this.each(function() {
            var $element = $(this);
            var $settings = $.extend({}, $.fn.yadws.defaultSettings, settings || {});          
            var carousel = new YAWDSlider($settings, $element);
            carousel.init();
            $element.data('_yawds', carousel);
        });
    }

    $.fn.yadws.defaultSettings = {
        url: null,
        str_show_all: 'Show All',
        thumbs_per_page: 3,
        category: '',
        layout: [3]
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
            this.$container = this.$element.find('.yadws_carousel_container');
            this.$inner = $('<div class="yadws_inner" />');
            this.$container.append(this.$inner);

            var $this = this;

            if(this.settings.layout.length > 1) {
                $this.$element.find('.yadws_btn_next,.yadws_btn_prev').bind('click', function(obj){ $this.carouselControls($this, obj.target); }).show();
            }

            $this.reloadCarousel($this);
        },

        /**
         * Performs the sliding animation, should be bound to click event of controlling elements
         *
         * TODO: Implement "linear" navigation, that doesn't loop.
         * TODO: If linear navigation is used, disable "Previous" control in the beginning and "Next" control at the end
         */
        carouselControls: function($this, obj) {
            var data = $this.$element.data('_yawds');
            var slide_width = data.$container.width();
            
            if($(obj).hasClass('yadws_btn_next')) {
                if(data.counter == $this.$inner.find('.yadws_slide').length-1) {
                    $this.$inner.find('.yadws_slide:last').after($this.$inner.find('.yadws_slide:first'));
                    $this.$inner.css('marginLeft', (slide_width*(-data.counter) + slide_width));
                } else {
                    data.counter++;
                }
            } else {
                if(data.counter == 0) {
                    $this.$inner.find('.$thisyadws_slide:first').before($this.$inner.find('.yadws_slide:last'));
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
         */
        reloadCarousel: function($this) {
            var url_params = '';

            if($this.settings.category) {
                url_params += '?byCategories='+$this.settings.category;
            }
          
            $.getJSON($this.settings.url+url_params, function(data) {
                var i = 0;
                $this.$inner.empty();

                $.each($this.settings.layout, function(slide_key, slide_data) {
                                        
                    var $slide = $('<div class="yadws_slide" />');
                    $this.$inner.append($slide);
                    
                    $.each(slide_data, function(row_key, items_amount) {
                        
                        var $row = $('<div class="yadws_row" />');
                        $slide.append($row);

                        for(i=0; i<items_amount; i++) {
                            var item_width = parseInt($this.$container.width() / items_amount);
                            var $item = $('<div class="yadws_item" />').width(item_width);
                            $row.append($item);
                            
                            var itemOuterWidth = $item.outerWidth();
                            if(itemOuterWidth > item_width) $item.width(item_width - (itemOuterWidth - item_width));
                            $row.width(item_width * items_amount);
                            $slide.width(item_width * items_amount);
                            var _i = i;
                            var $image = $('<img/>').attr('src',data.entries[i]['media$thumbnails'][0]['plfile$url'])
                                                    .width($item.width());
                            $item.append($image);
                        }
                    });
                });
          
                $this.$inner.width($this.$container.width() * $this.settings.layout.length);
            });
        },
    }
})(jQuery);

