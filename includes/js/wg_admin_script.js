//jquery tab
jQuery(document).ready(function(){

    jQuery('ul.tabs li').click(function(){
        var tab_id = jQuery(this).attr('data-tab');
        jQuery('ul.tabs li').removeClass('current');
        jQuery('.tab-content').removeClass('current');
        jQuery(this).addClass('current');
        jQuery("#"+tab_id).addClass('current');
    })


	jQuery('#wg_select_product').select2({
  	    ajax: {
    			url: ajaxurl,
    			dataType: 'json',
    			allowClear: true,
    			data: function (params) {
    				return {
        				q: params.term,
        				action: 'wg_product_ajax'
      				};
      			},
    			processResults: function( data ) {
  					var options = [];
  					if ( data ) {
  	 					jQuery.each( data, function( index, text ) { 
  							options.push( { id: text[0], text: text[1], 'price': text[2]} );
  						});
  	 				}
  					return {
  						results: options
  					};
				},
				cache: true
		},
		minimumInputLength: 3
	});


	jQuery('#wg_select_gift_product').select2({
  	    ajax: {
    			url: ajaxurl,
    			dataType: 'json',
    			allowClear: true,
    			data: function (params) {
    				
      				return {
        				q: params.term,
        				action: 'wg_product_ajax'
      				};

    			},
    			processResults: function( data ) {
					var options = [];
					if ( data ) {
	 					jQuery.each( data, function( index, text ) { 
							options.push( { id: text[0], text: text[1], 'price': text[2]} );
						});
	 				}
					return {
						results: options
					};
				},
				cache: true
		},
		minimumInputLength: 3
	});


    jQuery('.wg_gift_rule').change(function() {
        var option = jQuery(this).find('option:selected');
        var val = option.val();
        //alert(val);
        if(val == "") {
            jQuery('.wg_custom_rule').hide();
            jQuery('.wg_price_rule').hide(); 
            jQuery('.wg_qty_rule').hide();
            jQuery('.wg_category_rule').hide();
        }
        if(val == "custom") {
            jQuery('.wg_custom_rule').show();
            jQuery('.wg_price_rule').hide(); 
            jQuery('.wg_qty_rule').hide();
            jQuery('.wg_category_rule').hide();
        }
        if(val == "price") {
            jQuery('.wg_price_rule').show();
            jQuery('.wg_custom_rule').hide();
            jQuery('.wg_qty_rule').hide();
            jQuery('.wg_category_rule').hide();
        }
        if(val == "qty") {
            jQuery('.wg_qty_rule').show();
            jQuery('.wg_price_rule').hide(); 
            jQuery('.wg_custom_rule').hide();
            jQuery('.wg_category_rule').hide();
        }
        if(val == "category") {
            jQuery('.wg_category_rule').show();
            jQuery('.wg_qty_rule').hide();
            jQuery('.wg_price_rule').hide(); 
            jQuery('.wg_custom_rule').hide();
        }  
    });


    var gift_con = jQuery('.wg_gift_rule').find(":selected").val();
    if(gift_con == "") {
        jQuery('.wg_custom_rule').hide();
        jQuery('.wg_price_rule').hide(); 
        jQuery('.wg_qty_rule').hide();
        jQuery('.wg_category_rule').hide();
    }
    if(gift_con == "custom") {
        jQuery('.wg_custom_rule').show();
        jQuery('.wg_price_rule').hide(); 
        jQuery('.wg_qty_rule').hide();
        jQuery('.wg_category_rule').hide();
    }
    if(gift_con == "price") {
        jQuery('.wg_price_rule').show();
        jQuery('.wg_custom_rule').hide();
        jQuery('.wg_qty_rule').hide();
        jQuery('.wg_category_rule').hide();
    }
    if(gift_con == "qty") {
        jQuery('.wg_qty_rule').show();
        jQuery('.wg_price_rule').hide(); 
        jQuery('.wg_custom_rule').hide();
        jQuery('.wg_category_rule').hide();
    }
    if(gift_con == "category") {
        jQuery('.wg_category_rule').show();
        jQuery('.wg_qty_rule').hide();
        jQuery('.wg_price_rule').hide(); 
        jQuery('.wg_custom_rule').hide();
    }

});


function wg_select_id(id) {
	var copyText = id;
	jQuery("#"+copyText).select();
	document.execCommand("copy");
}