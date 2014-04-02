 jQuery(document).ready(function(){
 
 	setTimeout(showOverlay, jQuery("#wp_shabbat_time_interval").val());
 
 	function showOverlay(){
	        jQuery("#wp_shabbat_content").overlay({
	            
	          
	            mask: {
	                color: '#000',
	                loadSpeed: 200,
	            opacity: .8,
	            zIndex:99999
	          },
	              closeOnClick: false,
	              closeOnEsc: false,
	              api: true,
	             
	          }).load();
          }
        });