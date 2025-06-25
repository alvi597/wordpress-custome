<?php
ob_start();
// Register custom roles
function custom_register_roles() {
    add_role('customer', 'Customer');
    add_role('service_provider_pet_sitter', 'Pet Sitter');
    add_role('service_provider_tour_guide', 'Tour Guide');
    add_role('service_provider_tutor', 'Tutor');
}
add_action('after_switch_theme', 'custom_register_roles');

// Enqueue styles and scripts
function custom_enqueue_scripts() {
    wp_enqueue_style('custom-theme-style', get_stylesheet_uri());
    wp_enqueue_script('custom-reg-js', get_template_directory_uri() . '/reg.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'custom_enqueue_scripts');

// Registration handler (works for both page and static file)
function custom_handle_registration() {
    if (
        $_SERVER['REQUEST_METHOD'] === 'POST' &&
        isset($_POST['custom_register_nonce']) &&
        wp_verify_nonce($_POST['custom_register_nonce'], 'custom_register')
    ) {
        $user_type = sanitize_text_field($_POST['user_type']);
        $errors = array();

        if ($user_type === 'customer') {
            $first_name = sanitize_text_field($_POST['customer_first_name']);
            $last_name = sanitize_text_field($_POST['customer_last_name']);
            $email = sanitize_email($_POST['customer_email']);
            $password = $_POST['customer_password'];
            $country = sanitize_text_field($_POST['customer_country']);
            $interests = isset($_POST['customer_interests']) ? array_map('sanitize_text_field', $_POST['customer_interests']) : array();
            $agree = isset($_POST['agree']);

            if (!$first_name || !$last_name || !$email || !$password || !$country || !$agree) {
                $errors[] = 'Please fill all required fields and agree to the terms.';
            }
            if (email_exists($email)) {
                $errors[] = 'Email already registered.';
            }
            if (empty($errors)) {
                $user_id = wp_create_user($email, $password, $email);
                if (!is_wp_error($user_id)) {
                    wp_update_user(array(
                        'ID' => $user_id,
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'role' => 'customer'
                    ));
                    update_user_meta($user_id, 'country', $country);
                    update_user_meta($user_id, 'interests', $interests);
                    wp_set_current_user($user_id);
                    wp_set_auth_cookie($user_id);
                    wp_redirect(site_url('/dashboard-customer'));
                    exit;
                } else {
                    $errors[] = $user_id->get_error_message();
                }
            }
        }
        // Service Provider registration
        if ($user_type === 'provider') {
            $first_name = sanitize_text_field($_POST['provider_first_name']);
            $last_name = sanitize_text_field($_POST['provider_last_name']);
            $email = sanitize_email($_POST['provider_email']);
            $password = $_POST['provider_password'];
            $provider_role = sanitize_text_field($_POST['provider_role']);
            $city = sanitize_text_field($_POST['provider_city']);
            $country = sanitize_text_field($_POST['provider_country']);
            $experience = sanitize_text_field($_POST['provider_experience']);
            $bio = sanitize_textarea_field($_POST['provider_bio']);
            $categories = isset($_POST['provider_categories']) ? array_map('sanitize_text_field', $_POST['provider_categories']) : array();
            $agree = isset($_POST['agree']);

            if (!$first_name || !$last_name || !$email || !$password || !$provider_role || !$city || !$country || !$experience || !$bio || !$agree) {
                $errors[] = 'Please fill all required fields and agree to the terms.';
            }
            if (email_exists($email)) {
                $errors[] = 'Email already registered.';
            }
            if (empty($errors)) {
                $role_map = array(
                    'pet_sitter' => 'service_provider_pet_sitter',
                    'tour_guide' => 'service_provider_tour_guide',
                    'tutor' => 'service_provider_tutor'
                );
                $role = $role_map[$provider_role];
                $user_id = wp_create_user($email, $password, $email);
                if (!is_wp_error($user_id)) {
                    wp_update_user(array(
                        'ID' => $user_id,
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'role' => $role
                    ));
                    update_user_meta($user_id, 'city', $city);
                    update_user_meta($user_id, 'country', $country);
                    update_user_meta($user_id, 'experience', $experience);
                    update_user_meta($user_id, 'bio', $bio);
                    update_user_meta($user_id, 'categories', $categories);
                    wp_set_current_user($user_id);
                    wp_set_auth_cookie($user_id);
                    wp_redirect(site_url('/dashboard-provider'));
                    exit;
                } else {
                    $errors[] = $user_id->get_error_message();
                }
            }
        }
        // Show errors
        if (!empty($errors)) {
            add_action('wp_footer', function() use ($errors) {
                echo '<div style="color:red;text-align:center;margin-bottom:10px;">'.implode('<br>', $errors).'</div>';
            });
        }
    }
}
add_action('template_redirect', 'custom_handle_registration');
add_action('wp', 'custom_handle_registration'); // for static file

// Redirect after login based on role
function custom_login_redirect($redirect_to, $request, $user) {
    if (isset($user->roles) && is_array($user->roles)) {
        if (in_array('customer', $user->roles)) {
            return site_url('/dashboard-customer');
        } elseif (in_array('service_provider_pet_sitter', $user->roles) || in_array('service_provider_tour_guide', $user->roles) || in_array('service_provider_tutor', $user->roles)) {
            return site_url('/dashboard-provider');
        }
    }
    return $redirect_to;
}
add_filter('login_redirect', 'custom_login_redirect', 10, 3);

// ID Analyzer API Integration
define('ID_ANALYZER_API_KEY', 'hqCDQt689UjV9DJ1ZtNwfoEdMshy5Gw5');

function verify_provider_identity($user_id, $document_front, $document_back, $face_photo) {
    $api_key = ID_ANALYZER_API_KEY;
    
    // Create cURL request for document verification
    $ch = curl_init();
    $doc_data = array(
        'apikey' => $api_key,
        'file' => new CURLFile($document_front['tmp_name']),
        'face_detection' => 1,
        'check_expiry' => 1,
        'authenticate' => 1
    );

    curl_setopt($ch, CURLOPT_URL, 'https://api.idanalyzer.com/core/v1');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $doc_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    if ($response === false) {
        return new WP_Error('api_error', 'Failed to connect to ID Analyzer API');
    }
    
    $result = json_decode($response, true);
    
    if (isset($result['error'])) {
        return new WP_Error('verification_failed', $result['error']['message']);
    }
    
    // Store verification data
    update_user_meta($user_id, 'id_verification_data', $result);
    update_user_meta($user_id, 'verification_status', 'verified');
    update_user_meta($user_id, 'verified_name', $result['result']['firstName'] . ' ' . $result['result']['lastName']);
    update_user_meta($user_id, 'verified_dob', $result['result']['dob']);
    update_user_meta($user_id, 'verified_nationality', $result['result']['nationality']);
    update_user_meta($user_id, 'verified_document_number', $result['result']['documentNumber']);
    update_user_meta($user_id, 'verification_date', current_time('mysql'));
    
    // If face photo provided, do biometric verification
    if ($face_photo) {
        $ch = curl_init();
        $face_data = array(
            'apikey' => $api_key,
            'file' => new CURLFile($face_photo['tmp_name']),
            'photo1' => $result['result']['face']['url']
        );
        
        curl_setopt($ch, CURLOPT_URL, 'https://api.idanalyzer.com/face/v1');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $face_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $face_response = curl_exec($ch);
        curl_close($ch);
        
        $face_result = json_decode($face_response, true);
        
        if (!isset($face_result['error'])) {
            update_user_meta($user_id, 'face_verification', $face_result['similarity'] >= 0.8 ? 'verified' : 'failed');
            update_user_meta($user_id, 'face_similarity', $face_result['similarity']);
        }
    }
    
    return true;
}

// Add verification badge to provider name
function add_verification_badge($name, $user_id) {
    $verification_status = get_user_meta($user_id, 'verification_status', true);
    if ($verification_status === 'verified') {
        return $name . ' <span class="verified-badge" title="Verified Provider">✓</span>';
    }
    return $name;
}
add_filter('the_author', 'add_verification_badge', 10, 2);

// Webhook handler for verification status updates
add_action('rest_api_init', function() {
    register_rest_route('custom/v1', '/onfido-webhook', array(
        'methods' => 'POST',
        'callback' => 'handle_onfido_webhook',
        'permission_callback' => '__return_true'
    ));
});

function handle_onfido_webhook($request) {
    $payload = $request->get_json_params();
    
    if ($payload['payload']['resource_type'] === 'check' && isset($payload['payload']['object']['status'])) {
        $check_id = $payload['payload']['object']['id'];
        $status = $payload['payload']['object']['status'];
        
        // Find user by check ID
        $users = get_users(array(
            'meta_key' => 'onfido_check_id',
            'meta_value' => $check_id
        ));

        if (!empty($users)) {
            $user = $users[0];
            update_user_meta($user->ID, 'verification_status', $status);
            
            // Send email notification
            if ($status === 'complete') {
                wp_mail(
                    $user->user_email,
                    'Identity Verification Complete',
                    'Your identity verification has been completed. You can now access all provider features.'
                );
            }
        }
    }
    
    return new WP_REST_Response(array('status' => 'success'), 200);
}

// Redirect default WordPress login to custom login page
function custom_login_page_redirect() {
    $login_page = home_url('/login/');
    $page_viewed = basename($_SERVER['REQUEST_URI']);

    if($page_viewed == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET') {
        wp_redirect($login_page);
        exit;
    }
}
add_action('init', 'custom_login_page_redirect');

// Change all login page URLs to custom login page
function custom_login_page_url($login_url) {
    return home_url('/login/');
}
add_filter('login_url', 'custom_login_page_url');

// Modify login page links
function custom_login_page_links($url, $redirect) {
    if (empty($redirect)) {
        return home_url('/login/');
    }
    return add_query_arg('redirect_to', $redirect, home_url('/login/'));
}
add_filter('logout_url', 'custom_login_page_links', 10, 2);
add_filter('lostpassword_url', function($url) {
    return home_url('/login/?action=lostpassword');
});
?> 