<?php
/*
Template Name: Dashboard Content Creator
*/

get_header();

// Ensure only logged-in content creators can access
if (!is_user_logged_in() || !current_user_can('content_creator')) {
    wp_redirect(home_url('/login/'));
    exit;
}

$current_user = wp_get_current_user();
$approval_status = get_user_meta($current_user->ID, 'approval_status', true);
$content_type = get_user_meta($current_user->ID, 'content_type', true);
$profile_image = get_user_meta($current_user->ID, 'profile_image', true);

// Sample data - in real implementation, this would come from database
$total_events = 12;
$total_downloads = 45;
$total_revenue = 2850;
$pending_approval = isset($_GET['pending']) ? true : false;
?>

<div class="min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out" id="sidebar">
        <div class="flex items-center justify-center h-16 bg-blue-600">
            <h1 class="text-white text-xl font-bold">Creator Dashboard</h1>
        </div>
        
        <nav class="mt-8">
            <div class="px-4 space-y-2">
                <a href="#overview" class="flex items-center px-4 py-3 text-gray-700 bg-blue-50 rounded-lg sidebar-link active">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Overview
                </a>
                <a href="#events" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg sidebar-link">
                    <i class="fas fa-calendar-alt mr-3"></i>
                    Events & Workshops
                </a>
                <a href="#content" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg sidebar-link">
                    <i class="fas fa-download mr-3"></i>
                    Downloadable Content
                </a>
                <a href="#sales" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg sidebar-link">
                    <i class="fas fa-chart-line mr-3"></i>
                    Sales & Analytics
                </a>
                <a href="#customers" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg sidebar-link">
                    <i class="fas fa-users mr-3"></i>
                    Customers
                </a>
                <a href="#settings" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg sidebar-link">
                    <i class="fas fa-cog mr-3"></i>
                    Settings
                </a>
            </div>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="lg:ml-64">
        <!-- Top Bar -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="flex items-center justify-between h-16 px-6">
                <div class="flex items-center">
                    <button class="lg:hidden text-gray-500 hover:text-gray-700" id="sidebar-toggle">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="ml-4 text-xl font-semibold text-gray-800">Welcome back, <?php echo esc_html($current_user->display_name); ?></h2>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button class="flex items-center space-x-2 text-gray-600 hover:text-gray-800">
                            <?php if ($profile_image): ?>
                                <img src="<?php echo esc_url($profile_image); ?>" alt="Profile" class="w-8 h-8 rounded-full object-cover">
                            <?php else: ?>
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 font-semibold text-sm">
                                        <?php echo strtoupper(substr($current_user->display_name, 0, 1)); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <span class="hidden md:block"><?php echo esc_html($current_user->display_name); ?></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Dashboard Content -->
        <div class="p-6">
            <?php if ($pending_approval || $approval_status === 'pending'): ?>
                <!-- Pending Approval Notice -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-yellow-500 text-2xl mr-4"></i>
                        <div>
                            <h3 class="text-yellow-800 font-semibold text-lg">Account Pending Approval</h3>
                            <p class="text-yellow-700 mt-1">
                                Thank you for registering! Your account is currently under review by our admin team. 
                                You'll receive an email notification once your account is approved and you can start creating content.
                            </p>
                        </div>
                    </div>
                </div>
            <?php elseif ($approval_status === 'rejected'): ?>
                <!-- Rejected Notice -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-times-circle text-red-500 text-2xl mr-4"></i>
                        <div>
                            <h3 class="text-red-800 font-semibold text-lg">Account Application Rejected</h3>
                            <p class="text-red-700 mt-1">
                                Unfortunately, your application was not approved. Please contact support for more information.
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Overview Section -->
            <div id="overview" class="dashboard-section">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Total Events</p>
                                <p class="text-2xl font-bold text-gray-900"><?php echo $total_events; ?></p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-lg">
                                <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-green-600 font-medium">+12%</span>
                            <span class="text-gray-500 ml-1">from last month</span>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Downloads</p>
                                <p class="text-2xl font-bold text-gray-900"><?php echo $total_downloads; ?></p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-lg">
                                <i class="fas fa-download text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-green-600 font-medium">+8%</span>
                            <span class="text-gray-500 ml-1">from last month</span>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Total Revenue</p>
                                <p class="text-2xl font-bold text-gray-900">$<?php echo number_format($total_revenue); ?></p>
                            </div>
                            <div class="bg-purple-100 p-3 rounded-lg">
                                <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-green-600 font-medium">+15%</span>
                            <span class="text-gray-500 ml-1">from last month</span>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Active Customers</p>
                                <p class="text-2xl font-bold text-gray-900">127</p>
                            </div>
                            <div class="bg-orange-100 p-3 rounded-lg">
                                <i class="fas fa-users text-orange-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-green-600 font-medium">+5%</span>
                            <span class="text-gray-500 ml-1">from last month</span>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Events -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Events</h3>
                            <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">View All</button>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">Immigration Law Workshop</p>
                                    <p class="text-sm text-gray-500">Dec 15, 2024 • 25 attendees</p>
                                </div>
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">Completed</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">Tax Planning Seminar</p>
                                    <p class="text-sm text-gray-500">Dec 20, 2024 • 18 registered</p>
                                </div>
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">Upcoming</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Revenue Chart -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue Overview</h3>
                        <canvas id="revenueChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Events Section -->
            <div id="events" class="dashboard-section hidden">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Events & Workshops</h2>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Create New Event
                    </button>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6">
                        <div class="text-center py-12">
                            <i class="fas fa-calendar-plus text-gray-400 text-4xl mb-4"></i>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Events Yet</h3>
                            <p class="text-gray-500 mb-4">Create your first event to start engaging with your audience</p>
                            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                                Create Your First Event
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Content Section -->
            <div id="content" class="dashboard-section hidden">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Downloadable Content</h2>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Upload New Content
                    </button>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6">
                        <div class="text-center py-12">
                            <i class="fas fa-file-upload text-gray-400 text-4xl mb-4"></i>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Content Yet</h3>
                            <p class="text-gray-500 mb-4">Upload your first downloadable content to start earning</p>
                            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                                Upload Your First Content
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-section.hidden {
    display: none;
}

.sidebar-link.active {
    background-color: #dbeafe;
    color: #1d4ed8;
}

@media (max-width: 1024px) {
    #sidebar {
        transform: translateX(-100%);
    }
    
    #sidebar.open {
        transform: translateX(0);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle for mobile
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });
    }
    
    // Navigation
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    const sections = document.querySelectorAll('.dashboard-section');
    
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all links
            sidebarLinks.forEach(l => l.classList.remove('active'));
            // Add active class to clicked link
            this.classList.add('active');
            
            // Hide all sections
            sections.forEach(section => section.classList.add('hidden'));
            
            // Show target section
            const target = this.getAttribute('href').substring(1);
            const targetSection = document.getElementById(target);
            if (targetSection) {
                targetSection.classList.remove('hidden');
            }
        });
    });
    
    // Revenue Chart
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue',
                    data: [1200, 1900, 3000, 2500, 2200, 2850],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
});
</script>

<?php get_footer(); ?>
