<?php
/*
Plugin Name: Custom SEO Plugin
Description: Adds SEO meta box & meta tags for posts/pages, and generates sitemap.xml with preview under Tools.
Version: 1.0
Author: Your Name
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * -------------------------------------------------------------------
 * 1. SEO Meta Box and Meta Tags
 * -------------------------------------------------------------------
 */

// Hook into wp_head to output meta tags if set
add_action( 'wp_head', 'custom_seo_meta_tags' );
function custom_seo_meta_tags() {
    if ( is_singular() ) {
        global $post;
        // Retrieve saved meta (use plugin-prefixed keys)
        $meta_title = get_post_meta( $post->ID, '_custom_seo_title', true );
        $meta_desc  = get_post_meta( $post->ID, '_custom_seo_desc', true );

        if ( ! empty( $meta_title ) ) {
            // Output <title> only if theme hasn’t already output a title tag; 
            // many themes use add_theme_support('title-tag'), so double <title> may occur.
            // If your theme uses title-tag support, you may omit echoing <title> here,
            // or adjust logic accordingly.
            echo '<meta name="title" content="' . esc_attr( $meta_title ) . '">' . "\n";
            echo '<title>' . esc_html( $meta_title ) . '</title>' . "\n";
        }
        if ( ! empty( $meta_desc ) ) {
            echo '<meta name="description" content="' . esc_attr( $meta_desc ) . '">' . "\n";
        }
    }
}

// Add SEO meta box under post and page editors
add_action( 'add_meta_boxes', 'custom_seo_add_meta_box' );
function custom_seo_add_meta_box() {
    add_meta_box(
        'custom_seo_meta',             // ID
        'SEO Settings',                // Title
        'custom_seo_meta_callback',    // Callback
        array( 'post', 'page' ),       // Screens
        'normal',                      // Context
        'high'                         // Priority
    );
}

function custom_seo_meta_callback( $post ) {
    // Retrieve existing values
    $title = get_post_meta( $post->ID, '_custom_seo_title', true );
    $desc  = get_post_meta( $post->ID, '_custom_seo_desc', true );

    // Nonce for security
    wp_nonce_field( 'custom_seo_meta_box', 'custom_seo_meta_box_nonce' );

    ?>
    <p>
        <label for="custom_seo_title"><strong>Meta Title</strong></label><br>
        <input type="text" id="custom_seo_title" name="custom_seo_title" 
               value="<?php echo esc_attr( $title ); ?>" style="width:100%;" />
    </p>
    <p>
        <label for="custom_seo_desc"><strong>Meta Description</strong></label><br>
        <textarea id="custom_seo_desc" name="custom_seo_desc" rows="3" style="width:100%;"><?php echo esc_textarea( $desc ); ?></textarea>
    </p>
    <?php
}

add_action( 'save_post', 'custom_seo_save_meta' );
function custom_seo_save_meta( $post_id ) {
    // Check autosave, revision, or nonce
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! isset( $_POST['custom_seo_meta_box_nonce'] ) 
         || ! wp_verify_nonce( $_POST['custom_seo_meta_box_nonce'], 'custom_seo_meta_box' ) ) {
        return;
    }
    if ( isset( $_POST['custom_seo_title'] ) ) {
        update_post_meta( $post_id, '_custom_seo_title', sanitize_text_field( wp_unslash( $_POST['custom_seo_title'] ) ) );
    }
    if ( isset( $_POST['custom_seo_desc'] ) ) {
        update_post_meta( $post_id, '_custom_seo_desc', sanitize_textarea_field( wp_unslash( $_POST['custom_seo_desc'] ) ) );
    }
}


/**
 * -------------------------------------------------------------------
 * 2. Sitemap Generation
 * -------------------------------------------------------------------
 */

// Add rewrite rule at init
add_action( 'init', 'custom_seo_sitemap_rewrite' );
function custom_seo_sitemap_rewrite() {
    add_rewrite_rule( '^sitemap\.xml$', 'index.php?seo_sitemap=1', 'top' );
}

// Allow query var
add_filter( 'query_vars', 'custom_seo_query_vars' );
function custom_seo_query_vars( $vars ) {
    $vars[] = 'seo_sitemap';
    return $vars;
}

// Catch the sitemap request and output XML
add_action( 'template_redirect', 'custom_seo_sitemap_template' );
function custom_seo_sitemap_template() {
    if ( get_query_var( 'seo_sitemap' ) == '1' ) {
        // Send XML header
        header( 'Content-Type: application/xml; charset=utf-8' );
        echo custom_seo_generate_sitemap();
        exit;
    }
}

// Generate sitemap XML string
function custom_seo_generate_sitemap() {
    // Get all published posts and pages
    $posts = get_posts( array(
        'post_type'   => array( 'post', 'page' ),
        'post_status' => 'publish',
        'numberposts' => -1,
    ) );

    $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

    foreach ( $posts as $post ) {
        $permalink = get_permalink( $post->ID );
        if ( ! $permalink ) {
            continue;
        }
        $lastmod = get_the_modified_time( 'c', $post->ID );
        $xml    .= "  <url>\n";
        $xml    .= '    <loc>' . esc_url( $permalink ) . "</loc>\n";
        $xml    .= '    <lastmod>' . esc_html( $lastmod ) . "</lastmod>\n";
        $xml    .= '    <changefreq>weekly</changefreq>' . "\n";
        $xml    .= '    <priority>0.8</priority>' . "\n";
        $xml    .= "  </url>\n";
    }

    $xml .= '</urlset>';
    return $xml;
}

// Flush rewrite rules on activation/deactivation
register_activation_hook( __FILE__, 'custom_seo_activate' );
function custom_seo_activate() {
    // Ensure rewrite rule is added before flushing
    custom_seo_sitemap_rewrite();
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'custom_seo_deactivate' );
function custom_seo_deactivate() {
    flush_rewrite_rules();
}


/**
 * -------------------------------------------------------------------
 * 3. Admin Preview: “View Sitemap XML” under Tools
 * -------------------------------------------------------------------
 */

add_action( 'admin_menu', 'custom_seo_add_admin_menu' );
function custom_seo_add_admin_menu() {
    add_submenu_page(
        'tools.php',                // parent slug
        'View Sitemap XML',         // page title
        'View Sitemap XML',         // menu title
        'manage_options',           // capability
        'custom-seo-view-sitemap',  // menu slug
        'custom_seo_render_sitemap_admin'
    );
}

function custom_seo_render_sitemap_admin() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Unauthorized' );
    }
    $xml = custom_seo_generate_sitemap();
    echo '<div class="wrap">';
    echo '<h1>Sitemap XML Preview</h1>';
    echo '<p>Below is the generated sitemap XML. You can copy it or verify its structure.</p>';
    echo '<pre style="white-space: pre-wrap; word-wrap: break-word; background: #f9f9f9; padding: 1em; border: 1px solid #ddd;">';
    echo esc_html( $xml );
    echo '</pre>';
    echo '</div>';
}
