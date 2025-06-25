<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Chart.js for dashboards -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <?php wp_head(); ?>
    
    <style>
        :root {
            --primary-blue: #2563eb;
            --light-blue: #60a5fa;
            --dark-blue: #1d4ed8;
            --bg-gray: #f4f6fa;
            --text-gray: #64748b;
            --border-gray: #e5e7eb;
        }
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .verified-badge {
            color: #10b981;
            font-weight: bold;
            margin-left: 4px;
        }
        
        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body <?php body_class(); ?>>

<?php if (!is_page_template('page-login.php') && !is_page_template('page-register.php')): ?>
<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center space-x-4">
                <a href="<?php echo home_url(); ?>" class="text-2xl font-bold text-blue-600">
                    <i class="fas fa-globe-americas mr-2"></i>
                    Immigrant Knowhow
                </a>
            </div>
            
            <nav class="hidden md:flex items-center space-x-6">
                <a href="<?php echo home_url('/services'); ?>" class="text-gray-600 hover:text-blue-600 transition-colors">Services</a>
                <a href="<?php echo home_url('/forum'); ?>" class="text-gray-600 hover:text-blue-600 transition-colors">Forum</a>
                <a href="<?php echo home_url('/events'); ?>" class="text-gray-600 hover:text-blue-600 transition-colors">Events</a>
                <a href="<?php echo home_url('/news'); ?>" class="text-gray-600 hover:text-blue-600 transition-colors">News</a>
            </nav>
            
            <div class="flex items-center space-x-4">
                <?php if (is_user_logged_in()): ?>
                    <?php $current_user = wp_get_current_user(); ?>
                    <div class="relative">
                        <button class="flex items-center space-x-2 text-gray-600 hover:text-blue-600" id="user-menu-toggle">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 font-semibold text-sm">
                                    <?php echo strtoupper(substr($current_user->display_name, 0, 1)); ?>
                                </span>
                            </div>
                            <span><?php echo esc_html($current_user->display_name); ?></span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border hidden" id="user-menu">
                            <div class="py-2">
                                <?php if (current_user_can('customer')): ?>
                                    <a href="<?php echo home_url('/dashboard-customer'); ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                    </a>
                                <?php elseif (current_user_can('service_provider')): ?>
                                    <a href="<?php echo home_url('/dashboard-provider'); ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo home_url('/settings'); ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i>Settings
                                </a>
                                <hr class="my-2">
                                <a href="<?php echo wp_logout_url(home_url()); ?>" class="block px-4 py-2 text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?php echo home_url('/login'); ?>" class="text-gray-600 hover:text-blue-600 transition-colors">Login</a>
                    <a href="<?php echo home_url('/register'); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userMenuToggle = document.getElementById('user-menu-toggle');
    const userMenu = document.getElementById('user-menu');
    
    if (userMenuToggle && userMenu) {
        userMenuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            userMenu.classList.toggle('hidden');
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!userMenuToggle.contains(e.target) && !userMenu.contains(e.target)) {
                userMenu.classList.add('hidden');
            }
        });
    }
});
</script>
