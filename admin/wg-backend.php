<?php

if (!defined('ABSPATH'))
  exit;

if (!class_exists('OCWG_menu')) {

   	class OCWG_menu {

      	protected static $instance;
     
      	
      	function OCWG_create_menu() {
			add_menu_page('Woocommerce Gift', 'Woo Gift', 'manage_options', 'free_gift', array($this, 'OCWG_free_contain'));
		}
			

		function OCWG_free_contain() {
         	?> 
	         	<div class="wg_container">
	         		<form method="post">
	         			<?php wp_nonce_field( 'OCWG_meta_save', 'OCWG_meta_save_nounce' ); ?>
		            	<ul class="tabs">
		               		<li class="tab-link current" data-tab="tab-default">
		                  		<?php echo __( 'Default Settings', OCWG_DOMAIN ); ?>
		               		</li>
		               		<li class="tab-link" data-tab="tab-general">
		                  		<?php echo __( 'Other Settings', OCWG_DOMAIN ); ?>
		               		</li>
		            	</ul>
		            	<div id="tab-default" class="tab-content current">
		               		<div class="attribute_div">
		                  		<div class="label_div"><?php _e( 'Gift Rules', 'woocommerce' ); ?></div>
		                  		<div class="input_div">
		                  			<?php $wg_gift_rule = get_option('wg_gift_rule'); ?>
		                     		<select name="wg_gift_rule" class="wg_gift_rule">
		                        		<option value="">Select Rules</option>
		                        		<option value="custom" <?php if($wg_gift_rule == "custom") { echo "selected"; } ?>>Products Rule</option>
		                        		<option value="price" <?php if($wg_gift_rule == "price") { echo "selected"; } ?>>Cart Price Rule</option>
		                        		<option value="category" <?php if($wg_gift_rule == "category") { echo "selected"; } ?>>Category Rule</option>
		                     		</select>
		                  		</div>
		               		</div>   
		               		<div class="attribute_div">
		                  		<div class="child_div wg_custom_rule" style="display: none;">
			                     	<h2 class="des_head"><?php _e( 'Products Rules', 'woocommerce' ); ?></h2>
			                     	<div>
				                        <div class="label_div">
				                           <?php _e( 'Add Your Product', 'woocommerce' ); ?>       
				                        </div>
				                        <div class="input_div">
				                           	<select id="wg_select_product" name="wg_select2[]" multiple="multiple" style="width:30%;max-width:15em;">
					                           	<?php 
					                           		$productsa = get_option('wg_combo');
					                           		foreach ($productsa as $value) {
					                              		$productc = wc_get_product( $value );
					                              		if ( $productc && $productc->is_in_stock() && $productc->is_purchasable() ) {
					                                 		$title = $productc->get_name();
						                                 	?>
						                                 		<option value="<?php echo $value; ?>" selected="selected"><?php echo $title; ?></option>
						                                 	<?php   
					                              		}
					                           		}
					                           	?>
				                           </select> 
				                        </div>
				                    </div>      
			                  	</div>

			                  	<div class="child_div wg_price_rule" style="display: none;">
			                     	<h2 class="des_head"><?php _e( 'Price Rules', 'woocommerce' ); ?></h2>
			                     	<div>
			                     		<?php $wg_price = get_option('wg_price'); ?>
			                        	<div class="label_div">
			                           		<?php _e( 'Minimun Cart Price', 'woocommerce' ); ?>       
			                        	</div>
				                        <div class="input_div">
				                           	<input type="number" min="0" name="wg_price" value="<?php echo $wg_price; ?>">
				                        </div>
			                     	</div>
			                  	</div>

			                  	<div class="child_div wg_category_rule" style="display: none;">
			                     	<h2 class="des_head"><?php _e( 'Category Rules', 'woocommerce' ); ?></h2>
			                     	<div>
			                        	<div class="label_div">
			                           		<?php _e( 'Category', 'woocommerce' ); ?>       
			                        	</div>
			                        	<div class="input_div">
			                           		<?php

				                           		$args = array(
											          'taxonomy' => 'product_cat',
											          'hide_empty' => false,
											          'parent'   => 0
											    );
											  	$product_cat = get_terms( $args );
											  	$category = get_option('wg_cat');
											  	foreach ($product_cat as $wg_category) {
											  		?>
										 			<input type="checkbox" name="wg_cat[]" value="<?php echo $wg_category->term_id;?>" <?php if(!empty($category) && in_array($wg_category->term_id,$category)){echo "checked";} ?>><?php echo $wg_category->name ; ?></br>
													<?php
													$child_args = array(
										              	'taxonomy' => 'product_cat',
										              	'hide_empty' => false,
										              	'parent'   => $wg_category->term_id
										          	);
											  		$child_product_cats = get_terms( $child_args );
											  		foreach ($child_product_cats as $child_product_cat) {
														?>
											  	 			<input type="checkbox" name="wg_cat[]" value="<?php echo $child_product_cat->term_id;?>" <?php if(!empty($category) && in_array($child_product_cat->term_id,$category)){echo "checked";} ?>><?php echo $child_product_cat->name ;?></br>
									    				<?php
										  			}
											  	}
				                           	?>  
			                        	</div>
			                     	</div>  
			                  	</div> 
							</div>    
							<div class="attribute_div">
	                        	<div class="label_div"><?php _e( 'Min Product In Cart', 'woocommerce' ); ?></div>
		                        <div class="input_div">
		                        	<?php $wg_min_cart_qty = get_option('wg_min_cart_qty'); ?>
		                           	<input type="number" min="1" name="wg_min_cart_qty" value="<?php if(empty($wg_min_cart_qty)) { echo "1"; }else{ echo $wg_min_cart_qty; } ?>">
		                        </div>
	                     	</div>
	                     	<div class="attribute_div">
	                        	<div class="label_div"><?php _e( 'Min Qty In Cart', 'woocommerce' ); ?></div>
		                        <div class="input_div">
		                        	<?php $wg_min_qty_cart_qty = get_option('wg_min_qty_cart_qty'); ?>
		                           	<input type="number" min="1" name="wg_min_qty_cart_qty" value="<?php if(empty($wg_min_qty_cart_qty)) { echo "1"; }else{ echo $wg_min_qty_cart_qty; } ?>">
		                        </div>
	                     	</div>
							<div class="attribute_div">
	                        	<div class="label_div"><?php _e( 'Add Your Gift Product', 'woocommerce' ); ?></div>
		                        <div class="input_div">
		                           	<select id="wg_select_gift_product" name="wg_gift_select2[]" multiple="multiple" style="width:30%;max-width:15em;">
		                              	<?php 
		                              		$productsa = get_option('wg_gift_combo');
		                              		foreach ($productsa as $value) {
		                                 		$productc = wc_get_product( $value );
	                             				$title = $productc->get_name();
		                                    		?>
		                                    			<option value="<?php echo $value; ?>" selected="selected"><?php echo $title; ?></option>
		                                    		<?php   
		                                 		
		                              		}
		                              	?>
		                           </select> 
		                        </div>
	                     	</div> 
	                     	<div class="attribute_div">
	                        	<div class="label_div"><?php _e( 'Maximum Gift Product', 'woocommerce' ); ?></div>
		                        <div class="input_div">
		                        	<?php $wg_maximum_gift = get_option('wg_maximum_gift', 1); ?>
		                           	<input type="number" min="1" name="wg_maximum_gift" value="<?php echo $wg_maximum_gift; ?>">
		                        </div>
	                     	</div>              
			            </div>
			            <div id="tab-general" class="tab-content">
			               	<div class="attribute_div">
		                  		<div class="label_div"><?php _e( 'Gift Block Title', 'woocommerce' ); ?></div>
		                  		<div class="input_div">
		                  			<?php $wg_gift_title = get_option('wg_gift_title', 'Select Your Gift'); ?>
		                     		<input type="text" name="wg_gift_title" value="<?php echo $wg_gift_title; ?>">
		                  		</div>
		               		</div>
		               		<div class="attribute_div">
		                  		<div class="label_div"><?php _e( 'Gift Block Title Font Size', 'woocommerce' ); ?></div>
		                  		<div class="input_div">
		                  			<?php $wg_gift_title_font_size = get_option('wg_gift_title_font_size', '24px'); ?>
		                     		<input type="text" name="wg_gift_title_font_size" value="<?php echo $wg_gift_title_font_size; ?>">
		                  		</div>
		               		</div>  
			            </div>
			            <input type="hidden" name="action" value="wg_save_option">
                		<input type="submit" value="Save changes" name="submit" class="button-primary" id="wg_btn_space">
	        		</form>
	         	</div>  	
         	<?php
		}


		function OCWG_product_ajax(){
      
            $return = array();
            $post_types = array( 'product','product_variation');
           
         
            $search_results = new WP_Query( array( 
                's'=> $_GET['q'],
                'post_status' => 'publish',
                'post_type' => $post_types,
                'posts_per_page' => -1,
                'meta_query' => array(
                                    array(
                                        'key' => '_stock_status',
                                        'value' => 'instock',
                                        'compare' => '=',
                                    )
                                )
                ) );
             

            if( $search_results->have_posts() ) :
               while( $search_results->have_posts() ) : $search_results->the_post();   
                  $productc = wc_get_product( $search_results->post->ID );
                  if ( $productc && $productc->is_in_stock() && $productc->is_purchasable() ) {
                     $title = $search_results->post->post_title;
                     $price = $productc->get_price_html();
                     $return[] = array( $search_results->post->ID, $title, $price);   
                  }
               endwhile;
            endif;
            echo json_encode( $return );
            die;
      	}


      	function recursive_sanitize_text_field($array) {
      		
      		if(!empty($array)) {
	         	foreach ( $array as $key => $value ) {
	            	if ( is_array( $value ) ) {
	               		$value = $this->recursive_sanitize_text_field($value);
	            	}else{
	              		$value = sanitize_text_field( $value );
	            	}
	         	}
	        }
         	return $array;
      	}


      	function OCWG_save_options(){
        	if( current_user_can('administrator') ) { 
          		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'wg_save_option'){
            		if(!isset( $_POST['OCWG_meta_save_nounce'] ) || !wp_verify_nonce( $_POST['OCWG_meta_save_nounce'], 'OCWG_meta_save' ) ){

                		print 'Sorry, your nonce did not verify.';
                		exit;

            		}else{

               			$wg_gift_rule = sanitize_text_field( $_REQUEST['wg_gift_rule'] );
			            update_option('wg_gift_rule', $wg_gift_rule, 'yes');


			            /*---custom rules---*/
			            if(!empty($_REQUEST['wg_select2'])){
			            	$wg_combo = $this->recursive_sanitize_text_field( $_REQUEST['wg_select2'] );
			            	update_option('wg_combo', $wg_combo, 'yes');
			            }
			           
			            $wg_gift_combo = $this->recursive_sanitize_text_field( $_REQUEST['wg_gift_select2'] );
			            update_option('wg_gift_combo', $wg_gift_combo, 'yes');


			            /*---price rules---*/
			            $wg_price = sanitize_text_field( $_REQUEST['wg_price'] );
			            update_option('wg_price', $wg_price, 'yes');


			            /*---category rules---*/
			            if(!empty($_REQUEST['wg_cat'])){
				            $wg_cat = $this->recursive_sanitize_text_field( $_REQUEST['wg_cat'] );
				            update_option('wg_cat', $wg_cat, 'yes');
				        }


			            $wg_min_cart_qty = sanitize_text_field( $_REQUEST['wg_min_cart_qty'] );
			            update_option('wg_min_cart_qty', $wg_min_cart_qty, 'yes');

						$wg_min_qty_cart_qty = sanitize_text_field( $_REQUEST['wg_min_qty_cart_qty'] );
			            update_option('wg_min_qty_cart_qty', $wg_min_qty_cart_qty, 'yes');			            

			            $wg_maximum_gift = sanitize_text_field( $_REQUEST['wg_maximum_gift'] );
			            update_option('wg_maximum_gift', $wg_maximum_gift, 'yes');


			            $wg_gift_title = sanitize_text_field( $_REQUEST['wg_gift_title'] );
			            update_option('wg_gift_title', $wg_gift_title, 'yes');

			            $wg_gift_title_font_size = sanitize_text_field( $_REQUEST['wg_gift_title_font_size'] );
			            update_option('wg_gift_title_font_size', $wg_gift_title_font_size, 'yes');
            		}
          		}
        	}	
      	}


      	function init() {
      		add_action('admin_menu', array($this, 'OCWG_create_menu'));
         	add_action( 'wp_ajax_nopriv_wg_product_ajax',array($this, 'OCWG_product_ajax') );
         	add_action( 'wp_ajax_wg_product_ajax', array($this, 'OCWG_product_ajax') );
         	add_action( 'init',  array($this, 'OCWG_save_options'));
      	}

      	public static function instance() {
         	if (!isset(self::$instance)) {
            	self::$instance = new self();
            	self::$instance->init();
         	}
         	return self::$instance;
      	}

   	}
   	OCWG_menu::instance();
}

