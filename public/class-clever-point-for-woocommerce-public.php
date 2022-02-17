<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://cleverpoint.gr/
 * @since      1.0.0
 *
 * @package    Clever_Point_For_Woocommerce
 * @subpackage Clever_Point_For_Woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Clever_Point_For_Woocommerce
 * @subpackage Clever_Point_For_Woocommerce/public
 * @author     Clever Point <info@cleverpoint.gr>
 */
class Clever_Point_For_Woocommerce_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Clever_Point_For_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Clever_Point_For_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/clever-point-for-woocommerce-public.css', array(), $this->version, 'all' );
        wp_add_inline_style( $this->plugin_name, '
    .modal__container {
    max-width: '.intval(get_option('clever_point_modal_width',400)+60).'px;
    height: '.get_option('clever_point_modal_height',400).'px;
    }' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Clever_Point_For_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Clever_Point_For_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        wp_register_script( "micromodal", plugin_dir_url( __FILE__ ) . 'js/micromodal.min.js', [], $this->version, false );
        wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/clever-point-for-woocommerce-public.js', array( 'jquery'), $this->version, false );
        if (get_option('clever_point_test_mode',null)=='yes') {
            wp_register_script("cleverpoint-map", 'https://test.cleverpoint.gr/portal/content/clevermap_v2/script/cleverpoint-map.js', [], $this->version, false);
        }else {
            wp_register_script("cleverpoint-map", 'https://platform.cleverpoint.gr/portal/content/clevermap_v2/script/cleverpoint-map.js', [], $this->version, false);
        }

        if (is_checkout() || is_cart()) {
            wp_enqueue_script($this->plugin_name);
            wp_enqueue_script('micromodal');
            wp_enqueue_script('cleverpoint-map');
        }
    }

    function clever_point_shipping_maps_displayed() {
        if (empty(get_option( 'clever_point_api_key',null))) {
            return;
        }

        $chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );
        if (strpos( $chosen_shipping_methods[0], ':') !== false) {
            $chosen_shipping_method = substr( $chosen_shipping_methods[0], 0, strpos( $chosen_shipping_methods[0], ':' ) );
        }else {
            $chosen_shipping_method = $chosen_shipping_methods[0];
        }
        ?>
        <div class="shop_table clevermap-container" style="<?php echo ($chosen_shipping_method == 'clever_point_shipping_class' ? 'display:block;' : '');?>">
            <div id="clevermap-output">

            </div>
        <?php if (get_option('clever_point_trigger_method','embed')=="embed") {
            ?>
            <div id="clevermap" style="height: 500px"></div>
            <script>
                clevermap({
                    selector: '#clevermap',
                    cleverPointKey: '<?php echo get_option( 'clever_point_api_key',null);?>',
                    googleMapKey: '<?php echo get_option( 'clever_point_google_key',null);?>',
                    header: false,
                    defaultAddress: '',
                    onselect: (point) => {
                        document.getElementById("clever_point_station_id").value = point.StationId;
                        document.getElementById("clever_point_station_details").value = point.Name + "\n" + point.AddressLine1  + "\n" + point.City + ", " + point.ZipCode + "\n" + point.Phones;
                        document.getElementById("clever_point_prefix").value = point.Prefix;

                        document.getElementById("clevermap-output").innerHTML = "<div class='inner'><strong><?php _e('Selected collection point','clever-point-for-woocommerce');?></strong><br>" + point.Name + "<br>" + point.AddressLine1  + "<br>" + point.City + ", " + point.ZipCode + "<br>" + point.Phones;
                        document.getElementById("clevermap-output").innerHTML += "</div></div>";
                    },
                    filters: {
                        codAmount: 0
                    } });
            </script>
            <?php
        }else {
            $customer_data      = WC()->session->get('customer');
            ?>
            <div class="modal micromodal-slide" id="modal-1" aria-hidden="true">
                <div class="modal__overlay" tabindex="-1" data-micromodal-close>
                    <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
                        <header class="modal__header">
                            <a class="modal__close" aria-label="Close modal" data-micromodal-close></a>
                        </header>
                        <main class="modal__content" id="modal-1-content">
                            <div id="clevermap" style="width:<?php echo get_option( 'clever_point_modal_width',400);?>px;height: <?php echo get_option( 'clever_point_modal_height',400);?>px"></div>
                        </main>
                    </div>
                </div>
            </div>
            <a id="cleverpoint-modal-trigger" data-micromodal-trigger="modal-1">CleverPoint</a>
            <script>
                MicroModal.init({
                    onShow: modal => console.info(`${modal.id} is shown`), // [1]
                    onClose: modal => console.info(`${modal.id} is hidden`), // [2]
                    openClass: 'is-open', // [5]
                    disableScroll: true, // [6]
                    disableFocus: false, // [7]
                    awaitOpenAnimation: false, // [8]
                    awaitCloseAnimation: false, // [9]
                    debugMode: true // [10]
                });

                clevermap({
                    selector: '#clevermap',
                    cleverPointKey: '<?php echo get_option( 'clever_point_api_key',null);?>',
                    googleMapKey: '<?php echo get_option( 'clever_point_google_key',null);?>',
                    header: false,
                    defaultAddress: '<?php echo $customer_data['address_1'];?>',
                    onselect: (point) => {
                        document.getElementById("clever_point_station_id").value = point.StationId;
                        document.getElementById("clever_point_station_details").value = point.Name + "\n" + point.AddressLine1  + "\n" + point.City + ", " + point.ZipCode + "\n" + point.Phones;
                        document.getElementById("clever_point_prefix").value = point.Prefix;

                        document.getElementById("clevermap-output").innerHTML = "<div class='inner'><strong><?php _e('Selected collection point','clever-point-for-woocommerce');?></strong><br>" + point.Name + "<br>" + point.AddressLine1  + "<br>" + point.City + ", " + point.ZipCode + "<br>" + point.Phones + "<br> <a id='cleverpoint-modal-validate-trigger' href=''><?php _e('Change pickup location','clever-point-for-woocommerce');?></a></div></div>";
                        if (jQuery('#cleverpoint-modal-trigger').length) {
                            setTimeout(function(){
                                MicroModal.close('modal-1');
                            }, 500);
                        }
                    },
                    filters: {
                        codAmount: 0
                    } })

                <?php if ($chosen_shipping_method == 'clever_point_shipping_class' ? 'display:block;' : '') { ?>
                    setTimeout(function(){
                        MicroModal.show('modal-1');
                    }, 500);
                <?php } ?>
            </script>
            <?php
        }
        ?>
        </div>
<?php }
    function clever_point_station_hidden_field( $checkout ) {
        echo '<div id="clever_point_hidden_checkout_fields">
            <input type="hidden" class="input-hidden" name="clever_point_station_id" id="clever_point_station_id" value="">
            <input type="hidden" class="input-hidden" name="clever_point_station_details" id="clever_point_station_details" value="">
            <input type="hidden" class="input-hidden" name="clever_point_prefix" id="clever_point_prefix" value="">
    </div>';
    }

    function save_custom_checkout_hidden_field( $order_id ) {
        if ( ! empty( $_POST['clever_point_station_id'] ) )
            update_post_meta( $order_id, '_clever_point_station_id', sanitize_text_field( $_POST['clever_point_station_id'] ) );
        if ( ! empty( $_POST['clever_point_station_details'] ) )
            update_post_meta( $order_id, 'clever_point_station_details', sanitize_text_field( $_POST['clever_point_station_details'] ) );
        if ( ! empty( $_POST['clever_point_prefix'] ) )
            update_post_meta( $order_id, '_clever_point_prefix', sanitize_text_field( $_POST['clever_point_prefix'] ) );
    }

    function validate_clever_point($fields, $errors) {
        $chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );
        if (strpos( $chosen_shipping_methods[0], ':') !== false) {
            $chosen_shipping_method = substr( $chosen_shipping_methods[0], 0, strpos( $chosen_shipping_methods[0], ':' ) );
        }else {
            $chosen_shipping_method = $chosen_shipping_methods[0];
        }
        if ($chosen_shipping_method ==='clever_point_shipping_class' && empty($_POST[ 'clever_point_station_id' ])) {
            $pick_now= '';
            if (get_option('clever_point_trigger_method', 'embed') == "modal") {
                $pick_now = '<a href="#" id="cleverpoint-modal-validate-trigger">'.__('Pick now','clever-point-for-woocommerce').'</a>';
            }
            $errors->add('clever_point_station_id',sprintf(__('No Clever Point station has been chosen. %s','clever-point-for-woocommerce'),$pick_now,'error'));
        }
    }

    function clever_point_change_cart_shipping_method_full_label( $label, $method ) {
        if ($method->get_method_id()!='clever_point_shipping_class')
            return $label;

        $label = $method->get_label();
        $amount=0;
        if (get_option('clever_point_charges','yes')=='yes') {
            if ( false === $amount = get_transient( "clever_point_short_transient" ) ) {
                $args = array(
                    'headers' => array(
                        'Authorization' => 'ApiKey ' . get_option('clever_point_api_key', null),
                    ),
                );
                $request = wp_remote_get(CLEVER_POINT_API_ENDPOINT . "/Shipping/GetPrices", $args);
                if (is_wp_error($request) || wp_remote_retrieve_response_code($request) != 200) {
                    die();
                }
                $response = wp_remote_retrieve_body($request);
                if ($response) {
                    $response_array = json_decode($response, true, 512, JSON_UNESCAPED_UNICODE);
                    $amount = $response_array['Content'][0]['Price']['Value'];
                }
                set_transient( "clever_point_short_transient", $amount, 15 * MINUTE_IN_SECONDS );
            }

            if ( !WC()->cart->display_prices_including_tax() ) {
                $taxes = WC_Tax::get_rates_for_tax_class( get_option('woocommerce_shipping_tax_class') );
                if (get_option( 'clever_point_tax_status','taxable' )=='taxable' && is_array($taxes)) {
                    $first_tax=reset($taxes);
                    $amount = $amount/(1+($first_tax->tax_rate/100));
                }
            }

            if ($amount>0) {
                $label.="<br><small>".__('Service cost','clever-point-for-woocommerce').": ".wc_price($amount)."</small>";
            }
        }
        if ($method->cost + $method->get_shipping_tax()>0) {
            $label.="<br><small>".__('Shipping cost','clever-point-for-woocommerce').": ".wc_price($method->cost + $method->get_shipping_tax() - $amount)."</small>";
        }
        return $label;
    }
}