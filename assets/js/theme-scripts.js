jQuery( function ($) {
    "use strict";

    //#! Filter search results
    $( '#js-sort-results' ).on( 'change', function () {
        var formID = $( this ).attr( 'data-form-id' );
        if ( formID ) {
            $( '#' + formID ).trigger( 'submit' );
        }
    } );

    //#! [Responsive] Toggle nav menu
    $( '.js-toggle-menu' ).on( 'click', function (ev) {
        ev.preventDefault();
        $( '.topnav' ).toggleClass( 'responsive' );
    } );

} );
