<?php
/**
 * uninstall.php
 *
 * IMPORTANT (Shortcode API / Zero Data Loss):
 * WordPress only loads this file when the user clicks "Delete" on the plugin.
 * It does NOT run on normal updates.
 *
 * This file intentionally does NOT delete options, buttons, or click stats.
 * Users can reinstall without losing data.
 *
 * Do not add delete_option() calls here without a major-version decision.
 */
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}
// Intentionally empty — preserve all plugin data.
