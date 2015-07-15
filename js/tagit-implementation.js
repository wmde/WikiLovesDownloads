$( document ).ready( function() {

    $( '#names' ).tagit( {
        allowSpaces: true
    } );

    $( '.ui-helper-hidden-accessible' ).hide();

    $( 'ul.tagit' )
        .focusin( function() {
        $( '#hint' ).show();
        } )
        .focusout( function() {
            $( '#hint' ).hide();
    } );

} );

