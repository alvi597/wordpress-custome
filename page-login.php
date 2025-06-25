<?php
/*
Template Name: Login
*/

// Handle login if user is already logged in
if (is_user_logged_in()) {
    $user = wp_get_current_user();
    if (in_array('customer', $user->roles)) {
        wp_redirect(home_url('/dashboard-customer/'));
    } elseif (in_array('service_provider', $user->roles)) {
        wp_redirect(home_url('/dashboard-provider/'));
    } elseif (in_array('content_creator', $user->roles)) {
        wp_redirect(home_url('/dashboard-creator/'));
    } else {
        wp_redirect(home_url('/'));
    }
    exit;
}

get_header();

$login_error = get_login_error();
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-20 w-20 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                <i class="fas fa-globe-americas text-blue-600 text-3xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Welcome Back
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Sign in to your Immigrant Knowhow account
            </p>
        </div>
        
        <?php if (!empty($login_error)): ?>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <i class="fas fa-exclamation-circle text-red-400 mr-3 mt-0.5"></i>
                    <div class="text-sm text-red-700"><?php echo esc_html($login_error); ?></div>
                </div>
            </div>
        <?php endif; ?>
        
        <form class="mt-8 space-y-6" method="post">
            <?php wp_nonce_field('custom_login', 'custom_login_nonce'); ?>
            
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>
                    <input id="email" name="email" type="email" required 
                           class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                           placeholder="Enter your email address">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <input id="password" name="password" type="password" required 
                           class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                           placeholder="Enter your password">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-900">
                        Remember me
                    </label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                        Forgot your password?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-sign-in-alt text-blue-500 group-hover:text-blue-400"></i>
                    </span>
                    Sign In
                </button>
            </div>
            
            <!-- Social Login -->
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Or continue with</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <button type="button" 
                            class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <i class="fab fa-google text-red-500 mr-2"></i>
                        Google
                    </button>
                    <button type="button" 
                            class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <i class="fab fa-facebook-f text-blue-600 mr-2"></i>
                        Facebook
                    </button>
                </div>
            </div>
            
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account? 
                    <a href="<?php echo home_url('/register/'); ?>" class="font-medium text-blue-600 hover:text-blue-500">
                        Sign up here
                    </a>
                </p>
                <p class="text-xs text-gray-500 mt-2">
                    Are you a content creator? 
                    <a href="<?php echo home_url('/content-creator-register/'); ?>" class="font-medium text-blue-600 hover:text-blue-500">
                        Register here
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<?php get_footer(); ?>
