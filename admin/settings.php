<?php
/*
This is where all the code that handles the Settings lives. e.g. where the plugin lives in the WP Admin menu, where a user adds their webhook URL etc.
*/

// Basic Security.
defined('ABSPATH') or die('Unauthorised Access');

function wppostwebhook_register_settings()
{

    register_setting('wppostwebhook_options', 'webhook_url_field_name');
}
add_action('admin_menu', 'wppostwebhook_options_page');
add_action('admin_init', 'wppostwebhook_register_settings');



// Add the plugin to the WP Admin menu bar
function wppostwebhook_options_page()
{
    add_submenu_page(
        'options-general.php',
        'Post Webhook',
        'Post Webhook',
        'manage_options',
        'post-webhook-wp',
        'wppostwebhook_options_page_html'
    );
}

// Add Settings link in WP All Plugins list
add_filter('plugin_action_links_post-webhook-wp/post-webhook-wp.php', 'wppwh_settings_link');
function wppwh_settings_link($links)
{
    // Build and escape the URL.
    $url = esc_url(add_query_arg(
        'page',
        'post-webhook-wp',
        get_admin_url() . 'options-general.php'
    ));
    // Create the link.
    $settings_link = "<a href='$url'>" . __('Settings') . '</a>';
    // Adds the link to the end of the array.
    array_push(
        $links,
        $settings_link
    );
    return $links;
}
// End of - Add Settings link in WP All Plugins list


function wppostwebhook_options_page_html()
{
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php // output security fields for the registered setting "wppostwebhook_options"
            settings_fields('wppostwebhook_options');
            // output setting sections and their fields
            // (sections are registered for "wppostwebhook", each field is registered to a specific section) 
            ?>
            <table class="form-table">
                <tr>
                    <th><label for="url_field_id">Webhook URL:</label></th>
                    <td>
                        <input type='url' class="webhook-url" id="webhook_url_field_id" name="webhook_url_field_name" size="100" placeholder="e.g. https://hook.integromat.com/j2bb812345678" value="<?php echo esc_attr(get_option('webhook_url_field_name')); ?>">
                    </td>
                </tr>
            </table>
            <h4><?php echo 'Add the URL for the service you wish to send your Post and Page data to.'; ?></h4>
            <?php do_settings_sections('wppostwebhook');
            // output save settings button
            submit_button(__('Save Webhook URL', 'textdomain'));
            ?>
        </form>
        <h3><?php echo 'This plugin outputs post & page data to the URL you provide above, for full instrustions and examples please visit <a href="http://jonathan-wright.com/page.html">jonathan-wright.com</a>'; ?></h3>
    </div>
<?php
}
