document.addEventListener('DOMContentLoaded', function() {
    // Get all filter inputs
    const serviceCheckboxes = document.querySelectorAll('.service-checkbox input[type="checkbox"]');
    const locationSelect = document.getElementById('location-filter');
    const ratingRange = document.getElementById('rating-range');
    const searchInput = document.getElementById('service-search');
    const sortSelect = document.getElementById('sort-filter');
    
    let searchTimeout = null;

    // Function to get selected services
    function getSelectedServices() {
        return Array.from(serviceCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
    }

    // Function to update active filters display
    function updateActiveFilters() {
        const activeFiltersContainer = document.querySelector('.active-filters');
        activeFiltersContainer.innerHTML = '';

        // Add selected service types
        serviceCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const filterTag = document.createElement('span');
                filterTag.className = 'filter-tag';
                filterTag.innerHTML = `
                    ${checkbox.nextElementSibling.textContent}
                    <button class="remove-filter" data-service="${checkbox.value}">×</button>
                `;
                activeFiltersContainer.appendChild(filterTag);
            }
        });

        // Add location if selected
        if (locationSelect.value) {
            const filterTag = document.createElement('span');
            filterTag.className = 'filter-tag';
            filterTag.innerHTML = `
                ${locationSelect.options[locationSelect.selectedIndex].text}
                <button class="remove-filter" data-filter="location">×</button>
            `;
            activeFiltersContainer.appendChild(filterTag);
        }

        // Add rating if not default
        if (ratingRange.value != 4) {
            const filterTag = document.createElement('span');
            filterTag.className = 'filter-tag';
            filterTag.innerHTML = `
                ${ratingRange.value}+ Stars
                <button class="remove-filter" data-filter="rating">×</button>
            `;
            activeFiltersContainer.appendChild(filterTag);
        }

        // Add clear all button if there are active filters
        if (activeFiltersContainer.children.length > 0) {
            const clearButton = document.createElement('button');
            clearButton.className = 'clear-filters';
            clearButton.textContent = 'Clear All Filters';
            activeFiltersContainer.appendChild(clearButton);

            clearButton.addEventListener('click', function() {
                serviceCheckboxes.forEach(cb => cb.checked = false);
                locationSelect.value = '';
                ratingRange.value = 4;
                updateRating();
                updateActiveFilters();
                filterServices();
            });
        }

        // Attach remove filter event handlers
        document.querySelectorAll('.remove-filter').forEach(button => {
            button.addEventListener('click', function() {
                const serviceValue = this.dataset.service;
                const filterType = this.dataset.filter;

                if (serviceValue) {
                    document.querySelector(`input[value="${serviceValue}"]`).checked = false;
                } else if (filterType === 'location') {
                    locationSelect.value = '';
                } else if (filterType === 'rating') {
                    ratingRange.value = 4;
                    updateRating();
                }

                updateActiveFilters();
                filterServices();
            });
        });
    }

    // Function to render service cards
    function renderServices(services) {
        const servicesGrid = document.querySelector('.services-grid');
        const resultsCount = document.querySelector('.results-header h3');
        
        resultsCount.textContent = `${services.length} Services Found`;
        servicesGrid.innerHTML = '';

        if (services.length === 0) {
            servicesGrid.innerHTML = `
                <div class="error-message">
                    No services found matching your criteria. Try adjusting your filters.
                </div>
            `;
            return;
        }

        services.forEach(service => {
            const serviceCard = document.createElement('div');
            serviceCard.className = 'service-card';
            serviceCard.innerHTML = `
                <div class="service-image" style="background-image: url('${service.image || defaultServiceImage}')">
                    <div class="service-price">${service.price}</div>
                </div>
                <div class="service-content">
                    <h3>${service.title}</h3>
                    <p class="provider">${service.provider_name}</p>
                    <div class="service-rating">
                        <span class="stars">${'★'.repeat(Math.floor(service.rating))}${'☆'.repeat(5 - Math.floor(service.rating))}</span>
                        <span class="rating-number">${service.rating}</span>
                    </div>
                    <a href="${service.permalink}" class="book-now-btn">Book Now</a>
                </div>
            `;
            servicesGrid.appendChild(serviceCard);
        });
    }

    // Function to perform AJAX filter
    function filterServices() {
        const selectedServices = getSelectedServices();
        const location = locationSelect.value;
        const rating = ratingRange.value;
        const search = searchInput.value;
        const sort = sortSelect.value;

        // Show loading state
        document.querySelector('.services-grid').innerHTML = '<div class="loading-services">Loading services</div>';

        // Prepare form data
        const formData = new FormData();
        formData.append('action', 'filter_services');
        formData.append('nonce', filterNonce);
        formData.append('service_types', JSON.stringify(selectedServices));
        formData.append('location', location);
        formData.append('rating', rating);
        formData.append('search', search);
        formData.append('sort', sort);

        // Make AJAX request
        fetch(ajaxurl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderServices(data.services);
            } else {
                throw new Error(data.message || 'Error filtering services');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.querySelector('.services-grid').innerHTML = `
                <div class="error-message">
                    Error loading services. Please try again.
                </div>
            `;
        });
    }

    // Function to update rating display
    function updateRating() {
        const value = ratingRange.value;
        document.getElementById('rating-display').textContent = value + '.0';
        const stars = '★'.repeat(Math.floor(value)) + '☆'.repeat(5 - Math.floor(value));
        document.querySelector('.rating-value .stars').textContent = stars;
    }

    // Attach event listeners
    serviceCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            updateActiveFilters();
            filterServices();
        });
    });

    locationSelect.addEventListener('change', () => {
        updateActiveFilters();
        filterServices();
    });

    ratingRange.addEventListener('input', () => {
        updateRating();
    });

    ratingRange.addEventListener('change', () => {
        updateActiveFilters();
        filterServices();
    });

    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterServices();
        }, 500);
    });

    sortSelect.addEventListener('change', filterServices);

    // Initial load
    updateRating();
    filterServices();
}); 