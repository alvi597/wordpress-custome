<?php
/*
Template Name: Content Creator Registration
*/

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content_creator_nonce']) && wp_verify_nonce($_POST['content_creator_nonce'], 'content_creator_register')) {
    $errors = array();
    
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    $content_type = sanitize_text_field($_POST['content_type']);
    $bio = sanitize_textarea_field($_POST['bio']);
    $experience = sanitize_textarea_field($_POST['experience']);
    $agree = isset($_POST['agree']);
    
    // Validation
    if (!$first_name || !$last_name || !$email || !$password || !$content_type || !$bio || !$agree) {
        $errors[] = 'Please fill all required fields and agree to the terms.';
    }
    
    if (email_exists($email)) {
        $errors[] = 'Email already registered.';
    }
    
    if (empty($errors)) {
        $user_id = wp_create_user($email, $password, $email);
        if (!is_wp_error($user_id)) {
            // Add content creator role
            add_role('content_creator', 'Content Creator', array(
                'read' => true,
                'create_events' => true,
                'manage_content' => true
            ));
            
            wp_update_user(array(
                'ID' => $user_id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'display_name' => $first_name . ' ' . $last_name,
                'role' => 'content_creator'
            ));
            
            // Save additional meta
            update_user_meta($user_id, 'content_type', $content_type);
            update_user_meta($user_id, 'bio', $bio);
            update_user_meta($user_id, 'experience', $experience);
            update_user_meta($user_id, 'approval_status', 'pending');
            
            // Handle file uploads
            if (!empty($_FILES['sample_content']['name'])) {
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                $sample = $_FILES['sample_content'];
                $sample_upload = wp_handle_upload($sample, array('test_form' => false));
                if (!isset($sample_upload['error'])) {
                    update_user_meta($user_id, 'sample_content', $sample_upload['url']);
                }
            }
            
            if (!empty($_FILES['profile_image']['name'])) {
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                $profile = $_FILES['profile_image'];
                $profile_upload = wp_handle_upload($profile, array('test_form' => false));
                if (!isset($profile_upload['error'])) {
                    update_user_meta($user_id, 'profile_image', $profile_upload['url']);
                }
            }
            
            // Send notification to admin
            wp_mail(
                get_option('admin_email'),
                'New Content Creator Registration',
                "A new content creator has registered and requires approval.\n\nName: {$first_name} {$last_name}\nEmail: {$email}\nContent Type: {$content_type}"
            );
            
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id);
            wp_redirect(site_url('/dashboard-creator?pending=1'));
            exit;
        } else {
            $errors[] = $user_id->get_error_message();
        }
    }
}

get_header();
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-white py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                    <i class="fas fa-user-edit text-blue-600 text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Content Creator Registration</h1>
                <p class="text-gray-600">Join our platform to create and monetize your educational content</p>
            </div>
            
            <!-- Registration Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <?php if (!empty($errors)): ?>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                            <h4 class="text-red-800 font-semibold">Registration Error</h4>
                        </div>
                        <ul class="mt-2 text-red-700">
                            <?php foreach ($errors as $error): ?>
                                <li>• <?php echo esc_html($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form method="post" enctype="multipart/form-data" class="space-y-6">
                    <?php wp_nonce_field('content_creator_register', 'content_creator_nonce'); ?>
                    
                    <!-- Personal Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="first_name" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                   value="<?php echo isset($_POST['first_name']) ? esc_attr($_POST['first_name']) : ''; ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="last_name" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                   value="<?php echo isset($_POST['last_name']) ? esc_attr($_POST['last_name']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                               value="<?php echo isset($_POST['email']) ? esc_attr($_POST['email']) : ''; ?>">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                               minlength="8">
                        <p class="text-sm text-gray-500 mt-1">Minimum 8 characters</p>
                    </div>
                    
                    <!-- Content Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Type of Content <span class="text-red-500">*</span>
                        </label>
                        <select name="content_type" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <option value="">Select content type</option>
                            <option value="events" <?php echo (isset($_POST['content_type']) && $_POST['content_type'] === 'events') ? 'selected' : ''; ?>>Events Only</option>
                            <option value="downloadables" <?php echo (isset($_POST['content_type']) && $_POST['content_type'] === 'downloadables') ? 'selected' : ''; ?>>Downloadables Only</option>
                            <option value="both" <?php echo (isset($_POST['content_type']) && $_POST['content_type'] === 'both') ? 'selected' : ''; ?>>Both Events & Downloadables</option>
                        </select>
                    </div>
                    
                    <!-- Bio -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Description/Bio <span class="text-red-500">*</span>
                        </label>
                        <textarea name="bio" required rows="4" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                  placeholder="Tell us about yourself and the content you create..."><?php echo isset($_POST['bio']) ? esc_textarea($_POST['bio']) : ''; ?></textarea>
                    </div>
                    
                    <!-- Experience -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Experience or Specialization <span class="text-red-500">*</span>
                        </label>
                        <textarea name="experience" required rows="3" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                  placeholder="Describe your experience, qualifications, or areas of specialization..."><?php echo isset($_POST['experience']) ? esc_textarea($_POST['experience']) : ''; ?></textarea>
                    </div>
                    
                    <!-- File Uploads -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Sample Content (PDF/Image)
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                                <input type="file" name="sample_content" accept=".pdf,.jpg,.jpeg,.png" 
                                       class="hidden" id="sample-upload">
                                <label for="sample-upload" class="cursor-pointer">
                                    <span class="text-blue-600 hover:text-blue-700">Click to upload</span>
                                    <span class="text-gray-500"> or drag and drop</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG up to 10MB</p>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Profile Image/Logo
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                                <i class="fas fa-user-circle text-gray-400 text-2xl mb-2"></i>
                                <input type="file" name="profile_image" accept=".jpg,.jpeg,.png" 
                                       class="hidden" id="profile-upload">
                                <label for="profile-upload" class="cursor-pointer">
                                    <span class="text-blue-600 hover:text-blue-700">Click to upload</span>
                                    <span class="text-gray-500"> or drag and drop</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-1">JPG, PNG up to 5MB</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Terms Agreement -->
                    <div class="flex items-start space-x-3">
                        <input type="checkbox" name="agree" required 
                               class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label class="text-sm text-gray-700">
                            I agree to the <a href="<?php echo home_url('/terms'); ?>" class="text-blue-600 hover:text-blue-700 underline">Terms of Service</a>, 
                            <a href="<?php echo home_url('/privacy'); ?>" class="text-blue-600 hover:text-blue-700 underline">Privacy Policy</a>, 
                            and understand that my content requires admin approval before publication. <span class="text-red-500">*</span>
                        </label>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all">
                        <i class="fas fa-user-plus mr-2"></i>
                        Create Content Creator Account
                    </button>
                </form>
                
                <!-- Login Link -->
                <div class="text-center mt-6 pt-6 border-t border-gray-200">
                    <p class="text-gray-600">
                        Already have an account? 
                        <a href="<?php echo home_url('/login'); ?>" class="text-blue-600 hover:text-blue-700 font-semibold">Sign in here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // File upload preview
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const label = this.parentElement.querySelector('label');
            if (this.files.length > 0) {
                label.innerHTML = `<span class="text-green-600"><i class="fas fa-check mr-1"></i>${this.files[0].name}</span>`;
            }
        });
    });
});
</script>

<?php get_footer(); ?>
