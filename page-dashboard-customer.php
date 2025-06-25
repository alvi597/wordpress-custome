<?php
/* Template Name: Dashboard Customer */

// Include AJAX handlers
require_once get_template_directory() . '/inc/ajax-handlers.php';

get_header();

// Ensure only logged-in customers can access
if (!is_user_logged_in() || current_user_can('service_provider')) {
    wp_redirect(home_url('/login/'));
    exit;
}

$current_user = wp_get_current_user();
$profile_img = get_user_meta($current_user->ID, 'profile_img', true);
$country = get_user_meta($current_user->ID, 'country', true);
$profile_completion = 80; // Example static value, can be dynamic
$bookings = 3; // Example static value, can be dynamic
$messages = 5; // Example static value, can be dynamic
$show_settings = isset($_GET['settings']);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile_nonce']) && wp_verify_nonce($_POST['update_profile_nonce'], 'update_profile')) {
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $country = sanitize_text_field($_POST['country']);
    wp_update_user(['ID' => $current_user->ID, 'first_name' => $first_name, 'last_name' => $last_name]);
    update_user_meta($current_user->ID, 'country', $country);
    if (!empty($_POST['password'])) {
        wp_set_password($_POST['password'], $current_user->ID);
    }
    if (!empty($_FILES['profile_img']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $img = $_FILES['profile_img'];
        $img_upload = wp_handle_upload($img, array('test_form' => false));
        if (!isset($img_upload['error'])) {
            update_user_meta($current_user->ID, 'profile_img', $img_upload['url']);
        }
    }
    wp_redirect(site_url('/dashboard-customer?settings=1&updated=1'));
    exit;
}

?>

<div class="dashboard-container">
    <?php include(get_template_directory() . '/template-parts/dashboard-sidebar.php'); ?>

    <!-- Main Content -->
    <main class="dashboard-main">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="header-left">
                <h1>Dashboard Overview</h1>
                <p class="text-subtitle">Welcome back, <?php echo esc_html($current_user->display_name); ?></p>
            </div>
            <div class="header-right">
                <div class="search-container">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" placeholder="Search..." class="search-input">
                </div>
                <div class="header-actions">
                    <button class="icon-button" id="notifications-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                        <span class="notification-badge">3</span>
                    </button>
                    <button class="icon-button" id="theme-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="5"></circle>
                            <line x1="12" y1="1" x2="12" y2="3"></line>
                            <line x1="12" y1="21" x2="12" y2="23"></line>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                            <line x1="1" y1="12" x2="3" y2="12"></line>
                            <line x1="21" y1="12" x2="23" y2="12"></line>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Recent Transactions -->
            <div class="dashboard-card transactions-card">
                <div class="card-header">
                    <h2>Recent Transactions</h2>
                    <div class="transaction-amount negative">-$4.30</div>
                </div>
                <div class="transaction-list">
                    <?php
                    // Get recent transactions (customize this query based on your data structure)
                    $transactions = get_posts(array(
                        'post_type' => 'transaction',
                        'posts_per_page' => 5,
                        'author' => $current_user->ID
                    ));

                    foreach ($transactions as $transaction) {
                        $amount = get_post_meta($transaction->ID, 'amount', true);
                        $is_negative = $amount < 0;
                        ?>
                        <div class="transaction-item">
                            <div class="transaction-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                            </div>
                            <div class="transaction-details">
                                <div class="transaction-name"><?php echo esc_html($transaction->post_title); ?></div>
                                <div class="transaction-date"><?php echo get_the_date('F j, Y g:i A', $transaction); ?></div>
                            </div>
                            <div class="transaction-amount <?php echo $is_negative ? 'negative' : 'positive'; ?>">
                                <?php echo $is_negative ? '-' : '+'; ?>$<?php echo number_format(abs($amount), 2); ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <!-- Balance Card -->
            <div class="dashboard-card balance-card">
                <div class="card-header">
                    <h2>Current Balance</h2>
                </div>
                <div class="balance-amount">
                    <?php 
                    // Get user's balance (customize this based on your data structure)
                    $balance = get_user_meta($current_user->ID, 'account_balance', true);
                    $balance = $balance ? $balance : '0.00';
                    ?>
                    <h3>$<?php echo number_format($balance, 2); ?></h3>
                    <p class="balance-label">Available Balance</p>
                </div>
                <div class="balance-actions">
                    <button class="btn btn-secondary">Transfer</button>
                    <button class="btn btn-primary">Add money</button>
                </div>
            </div>

            <!-- Activity Graph -->
            <div class="dashboard-card graph-card">
                <div class="card-header">
                    <h2>Activity Overview</h2>
                    <select class="time-select">
                        <option>This Week</option>
                        <option>This Month</option>
                        <option>This Year</option>
                    </select>
                </div>
                <div class="graph-container">
                    <canvas id="activityGraph"></canvas>
                </div>
            </div>

            <!-- Users Table -->
            <div class="dashboard-card table-card">
                <div class="card-header">
                    <h2>Recent Service Providers</h2>
                    <button class="btn btn-text">View All</button>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>User ID</th>
                                <th>Service Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Get service providers (customize this query based on your data structure)
                            $providers = get_users(array('role' => 'service_provider', 'number' => 5));
                            foreach ($providers as $index => $provider) {
                                $service_type = get_user_meta($provider->ID, 'service_type', true);
                                $status = get_user_meta($provider->ID, 'status', true);
                                ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td>
                                        <div class="user-info-cell">
                                            <div class="user-avatar"><?php echo substr($provider->display_name, 0, 2); ?></div>
                                            <span><?php echo esc_html($provider->display_name); ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo esc_html($provider->ID); ?></td>
                                    <td><?php echo esc_html($service_type); ?></td>
                                    <td><span class="status-badge <?php echo esc_attr(strtolower($status)); ?>"><?php echo esc_html($status); ?></span></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
/* Reset and Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    line-height: 1.5;
    color: #1e293b;
    background: #f8fafc;
}

/* Dashboard Container */
.dashboard-container {
    display: flex;
    min-height: 100vh;
}

/* Sidebar Styles */
.dashboard-sidebar {
    width: 280px;
    background: #fff;
    border-right: 1px solid #e2e8f0;
    position: fixed;
    height: 100vh;
    display: flex;
    flex-direction: column;
}

.sidebar-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.brand {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.25rem;
    font-weight: 600;
    color: #1e293b;
}

.brand svg {
    width: 1.5rem;
    height: 1.5rem;
    color: #3b82f6;
}

.user-profile {
    padding: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.profile-image {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: #f1f5f9;
    margin: 0 auto 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.profile-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-image .initials {
    font-size: 1.5rem;
    font-weight: 600;
    color: #64748b;
}

.user-info {
    text-align: center;
}

.user-info h3 {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.user-email {
    font-size: 0.875rem;
    color: #64748b;
}

/* Navigation */
.sidebar-nav {
    padding: 1.5rem;
    flex: 1;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    color: #64748b;
    text-decoration: none;
    border-radius: 0.5rem;
    margin-bottom: 0.5rem;
    transition: all 0.2s;
}

.nav-item svg {
    width: 1.25rem;
    height: 1.25rem;
}

.nav-item:hover {
    background: #f1f5f9;
    color: #3b82f6;
}

.nav-item.active {
    background: #3b82f6;
    color: #fff;
}

.sidebar-footer {
    padding: 1.5rem;
    border-top: 1px solid #e2e8f0;
}

/* Main Content */
.dashboard-main {
    flex: 1;
    margin-left: 280px;
    padding: 2rem;
}

/* Header */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.header-left h1 {
    font-size: 1.875rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.text-subtitle {
    color: #64748b;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.search-container {
    position: relative;
}

.search-input {
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    width: 300px;
    font-size: 0.875rem;
}

.search-container svg {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    width: 1.25rem;
    height: 1.25rem;
    color: #94a3b8;
}

.icon-button {
    position: relative;
    padding: 0.5rem;
    border: none;
    background: #fff;
    border-radius: 0.5rem;
    color: #64748b;
    cursor: pointer;
}

.icon-button svg {
    width: 1.5rem;
    height: 1.5rem;
}

.notification-badge {
    position: absolute;
    top: -0.25rem;
    right: -0.25rem;
    background: #ef4444;
    color: #fff;
    font-size: 0.75rem;
    padding: 0.125rem 0.375rem;
    border-radius: 9999px;
}

/* Dashboard Grid */
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

/* Cards */
.dashboard-card {
    background: #fff;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.card-header h2 {
    font-size: 1.25rem;
    font-weight: 600;
}

/* Transactions */
.transactions-card {
    grid-column: span 1;
}

.transaction-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.transaction-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    border-radius: 0.5rem;
    transition: all 0.2s;
}

.transaction-item:hover {
    background: #f8fafc;
}

.transaction-icon {
    width: 2.5rem;
    height: 2.5rem;
    background: #f1f5f9;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.transaction-details {
    flex: 1;
}

.transaction-name {
    font-weight: 500;
}

.transaction-date {
    font-size: 0.875rem;
    color: #64748b;
}

.transaction-amount {
    font-weight: 600;
}

.transaction-amount.negative {
    color: #ef4444;
}

.transaction-amount.positive {
    color: #10b981;
}

/* Balance Card */
.balance-card {
    grid-column: span 1;
}

.balance-amount {
    margin-bottom: 2rem;
}

.balance-amount h3 {
    font-size: 2.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.balance-label {
    color: #64748b;
}

.balance-actions {
    display: flex;
    gap: 1rem;
}

/* Graph Card */
.graph-card {
    grid-column: span 2;
}

.time-select {
    padding: 0.5rem 2rem 0.5rem 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    background: #fff;
    font-size: 0.875rem;
    color: #64748b;
    cursor: pointer;
}

.graph-container {
    height: 300px;
    margin-top: 1rem;
}

/* Table Card */
.table-card {
    grid-column: span 2;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

.data-table th {
    font-weight: 500;
    color: #64748b;
}

.user-info-cell {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar {
    width: 2rem;
    height: 2rem;
    background: #f1f5f9;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-badge.active {
    background: #dcfce7;
    color: #15803d;
}

.status-badge.inactive {
    background: #f1f5f9;
    color: #64748b;
}

/* Buttons */
.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 500;
    font-size: 0.875rem;
    cursor: pointer;
    border: none;
    transition: all 0.2s;
}

.btn-primary {
    background: #3b82f6;
    color: #fff;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-secondary {
    background: #f1f5f9;
    color: #1e293b;
}

.btn-secondary:hover {
    background: #e2e8f0;
}

.btn-text {
    background: none;
    color: #3b82f6;
    padding: 0;
}

.btn-text:hover {
    text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 1280px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }

    .graph-card,
    .table-card {
        grid-column: span 1;
    }
}

@media (max-width: 768px) {
    .dashboard-sidebar {
        display: none;
    }

    .dashboard-main {
        margin-left: 0;
        padding: 1rem;
    }

    .dashboard-header {
        flex-direction: column;
        gap: 1rem;
    }

    .header-right {
        width: 100%;
    }

    .search-container {
        flex: 1;
    }

    .search-input {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Chart.js
    const ctx = document.getElementById('activityGraph').getContext('2d');
    const data = {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
            label: 'Activity',
            data: [65, 59, 80, 81, 56, 55, 40],
            fill: false,
            borderColor: '#3b82f6',
            tension: 0.4
        }]
    };
    
    const config = {
        type: 'line',
        data: data,
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
                        color: '#e2e8f0'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    };

    new Chart(ctx, config);

    // Theme Toggle
    const themeToggle = document.getElementById('theme-toggle');
    themeToggle.addEventListener('click', function() {
        document.body.classList.toggle('dark-theme');
    });
});
</script>

<?php get_footer(); ?> 