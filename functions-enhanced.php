<?php
/**
 * Immigrant Knowhow Theme Functions
 * Enhanced with complete user registration and role management system
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme setup
function immigrant_knowhow_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('custom-logo');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'immigrant-knowhow'),
        'footer' => __('Footer Menu', 'immigrant-knowhow'),
    ));
}
add_action('after_setup_theme', 'immigrant_knowhow_setup');

// Enqueue scripts and styles
function immigrant_knowhow_scripts() {
    wp_enqueue_style('immigrant-knowhow-style', get_stylesheet_uri());
    wp_enqueue_style('tailwind-css', 'https://cdn.tailwindcss.com');
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
    
    wp_enqueue_script('jquery');
    wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), '3.9.1', true);
    
    // Localize script for AJAX
    wp_localize_script('jquery', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('immigrant_knowhow_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'immigrant_knowhow_scripts');

// Create custom user roles
function create_custom_user_roles() {
    // Customer Role
    add_role('customer', 'Customer', array(
        'read' => true,
        'browse_services' => true,
        'book_services' => true,
        'manage_bookings' => true
    ));
    
    // Service Provider Role
    add_role('service_provider', 'Service Provider', array(
        'read' => true,
        'create_listings' => true,
        'manage_listings' => true,
        'view_bookings' => true,
        'manage_profile' => true
    ));
    
    // Content Creator Role
    add_role('content_creator', 'Content Creator', array(
        'read' => true,
        'create_events' => true,
        'manage_events' => true,
        'upload_content' => true,
        'view_sales' => true,
        'manage_creator_profile' => true
    ));
}
add_action('init', 'create_custom_user_roles');

// Handle custom registration
function handle_custom_registration() {
    if (!isset($_POST['custom_register_nonce']) || !wp_verify_nonce($_POST['custom_register_nonce'], 'custom_register')) {
        return;
    }
    
    $user_type = sanitize_text_field($_POST['user_type']);
    $errors = array();
    
    if ($user_type === 'customer') {
        $first_name = sanitize_text_field($_POST['customer_first_name']);
        $last_name = sanitize_text_field($_POST['customer_last_name']);
        $email = sanitize_email($_POST['customer_email']);
        $password = $_POST['customer_password'];
        $country = sanitize_text_field($_POST['customer_country']);
        $interests = isset($_POST['customer_interests']) ? array_map('sanitize_text_field', $_POST['customer_interests']) : array();
        
        // Validate required fields
        if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
            $errors[] = 'Please fill in all required fields.';
        }
        
        if (!is_email($email)) {
            $errors[] = 'Please enter a valid email address.';
        }
        
        if (email_exists($email)) {
            $errors[] = 'An account with this email already exists.';
        }
        
        if (empty($errors)) {
            $user_id = wp_create_user($email, $password, $email);
            
            if (!is_wp_error($user_id)) {
                // Update user meta
                wp_update_user(array(
                    'ID' => $user_id,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'display_name' => $first_name . ' ' . $last_name,
                    'role' => 'customer'
                ));
                
                update_user_meta($user_id, 'user_type', 'customer');
                update_user_meta($user_id, 'country', $country);
                update_user_meta($user_id, 'interests', $interests);
                update_user_meta($user_id, 'registration_date', current_time('mysql'));
                
                // Auto login and redirect
                wp_set_current_user($user_id);
                wp_set_auth_cookie($user_id);
                wp_redirect(home_url('/dashboard-customer/'));
                exit;
            } else {
                $errors[] = 'Registration failed. Please try again.';
            }
        }
    } 
    elseif ($user_type === 'provider') {
        $first_name = sanitize_text_field($_POST['provider_first_name']);
        $last_name = sanitize_text_field($_POST['provider_last_name']);
        $email = sanitize_email($_POST['provider_email']);
        $password = $_POST['provider_password'];
        $role = sanitize_text_field($_POST['provider_role']);
        $city = sanitize_text_field($_POST['provider_city']);
        $country = sanitize_text_field($_POST['provider_country']);
        $experience = sanitize_text_field($_POST['provider_experience']);
        $bio = sanitize_textarea_field($_POST['provider_bio']);
        $categories = isset($_POST['provider_categories']) ? array_map('sanitize_text_field', $_POST['provider_categories']) : array();
        
        // Handle profile image upload
        $profile_image_url = '';
        if (!empty($_FILES['profile_image']['name'])) {
            $upload = wp_handle_upload($_FILES['profile_image'], array('test_form' => false));
            if (!isset($upload['error'])) {
                $profile_image_url = $upload['url'];
            }
        }
        
        // Validate required fields
        if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($role)) {
            $errors[] = 'Please fill in all required fields.';
        }
        
        if (!is_email($email)) {
            $errors[] = 'Please enter a valid email address.';
        }
        
        if (email_exists($email)) {
            $errors[] = 'An account with this email already exists.';
        }
        
        if (empty($errors)) {
            $user_id = wp_create_user($email, $password, $email);
            
            if (!is_wp_error($user_id)) {
                // Update user meta
                wp_update_user(array(
                    'ID' => $user_id,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'display_name' => $first_name . ' ' . $last_name,
                    'role' => 'service_provider'
                ));
                
                update_user_meta($user_id, 'user_type', 'provider');
                update_user_meta($user_id, 'provider_role', $role);
                update_user_meta($user_id, 'city', $city);
                update_user_meta($user_id, 'country', $country);
                update_user_meta($user_id, 'experience', $experience);
                update_user_meta($user_id, 'bio', $bio);
                update_user_meta($user_id, 'categories', $categories);
                update_user_meta($user_id, 'profile_image', $profile_image_url);
                update_user_meta($user_id, 'verification_status', 'pending');
                update_user_meta($user_id, 'registration_date', current_time('mysql'));
                
                // Auto login and redirect
                wp_set_current_user($user_id);
                wp_set_auth_cookie($user_id);
                wp_redirect(home_url('/dashboard-provider/'));
                exit;
            } else {
                $errors[] = 'Registration failed. Please try again.';
            }
        }
    }
    
    // Store errors in session for display
    if (!empty($errors)) {
        session_start();
        $_SESSION['registration_errors'] = $errors;
    }
}
add_action('init', 'handle_custom_registration');

// Handle content creator registration
function handle_content_creator_registration() {
    if (!isset($_POST['content_creator_nonce']) || !wp_verify_nonce($_POST['content_creator_nonce'], 'content_creator_register')) {
        return;
    }
    
    $errors = array();
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    $content_type = sanitize_text_field($_POST['content_type']);
    $bio = sanitize_textarea_field($_POST['bio']);
    $experience = sanitize_textarea_field($_POST['experience']);
    
    // Handle file uploads
    $profile_image_url = '';
    $sample_content_url = '';
    
    if (!empty($_FILES['profile_image']['name'])) {
        $upload = wp_handle_upload($_FILES['profile_image'], array('test_form' => false));
        if (!isset($upload['error'])) {
            $profile_image_url = $upload['url'];
        }
    }
    
    if (!empty($_FILES['sample_content']['name'])) {
        $upload = wp_handle_upload($_FILES['sample_content'], array('test_form' => false));
        if (!isset($upload['error'])) {
            $sample_content_url = $upload['url'];
        }
    }
    
    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($content_type)) {
        $errors[] = 'Please fill in all required fields.';
    }
    
    if (!is_email($email)) {
        $errors[] = 'Please enter a valid email address.';
    }
    
    if (email_exists($email)) {
        $errors[] = 'An account with this email already exists.';
    }
    
    if (empty($errors)) {
        $user_id = wp_create_user($email, $password, $email);
        
        if (!is_wp_error($user_id)) {
            // Update user meta
            wp_update_user(array(
                'ID' => $user_id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'display_name' => $first_name . ' ' . $last_name,
                'role' => 'content_creator'
            ));
            
            update_user_meta($user_id, 'user_type', 'content_creator');
            update_user_meta($user_id, 'content_type', $content_type);
            update_user_meta($user_id, 'bio', $bio);
            update_user_meta($user_id, 'experience', $experience);
            update_user_meta($user_id, 'profile_image', $profile_image_url);
            update_user_meta($user_id, 'sample_content', $sample_content_url);
            update_user_meta($user_id, 'approval_status', 'pending');
            update_user_meta($user_id, 'registration_date', current_time('mysql'));
            
            // Send admin notification
            $admin_email = get_option('admin_email');
            $subject = 'New Content Creator Registration - Approval Required';
            $message = "A new content creator has registered and requires approval.\n\n";
            $message .= "Name: {$first_name} {$last_name}\n";
            $message .= "Email: {$email}\n";
            $message .= "Content Type: {$content_type}\n\n";
            $message .= "Please review and approve in the admin panel.";
            
            wp_mail($admin_email, $subject, $message);
            
            // Auto login and redirect
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id);
            wp_redirect(home_url('/dashboard-creator/?pending=true'));
            exit;
        } else {
            $errors[] = 'Registration failed. Please try again.';
        }
    }
    
    // Store errors in session for display
    if (!empty($errors)) {
        session_start();
        $_SESSION['registration_errors'] = $errors;
    }
}
add_action('init', 'handle_content_creator_registration');

// Handle custom login
function handle_custom_login() {
    if (!isset($_POST['custom_login_nonce']) || !wp_verify_nonce($_POST['custom_login_nonce'], 'custom_login')) {
        return;
    }
    
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);
    
    $user = wp_authenticate($email, $password);
    
    if (!is_wp_error($user)) {
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID, $remember);
        
        // Role-based redirect
        $user_roles = $user->roles;
        if (in_array('customer', $user_roles)) {
            wp_redirect(home_url('/dashboard-customer/'));
        } elseif (in_array('service_provider', $user_roles)) {
            wp_redirect(home_url('/dashboard-provider/'));
        } elseif (in_array('content_creator', $user_roles)) {
            wp_redirect(home_url('/dashboard-creator/'));
        } else {
            wp_redirect(home_url('/'));
        }
        exit;
    } else {
        session_start();
        $_SESSION['login_error'] = 'Invalid email or password.';
    }
}
add_action('init', 'handle_custom_login');

// Add rewrite rules for custom pages
function add_custom_rewrite_rules() {
    add_rewrite_rule('^register/?$', 'index.php?pagename=register', 'top');
    add_rewrite_rule('^login/?$', 'index.php?pagename=login', 'top');
    add_rewrite_rule('^dashboard-customer/?$', 'index.php?pagename=dashboard-customer', 'top');
    add_rewrite_rule('^dashboard-provider/?$', 'index.php?pagename=dashboard-provider', 'top');
    add_rewrite_rule('^dashboard-creator/?$', 'index.php?pagename=dashboard-creator', 'top');
    add_rewrite_rule('^content-creator-register/?$', 'index.php?pagename=content-creator-register', 'top');
}
add_action('init', 'add_custom_rewrite_rules');

// Custom template loader
function custom_template_loader($template) {
    global $wp_query;
    
    $pagename = get_query_var('pagename');
    
    $custom_templates = array(
        'register' => 'page-register-enhanced.php',
        'login' => 'page-login.php',
        'dashboard-customer' => 'page-dashboard-customer.php',
        'dashboard-provider' => 'page-dashboard-provider.php',
        'dashboard-creator' => 'page-dashboard-creator.php',
        'content-creator-register' => 'page-content-creator-register.php'
    );
    
    if (isset($custom_templates[$pagename])) {
        $custom_template = locate_template($custom_templates[$pagename]);
        if ($custom_template) {
            return $custom_template;
        }
    }
    
    return $template;
}
add_filter('template_include', 'custom_template_loader');

// Initialize session
function init_session() {
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'init_session');

// Helper function to get registration errors
function get_registration_errors() {
    session_start();
    $errors = isset($_SESSION['registration_errors']) ? $_SESSION['registration_errors'] : array();
    unset($_SESSION['registration_errors']);
    return $errors;
}

// Helper function to get login error
function get_login_error() {
    session_start();
    $error = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : '';
    unset($_SESSION['login_error']);
    return $error;
}

// Flush rewrite rules on theme activation
function flush_rewrite_rules_on_activation() {
    add_custom_rewrite_rules();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'flush_rewrite_rules_on_activation');

?>
