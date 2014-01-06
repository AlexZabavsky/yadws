//TODO: Implement AJAX requests handling

( function( $ ) {
  
    function YAWDSlider( settings, $element ) {
        this.carousel = null;
        this.counter = 0;
        this.settings = settings;
        this.$element = $element;
        return this;
    }

    YAWDSlider.prototype = {
      
        init: function() {
            this.$container = this.$element.find( '.yadws-container' );
            this.$inner = this.$element.find( '.yadws-inner' );
            this.$slides = this.$element.find( '.yadws-slide' );
            
            this.$inner.width( this.$container.width() * this.$slides.length );
            this.$inner.height( this.$container.height() );

            this.$slides.removeClass( 'yadws-hidden' );            
            
            var $this = this;

            if ( this.$slides.length > 1 ) {
                if ( this.settings.navigation.indexOf( 'arrows' ) >= 0 ) {
                    this.$element.find( '.yadws-next,.yadws-prev' ).bind( 'click', function( obj ) { $this.arrowControls( $this, obj.target ); } ).show();
                }
                if ( this.settings.navigation.indexOf( 'bullets' ) >= 0 ) {
                    this.$element.find( '.yadws-bullet' ).bind( 'click', function( obj ){ $this.bulletControls( $this, obj.target ); } ).show();
                }
            }
            
            this.updateBullets( $this, 0 );
        },
        
        /**
         * Performs sliding animation, should be bound to click event of controlling elements
         */        
        updateBullets: function( $this, id ) {
            $( '.yadws-bullet', $this.$element ).removeClass( 'yadws_active' );
            $( '[data-bullet-id="' + id + '"]', $this.$element ).addClass( 'yadws_active' );
        },
        
        /**
         * Performs sliding animation, should be bound to click event of controlling elements
         *
         * TODO: Implement "linear" navigation, that doesn't loop.
         * TODO: If linear navigation is used, disable "Previous" control in the beginning and "Next" control at the end
         */
        arrowControls: function( $this, obj ) {
            var data = $this.$element.data( '_yawds' ),
                slideWidth = data.$container.width();
            
            var $slide = {};
            
            if ( $( obj ).hasClass( 'yadws-next' ) ) {
                if ( data.counter == $this.$inner.find( '.yadws-slide' ).length - 1 ) {
                    $slide = $this.$inner.find( '.yadws-slide:last' );
                    $slide.after( $this.$inner.find( '.yadws-slide:first' ) );
                    $this.$inner.css( 'marginLeft', ( slideWidth * ( -data.counter ) + slideWidth ) );
                } else {
                    data.counter++;
                }
            } else {
                if(data.counter == 0) {
                    $slide = $this.$inner.find( '.yadws-slide:first' );
                    $slide.before( $this.$inner.find('.yadws-slide:last') );
                    $this.$inner.css( 'marginLeft', ( -slideWidth ) );
                } else {
                    data.counter--;
                }
            }
            
            var slides = $( '.yadws-slide', $this.$inner );
            $this.updateBullets( $this, $( slides[data.counter] ).data( 'slideId' ) );
            $this.$inner.animate( { 'marginLeft' : slideWidth * ( -data.counter ) } );
        },
        
        /**
         * Performs sliding animation, should be bound to click event of controlling elements
         */
        bulletControls: function( $this, obj ) {   
            
            var data = $this.$element.data( '_yawds' ),
                slideWidth = data.$container.width(),
                bulletId = $( obj ).data( 'bulletId' ),
                innerWidth = $this.$inner.width();

            while ( $( $( '.yadws-slide', $this.$inner )[0] ).data( 'slideId' ) != 0 ) {                    
                var $slide = $( '.yadws-slide:last', $this.$inner ),
                    margin = parseInt( $this.$inner.css( 'marginLeft' ), 10 );
                
                $slide.after( $( '.yadws-slide:first', $this.$inner ) );
                
                if ( margin >= 0 ) {
                    $this.$inner.css( 'marginLeft', ( - innerWidth + slideWidth ) + 'px' );
                } else {
                    $this.$inner.css( 'marginLeft', ( margin + slideWidth ) + 'px' );
                }
            } 
            $this.$inner.animate( { 'marginLeft' : slideWidth * ( -bulletId ) } );
            data.counter = bulletId;
            $this.updateBullets( $this, bulletId );
        }        
    };
    
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
            var $element = $( this ),
                $settings = $.extend( {}, $.fn.yadws.defaultSettings, settings || {} ),          
                slider = new YAWDSlider( $settings, $element );
            
            slider.init();
            $element.data( '_yawds', slider );
        });
    };

    $.fn.yadws.defaultSettings = {
        layout: [3],
        type: 'slider',
        navigation: 'bullets'
    };
    
})(jQuery);