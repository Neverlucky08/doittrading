// assets/js/insights.js
jQuery(document).ready(function($) {
    let currentPage = 1;
    let currentFilter = 'all';
    
    // Helper function to get URL parameter
    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }
    
    // Helper function to update URL parameter
    function updateQueryStringParameter(uri, key, value) {
        var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        var separator = uri.indexOf('?') !== -1 ? "&" : "?";
        if (uri.match(re)) {
            return uri.replace(re, '$1' + key + "=" + value + '$2');
        } else {
            return uri + separator + key + "=" + value;
        }
    }
    
    // Load More functionality
    $('.load-more-btn').on('click', function() {
        const button = $(this);
        const nextPage = currentPage + 1;
        
        button.text('Loading...');
        
        $.ajax({
            url: doittrading_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'load_more_insights',
                page: nextPage,
                category: currentFilter !== 'all' ? currentFilter : ''
            },
            success: function(response) {
                if (response.success) {
                    $('.posts-grid').append(response.data.html);
                    currentPage = nextPage;
                    
                    if (nextPage >= response.data.max_pages) {
                        button.hide();
                    } else {
                        button.text('Load More Insights');
                    }
                } else {
                    button.text('Error loading posts');
                    console.error('Error:', response.data);
                }
            },
            error: function(xhr, status, error) {
                button.text('Error loading posts');
                console.error('AJAX Error:', error);
            }
        });
    });
    
    // Filter functionality - Client-side filtering (faster)
    $('.filter-btn').on('click', function(e) {
        e.preventDefault();
        
        const category = $(this).data('category');
        currentFilter = category;
        
        // Update active state
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        
        // Filter posts with animation
        if (category === 'all') {
            $('.post-card').fadeIn(10);
        } else {
            $('.post-card').each(function() {
                const postCategory = $(this).data('category');
                if (postCategory === category) {
                    $(this).fadeIn(300);
                } else {
                    $(this).fadeOut(300);
                }
            });
        }
        
        // Reset load more button
        currentPage = 1;
        $('.load-more-btn').show().text('Load More Insights');
        
        // Optional: Update URL without page reload
        const newUrl = updateQueryStringParameter(window.location.href, 'filter', category);
        window.history.pushState({}, '', newUrl);
    });
    
    // Server-side filtering (uncomment if you prefer server-side)
    /*
    $('.filter-btn').on('click', function(e) {
        e.preventDefault();
        
        const category = $(this).data('category');
        const newUrl = updateQueryStringParameter(window.location.href, 'category', category);
        window.location.href = newUrl;
    });
    */
    
    // Initialize filter from URL parameter
    const urlFilter = getUrlParameter('filter') || getUrlParameter('category');
    if (urlFilter && urlFilter !== 'all') {
        currentFilter = urlFilter;
        $('.filter-btn[data-category="' + urlFilter + '"]').trigger('click');
    }
    
    // Search functionality (bonus feature)
    $('#insights-search').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        
        $('.post-card').each(function() {
            const title = $(this).find('.post-title').text().toLowerCase();
            const excerpt = $(this).find('.post-excerpt').text().toLowerCase();
            
            if (title.includes(searchTerm) || excerpt.includes(searchTerm)) {
                $(this).fadeIn(300);
            } else {
                $(this).fadeOut(300);
            }
        });
    });
    
    // Analytics tracking (optional)
    $('.filter-btn').on('click', function() {
        const category = $(this).data('category');
        
        // Google Analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'filter_insights', {
                'event_category': 'Insights',
                'event_label': category
            });
        }
        
        // Facebook Pixel
        if (typeof fbq !== 'undefined') {
            fbq('track', 'Search', {
                search_string: category,
                content_category: 'insights'
            });
        }
    });
});