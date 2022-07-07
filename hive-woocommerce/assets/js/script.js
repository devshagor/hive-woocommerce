// How many do you want to load each button click?
var postsPerPage = 12;

// How many posts there's total
var totalPosts = parseInt( jQuery( '#found-posts' ).text() );

// if( totalPosts == postOffset ) {
//  jQuery( '#load-more' ).fadeOut();
// }

$('#load-more').click( function(e){
    e.preventDefault();
    // Get current category
    var cat_id  =   $(this).data('product-category');
    ajax_next_posts( cat_id );
    $('body').addClass('ajaxLoading');
});

var ajaxLock = false; // ajaxLock is just a flag to prevent double clicks and spamming

if( !ajaxLock ) {

    function ajax_next_posts( cat_id ) {

        ajaxLock = true;

        // How many have been loaded
        var postOffset = jQuery( 'li.product' ).length;

        // Ajax call itself
        $.ajax({
            method: 'POST',
            url: leafshop.ajax_url,
            data: {
                action: 'ajax_next_posts',
                post_offset: postOffset,
                posts_per_page: postsPerPage,
                product_cat: cat_id
            },
            dataType: 'json'
        })
        .done( function( response ) { // Ajax call is successful

            // Add new posts
            jQuery( '.product-grid' ).append( response[0] );

            // Update Post Offset
            var postOffset = jQuery( 'li.product' ).length;

            ajaxLock = false;

            console.log( 'Success' );

            $('body').removeClass('ajaxLoading');

            // How many posts there's total
            console.log( "Total Posts: " + totalPosts );

            // How many have been loaded
            var postOffset = jQuery( 'li.product' ).length
            console.log( "Posts on Page: " + postOffset );

            // Hide button if all posts are loaded
            if( ( totalPosts - postOffset ) <= 0 ) {
                jQuery( '#load-more' ).fadeOut();
            }

        })
        // .fail( function() {
        .fail( function(jqXHR, textStatus, errorThrown) { 
            // Ajax call is not successful, still remove lock in order to try again

            ajaxLock = false;

            console.log(XMLHttpRequest);
            console.log(textStatus);
            console.log(errorThrown);

            console.log( 'Failed' );

        });
    }
}