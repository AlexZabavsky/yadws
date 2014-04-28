/**
 * Scripts for administration area
 *
 * @package   YADWS
 * @license   GPL-2.0+
 * @link      https://github.com/AlexZabavsky/yadws
 *
 */

String.prototype.format = function() {
  var args = arguments;
  return this.replace( /{(\d+)}/g, function( match, number ) { 
      return typeof args[number] != 'undefined' ? args[number] : match;
  });
};

( function( $ ) {

    var _yadwsMedia = true,
        _originalAttachment = wp.media.editor.send.attachment,
        _slideCounter = $( '.yadws-admin-slide' ).length;
    
    console.log(_slideCounter);
    
    var refreshSlides = function() {
        $( '.yadws-admin-slide span' ).show();       
        $( '.yadws-admin-slide:first .yadws-up-slide' ).parent().hide();
        $( '.yadws-admin-slide:last .yadws-down-slide' ).parent().hide();
        $( '.yadws-up-image, .yadws-down-image' ).show().prev().show();
        $( '.yadws-admin-slide' ).each( function() {
            $( 'tr:first .yadws-up-image', this ).hide().prev().hide();
            $( 'tr:last .yadws-down-image', this ).hide().prev().hide();
            var slideNumber = $( '.yadws-admin-slide' ).index( this );
            $( '.yadws-slide-number', this ).html( slideNumber + 1 );
        });
    };
    
    refreshSlides();
    
    $( '.yadws-slides-container' ).on( 'click', '.button, .yadws-editable-image', function( e ) {

        var $button = $( this ),
        
        _yadwsMedia = true;

        wp.media.editor.send.attachment = function( props, attachment ) {            
            if ( _yadwsMedia ) {
                
                if ( $button.hasClass( 'yadws-editable-image' ) ) {  
                    
                    $button.attr( 'src', attachment.sizes.medium.url );
                    $button.closest( 'tr' ).find( '.yadws-image-input' ).val( attachment.id );
                    
                } else {
                
                    var $table = $button.siblings( 'table' ),
                        $lines = $table.find( 'tr' );
                        linesCount = $lines.length,
                        imageTemplate = $( '#yadws-image-template' ).html(),
                        slideId = $button.parent().data( 'slide-id' ),
                        imageId = 0;
                        
                    if ( linesCount > 0 ) {
                        imageId = $lines.last().data( 'line-id' ) + 1;
                    } else {
                        imageId = 1;
                    }
                    
                    var template = imageTemplate.format( slideId, imageId, attachment.id, attachment.sizes.medium.url, '' );                
                    
                    $table.find( 'tbody' ).append( template );
                    
                    refreshSlides();                
                }
            } else {
                return _originalAttachment.apply( this, [props, attachment] );
            }
        };        
        
        wp.media.editor.open($button);
      
        return false;
    });
    
    $( '.add_media' ).on( 'click', function() {
        _yadwsMedia = false;
    });   
    
    $( '.yadws-add-slide' ).click( function() {
        
        _slideCounter++;
        
        var slideTemplate = $( '#yadws-slide-template' ).html(),
            template = slideTemplate.format( _slideCounter, '' );
        
        $( '.yadws-slides-container' ).append( template );
        
        refreshSlides();
    });
    
    $( '.yadws-slides-container' ).on( 'click', '.yadws-delete-image', function( e ) {        
        if ( !confirm( 'Do you really want to delete this image?' ) ) {
            e.preventDefault();
        } else {
            $( this ).closest( 'tr' ).remove();
        }
        
        refreshSlides();
    });
    
    $( '.yadws-slides-container' ).on( 'click', '.yadws-delete-slide', function( e ) {        
        if ( !confirm( 'Do you really want to delete this slide?' ) ) {
            e.preventDefault();
        } else {
            $( this ).closest( '.yadws-admin-slide' ).remove();
        }        
        
        refreshSlides();
    });
    
    $( '.yadws-slides-container' ).on( 'click', '.yadws-up-slide', function( e ) {
        var currentSlide = $( this ).closest( '.yadws-admin-slide' );
        currentSlide.prev().before( currentSlide );
        refreshSlides();
    });
    
    $( '.yadws-slides-container' ).on( 'click', '.yadws-down-slide', function( e ) {
        var currentSlide = $( this ).closest( '.yadws-admin-slide' );
        currentSlide.next().after( currentSlide );
        refreshSlides();
    });

    $( '.yadws-slides-container' ).on( 'click', '.yadws-up-image', function( e ) {
        var currentRow = $( this ).closest( 'tr' );
        currentRow.prev().before( currentRow );
        refreshSlides();
    });    
    
    $( '.yadws-slides-container' ).on( 'click', '.yadws-down-image', function( e ) {
        var currentRow = $( this ).closest( 'tr' );
        currentRow.next().after( currentRow );
        refreshSlides();
    });
    
})(jQuery);