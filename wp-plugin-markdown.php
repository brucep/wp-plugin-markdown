<?php
/**
 * Plugin Name: BP Markdown
 * Description: Adds support for Markdown and SmartyPants.
 * Author: Bruce Phillips
 * Requires PHP: 7.0.
 */
use Michelf\MarkdownExtra;
use Michelf\SmartyPantsTypographer;

if (!defined('ABSPATH')) {
    @header('HTTP/1.1 404 Not Found');
    exit;
}

class BPWP_MarkdownPlugin
{
    public static function markdown(string $content): string
    {
        return MarkdownExtra::defaultTransform($content);
    }

    public static function smartyPants(string $content): string
    {
        return SmartyPantsTypographer::defaultTransform($content);
    }

    public static function markdownFilter(string $content): string
    {
        if (in_the_loop() && is_main_query()) {
            return MarkdownExtra::defaultTransform($content);
        }

        return $content;
    }

    public static function smartyPantsFilter(string $content): string
    {
        if (in_the_loop() && is_main_query()) {
            return SmartyPantsTypographer::defaultTransform($content);
        }

        return $content;
    }

    public static function removeQuicktags(array $qtInit)
    {
        $qtInit['buttons'] = true; // a non-empty value without further meaning

        return $qtInit;
    }
}

add_filter('quicktags_settings', BPWP_MarkdownPlugin::class.'::removeQuicktags', 5);

add_filter('the_content', BPWP_MarkdownPlugin::class.'::markdown', 15);
add_filter('the_content', BPWP_MarkdownPlugin::class.'::smartyPants', 20);
add_filter('the_excerpt', BPWP_MarkdownPlugin::class.'::markdownFilter', 15);
add_filter('the_excerpt', BPWP_MarkdownPlugin::class.'::smartyPantsFilter', 20);
add_filter('the_title', BPWP_MarkdownPlugin::class.'::smartyPantsFilter', 20);

remove_filter('the_content', 'wpautop');
remove_filter('the_content', 'wptexturize');
remove_filter('the_excerpt', 'wpautop');
remove_filter('the_excerpt', 'wptexturize');
remove_filter('the_title', 'wptexturize');
