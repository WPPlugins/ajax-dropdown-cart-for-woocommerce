<?php
/**
 * Ajax Drop Cart Class
 *
 * Main class
 *
 * @author 		Netbase
 * @version 	1.0.0
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

if (!class_exists('NB_ADCart')) :

    class NB_ADCart {

        var $settings;
        var $icon_styles;
        private $_active = false;
        private $active_url = 'http://cmsmart.net/activedomain/netbase/';
        private $remove_url = 'http://cmsmart.net/removedomain/netbase/';
        private $product_sku = 'WPP1136';

        public function __construct() {


            $this->settings = get_option("nbadcart_plugin_settings");
            $this->icon_styles = array(
                1 => 'style1',
                2 => 'style2',
                3 => 'style3',
                4 => 'style4',
                5 => 'style5',
                6 => 'style6',
                7 => 'style7',
                8 => 'style8',
                9 => 'style9',
                10 => 'style10',
            );

            /** Check WooCommerce Instalation * */
            add_action('wp_head', array($this, 'woostore_check_environment'));
            //add_action('wp_head', array($this, 'netbase_print_dynamic_css'));
            //add_action( 'widgets_init', array( $this, 'register_widgets' ) );
            add_action('wp_enqueue_scripts', array($this, 'netbase_drop_cart_stylesheets'));
            add_action('wp_enqueue_scripts', array($this, 'netbase_drop_cart_scripts'), 100);

            add_action('wp_ajax_woocommerce_remove_from_cart', array(&$this, 'woocommerce_ajax_remove_from_cart'), 1000);
            add_action('wp_ajax_nopriv_woocommerce_remove_from_cart', array(&$this, 'woocommerce_ajax_remove_from_cart'), 1000);

            add_action('wp_ajax_add_to_cart_single', array(&$this, 'woocommerce_add_to_cart_single'));
            add_action('wp_ajax_nopriv_add_to_cart_single', array(&$this, 'woocommerce_add_to_cart_single'));

            add_action('wp_ajax_woocommerce_add_to_cart_variable_rc', array(&$this, 'woocommerce_add_to_cart_variable_rc_callback'));
            add_action('wp_ajax_nopriv_woocommerce_add_to_cart_variable_rc', array(&$this, 'woocommerce_add_to_cart_variable_rc_callback'));

            add_filter('add_to_cart_fragments', array(&$this, 'woocommerce_header_add_to_cart_fragment'));

            add_shortcode('nbadcart_widget', array($this, 'nbadcart_widget_shortcode'));

            if (is_admin()) {

                add_action('init', array($this, 'pw_add_image_sizes'));
                add_filter('image_size_names_choose', array($this, 'pw_show_image_sizes'));
                // create custom plugin settings menu
                add_action('admin_menu', array($this, 'nbajaxdropcart_create_menu'));
                add_action('admin_enqueue_scripts', array($this, 'netbase_drop_cart_admin_stylesheets'));
                add_action('admin_enqueue_scripts', array($this, 'netbase_drop_cart_admin_scripts'));

                add_action('wp_ajax_ajc_active_license', array($this, 'active_license_func'));
                add_action('wp_ajax_ajc_remove_license', array($this, 'remove_license_func'));
                $this->_active = $this->check_license();
            }
        }

        public function active_license_func() {
            $path = NB_AJAX_DROP_CART_PATH . 'key/key_info.json';
            if (!wp_verify_nonce($_POST['nonce'], 'nonce-license')) {
                die('Security error');
            }
            $mes = '';
            $ip = $this->geturlsite();
            if (isset($_POST['license'])) {
                $license = trim($_POST['license']);

                $result = file_get_contents($this->active_url . $this->product_sku . '/' . $license . '/' . $ip);

                $data = (array) json_decode($result);

                if (sizeof($data) > 0) {
                    switch ($data["code"]) {
                        case -1 :
                            $mes = 'Missing necessary information!';
                            break;
                        case 0 :
                            $mes = 'Incorrect information, check again license key';
                            break;
                        case 1 :
                            $mes = 'Incorrect License key';
                            break;
                        case 2 :
                            $mes = 'License key is locked ';
                            break;
                        case 3 :
                            $mes = 'License key have expired';
                            break;
                        case 4 :
                            $mes = 'Link your website incorrect';
                            break;
                        case 5 :
                            $mes = 'License key can using';
                            break;
                        case 6 :
                            $mes = 'Domain has been added successfully';
                            break;
                        case 7 :
                            $mes = 'Exceed your number of domain license';
                            break;
                        case 8 :
                            $mes = 'Unsuccessfully active license key';
                            break;
                    }
                    $data['last_active'] = time();
                    $data['key'] = $license;
                    $this->write_license(json_encode($data));
                } else {
                    $mes = 'Error! Try again later!';
                }
            }
            echo $mes;
            wp_die();
        }

        public function remove_license_func() {
            if (!wp_verify_nonce($_POST['nonce'], 'nonce-license')) {
                die('Security error');
            }
            $ip = $this->geturlsite();
            $license = '';
            $data = $this->get_license_from_json();
            if (isset($data['key']))
                $license = $data['key'];
            $mes = '';
            if ($license != '') {
                $result = file_get_contents($this->remove_url . $this->product_sku . '/' . $license . '/' . $ip);
                $data = (array) json_decode($result);

                $path = NB_AJAX_DROP_CART_PATH . 'key/key_info.json';
                if (sizeof($data) > 0) {
                    switch ($data["code"]) {
                        case -1:
                            $mes = 'Missing necessary information';
                        case 0:
                            $mes = 'Incorrect information';
                            break;
                        case 1:
                            $mes = 'Incorrect License key';
                            break;
                        case 2:
                            if (!unlink($path)) {
                                $mes = 'Error, try again later!';
                            } else {
                                $mes = 'Remove license key Successfully';
                            }
                            break;
                        case 3:
                            $mes = 'Remove license key Unsuccessfully!';
                            break;
                    }
                } else {
                    $mes = 'Error! Try again later!';
                }
            }
            echo $mes;
            wp_die();
        }

        public function write_license($license) {
            $path = NB_AJAX_DROP_CART_PATH . 'key/key_info.json';
            if ($file_handle = @fopen($path, 'w')) {
                fwrite($file_handle, $license);
                fclose($file_handle);
            }
        }

        function get_license_from_json() {
            $path = NB_AJAX_DROP_CART_PATH . 'key/key_info.json';
            if (!file_exists($path))
                return '';
            $result = file_get_contents($path);
            return (array) json_decode($result);
        }

        public function geturlsite() {
            if (isset($_SERVER['HTTPS'])) {
                $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
            } else {
                $protocol = 'http';
            }
            $base_url = $protocol . "://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER["REQUEST_URI"] . '?') . '/';
            return base64_encode($base_url);
        }

        public function check_license() {
            $result = $this->get_license_from_json();
            $check = true;
            if (!is_array($result)) {
                $check = false;
            } else {
                if ($result['last_active'] - time() > 172800) {
                    $ip = $this->geturlsite();
                    $license = $result['key'];
                    $data = file_get_contents($active_url . $product_sku . '/' . $license . '/' . $ip);
                    $result = json_decode($data);
                    $result['last_active'] = time();
                    $result['key'] = $license;
                    write_license(json_encode($result));
                }
                $expiry_date = $result["expiry-date"];
                if ($expiry_date < time()) {
                    $check = false;
                } else {
                    if ($result['code'] == '5' || $result['code'] == '6') {
                        $check = true;
                    } else {
                        $check = false;
                    }
                }
            }
            return $check;
        }

        public function netbase_print_dynamic_css() {
            $widget_params = get_option('nbadcart_plugin_settings');
            if ($this->settings['adcart-style'] == 'cus') : ?>
                <?php ob_start(); ?>
.nw-cart-drop-content { -webkit-border-radius: <?php echo $widget_params['adcart-border-radius']; ?>px;-moz-border-radius: <?php echo $widget_params['adcart-border-radius']; ?>px;border-radius: <?php echo $widget_params['adcart-border-radius']; ?>px;border:1px solid #<?php echo $widget_params['adcart-background-border-color']; ?>;width:<?php echo $widget_params['adcart-width']; ?>px;}
.nw-drop-cart .nw-cart-drop-content-in a.button{color:#<?php echo $widget_params['adcart-button-text-color']; ?>;background:#<?php echo $widget_params['adcart-button-bg-color']; ?> }
.nw-drop-cart a.button:hover{background:#<?php echo $widget_params['adcart-button-bghv-color']; ?> }
.nw-cart-drop-toggle { -webkit-border-radius: <?php echo $widget_params['adcart-border-radius']; ?>px;-moz-border-radius: <?php echo $widget_params['adcart-border-radius']; ?>px;border-radius: <?php echo $widget_params['adcart-border-radius']; ?>px;border:1px solid #<?php echo $widget_params['adcart-background-border-color']; ?>;background-color: #<?php echo $widget_params['adcart-background-color']; ?>;color:#<?php echo $widget_params['adcart-text-color']; ?> }
.nw-cart-contents, .nw-cart-drop-content { color:#<?php echo $widget_params['adcart-text-color']; ?>; }
.nw-cart-drop-content a { color:#<?php echo $widget_params['adcart-link-color']; ?>; }
.nw-cart-drop-content a:hover{ color:#<?php echo $widget_params['adcart-link-hover-color']; ?>; }
.icns-adcartfont { color:#<?php echo $widget_params['adcart-icon-color']; ?> !important; }
            <?php else : ?>
                <?php ob_start(); ?>
.icns-adcartfont { color:#<?php echo $widget_params['adcart-icon-color']; ?> !important; }
            <?php            
            endif;
                $content = ob_get_clean();
                $path = NB_AJAX_DROP_CART_PATH . 'assets/css/ajaxcart_custom.css';
                if ($file_handle = @fopen($path, 'w')) {
                    fwrite($file_handle, $content);
                    fclose($file_handle);
                }              
        }

        public function woocommerce_add_to_cart_variable_rc_callback() {

            ob_start();

            $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
            $quantity = empty($_POST['quantity']) ? 1 : apply_filters('woocommerce_stock_amount', $_POST['quantity']);
            $variation_id = $_POST['variation_id'];
            $variation = $_POST['variation'];
            $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);

            if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation)) {
                do_action('woocommerce_ajax_added_to_cart', $product_id);
                if (get_option('woocommerce_cart_redirect_after_add') == 'yes') {
                    wc_add_to_cart_message($product_id);
                }

                WC_AJAX::get_refreshed_fragments();
            } else {
                $this->json_headers();

                $data = array(
                    'error' => true,
                    'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
                );
                echo json_encode($data);
            }
            die();
        }

        public function woocommerce_add_to_cart_single() {
            ob_start();

            $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
            //$quantity          = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( $_POST['quantity'] );
            $quantity = empty($_POST['quantity']) ? 1 : apply_filters('woocommerce_stock_amount', $_POST['quantity']);
            $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
            $product_status = get_post_status($product_id);

            if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity) && 'publish' === $product_status) {

                do_action('woocommerce_ajax_added_to_cart', $product_id);

                if (get_option('woocommerce_cart_redirect_after_add') == 'yes') {
                    wc_add_to_cart_message($product_id);
                }

                // Return fragments
                $wc_ajax = new WC_AJAX();
                $wc_ajax->get_refreshed_fragments();
            } else {

                // If there was an error adding to the cart, redirect to the product page to show any errors
                $data = array(
                    'error' => true,
                    'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
                );

                wp_send_json($data);
            }

            die();
        }

        function pw_show_image_sizes($sizes) {
            $sizes['nwadcart-thumb'] = __('Cart icon', 'netbasecart');
            return $sizes;
        }

        function pw_add_image_sizes() {
            add_image_size('nwadcart-thumb', 20, 20, true);
        }

        function nbadcart_widget_shortcode($atts) {
            $type = "NB_Widget_Ajax_Drop_Cart";
            // Configure defaults and extract the attributes into variables
            extract(shortcode_atts(
                            array(
                'title' => ''
                            ), $atts
            ));

            $args = array(
            );

            ob_start();
            the_widget($type, $atts, $args);
            $output = ob_get_clean();

            return $output;
        }

        //admin tab
        function nbadcart_admin_tabs($current = 'homepage') {
            $tabs = array('adcart-default' => 'Default', 'nbcart-setting' => 'Advanced Setting');
            echo '<h2 class="nav-tab-wrapper">';
            foreach ($tabs as $tab => $name) {
                $class = ( $tab == $current ) ? ' nav-tab-active' : '';
                echo "<a class='nav-tab$class' href='?page=netbasecart-settings&tab=$tab'>$name</a>";
            }
            echo '</h2>';
        }

        function nbajaxdropcart_create_menu() {

            //create new top-level menu
            $settings_page = add_menu_page('Netbase Addcart Setting', 'Netbase Ajax Cart', 'manage_options', 'netbasecart-settings', array($this, 'nbadcart_settings_page'), "");

            add_action("load-{$settings_page}", array($this, 'nbadcart_load_settings_page'));
        }

        function nbadcart_load_settings_page() {
            if (isset($_POST["nbadcart-settings-submit"]) && $_POST["nbadcart-settings-submit"] == 'Y') {
                check_admin_referer("nbadcart-settings-page");
                $this->nwadcart_save_plugin_settings();
                $url_parameters = isset($_GET['tab']) ? 'updated=true&tab=' . $_GET['tab'] : 'updated=true';
                wp_redirect(admin_url('admin.php?page=netbasecart-settings&' . $url_parameters));
                exit;
            }
        }

        function nwadcart_save_plugin_settings() {
            global $pagenow;
            $settings = get_option("nbadcart_plugin_settings");

            if ($pagenow == 'admin.php' && $_GET['page'] == 'netbasecart-settings') {
                if (isset($_GET['tab']))
                    $tab = $_GET['tab'];
                else
                    $tab = 'adcart-default';

                switch ($tab) {

                    case 'adcart-default' :
                        $settings['adcart-skin'] = $_POST['adcart-skin'];
                        $settings['adcart-style'] = $_POST['jform'];

                        $settings['adcart-text-color'] = $_POST['adcart-text-color'];
                        $settings['adcart-link-color'] = $_POST['adcart-link-color'];
                        $settings['adcart-link-hover-color'] = $_POST['adcart-link-hover-color'];
                        $settings['adcart-button-text-color'] = $_POST['adcart-button-text-color'];
                        $settings['adcart-button-bg-color'] = $_POST['adcart-button-bg-color'];
                        $settings['adcart-button-bghv-color'] = $_POST['adcart-button-bghv-color'];
                        $settings['adcart-background-color'] = $_POST['adcart-background-color'];
                        $settings['adcart-background-border-color'] = $_POST['adcart-background-border-color'];
                        $settings['adcart-border-radius'] = $_POST['adcart-border-radius'];
                        $settings['adcart-icon-display'] = $_POST['jform-icon-display'];
                        $settings['adcart-icon-skin'] = $_POST['jform-icon'];
                        if(isset($_POST['icon-style'])) $settings['adcart-icon-style'] = $_POST['icon-style'];
                        if (isset($_POST['product_label_image_id']) && !empty($_POST['product_label_image_id']))
                              $settings['adcart-custom-icon'] = $_POST['product_label_image_id'];
                        $settings['adcart-icon-color'] = $_POST['adcart-icon-color'];
                        $settings['adcart-width'] = $_POST['adcart-width'];
                        break;
                    case 'nbcart-setting' :
                        $settings['adcart-windown'] = $_POST['adcart-windown'];
                        $settings['adcart-drop-trigger'] = $_POST['drop-trigger'];
                        $settings['adcart-numsub'] = $_POST['adcart-numsub'];
                        $settings['adcart-subtotal'] = $_POST['adcart-subtotal'];
                        $settings['adcart-icon-position'] = $_POST['icon-position'];
                        $settings['adcart-speed'] = $_POST['adcart-speed'];
                        $settings['adcart-x'] = $_POST['adcart-x'];
                        $settings['adcart-y'] = $_POST['adcart-y'];
                        break;
                }
            }

            $updated = update_option("nbadcart_plugin_settings", $settings);
            $this->settings = get_option("nbadcart_plugin_settings");
            $this->netbase_print_dynamic_css();
        }

        function nbadcart_settings_page() {

            global $pagenow;
            $settings = get_option("nbadcart_plugin_settings");
            include_once(NB_AJAX_DROP_CART_PATH . "includes/ajax-drop-cart.php");
        }

        function action_key() {
            $path = NB_AJAX_DROP_CART_PATH . 'key/key_info.json';

            if (file_exists($path)) {
                $key = (array) json_decode(file_get_contents($path));

                $now = strtotime("now");
                //$exp = $key['expiry-date'];

                if ($key['code'] == 5 || $key['code'] == 6) {
                    if ($now >= $key['expiry-date']) {
                        $this->_active = true;
                    } else {
                        $this->_active = false;
                    }
                } else {
                    $this->_active = false;
                }
            }
        }

        public function woocommerce_ajax_remove_from_cart() {
            global $woocommerce;

            $woocommerce->cart->set_quantity($_POST['remove_item'], 0);

            $ver = explode(".", WC_VERSION);

            if ($ver[0] >= 2 && $ver[1] >= 0 && $ver[2] >= 0) :
                $wc_ajax = new WC_AJAX();
                $wc_ajax->get_refreshed_fragments();
            else :
                woocommerce_get_refreshed_fragments();
            endif;

            die();
        }

        /**
         * Checks WooCommerce Installation
         *
         * @since 1.0.0
         * @access public
         */
        public function woostore_check_environment() {
            if (!class_exists('woocommerce'))
                wp_die(__('WooCommerce must be installed', 'oxfordshire'));
        }

        /**
         * Enqueue plugin style-files
         *
         * @since 1.0.0
         * @access public
         */
        public function netbase_drop_cart_stylesheets() {
            // Respects SSL, Style.css is relative to the current file
            wp_register_style('netbase-style', plugins_url('assets/css/style.css', dirname(__FILE__)));

            wp_register_style('netbase-style-dark', plugins_url('assets/css/style-dark.css', dirname(__FILE__)), 'netbase-style');
            wp_register_style('netbase-style-pink', plugins_url('assets/css/style-pink.css', dirname(__FILE__)), 'netbase-style');
            wp_register_style('netbase-style-red', plugins_url('assets/css/style-red.css', dirname(__FILE__)), 'netbase-style');
            wp_register_style('netbase-style-orange', plugins_url('assets/css/style-orange.css', dirname(__FILE__)), 'netbase-style');
            wp_register_style('netbase-style-blue', plugins_url('assets/css/style-blue.css', dirname(__FILE__)), 'netbase-style');
            wp_register_style('netbase-style-custom', plugins_url('assets/css/ajaxcart_custom.css', dirname(__FILE__)), 'netbase-style');

            wp_enqueue_style('netbase-style');

            if ($this->settings['adcart-style'] == "cus") {
                wp_enqueue_style('netbase-style-custom');
            } else {
                switch ($this->settings['adcart-skin']) {
                    case'pink':
                        wp_enqueue_style('netbase-style-pink');
                        break;
                    case'dark':
                        wp_enqueue_style('netbase-style-dark');
                        break;
                    case'blue':
                        wp_enqueue_style('netbase-style-blue');
                        break;
                    case'orange':
                        wp_enqueue_style('netbase-style-orange');
                        break;
                    case'red':
                        wp_enqueue_style('netbase-style-red');
                        break;
                }
            }
        }

        /**
         * Enqueue plugin style-files for admin
         *
         * @since 1.0.0
         * @access public
         */
        function netbase_drop_cart_admin_stylesheets() {
            if (strstr($_SERVER['REQUEST_URI'], 'netbasecart-settings')) {
                wp_register_style('netbase-style', plugins_url('admin/assets/css/style.css', dirname(__FILE__)));
                wp_register_style('netbase-style-colorpicker', plugins_url('admin/lib/colorpicker/css/colorpicker.css', dirname(__FILE__)));
                wp_register_style('netbase-style-colorpicker-layout', plugins_url('admin/lib/colorpicker/css/layout.css', dirname(__FILE__)), 'netbase-style-colorpicker');
                wp_register_style('netbase-style-jquery-ui', "http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css");

                wp_enqueue_style('netbase-style-jquery-ui');
                wp_enqueue_style('netbase-style');
                wp_enqueue_style('netbase-style-colorpicker');
                wp_enqueue_style('netbase-style-colorpicker-layout');
            }
        }

        /**
         * Enqueue plugin javascript-files for admin
         *
         * @since 1.0.0
         * @access public
         */
        function netbase_drop_cart_admin_scripts() {
            if (strstr($_SERVER['REQUEST_URI'], 'netbasecart-settings')) {
                wp_register_script('netbase-scripts', plugins_url('admin/assets/js/ui.js', dirname(__FILE__)));
                wp_localize_script('netbase-scripts', 'admin_ajaxcart', [
                    'url' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('nonce-license')]);
                wp_register_script('jquery-ui', 'http://code.jquery.com/ui/1.10.3/jquery-ui.js', 'jquery', '', true);
                wp_register_script('netbase-scripts-colorpicker', plugins_url('admin/lib/colorpicker/js/colorpicker.js', dirname(__FILE__)));
                wp_enqueue_script('jquery-ui');
                wp_enqueue_script('netbase-scripts');
                wp_enqueue_script('netbase-scripts-colorpicker');
            }
        }

        /**
         * Enqueue plugin javascript-files
         *
         * @since 1.0.0
         * @access public
         */
        public function netbase_drop_cart_scripts() {
            $settings = get_option('nbadcart_plugin_settings');
            wp_enqueue_script("netbase-scripts");
            wp_enqueue_script("jquery-effects-core");
            wp_register_script('netbase-scripts', plugins_url('assets/js/ui.js', dirname(__FILE__)));
            wp_enqueue_script('netbase-scripts', plugins_url('assets/js/ui.js', dirname(__FILE__)), array('netbase-scripts', 'jquery-effects-core'));
            wp_localize_script('netbase-scripts', 'nb_script_vars', array(
                'speed' => $settings['adcart-speed'],
                'x' => $settings['adcart-x'],
                'y' => $settings['adcart-y'],
            ));
        }

        function woocommerce_header_add_to_cart_fragment($fragments) {
            global $woocommerce;

            $cart_contents_count = 0;
            $show_only_individual = false;
            $settings = get_option("nbadcart_plugin_settings");
            foreach ($woocommerce->cart->cart_contents as $key => $product) {

                if ($show_only_individual) {
                    if ($product['data']->product_type == 'simple' && !isset($product['bundled_by']))
                        $cart_contents_count++;
                    if ($product['data']->product_type == 'bundle')
                        $cart_contents_count++;
                    if ($product['data']->product_type == 'variation')
                        $cart_contents_count++;
                }else {
                    if ($product['data']->product_type == 'simple' && !isset($product['bundled_by']))
                        $cart_contents_count += $product['quantity'];
                    if ($product['data']->product_type == 'bundle')
                        $cart_contents_count += $product['quantity'];
                    if ($product['data']->product_type == 'variation')
                        $cart_contents_count += $product['quantity'];
                }
            }

            ob_start();

            include_once( NB_ADCart::get_template_path('summary') );

            $fragments['div.nw-cart-contents'] = ob_get_clean();

            ob_start();

            include_once( NB_ADCart::get_template_path('mini-cart') );

            $fragments['div.nw-cart-drop-content-in'] = ob_get_clean();

            return $fragments;
        }

        /**
         * get template path for everriding
         * @param  string $filename template file name
         * @return string           template file full path
         */
        public static function get_template_path($filename) {

            if ($overridden_template = locate_template('woocommerce-netbase-adcart/cart/' . $filename . '.php')) {
                // locate_template() returns path to file
                // if either the child theme or the parent theme have overridden the template
                $template_file = $overridden_template;
            } else {
                // If neither the child nor parent theme have overridden the template,
                // we load the template from the 'templates' sub-directory of the directory this file is in
                $template_file = NB_AJAX_DROP_CART_PATH . 'templates/' . $filename . '.php';
            }

            return $template_file;
        }

    }

    

endif;