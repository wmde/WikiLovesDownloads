$( document ).ready( function() {

    var img = [
        {
            src: 'A_butterfly_feeding_on_the_tears_of_a_turtle_in_Ecuador.jpg',
            license: 'amalavida.tv [CC BY-SA 2.0 (http://creativecommons.org/licenses/by-sa/2.0)], via Wikimedia Commons'
        },

        {
            src: 'Penguin_in_Antarctica_jumping_out_of_the_water.jpg',
            license: 'Christopher Michel [CC BY 2.0 (http://creativecommons.org/licenses/by/2.0)], via Wikimedia Commons'
        },

        {
            src: 'Swallow_flying_drinking.jpg',
            license: 'By sanchezn (Own work) [CC BY-SA 3.0 (http://creativecommons.org/licenses/by-sa/3.0)], via Wikimedia Commons'
        },

        {
            src: 'Ніжний_ранковий_світло.jpg',
            license: 'By Balkhovitin (Own work) [CC BY-SA 3.0 (http://creativecommons.org/licenses/by-sa/3.0)], via Wikimedia Commons'
        },

        {
            src: 'Pair_of_Merops_apiaster_feeding.jpg',
            license: 'By Pierre Dalous (Own work) [CC BY-SA 3.0 (http://creativecommons.org/licenses/by-sa/3.0)], via Wikimedia Commons'
        },

        {
            src: 'Elakala_Waterfalls_Swirling_Pool_Mossy_Rocks.jpg',
            license: 'By Forest Wander from Cross Lanes, USA (Elakala Waterfalls Swirling Pool Mossy Rocks) [CC BY-SA 2.0 (http://creativecommons.org/licenses/by-sa/2.0)], via Wikimedia Commons'
        },

        {
            src: 'Cyanocitta-cristata-004.jpg',
            license: 'By Mdf (Own work) [GFDL (http://www.gnu.org/copyleft/fdl.html) or CC-BY-SA-3.0 (http://creativecommons.org/licenses/by-sa/3.0/)], via Wikimedia Commons'
        }
    ];

    var number = Math.floor( ( Math.random() * img.length ) );

    $( '#frontImage' ).css( 'background-image', 'url(res/img/' + img[ number ].src + ')' );
    $( '#license' ).text( 'Photo: ' + img[ number ].license );

} );