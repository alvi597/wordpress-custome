<?php
/*
Template Name: Login Page
*/

get_header();

// Check if user is already logged in
if (is_user_logged_in()) {
    // Redirect to appropriate dashboard based on user role
    $user = wp_get_current_user();
    if (in_array('service_provider', (array) $user->roles)) {
        wp_redirect(home_url('/dashboard-provider/'));
    } else {
        wp_redirect(home_url('/dashboard-customer/'));
    }
    exit;
}

$login_error = '';
if (isset($_GET['login']) && $_GET['login'] == 'failed') {
    $login_error = 'Invalid username or password. Please try again.';
}
?>

<div class="login-container">
    <div class="login-box">
        <div class="login-header">
            <h1><?php echo get_bloginfo('name'); ?></h1>
            <p>Welcome back! Please login to your account.</p>
        </div>

        <?php if ($login_error) : ?>
            <div class="login-error">
                <?php echo $login_error; ?>
            </div>
        <?php endif; ?>

        <form name="loginform" id="loginform" action="<?php echo esc_url(site_url('wp-login.php', 'login_post')); ?>" method="post">
            <div class="form-group">
                <label for="user_login">Username or Email Address</label>
                <input type="text" name="log" id="user_login" class="input" required />
            </div>

            <div class="form-group">
                <label for="user_pass">Password</label>
                <input type="password" name="pwd" id="user_pass" class="input" required />
            </div>

            <div class="form-group remember-me">
                <label>
                    <input name="rememberme" type="checkbox" id="rememberme" value="forever" />
                    Remember Me
                </label>
            </div>

            <div class="form-group">
                <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary" value="Log In" />
                <input type="hidden" name="redirect_to" value="<?php echo esc_url(home_url()); ?>" />
            </div>

            <div class="login-links">
                <a href="<?php echo wp_lostpassword_url(); ?>">Lost your password?</a>
                <span class="separator">|</span>
                <a href="<?php echo home_url('/register/'); ?>">Create an account</a>
            </div>
        </form>
    </div>
</div>

<style>
.login-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.login-box {
    background: white;
    padding: 2.5rem;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 400px;
}

.login-header {
    text-align: center;
    margin-bottom: 2rem;
}

.login-header h1 {
    margin-bottom: 0.5rem;
    color: #333;
}

.login-header p {
    color: #666;
    margin: 0;
}

.login-error {
    background: #ffe6e6;
    color: #d63031;
    padding: 1rem;
    border-radius: 5px;
    margin-bottom: 1.5rem;
    text-align: center;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #333;
}

.form-group input[type="text"],
.form-group input[type="password"] {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    transition: border-color 0.3s ease;
}

.form-group input[type="text"]:focus,
.form-group input[type="password"]:focus {
    border-color: #3498db;
    outline: none;
}

.remember-me {
    display: flex;
    align-items: center;
}

.remember-me label {
    margin: 0;
    margin-left: 0.5rem;
    cursor: pointer;
}

#wp-submit {
    width: 100%;
    padding: 0.75rem;
    background: #3498db;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

#wp-submit:hover {
    background: #2980b9;
}

.login-links {
    text-align: center;
    margin-top: 1.5rem;
}

.login-links a {
    color: #3498db;
    text-decoration: none;
    transition: color 0.3s ease;
}

.login-links a:hover {
    color: #2980b9;
}

.separator {
    margin: 0 0.5rem;
    color: #666;
}

@media (max-width: 480px) {
    .login-box {
        padding: 1.5rem;
    }
}
</style>

<?php get_footer(); ?> 