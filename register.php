<?php
/*
 * Static Registration Page
 */
get_header();
?>
<div id="registration-form">
    <h2>Immigrant Knowhow</h2>
    <h3>Join Our Community</h3>
    <form method="post" enctype="multipart/form-data">
        <?php wp_nonce_field('custom_register', 'custom_register_nonce'); ?>
        <div class="form-row" style="margin-bottom:18px;">
            <label>
                <input type="radio" name="user_type" value="customer" checked> Looking for Services (Customer)
            </label>
            <label>
                <input type="radio" name="user_type" value="provider"> Providing Services (Service Provider)
            </label>
        </div>
        <div id="customer-fields">
            <div class="form-row">
                <div>
                    <label>First Name</label>
                    <input type="text" name="first_name" required>
                </div>
                <div>
                    <label>Last Name</label>
                    <input type="text" name="last_name" required>
                </div>
            </div>
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <label>Current Country</label>
            <select name="country" required>
                <option value="">Select your current country</option>
                <option value="us">United States</option>
                <option value="uk">United Kingdom</option>
                <option value="eu">Europe</option>
            </select>
            <label>Select Your Interests (Choose up to 5)</label>
            <div class="checkbox-group">
                <label><input type="checkbox" name="interests[]" value="Legal & Immigration"> Legal & Immigration</label>
                <label><input type="checkbox" name="interests[]" value="Health & Wellness"> Health & Wellness</label>
                <label><input type="checkbox" name="interests[]" value="Financial Planning"> Financial Planning</label>
                <label><input type="checkbox" name="interests[]" value="Culture & Integration"> Culture & Integration</label>
                <label><input type="checkbox" name="interests[]" value="Housing & Transportation"> Housing & Transportation</label>
                <label><input type="checkbox" name="interests[]" value="Food & Cooking"> Food & Cooking</label>
                <label><input type="checkbox" name="interests[]" value="Dating & Relationships"> Dating & Relationships</label>
                <label><input type="checkbox" name="interests[]" value="Career & Education"> Career & Education</label>
                <label><input type="checkbox" name="interests[]" value="Insurance"> Insurance</label>
                <label><input type="checkbox" name="interests[]" value="Religion & Spirituality"> Religion & Spirituality</label>
            </div>
        </div>
        <div id="provider-fields" style="display:none;">
            <div class="form-row">
                <div>
                    <label>First Name</label>
                    <input type="text" name="first_name">
                </div>
                <div>
                    <label>Last Name</label>
                    <input type="text" name="last_name">
                </div>
            </div>
            <label>Email</label>
            <input type="email" name="email">
            <label>Password</label>
            <input type="password" name="password">
            <label>Role</label>
            <select name="provider_role">
                <option value="">Select role</option>
                <option value="pet_sitter">Pet Sitter</option>
                <option value="tour_guide">Tour Guide</option>
                <option value="tutor">Tutor</option>
            </select>
            <div class="form-row">
                <div>
                    <label>City</label>
                    <input type="text" name="city">
                </div>
                <div>
                    <label>Country</label>
                    <input type="text" name="country">
                </div>
            </div>
            <label>Years of Experience</label>
            <input type="text" name="experience">
            <label>Short Bio</label>
            <input type="text" name="bio">
            <label>Service Category (multi-select)</label>
            <div class="checkbox-group">
                <label><input type="checkbox" name="categories[]" value="Pet Care"> Pet Care</label>
                <label><input type="checkbox" name="categories[]" value="Tourism"> Tourism</label>
                <label><input type="checkbox" name="categories[]" value="Tutoring"> Tutoring</label>
            </div>
        </div>
        <div class="terms">
            <label>
                <input type="checkbox" name="agree" required>
                I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
            </label>
        </div>
        <button type="submit">Create Account</button>
        <div class="social-signup">
            <button type="button">Sign up with Google</button>
            <button type="button">Sign up with Facebook</button>
        </div>
        <div class="signin-link">
            Already have an account? <a href="<?php echo wp_login_url(); ?>">Sign in</a>
        </div>
    </form>
</div>
<?php get_footer(); ?> 