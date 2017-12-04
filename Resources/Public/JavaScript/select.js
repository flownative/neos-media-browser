(function ($) {
    if (window.parent !== window && window.parent.NeosMediaBrowserCallbacks) {
        // we are inside iframe
        $('.asset-list').on('click', '[data-asset-identifier]', function(e) {
            debugger;
            if ($(e.target).closest('a, button').not('[data-asset-identifier]').length === 0) {
                if (window.parent.NeosMediaBrowserCallbacks && typeof window.parent.NeosMediaBrowserCallbacks.assetChosen === 'function') {
                    window.parent.NeosMediaBrowserCallbacks.assetChosen($(this).attr('data-asset-identifier'));
                }
                e.preventDefault();
            }
        });
    }
})(jQuery);
