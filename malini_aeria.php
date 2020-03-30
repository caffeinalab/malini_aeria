<?php
/**
 * Malini Aeria.
 *
 * @author      Caffeina
 * @copyright   2019 Caffeina
 * @license     MIT
 *
 * @wordpress-plugin
 * Plugin Name: Malini Aeria
 * Plugin URI:  https://github.com/caffeinalab/malini_aeria
 * Description: Malini Aeria is a Malini plugin to quickly decorate content with Aeria defined data.
 * Version:     1.0.0
 * Author:      Caffeina
 * Author URI:  https://caffeina.com
 * Text Domain: malini
 * License:     MIT
 */
defined('ABSPATH') or die('No script kiddies please!');

require_once __DIR__.'/vendor/autoload.php';

add_action('malini_register_decorators', function () {
    malini_add_decorator('aeria', \MaliniAeria\Decorators\WithAeria::class);
});

add_action(
    'malini_register_accessors',
    function () {
        malini_add_accessor('aeria', \MaliniAeria\Accessors\AeriaAccessor::class);
    }
);

$malini_aeria = [
    'name' => 'Malini Aeria',
    'reference' => 'malini_aeria/malini_aeria.php',
    'requirements' => [
        'Malini' => 'malini/malini.php',
        'Aeria' => 'aeria/aeria.php',
    ],
];

add_action('admin_init', function () use ($malini_aeria) {
    $config = $malini_aeria;

    $plugin_name = $config['name'];
    $plugin_reference = $config['reference'];
    $requires = $config['requirements'];

    $plugins_needed = [];
    foreach ($requires as $requirement_name => $requirement_reference) {
        if (!is_plugin_active($requirement_reference) && is_plugin_active($plugin_reference)) {
            $plugins_needed[] = $requirement_name;
        }
    }

    if (!empty($plugins_needed)) {
        deactivate_plugins($plugin_reference);

        $dependencies = implode(', ', $plugins_needed);
        if (count($plugins_needed) == 1) {
            $message = "<b>{$plugin_name}</b> was deactivated because <b>{$dependencies}</b> plugin is not active.";
        } else {
            $message = "<b>{$plugin_name}</b> was deactivated because <b>{$dependencies}</b> plugin are not active.";
        }

        add_action('admin_notices', function () use ($message) {
            ?>
            <div class="notice updated is-dismissible">
                <p><?= $message; ?></p>
            </div>
            <?php
        });
    }
});

register_activation_hook(
    __FILE__,
    function () use ($malini_aeria) {
        if (!function_exists('is_plugin_active_for_network')) {
            include_once ABSPATH.'/wp-admin/includes/plugin.php';
        }

        $requires = $malini_aeria['requirements'];

        $plugins_needed = [];
        foreach ($requires as $requirement_name => $requirement_reference) {
            if (!is_plugin_active($requirement_reference)) {
                $plugins_needed[] = $requirement_name;
            }
        }

        if (!empty($plugins_needed)) {
            $dependencies = implode(', ', $plugins_needed);
            deactivate_plugins(plugin_basename(__FILE__));
            // Throw an error in the WordPress admin console.
            wp_die(
                "Please activate <b>{$dependencies}</b> before.",
                'Plugin dependency check',
                [
                    'back_link' => true,
                ]
            );
        }
    }
);

add_action('init', function () {
    \MaliniAeria\Updater::updateService();
});
