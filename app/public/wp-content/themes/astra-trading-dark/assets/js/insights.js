// assets/js/insights.js
jQuery(document).ready(function($) {
    let currentPage = 1;
    
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
                category: getUrlParameter('category')
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
                }
            }
        });
    });
    
    // Filter functionality
    $('.filter-btn').on('click', function() {
        const category = $(this).data('category');
        window.location.href = updateQueryStringParameter(window.location.href, 'category', category);
    });
});