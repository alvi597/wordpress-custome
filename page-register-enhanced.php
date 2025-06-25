<?php
/*
Template Name: Registration
*/
echo '<!-- Registration Template Loaded -->';
get_header();
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-6">
                    <i class="fas fa-globe-americas text-blue-600 text-3xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Join Immigrant Knowhow</h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Connect with trusted service providers and build your new life with confidence
                </p>
            </div>
            
            <!-- Registration Form -->
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <div class="p-8 lg:p-12">
                    <form method="post" enctype="multipart/form-data" id="main-reg-form" class="space-y-8">
                        <?php wp_nonce_field('custom_register', 'custom_register_nonce'); ?>
                        
                        <!-- User Type Selection -->
                        <div class="text-center">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Choose Your Path</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl mx-auto">
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="user_type" value="customer" checked class="sr-only peer">
                                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-200 rounded-2xl p-6 transition-all duration-300 peer-checked:border-blue-500 peer-checked:bg-gradient-to-br peer-checked:from-blue-100 peer-checked:to-blue-200 hover:shadow-lg">
                                        <div class="text-center">
                                            <i class="fas fa-search text-blue-600 text-3xl mb-4"></i>
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Looking for Services</h3>
                                            <p class="text-gray-600 text-sm">Find trusted providers for your immigration journey</p>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="user_type" value="provider" class="sr-only peer">
                                    <div class="bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-200 rounded-2xl p-6 transition-all duration-300 peer-checked:border-green-500 peer-checked:bg-gradient-to-br peer-checked:from-green-100 peer-checked:to-green-200 hover:shadow-lg">
                                        <div class="text-center">
                                            <i class="fas fa-hands-helping text-green-600 text-3xl mb-4"></i>
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Providing Services</h3>
                                            <p class="text-gray-600 text-sm">Help immigrants with your expertise and services</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Customer Fields -->
                        <div id="customer-fields" class="space-y-6">
                            <div class="text-center mb-6">
                                <h3 class="text-xl font-semibold text-gray-900">Customer Registration</h3>
                                <p class="text-gray-600">Join our community to find the services you need</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        First Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="customer_first_name" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-gray-50 focus:bg-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Last Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="customer_last_name" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-gray-50 focus:bg-white">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="customer_email" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-gray-50 focus:bg-white">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="customer_password" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-gray-50 focus:bg-white">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Current Location <span class="text-red-500">*</span>
                                </label>
                                <select name="customer_country" required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-gray-50 focus:bg-white">
                                    <option value="">Select your current location</option>
                                    <option value="us">🇺🇸 United States</option>
                                    <option value="uk">🇬🇧 United Kingdom</option>
                                    <option value="ca">🇨🇦 Canada</option>
                                    <option value="au">🇦🇺 Australia</option>
                                    <option value="de">🇩🇪 Germany</option>
                                    <option value="fr">🇫🇷 France</option>
                                    <option value="eu">🇪🇺 Other European Union</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    Select Your Interests <span class="text-gray-500">(Choose up to 5)</span>
                                </label>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    <label class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-all">
                                        <input type="checkbox" name="customer_interests[]" value="Legal & Immigration" class="text-blue-600 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Legal & Immigration</span>
                                    </label>
                                    <label class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-all">
                                        <input type="checkbox" name="customer_interests[]" value="Health & Wellness" class="text-blue-600 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Health & Wellness</span>
                                    </label>
                                    <label class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-all">
                                        <input type="checkbox" name="customer_interests[]" value="Financial Planning" class="text-blue-600 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Financial Planning</span>
                                    </label>
                                    <label class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-all">
                                        <input type="checkbox" name="customer_interests[]" value="Culture & Integration" class="text-blue-600 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Culture & Integration</span>
                                    </label>
                                    <label class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-all">
                                        <input type="checkbox" name="customer_interests[]" value="Housing & Transportation" class="text-blue-600 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Housing & Transport</span>
                                    </label>
                                    <label class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-all">
                                        <input type="checkbox" name="customer_interests[]" value="Career & Education" class="text-blue-600 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Career & Education</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Provider Fields -->
                        <div id="provider-fields" style="display:none;" class="space-y-6">
                            <div class="text-center mb-6">
                                <h3 class="text-xl font-semibold text-gray-900">Service Provider Registration</h3>
                                <p class="text-gray-600">Share your expertise and help immigrants succeed</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        First Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="provider_first_name" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all bg-gray-50 focus:bg-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Last Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="provider_last_name" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all bg-gray-50 focus:bg-white">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="provider_email" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all bg-gray-50 focus:bg-white">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="provider_password" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all bg-gray-50 focus:bg-white">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Service Type <span class="text-red-500">*</span>
                                </label>
                                <select name="provider_role" required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all bg-gray-50 focus:bg-white">
                                    <option value="">Select your service type</option>
                                    <option value="pet_sitter">🐕 Pet Sitter</option>
                                    <option value="tour_guide">🗺️ Tour Guide</option>
                                    <option value="tutor">📚 Tutor</option>
                                </select>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        City <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="provider_city" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all bg-gray-50 focus:bg-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Country <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="provider_country" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all bg-gray-50 focus:bg-white">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Years of Experience <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="provider_experience" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all bg-gray-50 focus:bg-white"
                                       placeholder="e.g., 5 years">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Short Bio <span class="text-red-500">*</span>
                                </label>
                                <textarea name="provider_bio" required rows="4" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all bg-gray-50 focus:bg-white"
                                          placeholder="Tell us about yourself and your services..."></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    Service Categories <span class="text-gray-500">(Select all that apply)</span>
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <label class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-green-50 hover:border-green-300 transition-all">
                                        <input type="checkbox" name="provider_categories[]" value="Pet Care" class="text-green-600 rounded focus:ring-green-500">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Pet Care</span>
                                    </label>
                                    <label class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-green-50 hover:border-green-300 transition-all">
                                        <input type="checkbox" name="provider_categories[]" value="Tourism" class="text-green-600 rounded focus:ring-green-500">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Tourism</span>
                                    </label>
                                    <label class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-green-50 hover:border-green-300 transition-all">
                                        <input type="checkbox" name="provider_categories[]" value="Tutoring" class="text-green-600 rounded focus:ring-green-500">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Tutoring</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Profile Image Upload -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Profile Image
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-green-400 transition-colors">
                                    <i class="fas fa-camera text-gray-400 text-2xl mb-2"></i>
                                    <input type="file" name="profile_image" accept=".jpg,.jpeg,.png" 
                                           class="hidden" id="profile-upload">
                                    <label for="profile-upload" class="cursor-pointer">
                                        <span class="text-green-600 hover:text-green-700 font-medium">Click to upload</span>
                                        <span class="text-gray-500"> or drag and drop</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1">JPG, PNG up to 5MB</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Terms Agreement -->
                        <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-xl">
                            <input type="checkbox" name="agree" required 
                                   class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label class="text-sm text-gray-700">
                                I agree to the <a href="<?php echo home_url('/terms'); ?>" class="text-blue-600 hover:text-blue-700 underline font-medium">Terms of Service</a> 
                                and <a href="<?php echo home_url('/privacy'); ?>" class="text-blue-600 hover:text-blue-700 underline font-medium">Privacy Policy</a>. 
                                I understand that service providers require identity verification before publishing listings. <span class="text-red-500">*</span>
                            </label>
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 px-6 rounded-xl font-semibold text-lg hover:from-blue-700 hover:to-blue-800 focus:ring-4 focus:ring-blue-200 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                            <i class="fas fa-user-plus mr-2"></i>
                            Create My Account
                        </button>
                        
                        <!-- Social Signup -->
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">Or continue with</span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <button type="button" 
                                    class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fab fa-google mr-2 text-red-500"></i>
                                Google
                            </button>
                            <button type="button" 
                                    class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fab fa-facebook-f mr-2 text-blue-600"></i>
                                Facebook
                            </button>
                        </div>
                        
                        <!-- Login Link -->
                        <div class="text-center pt-6 border-t border-gray-200">
                            <p class="text-gray-600">
                                Already have an account? 
                                <a href="<?php echo wp_login_url(); ?>" class="text-blue-600 hover:text-blue-700 font-semibold">Sign in here</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(function($){
    function toggleFields() {
        const userType = $('input[name="user_type"]:checked').val();
        if (userType === 'customer') {
            $('#customer-fields').show().find('input,select,textarea').prop('disabled', false);
            $('#provider-fields').hide().find('input,select,textarea').prop('disabled', true);
        } else {
            $('#customer-fields').hide().find('input,select,textarea').prop('disabled', true);
            $('#provider-fields').show().find('input,select,textarea').prop('disabled', false);
        }
    }
    
    $('input[name="user_type"]').change(toggleFields);
    toggleFields();
    
    // Limit interests selection to 5
    $('.checkbox-group input[type="checkbox"], input[name="customer_interests[]"]').on('change', function() {
        const max = 5;
        const checked = $('input[name="customer_interests[]"]:checked');
        if (checked.length > max) {
            this.checked = false;
            alert('You can select up to ' + max + ' interests only.');
        }
    });
    
    // File upload preview
    $('#profile-upload').on('change', function() {
        const label = $(this).siblings('label');
        if (this.files.length > 0) {
            label.html('<span class="text-green-600"><i class="fas fa-check mr-1"></i>' + this.files[0].name + '</span>');
        }
    });
    
    // Form validation
    $('#main-reg-form').on('submit', function(e) {
        const userType = $('input[name="user_type"]:checked').val();
        const agree = $('input[name="agree"]').is(':checked');
        
        if (!agree) {
            e.preventDefault();
            alert('Please agree to the Terms of Service and Privacy Policy.');
            return false;
        }
        
        // Additional validation can be added here
    });
});
</script>

<?php get_footer(); ?>
