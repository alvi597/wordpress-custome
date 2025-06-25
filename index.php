<?php
/*
Template Name: Registration
*/
echo '<!-- Registration Template Loaded -->';
get_header();
?>
<style>
.home-hero {
    background: #2563eb;
    color: #fff;
    padding: 60px 0 40px 0;
    text-align: center;
}
.home-hero h1 {
    font-size: 2.6rem;
    font-weight: 800;
    margin-bottom: 18px;
}
.home-hero p {
    font-size: 1.25rem;
    margin-bottom: 30px;
}
.home-hero .cta-btn {
    background: #fff;
    color: #2563eb;
    font-weight: 700;
    border: none;
    border-radius: 8px;
    padding: 14px 36px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    text-decoration: none;
    display: inline-block;
}
.home-hero .cta-btn:hover {
    background: #1d4ed8;
    color: #fff;
}
.home-section {
    max-width: 900px;
    margin: 40px auto;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 32px rgba(0,0,0,0.07);
    padding: 40px 32px 32px 32px;
    text-align: center;
}
.home-section h2 {
    color: #2563eb;
    font-weight: 700;
    margin-bottom: 18px;
}
.home-section ul {
    text-align: left;
    display: inline-block;
    margin: 0 auto 0 auto;
    padding: 0 0 0 18px;
    font-size: 1.08rem;
}
@media (max-width: 700px) {
    .home-section, .home-hero { padding: 18px 6px; }
    .home-hero h1 { font-size: 2rem; }
}
</style>
<div class="home-hero">
    <h1>Welcome to Immigrant Knowhow</h1>
    <p>Your all-in-one platform to connect, find services, and grow as a community.<br>
    Join as a Customer, Service Provider, or Content Creator!</p>
    <a href="<?php echo esc_url(home_url('/register')); ?>" class="cta-btn">Get Started - Register Now</a>
</div>
<div class="home-section">
    <h2>Why Join Our Community?</h2>
    <ul>
        <li><strong>Find Services:</strong> Instantly connect with trusted Pet Sitters, Tour Guides, Tutors, and more.</li>
        <li><strong>Offer Your Skills:</strong> Register as a Service Provider and reach new clients in your area.</li>
        <li><strong>Personalized Experience:</strong> Role-based dashboards and forms tailored to your needs.</li>
        <li><strong>Safe & Secure:</strong> Your data is protected and your privacy is our priority.</li>
        <li><strong>Content Creators:</strong> Share your knowledge and help others on their journey (coming soon!).</li>
    </ul>
</div>
<div class="home-section">
    <h2>How It Works</h2>
    <ul>
        <li>1. <strong>Register</strong> as a Customer or Service Provider.</li>
        <li>2. <strong>Complete your profile</strong> with your interests or services.</li>
        <li>3. <strong>Access your dashboard</strong> for a personalized experience.</li>
        <li>4. <strong>Connect, collaborate, and grow</strong> with our vibrant community!</li>
    </ul>
</div>
<?php
get_footer();
?> 