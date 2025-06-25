<?php
// AJAX handler functions for service filtering

// Add AJAX actions
add_action('wp_ajax_filter_services', 'filter_services_handler');
add_action('wp_ajax_nopriv_filter_services', 'filter_services_handler');

function filter_services_handler() {
    // Verify nonce for security
    check_ajax_referer('filter_services_nonce', 'nonce');

    $response = array();
    
    // Get filter parameters
    $service_types = isset($_POST['service_types']) ? json_decode(stripslashes($_POST['service_types'])) : array();
    $location = isset($_POST['location']) ? sanitize_text_field($_POST['location']) : '';
    $rating = isset($_POST['rating']) ? floatval($_POST['rating']) : 0;
    
    // Build query args
    $args = array(
        'post_type' => 'service_provider',
        'posts_per_page' => 10,
        'post_status' => 'publish'
    );

    // Add service type filter
    if (!empty($service_types)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'service_type',
            'field' => 'slug',
            'terms' => $service_types
        );
    }

    // Add location filter
    if (!empty($location)) {
        $args['meta_query'][] = array(
            'key' => 'provider_location',
            'value' => $location,
            'compare' => 'LIKE'
        );
    }

    // Add rating filter
    if ($rating > 0) {
        $args['meta_query'][] = array(
            'key' => 'provider_rating',
            'value' => $rating,
            'compare' => '>=',
            'type' => 'NUMERIC'
        );
    }

    $query = new WP_Query($args);
    $services = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $service = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'provider_name' => get_post_meta(get_the_ID(), 'provider_name', true),
                'rating' => get_post_meta(get_the_ID(), 'provider_rating', true),
                'price' => get_post_meta(get_the_ID(), 'service_price', true),
                'image' => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
                'permalink' => get_permalink()
            );
            $services[] = $service;
        }
        wp_reset_postdata();
    }

    $response['success'] = true;
    $response['services'] = $services;
    $response['count'] = count($services);

    wp_send_json($response);
}

// Function to register AJAX URL
function register_ajax_url() {
    ?>
    <script type="text/javascript">
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    </script>
    <?php
}
add_action('wp_head', 'register_ajax_url');
?> 