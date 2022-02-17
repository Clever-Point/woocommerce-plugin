<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://cleverpoint.gr/
 * @since      1.0.0
 *
 * @package    Clever_Point_For_Woocommerce
 * @subpackage Clever_Point_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Clever_Point_For_Woocommerce
 * @subpackage Clever_Point_For_Woocommerce/admin
 * @author     Clever Point <info@cleverpoint.gr>
 */
class Clever_Point_For_Woocommerce_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/clever-point-for-woocommerce-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/clever-point-for-woocommerce-admin.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script($this->plugin_name);
        wp_localize_script($this->plugin_name, 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
	}

    public function clever_point_orders_metabox() {
        add_meta_box(
            'clever-point-metabox',
            __('Clever Point', $this->plugin_name),
            [$this, 'clever_point_orders_metabox_content'],
            'shop_order', 'side', 'core'
        );
    }

    function clever_point_add_section( $sections ) {
        $sections['clevepoint'] = __( 'Clever Point', 'clever-point-for-woocommerce' );
        return $sections;
    }

    function clever_point_all_settings( $settings, $current_section ) {
        if ( $current_section == 'clevepoint' ) {
            $settings_slider = array();
            // Add Title to the Settings
            $settings_slider[] = array(
                'name' => __( 'Clever Point Settings', 'clever-point-for-woocommerce' ),
                'type' => 'title',
                'desc' => __( 'The following options are used to configure Clever Point.', 'clever-point-for-woocommerce' ),
                'id' => 'clevepoint' );

            $settings_slider[] = array(
                'id' => 'clever_point_api_key',
                'title'       => __('Clever point API key','clever-point-for-woocommerce'),
                'type'        => 'text',
                'desc_tip' => '',
                'default'     => '',
            );

            $settings_slider[] = array(
                'id' => 'clever_point_google_key',
                'title'       => __('Google key','clever-point-for-woocommerce'),
                'type'        => 'text',
                'desc_tip' => __('This is used by Clever Point to display map correctly','clever-point-for-woocommerce'),
                'default'     => '',
            );

            $settings_slider[] = array(
                'id' => 'clever_point_trigger_method',
                'title'   => __( 'Trigger', 'clever-point-for-woocommerce' ),
                'type'    => 'select',
                'class'   => 'wc-enhanced-select',
                'default' => 'embed',
                'options' => array(
                    'embed' => __( 'Embed', 'clever-point-for-woocommerce' ),
                    'modal'    => _x( 'Modal', 'Trigger', 'clever-point-for-woocommerce' ),
                ),
            );

            $settings_slider[] = array(
                'id' => 'clever_point_modal_width',
                'title'   => __( 'Map Width (in px)', 'clever-point-for-woocommerce' ),
                'type'    => 'number',
                'default' => '400',
            );

            $settings_slider[] = array(
                'id' => 'clever_point_modal_height',
                'title'   => __( 'Map Height (in px)', 'clever-point-for-woocommerce' ),
                'type'    => 'number',
                'default' => '400',
            );

            $settings_slider[] = array(
                'id' => 'clever_point_tax_status',
                'title'   => __( 'Tax status', 'woocommerce' ),
                'type'    => 'select',
                'class'   => 'wc-enhanced-select',
                'default' => 'taxable',
                'options' => array(
                    'taxable' => __( 'Taxable', 'woocommerce' ),
                    'none'    => _x( 'None', 'Tax status', 'woocommerce' ),
                ),
            );

            $settings_slider[] = array(
                'id' => 'clever_point_charges',
                'title'       => __('Charges','clever-point-for-woocommerce'),
                'type'        => 'checkbox',
                'default' => "yes",
                'desc'     => __( 'Add service fee as order fee', 'clever-point-for-woocommerce' ),
            );

            $settings_slider[] = array(
                'id' => 'clever_point_test_mode',
                'title'       => __('Test Mode','clever-point-for-woocommerce'),
                'type'        => 'checkbox',
                'default' => "yes",
                'desc'     => __( 'Test Mode', 'clever-point-for-woocommerce' ),
            );

            $settings_slider[] = array( 'type' => 'sectionend', 'id' => 'clevepoint' );
            return $settings_slider;

        } else {
            return $settings;
        }
    }

    public function clever_point_orders_metabox_content($post) {
        $order = wc_get_order($post->ID);
        $shipping_methods=$order->get_shipping_methods();
        $shipping_method = array_shift($shipping_methods);
        $shipping_method_id = $shipping_method['method_id'];

        if ($shipping_method_id!=='clever_point_shipping_class') {
            return;
        }

        $_clever_point_response=$order->get_meta('_clever_point_response');
        $lock_me= !empty($_clever_point_response) ? 'disabled' : '';

        $weight = 0;
        foreach ($order->get_items() as $item) {
            $product_variation_id = $item['variation_id'];
            if ($product_variation_id) {
                $product = wc_get_product($item['variation_id']);
            } else {
                $product = wc_get_product($item['product_id']);
            }
            if ($product) {
                $weight += floatval($product->get_weight()) * intval($item->get_quantity());
            }
        }
        ?>
        <div class="clever-point-field-group">
            <div class="clever-point-field">
                <?php _e("Clever Point Station", $this->plugin_name); ?>:
                <?php echo (empty($order->get_meta('clever_point_station_details')) ? '-' : "<br>".$order->get_meta('clever_point_station_details')) ;?>
            </div>
            <div class="clever-point-field">
                <?php _e("Voucher", $this->plugin_name); ?>: <?php echo($_clever_point_response['ShipmentAwb'] ?? '-'); ?><br>
            </div>
            <div class="clever-point-field">
                <?php _e('Status',$this->plugin_name);?> <?php echo($_clever_point_response['ShipmentStatus'] ?? '-'); ?>
            </div>
            <div class="clever-point-field">
                <?php
                if (!empty($_clever_point_response['ShipmentAwb'])) {
                    if ( false === ( $clever_point_tracking = get_transient( 'clever_point_tracking_order_'.$order->get_id() ) ) ) {
                        $args = array(
                            'headers' => array(
                                'Authorization' => 'ApiKey ' . get_option('clever_point_api_key', null),
                            ),
                        );
                        $request = wp_remote_get(CLEVER_POINT_API_ENDPOINT . "/ShipmentTracking/" . $_clever_point_response['ShipmentAwb'], $args);

                        if (is_wp_error($request) || wp_remote_retrieve_response_code($request) != 200) {
                            die();
                        }

                        $response = wp_remote_retrieve_body($request);
                        if ($response) {
                            $response_array = json_decode($response, true, 512, JSON_UNESCAPED_UNICODE);
                            if (!empty($response_array['Content']['TrackingData'])) {
                                $clever_point_tracking = end($response_array['Content']['TrackingData'])['TrackingNote'];
                            }
                        }
                        set_transient( 'clever_point_tracking_order_'.$order->get_id(), $clever_point_tracking, 30 * MINUTE_IN_SECONDS );
                    }
                }
                ?>
                <?php _e('Track & Trace',$this->plugin_name);?>: <?php echo ($clever_point_tracking ?? '-'); ?>
            </div>
        </div>
        <div class="clever-point-field-group">
            <div class="clever-point-field">
                <div class="clever-point-field-label">
                    <label for="clever_point_parcels"><?php echo __('Parcels', $this->plugin_name); ?></label>
                </div>
                <div class="clever-point-field-input">
                    <input  <?php echo $lock_me;?>  id="clever_point_parcels" value="<?php echo (get_post_meta($post->ID, 'clever_point_parcels', true)>0 ? get_post_meta($post->ID, 'clever_point_parcels', true) : 1); ?>" type="text" name="clever_point_parcels">
                </div>
            </div>
            <div class="clever-point-field">
                <div class="clever-point-field-label">
                    <label for="clever_point_weight"><?php echo __('Weight (in kg)', $this->plugin_name); ?></label>
                </div>
                <div class="clever-point-field-input">
                    <input  <?php echo $lock_me;?>  id="clever_point_weight" value="<?php echo((get_post_meta($post->ID, 'clever_point_weight', true)>0 ? get_post_meta($post->ID, 'clever_point_weight', true) : $weight>0) ? $weight : 0.5); ?>" type="text" name="clever_point_weight">
                </div>
            </div>
            <?php if ($order->get_payment_method() == "cod") : ?>
                <div class="clever-point-field">
                    <div class="clever-point-field-label">
                        <label for="clever_point_cod"><?php echo __('Cash on delivery price', $this->plugin_name); ?></label>
                    </div>
                    <div class="clever-point-field-input">
                        <input  <?php echo $lock_me;?>  id="clever_point_cod" type="text" placeholder="0.0" value="<?php echo(get_post_meta($post->ID, 'clever_point_cod', true) ? number_format(get_post_meta($post->ID, 'clever_point_cod', true), 2) : $order->get_total()); ?>" name="clever_point_cod">
                    </div>
                </div>
            <?php else: ?>
                <input type="hidden" name="clever_point_cod" value="0">
            <?php endif; ?>
            <div class="clever-point-field">
                <div class="clever-point-field-label">
                    <label for="clever_point_comments"><?php _e('Comments', $this->plugin_name); ?></label>
                </div>
                <div class="clever-point-field-input">
                    <textarea  <?php echo $lock_me;?>  name="clever_point_comments" id="clever_point_comments" cols="25" rows="5"><?php echo !empty(get_post_meta($post->ID, 'clever_point_comments', true)) ? get_post_meta($post->ID, 'clever_point_comments', true) : $order->get_customer_note(); ?></textarea>
                </div>
            </div>
            <p>
                <button <?php echo $lock_me;?>  data-order="<?php echo $post->ID; ?>" type="button" class="button has-spinner" id="clever_point_create_voucher" data-error="<?php _e('There was an error issuing the voucher.',$this->plugin_name);?>" data-success="<?php _e('Vouchers have been created!',$this->plugin_name);?>"><?php _e('Create voucher',
                        $this->plugin_name); ?> <span class="we_spinner"></span> </button>
        </div>

        <?php
        $lock_me= !empty($_clever_point_response) ? '' : 'disabled';
        ?>
        <div class="clever-point-field-group">
            <div class="clever-point-field">
                <div class="clever-point-field-label">
                    <label for="clever_point_print_voucher_type"><?php echo __('Print voucher', $this->plugin_name); ?></label>
                </div>
                <select id="clever_point_print_voucher_type" name="clever_point_print_voucher_type" >
                    <option value="singlepdf"><?php _e('Single (A4 - 1 / page)');?></option>
                    <option value="singlepdf_a5"><?php _e('Single (A5 - 1 / page)');?></option>
                    <option value="image_double"><?php _e('Double (A4 - 2 / page)');?></option>
                    <option value="image"><?php _e('Triple (A4 - 3 / page)');?></option>
                    <option value="voucher_quad"><?php _e('Quadruple (A4 - 4 / page)');?></option>
                    <option value="image10"><?php _e('Single (A7 - 1 / page)');?></option>
                </select>
                <div class="clever-point-field-label">
                    <button <?php echo $lock_me;?> data-order="<?php echo $post->ID; ?>" type="button" class="button has-spinner" id="clever_point_print_voucher" <?php echo(!empty($_clever_point_response['ShipmentAwb']) ? '' : 'disabled') ?>>
                        <?php _e('Print voucher', $this->plugin_name); ?> <span class="we_spinner"></span></button>
                </div>
            </div>
        </div>

        <div class="clever-point-field-group">
            <div class="clever-point-field-label">
                <label for="clever_point_cancel_voucher"><?php echo __('Cancel voucher', $this->plugin_name); ?></label>
            </div>
            <div class="clever-point-field">
                <button <?php echo $lock_me;?> data-order="<?php echo $post->ID; ?>" type="button" class="button has-spinner clever_point_cancel_voucher" data-success="<?php _e('Voucher has been cancelled',$this->plugin_name);?>" id="clever_point_cancel_voucher">
                    <?php _e('Cancel voucher', $this->plugin_name); ?> <span class="we_spinner"></span></button><br>
            </div>
        </div>

        <?php
    }

    function clever_point_create_voucher_process($args) {
        $order_id = isset($args['order_id']) ? sanitize_text_field($args['order_id']) : null;
        $order=wc_get_order($order_id);
        $comments = isset($args['comments']) ? sanitize_text_field($args['comments']) : apply_filters('clever_point_voucher_customer_note',$order->get_customer_note());;
        $comments = apply_filters('clever_point_voucher_custom_comments',$comments,$order_id);
        $cod = isset($args['cod']) ? floatval($args['cod']) : 0;
        $weight = isset($args['weight']) ? floatval($args['weight']) : 0;
        $parcels = isset($args['parcels']) ? floatval($args['parcels']) : 1;
        $weight_per_parcel=round($weight/$parcels,2);
        $first_name=!empty($order->get_shipping_first_name()) ? $order->get_shipping_first_name() : $order->get_billing_first_name();
        $last_name=!empty($order->get_shipping_last_name()) ? $order->get_shipping_last_name() : $order->get_billing_last_name();
        $address_1=!empty($order->get_shipping_address_1()) ? $order->get_shipping_address_1() : $order->get_billing_address_1();
        $state=!empty($order->get_shipping_state()) ? $order->get_shipping_state() : $order->get_billing_state();
        $city=!empty($order->get_shipping_city()) ? $order->get_shipping_city() : $order->get_billing_city();
        $post_code=!empty($order->get_shipping_postcode()) ? $order->get_shipping_postcode() : $order->get_billing_postcode();
        $country = !empty($order->get_shipping_country()) ? $order->get_shipping_country() : $order->get_billing_country();
        update_post_meta($order_id,'clever_point_parcels',$parcels);

        $Shipping=[];
        $Shipping['ItemsDescription']="Order {$order->get_id()}";
        if (!empty($comments))
            $Shipping['PickupComments']=$comments;

        $Shipping['Consignee']=[
            'ContactName'=>"$first_name $last_name",
            'Address'=>"$address_1",
            'Area'=>WC()->countries->get_states( $country )[$state],
            'City'=>$city,
            'PostalCode'=>$post_code,
            'Phones'=>$order->get_billing_phone(),
            'NotificationPhone'=>$order->get_billing_phone(),
            'Emails'=>$order->get_billing_email(),
            'ShipmentCost'=> $order->get_total()-$order->get_total_refunded(),
            'CustomerReferenceId'=>$order->get_id()
        ];
        $Shipping['DeliveryStation']=$order->get_meta('_clever_point_station_id');

        if ($cod>0) {
            $Shipping['CODs']=[];
            array_push($Shipping['CODs'],['Amount'=>['CurrencyCode'=>'EUR','Value'=>$cod]]);
        }

        $Shipping['Items']=[];

        for ($x = 1; $x <= $parcels; $x++) {
            $to_push = [
                'Description'=>__('Order','')." $x/$parcels",
                'IsFragile'=>'false',
                'Weight'=>[
                    'UnitType'=>'kg',
                    'Value' => $weight_per_parcel > 0 ? $weight_per_parcel : 0.5
                ]
            ];
            array_push($Shipping['Items'],$to_push);
        }

        $args = array(
            'headers'     => array(
                'Authorization' => 'ApiKey ' . get_option( 'clever_point_api_key',null),
            ),
            'body' => $Shipping
        );
        $request = wp_remote_post( CLEVER_POINT_API_ENDPOINT."/Shipping", $args );

        if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
            die();
        }

        $response = wp_remote_retrieve_body( $request );
        if ($response) {
            $response_array=json_decode($response, true, 512, JSON_UNESCAPED_UNICODE);
            if ($response_array['ResultType']=="Success") {
                $order->update_meta_data('_clever_point_response',$response_array['Content']);
                $order->save();
                return 'success';
            }else {
                wp_send_json(implode(',', array_column($response_array['Messages'], 'Code')));
            }
        }
    }

    function clever_point_create_voucher() {
        $order_id = isset($_POST['order_id']) ? sanitize_text_field($_POST['order_id']) : null;
        $order=wc_get_order($order_id);
        $comments = isset($_POST['comments']) ? sanitize_text_field($_POST['comments']) : apply_filters('clever_point_voucher_customer_note',$order->get_customer_note());;
        $cod = isset($_POST['cod']) ? sanitize_text_field($_POST['cod']) : 0;
        $weight = isset($_POST['weight']) ? sanitize_text_field($_POST['weight']) : 0;
        $parcels = isset($_POST['parcels']) ? sanitize_text_field($_POST['parcels']) : 1;
        wp_send_json($this->clever_point_create_voucher_process(['order_id'=>$order_id,'comments'=>$comments,'cod'=>$cod, 'weight'=>$weight, 'parcels'=>$parcels]));
    }

    function clever_point_cancel_voucher() {
        $order_id = isset($_POST['order_id']) ? sanitize_text_field($_POST['order_id']) : null;
        $order=wc_get_order($order_id);
        if ($order) {
            $_clever_point_response=$order->get_meta('_clever_point_response');
            $awbs=$_clever_point_response['ShipmentAwb'];
            $args = array(
                'headers' => array(
                    'Authorization' => 'ApiKey ' . get_option('clever_point_api_key', null),
                ),
            );
            $request = wp_remote_post(CLEVER_POINT_API_ENDPOINT . "/Shipping/$awbs/Cancel", $args);

            if (is_wp_error($request) || wp_remote_retrieve_response_code($request) != 200) {
                die();
            }

            $response = wp_remote_retrieve_body($request);
            if ($response) {
                $response_array = json_decode($response, true, 512, JSON_UNESCAPED_UNICODE);
                $order->delete_meta_data('_clever_point_response');
                $order->save();
                if ($response_array['ResultType']=="Success" && $response_array['Content']['ShipmentStatus']=="Cancel") {
                    wp_send_json(['success'=>1]);
                }else {
                    wp_send_json(implode(',', array_column($response_array['Messages'], 'Description')));
                }
            }
        }
    }

    function clever_point_print_voucher() {
        $print_type = isset($_POST['print_type']) ? sanitize_text_field($_POST['print_type']) : 'singlepdf';
        $order_id = isset($_POST['order_id']) ? sanitize_text_field($_POST['order_id']) : null;
        $order=wc_get_order($order_id);
        if ($order) {
            $_clever_point_response=$order->get_meta('_clever_point_response');
            $awbs=$_clever_point_response['ShipmentAwb'];

            $args = array(
                'headers' => array(
                    'Authorization' => 'ApiKey ' . get_option('clever_point_api_key', null),
                ),
            );
            $request = wp_remote_get(CLEVER_POINT_API_ENDPOINT . "/Vouchers/?awbs=" . $awbs."&template=$print_type", $args);

            if (is_wp_error($request) || wp_remote_retrieve_response_code($request) != 200) {
                die();
            }

            $response = wp_remote_retrieve_body($request);
            if ($response) {
                $response_array = json_decode($response, true, 512, JSON_UNESCAPED_UNICODE);
                if ($response_array['ResultType'] == "Success") {
                    $file = wp_upload_bits("$awbs.pdf", null, base64_decode($response_array['Content']['Document']));
                    wp_send_json($file);
                } else {

                }
            }
        }
    }
}

if (!function_exists('write_log')) {

    function write_log($log) {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }

}