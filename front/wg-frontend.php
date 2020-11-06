<?php

if (!defined('ABSPATH'))
  exit;

if (!class_exists('OCWG_front')) {

    class OCWG_front {

        protected static $instance;
       
        function OCWG_frontdesign() {
            
            global $post, $woocommerce;

            $wg_gift_rule = get_option( 'wg_gift_rule' );
            $wg_gift_combo = get_option( 'wg_gift_combo' );
            $wg_min_cart_qty = get_option( 'wg_min_cart_qty', 1 );
            $wg_maximum_gift = get_option( 'wg_maximum_gift', 1 );
            $wg_min_qty_cart_qty = get_option( 'wg_min_qty_cart_qty',  1 );
            $prod_line_count = count(WC()->cart->get_cart());
            $cart_total_qty_count = WC()->cart->get_cart_contents_count();
             

            if($wg_gift_rule == "custom") {
                
            	$wg_combo = get_option( 'wg_combo' );
                $combo_rule_qty = 0;
                $prod_line = 0;
                $cart_product = array();


                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

                    if($cart_item['variation_id'] != 0){
                        $pid = $cart_item['variation_id'];
                    }else{
                        $pid = $cart_item['product_id'];
                    }

                    if(in_array($pid, $wg_combo)){
                        $combo_rule_qty += $cart_item['quantity'];
                        $prod_line += 1;
                    }
                
                }
                
                if($wg_min_qty_cart_qty <= $combo_rule_qty && $wg_min_cart_qty <= $prod_line){
                    $this->OCWG_free_item_slider( $post->ID );
                }
            }


            if($wg_gift_rule == "price") {
                
            	$wg_price = get_option( 'wg_price' );
                $cart_total = 0;
                $pline = 0;
                $pqty = 0;

                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

                    if($cart_item['variation_id'] != 0) {
                        $pid = $cart_item['variation_id'];
                    } else {
                        $pid = $cart_item['product_id'];
                    }

                    if(!in_array($pid, $wg_gift_combo)) {
                        $cart_total += $cart_item['line_subtotal'];
                    }
                }
                

                if($wg_price <= $cart_total && $prod_line_count >= $wg_min_cart_qty && $cart_total_qty_count >= $wg_min_qty_cart_qty) {
                    $this->OCWG_free_item_slider( $post->ID );
                }
            }


            if($wg_gift_rule == "category") {
                
                $wg_cat = get_option( 'wg_cat' );
                $cart_total_qty_count = 0;
                $prod_line_count = 0;

                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

                    if($cart_item['variation_id'] != 0) {
                        $pid = $cart_item['variation_id'];
                    } else {
                        $pid = $cart_item['product_id'];
                    }
                    
                    if(!in_array($pid, $wg_gift_combo)) {
                        $terms = get_the_terms ( $cart_item['product_id'], 'product_cat' );

                        foreach ($terms as $key => $value) {
                            if (in_array($value->term_id, $wg_cat)) {
                                $cart_total_qty_count += $cart_item['quantity'];
                                $prod_line_count += 1;
                            }
                        }

                    }
                }

                if($wg_min_cart_qty <= $prod_line_count && $wg_min_qty_cart_qty <= $cart_total_qty_count) {
                    $this->OCWG_free_item_slider( $post->ID );
                }
            }
        }


        function OCWG_free_item_slider($post_id) {
            ?>
                <div class="wg_gift">
                    <p style="font-size: <?php echo get_option( 'wg_gift_title_font_size', '24px' ); ?>"><?php echo get_option( 'wg_gift_title', 'Select Your Gift' ); ?></p>
                    <div class="wg_gift_slider owl-carousel owl-theme">
                        <?php
                            $gift_pro = get_option( 'wg_gift_combo' );
                            foreach ($gift_pro as $value) {
                                $productc = wc_get_product( $value );
                                $title = $productc->get_name();
                                ?>
                                    <div class="item wg_gift_product">
                                        <a href="<?php echo get_permalink( $productc->get_id() ); ?>">
                                            <div><?php echo $productc->get_image(); ?></div>
                                            <div class="wg_title"><?php echo $title; ?></div>
                                            <div class="wg_gift_atc_btn">
                                                <a href="<?php echo home_url(); ?>?action=ocwg_giftred&ocwg_prod=<?php echo $value; ?>" class="single_add_to_cart_button button alt">Add to cart</a>
                                            </div>
                                        </a>
                                    </div>
                                <?php
                            }
                        ?>
                    </div>
                </div>
            <?php
        }


        function OCWG_owl_script() {
            ?>
            <script type="text/javascript">
                setInterval(function(){ 
                    jQuery('.wg_gift_slider').owlCarousel({
                        loop:false,
                        margin:10,
                        nav:true,
                        dots: true,
                        autoplay:true,
                        autoplayTimeout:1000,
                        autoplayHoverPause:true,
                        responsive:{
                            0:{
                                items:1
                            },
                            600:{
                                items:3
                            },
                            1000:{
                                items:5
                            }
                        }
                    })
                }, 1000);
            </script>
            <?php
        }


        function OCWG_add_custom_price( $cart_object ) { 
            
            if ( is_admin() && ! defined( 'DOING_AJAX' ) )
            return;

            if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
            return;

            global $post, $woocommerce;

           	$wg_gift_rule = get_option( 'wg_gift_rule' );
            $wg_gift_combo = get_option( 'wg_gift_combo');
            $wg_min_cart_qty = get_option( 'wg_min_cart_qty', 1 );
            $wg_maximum_gift = get_option( 'wg_maximum_gift', 1 );
            $wg_min_qty_cart_qty = get_option( 'wg_min_qty_cart_qty',  1 );
            $prod_line_count = count(WC()->cart->get_cart());
            $cart_total_qty_count = WC()->cart->get_cart_contents_count();
            
            if($wg_gift_rule == "custom") {

                $wg_combo = get_option( 'wg_combo' );
                $combo_rule_qty = 0;
                $prod_line = 0;
                $cart_product = array();


                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

                    if($cart_item['variation_id'] != 0){
                        $pid = $cart_item['variation_id'];
                    }else{
                        $pid = $cart_item['product_id'];
                    }

                    if(in_array($pid, $wg_combo)){
                        $combo_rule_qty += $cart_item['quantity'];
                        $prod_line += 1;
                    }
                
                }
                
                if($wg_min_qty_cart_qty <= $combo_rule_qty && $wg_min_cart_qty <= $prod_line){
                    $this->OCWG_setfree_product($cart_object, $wg_gift_combo, $wg_maximum_gift);   
                }
            }


            if($wg_gift_rule == "price") {
                $wg_price = get_option( 'wg_price' );
                $cart_total = 0;
                $pline = 0;
                $pqty = 0;

                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

                    if($cart_item['variation_id'] != 0) {
                        $pid = $cart_item['variation_id'];
                    } else {
                        $pid = $cart_item['product_id'];
                    }

                    if(!in_array($pid, $wg_gift_combo)) {
                        $cart_total += $cart_item['line_subtotal'];
                    }
                }
                

                if($wg_price <= $cart_total && $prod_line_count >= $wg_min_cart_qty && $cart_total_qty_count >= $wg_min_qty_cart_qty) {
                    $this->OCWG_setfree_product($cart_object, $wg_gift_combo, $wg_maximum_gift);  
                }
            }


            if($wg_gift_rule == "category") {
               
                $wg_cat = get_option( 'wg_cat' );
                $cart_total_qty_count = 0;
                $prod_line_count = 0;

                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

                    if($cart_item['variation_id'] != 0) {
                        $pid = $cart_item['variation_id'];
                    } else {
                        $pid = $cart_item['product_id'];
                    }
                    
                    if(!in_array($pid, $wg_gift_combo)) {
                        $terms = get_the_terms ( $cart_item['product_id'], 'product_cat' );

                        foreach ($terms as $key => $value) {
                            if (in_array($value->term_id, $wg_cat)) {
                                $cart_total_qty_count += $cart_item['quantity'];
                                $prod_line_count += 1;
                            }
                        }

                    }
                }

                if($wg_min_cart_qty <= $prod_line_count && $wg_min_qty_cart_qty <= $cart_total_qty_count) {
                    $this->OCWG_setfree_product($cart_object, $wg_gift_combo, $wg_maximum_gift);
                }
            }

        }


        function OCWG_setfree_product($cart_object, $wg_gift_combo, $wg_maximum_gift) {
            $custom_price = 0;
            $new_qty = 1;
            $d_qty=0;
            foreach ( $cart_object->cart_contents as $key => $value ) {
                
                if($d_qty < $wg_maximum_gift) {
                    if($value['variation_id'] != 0) {
                        if(in_array($value['variation_id'], $wg_gift_combo)) {
                            $value['data']->price = $custom_price;
                            $value['data']->set_price($custom_price);  
                            $cart_object->set_quantity( $key, $new_qty );
                            $d_qty = $d_qty+1;
                        } elseif(in_array($value['product_id'], $wg_gift_combo)) {
                        	$value['data']->price = $custom_price;
                            $value['data']->set_price($custom_price);
                            $cart_object->set_quantity( $key, $new_qty );
                            $d_qty = $d_qty+1;
                        }
                    }else{
                        if(in_array($value['product_id'], $wg_gift_combo)) {
                            $value['data']->price = $custom_price;
                            $value['data']->set_price($custom_price);
                            $cart_object->set_quantity( $key, $new_qty );
                            $d_qty = $d_qty+1;
                        }
                    }
                }

            }   
        }

        
        function OCWG_init_action() {
            if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'ocwg_giftred' && isset($_REQUEST['ocwg_prod']) && $_REQUEST['ocwg_prod'] !='') {

                $prod_id = $_REQUEST['ocwg_prod'];

                $product = wc_get_product( $prod_id );

                $prod_type = $product->get_type();

                if($prod_type == 'simple') {
                	WC()->cart->add_to_cart( $prod_id );
                }
                
                if($prod_type == 'simple') {
                	WC()->cart->add_to_cart( $prod_id );
					wp_safe_redirect( wc_get_cart_url() );
					exit;
                } else {
                	$url = get_permalink( $prod_id );
                	wp_redirect( $url );
					exit;
                }
            }
        }


        function init() {
            add_action( 'woocommerce_after_cart_table', array($this, 'OCWG_frontdesign' ));
            add_action( 'wp_footer', array($this, 'OCWG_owl_script' ));
            add_action( 'woocommerce_before_calculate_totals', array($this, 'OCWG_add_custom_price' ));
            add_action( 'wp', array($this, 'OCWG_init_action' ));
        }

        public static function instance() {
            if (!isset(self::$instance)) {
                self::$instance = new self();
                self::$instance->init();
            }
            return self::$instance;
        }

    }
    OCWG_front::instance();
}




