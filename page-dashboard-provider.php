<?php
/* Template Name: Provider Dashboard */
get_header();
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$profile_img = get_user_meta($user_id, 'profile_img', true);
$country = get_user_meta($user_id, 'country', true);
$provider_name = get_user_meta($user_id, 'provider_name', true);
$verification_status = get_user_meta($user_id, 'id_verification_status', true);
$admin_note = get_user_meta($user_id, 'id_verification_note', true);
$doc_type = get_user_meta($user_id, 'id_doc_type', true);
$front_id = get_user_meta($user_id, 'id_front', true);
$back_id = get_user_meta($user_id, 'id_back', true);
$listings = 2; // Example static value
$bookings = 4; // Example static value
$earnings = 1200; // Example static value
$show_settings = isset($_GET['settings']);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile_nonce']) && wp_verify_nonce($_POST['update_profile_nonce'], 'update_profile')) {
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $provider_name = sanitize_text_field($_POST['provider_name']);
    $country = sanitize_text_field($_POST['country']);
    wp_update_user(['ID' => $user_id, 'first_name' => $first_name, 'last_name' => $last_name]);
    update_user_meta($user_id, 'country', $country);
    update_user_meta($user_id, 'provider_name', $provider_name);
    if (!empty($_POST['password'])) {
        wp_set_password($_POST['password'], $user_id);
    }
    if (!empty($_FILES['profile_img']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $img = $_FILES['profile_img'];
        $img_upload = wp_handle_upload($img, array('test_form' => false));
        if (!isset($img_upload['error'])) {
            update_user_meta($user_id, 'profile_img', $img_upload['url']);
        }
    }
    wp_redirect(site_url('/dashboard-provider?settings=1&updated=1'));
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_id_nonce']) && wp_verify_nonce($_POST['verify_id_nonce'], 'verify_id')) {
    $country = sanitize_text_field($_POST['country']);
    $doc_type = sanitize_text_field($_POST['doc_type']);
    update_user_meta($user_id, 'id_country', $country);
    update_user_meta($user_id, 'id_doc_type', $doc_type);
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    $front = $_FILES['id_front'];
    $back = $_FILES['id_back'];
    if ($front && $front['size'] > 0) {
        $front_upload = wp_handle_upload($front, array('test_form' => false));
        if (!isset($front_upload['error'])) {
            update_user_meta($user_id, 'id_front', $front_upload['url']);
        }
    }
    if ($back && $back['size'] > 0) {
        $back_upload = wp_handle_upload($back, array('test_form' => false));
        if (!isset($back_upload['error'])) {
            update_user_meta($user_id, 'id_back', $back_upload['url']);
        }
    }
    update_user_meta($user_id, 'id_verification_status', 'Pending');
    update_user_meta($user_id, 'id_verification_note', '');
    $verification_status = 'Pending';
}
?>
<style>
.prov-dash-layout {
    display: flex;
    min-height: 100vh;
    background: linear-gradient(135deg, #f0f9ff 0%, #e0e7ff 100%);
}

.prov-dash-nav {
    width: 280px;
    background: #fff;
    padding: 40px 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: fixed;
    height: 100vh;
    box-shadow: 4px 0 24px rgba(37,99,235,0.08);
    z-index: 10;
}

.prov-dash-main {
    flex: 1;
    padding: 40px;
    margin-left: 280px;
}

.prov-dash-nav .avatar {
    width: 100px;
    height: 100px;
    border-radius: 20px;
    background: linear-gradient(135deg, #e0e7ff 0%, #f0f9ff 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: #2563eb;
    font-weight: 700;
    margin-bottom: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(37,99,235,0.15);
    border: 4px solid #fff;
}

.prov-dash-nav .name {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e40af;
    margin-bottom: 4px;
}

.prov-dash-nav nav {
    width: 100%;
    margin-top: 40px;
    padding: 0 20px;
}

.prov-dash-nav nav a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 24px;
    color: #4b5563;
    font-weight: 500;
    text-decoration: none;
    border-radius: 16px;
    transition: all 0.2s;
    margin-bottom: 8px;
}

.prov-dash-nav nav a svg {
    width: 20px;
    height: 20px;
}

.prov-dash-nav nav a.active,
.prov-dash-nav nav a:hover {
    background: #2563eb;
    color: #fff;
    transform: translateX(4px);
}

.prov-dash-widgets {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.prov-widget {
    background: #fff;
    border-radius: 20px;
    padding: 32px;
    text-align: center;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(37,99,235,0.08);
    transition: transform 0.2s;
}

.prov-widget:hover {
    transform: translateY(-4px);
}

.prov-widget::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #2563eb, #60a5fa);
}

.prov-widget .stat {
    font-size: 2.5rem;
    font-weight: 800;
    color: #1e40af;
    margin-bottom: 8px;
}

.prov-widget .label {
    color: #6b7280;
    font-weight: 500;
}

.prov-dash-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.prov-action-card {
    background: #fff;
    border-radius: 20px;
    padding: 32px 24px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(37,99,235,0.08);
    transition: all 0.2s;
    border: 2px solid transparent;
}

.prov-action-card:hover {
    transform: translateY(-4px);
    border-color: #2563eb;
    background: linear-gradient(135deg, #fff 0%, #f0f9ff 100%);
}

.prov-action-card svg {
    width: 32px;
    height: 32px;
    color: #2563eb;
    margin-bottom: 16px;
}

.prov-action-card .label {
    font-weight: 600;
    color: #1e40af;
    font-size: 1.1rem;
}

.prov-dash-sections {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 32px;
}

.section-card {
    background: #fff;
    border-radius: 20px;
    padding: 32px;
    box-shadow: 0 4px 20px rgba(37,99,235,0.08);
}

.section-card h3 {
    color: #1e40af;
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid #e5e7eb;
}

.empty-state {
    text-align: center;
    color: #6b7280;
    padding: 40px 24px;
    background: linear-gradient(135deg, #f8fafc 0%, #f0f9ff 100%);
    border-radius: 16px;
    font-size: 0.95rem;
}

@media (max-width: 1024px) {
    .prov-dash-nav {
        width: 100%;
        height: auto;
        position: relative;
        padding: 24px;
    }
    
    .prov-dash-main {
        margin-left: 0;
        padding: 24px;
    }
    
    .prov-dash-nav nav {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 24px;
    }
    
    .prov-dash-nav nav a {
        padding: 12px 20px;
        margin-bottom: 0;
    }
}

@media (max-width: 768px) {
    .prov-dash-sections {
        grid-template-columns: 1fr;
    }
    
    .prov-widget {
        padding: 24px;
    }
    
    .prov-action-card {
        padding: 24px;
    }
}
</style>
<div class="prov-dash-layout">
    <aside class="prov-dash-nav">
        <div class="avatar">
            <?php if ($profile_img): ?>
                <img src="<?php echo esc_url($profile_img); ?>" alt="Profile" />
            <?php else: ?>
                <?php echo strtoupper(substr($current_user->first_name,0,1)); ?>
            <?php endif; ?>
        </div>
        <div class="name"><?php echo esc_html($current_user->first_name . ' ' . $current_user->last_name); ?></div>
        <div class="email"><?php echo esc_html($current_user->user_email); ?></div>
        <nav>
            <a href="/dashboard-provider" class="active">Dashboard</a>
            <a href="/dashboard-provider?settings=1">Settings</a>
        </nav>
        <form method="post" action="<?php echo wp_logout_url(home_url()); ?>">
            <button type="submit" class="settings-btn">Logout</button>
        </form>
    </aside>
    <main class="prov-dash-main">
        <div class="prov-dash-header">
            <span class="provider-name">
                <?php if ($provider_name): ?>
                    <?php echo esc_html($provider_name); ?>
                <?php else: ?>
                    <span style="color:#dc2626;">Please set your Provider Name in <a href="/dashboard-provider?settings=1" style="color:#2563eb;text-decoration:underline;">Settings</a></span>
                <?php endif; ?>
            </span>
            <?php if ($verification_status === 'Verified'): ?>
                <span class="status-badge status-verified">Verified</span>
            <?php elseif ($verification_status === 'Rejected'): ?>
                <span class="status-badge status-rejected">Rejected</span>
            <?php elseif ($verification_status === 'Pending'): ?>
                <span class="status-badge status-pending">Pending</span>
            <?php endif; ?>
        </div>
        <?php if ($show_settings): ?>
            <div class="prov-settings-panel">
                <h3>Profile Settings</h3>
                <?php if (isset($_GET['updated'])): ?><div style="color:#10b981;text-align:center;margin-bottom:10px;">Profile updated!</div><?php endif; ?>
                <form method="post" enctype="multipart/form-data">
                    <?php wp_nonce_field('update_profile', 'update_profile_nonce'); ?>
                    <label>First Name</label>
                    <input type="text" name="first_name" value="<?php echo esc_attr($current_user->first_name); ?>" required>
                    <label>Last Name</label>
                    <input type="text" name="last_name" value="<?php echo esc_attr($current_user->last_name); ?>" required>
                    <label>Provider Name</label>
                    <input type="text" name="provider_name" value="<?php echo esc_attr($provider_name); ?>" placeholder="Your business or provider name" required>
                    <label>Country</label>
                    <input type="text" name="country" value="<?php echo esc_attr($country); ?>" required>
                    <label>Change Password</label>
                    <input type="password" name="password" placeholder="New password (leave blank to keep current)">
                    <label>Profile Image</label>
                    <?php if ($profile_img): ?><img src="<?php echo esc_url($profile_img); ?>" class="profile-img-preview" alt="Profile" /><?php endif; ?>
                    <input type="file" name="profile_img" accept="image/*">
                    <button type="submit">Save Changes</button>
                </form>
            </div>
        <?php elseif (!$verification_status || $verification_status === 'Pending' || $verification_status === 'Rejected'): ?>
            <div class="verification-section">
                <?php 
                $verification_status = get_user_meta($user_id, 'verification_status', true);
                $verified_name = get_user_meta($user_id, 'verified_name', true);
                $verified_dob = get_user_meta($user_id, 'verified_dob', true);
                $verified_nationality = get_user_meta($user_id, 'verified_nationality', true);
                $verified_document_number = get_user_meta($user_id, 'verified_document_number', true);
                $verification_date = get_user_meta($user_id, 'verification_date', true);
                $face_verification = get_user_meta($user_id, 'face_verification', true);
                
                if ($verification_status === 'verified'): ?>
                    <div class="verification-status verified">
                        <span class="verified-badge">✓</span>
                        <h3>Verified Provider</h3>
                        <p>Verified on <?php echo date('F j, Y', strtotime($verification_date)); ?></p>
                    </div>
                    <div class="verified-info">
                        <div class="info-row">
                            <label>Verified Name:</label>
                            <span><?php echo esc_html($verified_name); ?></span>
                        </div>
                        <div class="info-row">
                            <label>Date of Birth:</label>
                            <span><?php echo esc_html($verified_dob); ?></span>
                        </div>
                        <div class="info-row">
                            <label>Nationality:</label>
                            <span><?php echo esc_html($verified_nationality); ?></span>
                        </div>
                        <div class="info-row">
                            <label>Document Number:</label>
                            <span><?php echo esc_html($verified_document_number); ?></span>
                        </div>
                        <?php if ($face_verification === 'verified'): ?>
                            <div class="info-row">
                                <label>Face Verification:</label>
                                <span class="verified-text">Verified</span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="verification-status unverified">
                        <h3>Verify Your Identity</h3>
                        <p>Please verify your identity to access all provider features.</p>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <?php wp_nonce_field('verify_id', 'verify_id_nonce'); ?>
                        <label>ID Document Front</label>
                        <input type="file" name="id_front" accept="image/*" required>
                        <label>ID Document Back</label>
                        <input type="file" name="id_back" accept="image/*" required>
                        <label>Face Photo</label>
                        <input type="file" name="face_photo" accept="image/*" required>
                        <button type="submit">Submit for Verification</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php elseif ($verification_status === 'Verified'): ?>
            <div class="prov-dash-widgets">
                <div class="prov-widget">
                    <div class="stat"><?php echo $listings; ?></div>
                    <div class="label">Active Listings</div>
                </div>
                <div class="prov-widget">
                    <div class="stat"><?php echo $bookings; ?></div>
                    <div class="label">Pending Bookings</div>
                </div>
                <div class="prov-widget">
                    <div class="stat">$<?php echo number_format($earnings); ?></div>
                    <div class="label">Total Earnings</div>
                </div>
            </div>
            <div class="prov-dash-actions">
                <a href="/add-listing" class="prov-action-card">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 4v16m8-8H4"></path>
                    </svg>
                    <div class="label">Add New Listing</div>
                </a>
                <a href="/my-listings" class="prov-action-card">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <div class="label">Manage Listings</div>
                </a>
                <a href="/bookings" class="prov-action-card">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div class="label">Accept Bookings</div>
                </a>
                <a href="/payments" class="prov-action-card">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <div class="label">Manage Payments</div>
                </a>
            </div>
            <div class="prov-dash-sections">
                <div class="section-card recent-bookings">
                    <h3>Recent Bookings</h3>
                    <div class="booking-list">
                        <!-- Add your booking list items here -->
                        <div class="empty-state">No recent bookings</div>
                    </div>
                </div>
                <div class="section-card recent-earnings">
                    <h3>Recent Earnings</h3>
                    <div class="earnings-list">
                        <!-- Add your earnings list items here -->
                        <div class="empty-state">No recent transactions</div>
                    </div>
                </div>
            </div>
        <?php elseif ($verification_status === 'Pending'): ?>
            <div class="verification-section">
                <strong>Your verification is pending. Please wait for admin approval.</strong>
            </div>
        <?php endif; ?>
    </main>
</div>
<?php get_footer(); ?> 