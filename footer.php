
<footer class="bg-gray-900 text-white mt-16">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Company Info -->
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center space-x-2 mb-4">
                    <i class="fas fa-globe-americas text-blue-400 text-2xl"></i>
                    <h3 class="text-xl font-bold">Immigrant Knowhow</h3>
                </div>
                <p class="text-gray-300 mb-4 max-w-md">
                    Connecting immigrants with trusted service providers and essential resources for a successful journey in their new home.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="<?php echo home_url('/services'); ?>" class="text-gray-300 hover:text-white transition-colors">Browse Services</a></li>
                    <li><a href="<?php echo home_url('/forum'); ?>" class="text-gray-300 hover:text-white transition-colors">Community Forum</a></li>
                    <li><a href="<?php echo home_url('/events'); ?>" class="text-gray-300 hover:text-white transition-colors">Events & Workshops</a></li>
                    <li><a href="<?php echo home_url('/news'); ?>" class="text-gray-300 hover:text-white transition-colors">Immigration News</a></li>
                    <li><a href="<?php echo home_url('/about'); ?>" class="text-gray-300 hover:text-white transition-colors">About Us</a></li>
                </ul>
            </div>
            
            <!-- Support -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Support</h4>
                <ul class="space-y-2">
                    <li><a href="<?php echo home_url('/help'); ?>" class="text-gray-300 hover:text-white transition-colors">Help Center</a></li>
                    <li><a href="<?php echo home_url('/contact'); ?>" class="text-gray-300 hover:text-white transition-colors">Contact Us</a></li>
                    <li><a href="<?php echo home_url('/privacy'); ?>" class="text-gray-300 hover:text-white transition-colors">Privacy Policy</a></li>
                    <li><a href="<?php echo home_url('/terms'); ?>" class="text-gray-300 hover:text-white transition-colors">Terms of Service</a></li>
                    <li><a href="<?php echo home_url('/safety'); ?>" class="text-gray-300 hover:text-white transition-colors">Safety Guidelines</a></li>
                </ul>
            </div>
        </div>
        
        <hr class="border-gray-700 my-8">
        
        <div class="flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-400 text-sm">
                © <?php echo date('Y'); ?> Immigrant Knowhow. All rights reserved.
            </p>
            <div class="flex items-center space-x-4 mt-4 md:mt-0">
                <span class="text-gray-400 text-sm">Available in:</span>
                <div class="flex space-x-2">
                    <img src="https://flagcdn.com/w20/us.png" alt="US" class="w-5 h-auto">
                    <img src="https://flagcdn.com/w20/gb.png" alt="UK" class="w-5 h-auto">
                    <img src="https://flagcdn.com/w20/eu.png" alt="EU" class="w-5 h-auto">
                </div>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

<!-- Custom Scripts -->
<script>
// Global utility functions
window.ImmigrantKnowhow = {
    // Show notification
    showNotification: function(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transition-all duration-300 transform translate-x-full`;
        
        const bgColor = {
            'success': 'bg-green-500',
            'error': 'bg-red-500',
            'warning': 'bg-yellow-500',
            'info': 'bg-blue-500'
        }[type] || 'bg-blue-500';
        
        notification.className += ` ${bgColor} text-white`;
        notification.innerHTML = `
            <div class="flex items-center justify-between">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    },
    
    // Format currency
    formatCurrency: function(amount, currency = 'USD') {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: currency
        }).format(amount);
    },
    
    // Format date
    formatDate: function(date, options = {}) {
        const defaultOptions = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        return new Date(date).toLocaleDateString('en-US', {...defaultOptions, ...options});
    }
};

// Initialize tooltips and other UI components
document.addEventListener('DOMContentLoaded', function() {
    // Add loading states to forms
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                const originalText = submitBtn.textContent || submitBtn.value;
                submitBtn.textContent = 'Processing...';
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
                
                // Re-enable after 10 seconds as fallback
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }, 10000);
            }
        });
    });
    
    // Add smooth scrolling to anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>

</body>
</html>
