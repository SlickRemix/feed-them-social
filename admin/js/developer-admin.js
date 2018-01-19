jQuery(document).ready(function() {
		//alert('etf');
        console.log('Developer Clear Cache Function');
     
        jQuery.ajax({
            data: {action: "fts_clear_cache_ajax" },
            type: 'POST',
            url: ftsAjax.ajaxurl,
            success: function( response ) { 
			//	jQuery('body').hide();
				console.log('Well Done and got this from sever: ' + response);
				// alert and upon clicking refresh the page
				// if(!alert('Cache for all FTS Feeds cleared!')){window.location.reload();}

				return false;
			}
        }); // end of ajax()
        return false;
}); // end of document.ready