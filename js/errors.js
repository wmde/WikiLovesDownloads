$( document ).ready(function() {

    $( '#form' ).submit(function( event ) {

        var category = $( '#category' ).val();
        var number = $( '#listQuantity' ).val();
        var names = $( '#names' ).val();
        var message;

        if ( category === '' || number === '' ) {
            event.preventDefault();
            $( '#errorBox' ).show();
            $( '.errorMessage' ).remove();

            if ( category === '' ) {
                message = '- Kategorie darf nicht leer sein!';
                errorGen( message );
            }

            if ( number === '' ) {
                message = '- Anzahl der Listen darf nicht leer sein!';
                errorGen( message );
            }

        } else {
            $( '#form' ).submit();
        }

        function errorGen( message ) {
            $( '<p>' )
                .text( message )
                .addClass( 'errorMessage' )
                .appendTo( $( '#errorBox' ) );
        }

    });

});