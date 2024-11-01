<?php
/**
 * Plugin Name: ToolBridges Order Notification
 * Plugin URI: https://app.toolbridges.com/
 * Description: Seamless WooCommerce to WhatsApp Integration by ToolBridges.
 * Version: 2.3.2
 * Tested up to:6.6.1
 * Author: ToolBridges
 * Author URI: https://toolbridges.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Register activation and deactivation hooks
register_activation_hook(__FILE__, 'tb_order_notification_activate');
register_deactivation_hook(__FILE__, 'tb_order_notification_deactivate');

// Register the admin menu
add_action('admin_menu', 'tb_order_notification_menu');

// Register settings
add_action('admin_init', 'tb_order_notification_settings_init');

function tb_order_notification_activate() {
    // Activation code here
}

function tb_order_notification_deactivate() {
    // Deactivation code here
}

function tb_order_notification_menu() {
    add_menu_page(
        'Order Notification Settings',    
        'Order Notifications',            
        'manage_options',                 
        'order_notification',             
        'tb_order_notification_settings_page', 
        'dashicons-whatsapp'              
    );
}

function tb_order_notification_settings_init() {
    register_setting('order_notifications', 'tb_order_notification_settings');

    add_settings_section(
        'tb_order_notification_section',
        'Order Notification Settings',
        'tb_order_notification_section_cb',
        'order_notifications'
    );

    add_settings_field(
        'tb_order_notification_api_key',
        'API Key',
        'tb_order_notification_api_key_cb',
        'order_notifications',
        'tb_order_notification_section'
    );

    add_settings_field(
        'tb_order_notification_campaign_id',
        'Campaign ID',
        'tb_order_notification_campaign_id_cb',
        'order_notifications',
        'tb_order_notification_section'
    );

    add_settings_field(
        'tb_order_notification_template_name',
        'Template Name',
        'tb_order_notification_template_name_cb',
        'order_notifications',
        'tb_order_notification_section'
    );

    add_settings_field(
        'tb_order_notification_template_language',
        'Template Language',
        'tb_order_notification_template_language_cb',
        'order_notifications',
        'tb_order_notification_section'
    );

    add_settings_field(
        'tb_order_notification_currency_symbol',
        'Currency Symbol',
        'tb_order_notification_currency_symbol_cb',
        'order_notifications',
        'tb_order_notification_section'
    );

    add_settings_field(
        'tb_order_notification_country_code',
        'Country Code (without +)',
        'tb_order_notification_country_code_cb',
        'order_notifications',
        'tb_order_notification_section'
    );

    add_settings_field(
        'tb_order_notification_order_statuses',
        'Order Statuses for Notification',
        'tb_order_notification_order_statuses_cb',
        'order_notifications',
        'tb_order_notification_section'
    );
}

function tb_order_notification_section_cb() {
    echo '<p>Enter your API and Notification settings below:</p>';
}

function tb_order_notification_api_key_cb() {
    $options = get_option('tb_order_notification_settings');
    echo '<input type="text" id="tb_order_notification_api_key" name="tb_order_notification_settings[api_key]" value="' . esc_attr($options['api_key'] ?? '') . '"/>';
}

function tb_order_notification_campaign_id_cb() {
    $options = get_option('tb_order_notification_settings');
    echo '<input type="text" id="tb_order_notification_campaign_id" name="tb_order_notification_settings[campaign_id]" value="' . esc_attr($options['campaign_id'] ?? '') . '"/>';
}

function tb_order_notification_template_name_cb() {
    $options = get_option('tb_order_notification_settings');
    echo '<input type="text" id="tb_order_notification_template_name" name="tb_order_notification_settings[template_name]" value="' . esc_attr($options['template_name'] ?? '') . '"/>';
}

function tb_order_notification_template_language_cb() {
    $options = get_option('tb_order_notification_settings');
    echo '<input type="text" id="tb_order_notification_template_language" name="tb_order_notification_settings[template_language]" value="' . esc_attr($options['template_language'] ?? '') . '"/>';
}

function tb_order_notification_currency_symbol_cb() {
    $options = get_option('tb_order_notification_settings');
    echo '<input type="text" id="tb_order_notification_currency_symbol" name="tb_order_notification_settings[currency_symbol]" value="' . esc_attr($options['currency_symbol'] ?? '$') . '"/>';
}

function tb_order_notification_country_code_cb() {
    $options = get_option('tb_order_notification_settings');
    echo '<input type="text" id="tb_order_notification_country_code" name="tb_order_notification_settings[country_code]" value="' . esc_attr($options['country_code'] ?? '91') . '"/>';
}

function tb_order_notification_order_statuses_cb() {
    $options = get_option('tb_order_notification_settings');
    $statuses = wc_get_order_statuses();
    foreach ($statuses as $status => $status_name) {
        $checked = isset($options['order_statuses'][$status]) ? 'checked="checked"' : '';
        echo '<input type="checkbox" id="tb_order_statuses_' . esc_attr($status) . '" name="tb_order_notification_settings[order_statuses][' . esc_attr($status) . ']" value="1" ' . esc_attr($checked) . '/>';
        echo '<label for="tb_order_statuses_' . esc_attr($status) . '">' . esc_html($status_name) . '</label><br />';
    }
}

function tb_order_notification_settings_page() {
    ?>
    <div class="wrap">
        <h2>Order Notification Settings</h2>
        <p><a href="https://toolbridges.com/post/how-to-configure-toolbridges-order-notification-wordpress-plugin">Plugin Documentation</a></p>
        <p><a href="https://app.toolbridges.com/login" target="_blank" class="button button-secondary">Get API Key from ToolBridges</a></p>
        <form action="options.php" method="post">
            <?php
            settings_fields('order_notifications');
            do_settings_sections('order_notifications');
            submit_button();
            ?>
        </form>
        <h1>Sample Template</h1>
        <p>🎉 Hi 🌟 [Customer Name]! Thank you for your purchase at [Your Store Name]! 🎉</p>
        <p>✅ Your order 📋 [Order ID] has been successfully placed.</p>
        <p>📦 We're working hard to get it ready for shipment and will update you once it's on its way!</p>
        <p>🔍 Order Details:</p>
        <p>Item(s): 🛍 [Item(s)]</p>
        <p>Total Amount: [Amount]</p>
        <p>Order Status: 🔄 [Order Status]</p>
        <p>📞 Need help? Feel free to reply to this message or call us at [Store Phone Number]. We're here to help!</p>
        <p>Thank you for choosing [Store Name]. We are thrilled to have you as a customer and can’t wait for you to receive your order!</p>
        <p>🌟 Have a great day! 🌟</p>
    </div>
    <?php
}

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    add_action('woocommerce_order_status_changed', 'tb_send_order_notification', 10, 4);

    function tb_send_order_notification($order_id, $from_status, $to_status, $order) {
        $settings = get_option('tb_order_notification_settings');
        if (isset($settings['order_statuses']['wc-' . $to_status])) {
            $api_key = sanitize_text_field($settings['api_key']);
            $api_url = 'https://app.toolbridges.com/api/wpbox/sendtemplatemessage';
            $country_code = sanitize_text_field($settings['country_code'] ?? '91');
            $full_phone = $order->get_billing_phone();
            $phone_number = $country_code . substr($full_phone, -10);

            $customer_name = sanitize_text_field($order->get_billing_first_name() . " " . $order->get_billing_last_name());
            $items = $order->get_items();
            $item_list = array();
            foreach ($items as $item) {
                $item_list[] = sanitize_text_field($item->get_name() . ' x ' . $item->get_quantity());
            }
            $item_description = implode(", ", $item_list);
            $currency_symbol = sanitize_text_field($settings['currency_symbol'] ?? '$');
            $total_amount = sanitize_text_field($order->get_total());

            $payload = [
                'campaign_id' => sanitize_text_field($settings['campaign_id']),
                'template_name' => sanitize_text_field($settings['template_name']),
                'template_language' => sanitize_text_field($settings['template_language']),
                'token' => $api_key,
                'phone' => $phone_number,
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            ['type' => 'text', 'text' => "🌟 " . $customer_name],
                            ['type' => 'text', 'text' => "📋 " . $order_id],
                            ['type' => 'text', 'text' => "🛍️ " . $item_description],
                            ['type' => 'text', 'text' => $currency_symbol . $total_amount],
                            ['type' => 'text', 'text' => "🔄 " . $to_status]
                        ]
                    ]
                ]
            ];

            $response = wp_remote_post($api_url, [
                'method'      => 'POST',
                'timeout'     => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking'    => true,
                'headers'     => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $api_key
                ],
                'body'        => wp_json_encode($payload),
                'cookies'     => []
            ]);

            if (is_wp_error($response)) {
                error_log('Error in sending order notification: ' . $response->get_error_message());
            } else {
                error_log('Order notification sent successfully!');
            }
        }
    }
}
?>
