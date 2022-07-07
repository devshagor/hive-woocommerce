<?php 
/**
 * Load next 12 products using AJAX
 */
function ajax_next_posts() {
    global $product;
    // Build Query
        $args = array(
            'post_type'             =>  'product',
            'posts_per_page'        =>  (int)$_POST['posts_per_page'],
            'orderby'               =>  'title',
            'order'                 =>  'ASC',
            'offset'                =>  (int)$_POST['post_offset'],
        );
    
    if( !empty( $_POST['product_cat'] ) ) {
        $args['tax_query'] = array(
            'relation'  => 'AND',
                array (
                    'taxonomy'  =>  'product_cat',
                    'field' =>  'slug',
                    'terms' => $_POST['product_cat'],
                    'operator'  =>  'IN'
                ),
        );
    }
    
    $count_results = '0';
    
    $ajax_query = new WP_Query( $args );
    
    // Results found
    if( $ajax_query->have_posts() ){
    
        // Start "saving" results' HTML
        $results_html = '';
        ob_start();
    
        while( $ajax_query->have_posts() ) {
    
            $ajax_query->the_post();
            echo wc_get_template_part( 'content', 'product' );
    
        }
        wp_reset_postdata();
    
        // "Save" results' HTML as variable
        $results_html = ob_get_clean();
    
    } else {
    
        // Start "saving" results' HTML
        $results_html = '';
        ob_start();
    
        echo "none found!";
    
        // "Save" results' HTML as variable
        $results_html = ob_get_clean();
    
    }
    
    // Build ajax response
    $response = array();
    
    // 1. value is HTML of new posts and 2. is total count of posts
    array_push ( $response, $results_html );
    echo json_encode( $response );
    
    // Always use die() in the end of ajax functions
    die();
}

add_action('wp_ajax_ajax_next_posts', 'ajax_next_posts');
add_action('wp_ajax_nopriv_ajax_next_posts', 'ajax_next_posts');