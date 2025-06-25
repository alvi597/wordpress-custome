<?php
/* Template Name: Settings */
get_header();

// Get current user
$current_user = wp_get_current_user();
if (!is_user_logged_in()) {
    wp_redirect(home_url('/login'));
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $user_id = $current_user->ID;
    $errors = array();
    $success = false;
    
    // Update basic info
    $user_data = array(
        'ID' => $user_id,
        'first_name' => sanitize_text_field($_POST['first_name']),
        'last_name' => sanitize_text_field($_POST['last_name']),
        'display_name' => sanitize_text_field($_POST['display_name']),
        'user_email' => sanitize_email($_POST['email'])
    );
    
    // Validate email
    if (!is_email($user_data['user_email'])) {
        $errors[] = "Please enter a valid email address.";
    }
    
    // Update password if provided
    if (!empty($_POST['new_password'])) {
        if (empty($_POST['confirm_password'])) {
            $errors[] = "Please confirm your new password.";
        } elseif ($_POST['new_password'] !== $_POST['confirm_password']) {
            $errors[] = "Passwords do not match.";
        } elseif (strlen($_POST['new_password']) < 8) {
            $errors[] = "Password must be at least 8 characters long.";
        } else {
            $user_data['user_pass'] = $_POST['new_password'];
        }
    }
    
    // Update user data if no errors
    if (empty($errors)) {
        $update_result = wp_update_user($user_data);
        
        if (is_wp_error($update_result)) {
            $errors[] = $update_result->get_error_message();
        } else {
            // Update additional profile fields
            update_user_meta($user_id, 'phone', sanitize_text_field($_POST['phone']));
            update_user_meta($user_id, 'address', sanitize_textarea_field($_POST['address']));
            update_user_meta($user_id, 'city', sanitize_text_field($_POST['city']));
            update_user_meta($user_id, 'state', sanitize_text_field($_POST['state']));
            update_user_meta($user_id, 'zip_code', sanitize_text_field($_POST['zip_code']));
            update_user_meta($user_id, 'country', sanitize_text_field($_POST['country']));
            update_user_meta($user_id, 'bio', sanitize_textarea_field($_POST['bio']));
            update_user_meta($user_id, 'date_of_birth', sanitize_text_field($_POST['date_of_birth']));
            update_user_meta($user_id, 'gender', sanitize_text_field($_POST['gender']));
            
            // Handle profile image upload
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['size'] > 0) {
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');
                
                $attachment_id = media_handle_upload('profile_image', 0);
                if (!is_wp_error($attachment_id)) {
                    update_user_meta($user_id, 'profile_image', $attachment_id);
                } else {
                    $errors[] = "Failed to upload profile image. Please try again.";
                }
            }
            
            $success = true;
        }
    }
    
    // Redirect to prevent form resubmission
    if ($success) {
        wp_redirect(add_query_arg('updated', 'true', get_permalink()));
    } else {
        wp_redirect(add_query_arg('errors', urlencode(implode(', ', $errors)), get_permalink()));
    }
    exit;
}

// Get current profile image
$profile_image_id = get_user_meta($current_user->ID, 'profile_image', true);
$profile_image_url = $profile_image_id ? wp_get_attachment_url($profile_image_id) : get_avatar_url($current_user->ID);

// Get additional profile data
$phone = get_user_meta($current_user->ID, 'phone', true);
$address = get_user_meta($current_user->ID, 'address', true);
$city = get_user_meta($current_user->ID, 'city', true);
$state = get_user_meta($current_user->ID, 'state', true);
$zip_code = get_user_meta($current_user->ID, 'zip_code', true);
$country = get_user_meta($current_user->ID, 'country', true);
$bio = get_user_meta($current_user->ID, 'bio', true);
$date_of_birth = get_user_meta($current_user->ID, 'date_of_birth', true);
$gender = get_user_meta($current_user->ID, 'gender', true);
?>

<div class="dashboard-container">
    <!-- Include the same sidebar as dashboard -->
    <?php include(get_template_directory() . '/template-parts/dashboard-sidebar.php'); ?>

    <main class="dashboard-main">
        <div class="dashboard-header">
            <div class="header-left">
                <h1>Settings</h1>
                <p class="text-subtitle">Manage your account settings and profile</p>
            </div>
        </div>

        <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            Your profile has been updated successfully!
        </div>
        <?php endif; ?>

        <?php if (isset($_GET['errors'])): ?>
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            <?php echo esc_html(urldecode($_GET['errors'])); ?>
        </div>
        <?php endif; ?>

        <div class="settings-grid">
            <!-- Profile Settings Card -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h2>Profile Information</h2>
                    <p class="card-subtitle">Update your personal information and profile details</p>
                </div>
                <form action="" method="POST" enctype="multipart/form-data" class="settings-form">
                    <div class="profile-image-upload">
                        <div class="current-image">
                            <img src="<?php echo esc_url($profile_image_url); ?>" alt="Profile Image" id="profile-preview">
                        </div>
                        <div class="upload-controls">
                            <label for="profile_image" class="btn btn-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7"></path>
                                    <line x1="16" y1="5" x2="22" y2="5"></line>
                                    <line x1="19" y1="2" x2="19" y2="8"></line>
                                    <circle cx="9" cy="9" r="2"></circle>
                                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                </svg>
                                Change Photo
                            </label>
                            <input type="file" id="profile_image" name="profile_image" accept="image/*" class="hidden-input">
                            <p class="help-text">Recommended: Square image, at least 200x200px</p>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name" value="<?php echo esc_attr($current_user->first_name); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" id="last_name" name="last_name" value="<?php echo esc_attr($current_user->last_name); ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="display_name">Display Name</label>
                        <input type="text" id="display_name" name="display_name" value="<?php echo esc_attr($current_user->display_name); ?>" required>
                        <p class="help-text">This is the name that will be displayed to other users</p>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="<?php echo esc_attr($current_user->user_email); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo esc_attr($phone); ?>">
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo esc_attr($date_of_birth); ?>">
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender">
                            <option value="">Select Gender</option>
                            <option value="male" <?php selected($gender, 'male'); ?>>Male</option>
                            <option value="female" <?php selected($gender, 'female'); ?>>Female</option>
                            <option value="other" <?php selected($gender, 'other'); ?>>Other</option>
                            <option value="prefer_not_to_say" <?php selected($gender, 'prefer_not_to_say'); ?>>Prefer not to say</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea id="bio" name="bio" rows="4" placeholder="Tell us about yourself..."><?php echo esc_textarea($bio); ?></textarea>
                        <p class="help-text">A brief description about yourself (optional)</p>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" rows="3" placeholder="Enter your address"><?php echo esc_textarea($address); ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" value="<?php echo esc_attr($city); ?>">
                        </div>
                        <div class="form-group">
                            <label for="state">State/Province</label>
                            <input type="text" id="state" name="state" value="<?php echo esc_attr($state); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="zip_code">ZIP/Postal Code</label>
                            <input type="text" id="zip_code" name="zip_code" value="<?php echo esc_attr($zip_code); ?>">
                        </div>
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" id="country" name="country" value="<?php echo esc_attr($country); ?>">
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Change Password</h3>
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" minlength="8">
                            <p class="help-text">Leave blank to keep current password. Minimum 8 characters.</p>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" minlength="8">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="update_profile" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                <polyline points="7 3 7 8 15 8"></polyline>
                            </svg>
                            Save Changes
                        </button>
                        <button type="reset" class="btn btn-secondary">Reset Form</button>
                    </div>
                </form>
            </div>

            <!-- Notification Settings Card -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h2>Notification Settings</h2>
                    <p class="card-subtitle">Manage your notification preferences</p>
                </div>
                <div class="notification-settings">
                    <div class="setting-item">
                        <div class="setting-info">
                            <h3>Email Notifications</h3>
                            <p>Receive email updates about your account activity</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h3>Service Updates</h3>
                            <p>Get notified about new services and features</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h3>Marketing Emails</h3>
                            <p>Receive promotional offers and updates</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h3>SMS Notifications</h3>
                            <p>Receive text messages for important updates</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
// Profile image preview
document.getElementById('profile_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profile-preview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Password confirmation validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('new_password').value;
    const confirm = this.value;
    
    if (password && confirm && password !== confirm) {
        this.setCustomValidity('Passwords do not match');
    } else {
        this.setCustomValidity('');
    }
});
</script>

<style>
/* Settings Grid */
.settings-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
}

/* Alert Styles */
.alert {
    padding: 1rem 1.5rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.alert-success {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
}

.alert-error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
}

.alert svg {
    width: 1.25rem;
    height: 1.25rem;
    flex-shrink: 0;
}

/* Form Styles */
.settings-form {
    padding: 1rem 0;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-section {
    margin: 2rem 0;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 0.5rem;
}

.form-section h3 {
    margin-bottom: 1rem;
    color: #1e293b;
    font-size: 1.125rem;
}

.profile-image-upload {
    display: flex;
    align-items: center;
    gap: 2rem;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 0.5rem;
}

.current-image {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #e2e8f0;
}

.current-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.upload-controls {
    flex: 1;
}

.hidden-input {
    display: none;
}

.help-text {
    font-size: 0.875rem;
    color: #64748b;
    margin-top: 0.25rem;
}

.error-text {
    font-size: 0.875rem;
    color: #dc2626;
    margin-top: 0.25rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e2e8f0;
}

/* Notification Settings */
.notification-settings {
    padding: 1rem 0;
}

.setting-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.setting-item:last-child {
    border-bottom: none;
}

.setting-info h3 {
    font-size: 1rem;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.setting-info p {
    font-size: 0.875rem;
    color: #64748b;
}

/* Switch Toggle */
.switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e1;
    transition: 0.3s;
    border-radius: 24px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: #3b82f6;
}

input:checked + .slider:before {
    transform: translateX(26px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .settings-grid {
        grid-template-columns: 1fr;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .profile-image-upload {
        flex-direction: column;
        text-align: center;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>

<?php get_footer(); ?> 