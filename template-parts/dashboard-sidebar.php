<?php
$current_user = wp_get_current_user();
$current_page = basename(get_permalink());
?>

<aside class="dashboard-sidebar">
    <div class="sidebar-header">
        <div class="brand">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
            </svg>
            <span>Transade</span>
        </div>
    </div>

    <div class="user-profile">
        <div class="profile-image">
            <?php 
            $profile_image_id = get_user_meta($current_user->ID, 'profile_image', true);
            $profile_image_url = $profile_image_id ? wp_get_attachment_url($profile_image_id) : get_avatar_url($current_user->ID);
            ?>
            <img src="<?php echo esc_url($profile_image_url); ?>" alt="Profile">
        </div>
        <div class="user-info">
            <h3><?php echo esc_html($current_user->display_name); ?></h3>
            <p class="user-email"><?php echo esc_html($current_user->user_email); ?></p>
        </div>
    </div>

    <nav class="sidebar-nav">
        <!-- Main Navigation -->
        <div class="nav-group">
            <div class="nav-group-title">Main</div>
            
            <a href="<?php echo home_url('/dashboard-customer'); ?>" class="nav-item <?php echo $current_page === 'dashboard-customer' ? 'active' : ''; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="<?php echo home_url('/browse-services'); ?>" class="nav-item <?php echo $current_page === 'browse-services' ? 'active' : ''; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <span>Browse Services</span>
            </a>
        </div>

        <!-- Account Management -->
        <div class="nav-group">
            <div class="nav-group-title">Account</div>
            
            <a href="<?php echo home_url('/bookings'); ?>" class="nav-item <?php echo $current_page === 'bookings' ? 'active' : ''; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span>My Bookings</span>
            </a>

            <a href="<?php echo home_url('/messages'); ?>" class="nav-item <?php echo $current_page === 'messages' ? 'active' : ''; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                <span>Messages</span>
                <span class="nav-indicator active">3</span>
            </a>

            <a href="<?php echo home_url('/settings'); ?>" class="nav-item <?php echo $current_page === 'settings' ? 'active' : ''; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
                <span>Settings</span>
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <a href="<?php echo wp_logout_url(home_url()); ?>" class="nav-item logout">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            <span>Sign Out</span>
        </a>
    </div>
</aside>

<style>
/* Dashboard Sidebar */
.dashboard-sidebar {
    width: 280px;
    background: #fff;
    border-right: 1px solid #e2e8f0;
    position: fixed;
    height: 100vh;
    display: flex;
    flex-direction: column;
}

/* Sidebar Header */
.sidebar-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    flex-shrink: 0;
}

/* User Profile */
.user-profile {
    padding: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    flex-shrink: 0;
}

/* Navigation */
.sidebar-nav {
    padding: 1.5rem;
    flex: 1;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #94a3b8 #f1f5f9;
}

/* Webkit scrollbar styles */
.sidebar-nav::-webkit-scrollbar {
    width: 6px;
}

.sidebar-nav::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.sidebar-nav::-webkit-scrollbar-thumb {
    background-color: #94a3b8;
    border-radius: 3px;
}

.sidebar-nav::-webkit-scrollbar-thumb:hover {
    background-color: #64748b;
}

/* Navigation Groups */
.nav-group {
    margin-bottom: 2rem;
}

.nav-group-title {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    color: #64748b;
    margin-bottom: 0.75rem;
    padding: 0 0.75rem;
}

/* Navigation Items */
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

/* Indicators */
.nav-indicator {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 1.5rem;
    height: 1.5rem;
    padding: 0 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    background: #e2e8f0;
    color: #64748b;
}

.nav-indicator.active {
    background: #ef4444;
    color: #fff;
}

.nav-item.active .nav-indicator {
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
}

/* Sidebar Footer */
.sidebar-footer {
    padding: 1.5rem;
    border-top: 1px solid #e2e8f0;
    flex-shrink: 0;
}

/* Logout Button */
.nav-item.logout {
    color: #ef4444;
}

.nav-item.logout:hover {
    background: #fef2f2;
    color: #dc2626;
}

/* Responsive Design */
@media (max-width: 768px) {
    .dashboard-sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }

    .dashboard-sidebar.active {
        transform: translateX(0);
    }
}
</style> 