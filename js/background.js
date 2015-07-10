$( document ).ready(function() {

    var img = [ 'A_butterfly_feeding_on_the_tears_of_a_turtle_in_Ecuador.jpg',
        'Penguin_in_Antarctica_jumping_out_of_the_water.jpg',
        'Swallow_flying_drinking.jpg',
        'Ніжний_ранковий_світло.jpg',
        'Pair_of_Merops_apiaster_feeding.jpg',
        'Elakala_Waterfalls_Swirling_Pool_Mossy_Rocks.jpg',
        'Cyanocitta-cristata-004.jpg' ];

    var license = [ 'amalavida.tv [CC BY-SA 2.0 (http://creativecommons.org/licenses/by-sa/2.0)], via Wikimedia Commons',
        'Christopher Michel [CC BY 2.0 (http://creativecommons.org/licenses/by/2.0)], via Wikimedia Commons',
        'By sanchezn (Own work) [CC BY-SA 3.0 (http://creativecommons.org/licenses/by-sa/3.0)], via Wikimedia Commons',
        'By Balkhovitin (Own work) [CC BY-SA 3.0 (http://creativecommons.org/licenses/by-sa/3.0)], via Wikimedia Commons',
        'By Pierre Dalous (Own work) [CC BY-SA 3.0 (http://creativecommons.org/licenses/by-sa/3.0)], via Wikimedia Commons',
        'By Forest Wander from Cross Lanes, USA (Elakala Waterfalls Swirling Pool Mossy Rocks) [CC BY-SA 2.0 (http://creativecommons.org/licenses/by-sa/2.0)], via Wikimedia Commons',
        'By Mdf (Own work) [GFDL (http://www.gnu.org/copyleft/fdl.html) or CC-BY-SA-3.0 (http://creativecommons.org/licenses/by-sa/3.0/)], via Wikimedia Commons' ];

    var github = 'View Source on Github';
    var authors = 'Authors: Andrew Pekarek-Kostka & Kai Nissen'

    var number = Math.floor( ( Math.random() * img.length) );

    $( '#frontImage' ).css( 'background-image', 'url(res/img/' + img[ number ] + ')' );
    $( '#license' ).text( 'Photo: ' + license[ number ] );
    $( '#github' ).text( github );
    $( '#authors' ).text( authors );

});