<?php
// =============================
// Enqueue Styles and Scripts
// =============================

function load_css()
{
    wp_register_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', [], false, 'all');
    wp_enqueue_style('bootstrap');

    wp_register_style('main', get_template_directory_uri() . '/css/main.css', [], false, 'all');
    wp_enqueue_style('main');
}
add_action('wp_enqueue_scripts', 'load_css');

function load_js()
{
    wp_enqueue_script('jquery');

    wp_register_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', ['jquery'], false, true);
    wp_enqueue_script('bootstrap');
}
add_action('wp_enqueue_scripts', 'load_js');

// =============================
// Theme Setup
// =============================
function my_theme_setup()
{
    add_theme_support('menus');
    add_theme_support('post-thumbnails');
    add_theme_support('widgets');
}
add_action('after_setup_theme', 'my_theme_setup');

// =============================
// Register Menus
// =============================
register_nav_menus([
    'top-menu' => 'Top Menu Location',
    'mobile-menu' => 'Mobile Menu Location',
    'footer-menu' => 'Footer Menu Location',
]);

// =============================
// Custom Image Sizes
// =============================
add_image_size('blog-large', 800, 300, true);
add_image_size('blog-small', 300, 200, true);

// =============================
// Sidebars
// =============================
function my_sidebars()
{
    register_sidebar([
        'name' => 'Page Sidebar',
        'id' => 'page-sidebar',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ]);

    register_sidebar([
        'name' => 'Blog Sidebar',
        'id' => 'blog-sidebar',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ]);

    // ✅ Fixed ID: 'footer-sidebar'
    register_sidebar([
        'name' => 'Footer Sidebar',
        'id' => 'footer-sidebar',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ]);
}
add_action('widgets_init', 'my_sidebars');


// =============================
// Custom Post Type: Cars
// =============================
function my_first_post_type()
{
    $args = [
        'labels' => [
            'name' => 'Cars',
            'singular_name' => 'Car'
        ],
        'hierarchical' => true,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-car',
        'supports' => ['title', 'editor', 'thumbnail', 'custom-fields'],
    ];
    register_post_type('cars', $args);
}
add_action('init', 'my_first_post_type');

// =============================
// Custom Taxonomy: Brands
// =============================
function my_first_taxonomy()
{
    $args = [
        'labels' => [
            'name' => 'Brands',
            'singular_name' => 'Brand',
        ],
        'hierarchical' => true,
        'public' => true,
    ];
    register_taxonomy('brands', ['cars'], $args);
}
add_action('init', 'my_first_taxonomy');

// =============================
// SEO Meta Fields in <head>
// =============================
function theme_seo_meta_tags()
{
    if (is_singular()) {
        global $post;
        $meta_title = get_post_meta($post->ID, '_theme_seo_title', true);
        $meta_desc = get_post_meta($post->ID, '_theme_seo_desc', true);

        // Fallbacks
        if (!$meta_title) {
            $meta_title = get_the_title($post->ID);
        }
        if (!$meta_desc) {
            $meta_desc = get_the_excerpt($post->ID);
        }

        echo '<title>' . esc_html($meta_title) . '</title>' . "\n";
        echo '<meta name="title" content="' . esc_attr($meta_title) . '">' . "\n";
        echo '<meta name="description" content="' . esc_attr($meta_desc) . '">' . "\n";
    }
}
add_action('wp_head', 'theme_seo_meta_tags');

// =============================
// SEO Meta Box in Admin
// =============================
function theme_seo_add_meta_box()
{
    add_meta_box('theme_seo_meta', 'SEO Settings', 'theme_seo_meta_callback', ['post', 'page', 'cars']);
}
add_action('add_meta_boxes', 'theme_seo_add_meta_box');

function theme_seo_meta_callback($post)
{
    $title = get_post_meta($post->ID, '_theme_seo_title', true);
    $desc = get_post_meta($post->ID, '_theme_seo_desc', true);
    ?>
    <p><label for="theme_seo_title"><strong>Meta Title</strong></label></p>
    <input type="text" name="theme_seo_title" value="<?php echo esc_attr($title); ?>" style="width:100%;" />

    <p><label for="theme_seo_desc"><strong>Meta Description</strong></label></p>
    <textarea name="theme_seo_desc" style="width:100%;" rows="3"><?php echo esc_textarea($desc); ?></textarea>
    <?php
}

function theme_seo_save_meta($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if (isset($_POST['theme_seo_title'])) {
        update_post_meta($post_id, '_theme_seo_title', sanitize_text_field($_POST['theme_seo_title']));
    }
    if (isset($_POST['theme_seo_desc'])) {
        update_post_meta($post_id, '_theme_seo_desc', sanitize_textarea_field($_POST['theme_seo_desc']));
    }
}
add_action('save_post', 'theme_seo_save_meta');


// Add sitemap link to footer
add_action('wp_head', function () {
    echo '<link rel="sitemap" type="application/xml" title="Sitemap" href="' . home_url('/sitemap.xml') . '" />';
});




// === Sitemap generation in theme's functions.php ===

// 1. Add rewrite rule for sitemap.xml
function theme_sitemap_rewrite()
{
    add_rewrite_rule('^sitemap\.xml$', 'index.php?theme_sitemap=1', 'top');
}
add_action('init', 'theme_sitemap_rewrite');

// 2. Allow custom query var
function theme_sitemap_query_var($vars)
{
    $vars[] = 'theme_sitemap';
    return $vars;
}
add_filter('query_vars', 'theme_sitemap_query_var');

// 3. Catch request and output XML
function theme_sitemap_template()
{
    if (intval(get_query_var('theme_sitemap')) === 1) {
        header('Content-Type: application/xml; charset=utf-8');
        echo theme_generate_sitemap();
        exit;
    }
}
add_action('template_redirect', 'theme_sitemap_template');

// 4. Generate sitemap XML string
function theme_generate_sitemap()
{
    // Query all published posts and pages (adjust post types as needed)
    $items = get_posts(array(
        'post_type' => array('post', 'page'),
        'post_status' => 'publish',
        'numberposts' => -1,
    ));

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

    foreach ($items as $post) {
        $permalink = get_permalink($post->ID);
        if (!$permalink) {
            continue;
        }
        $lastmod = get_the_modified_time('c', $post->ID);
        $xml .= "  <url>\n";
        $xml .= '    <loc>' . esc_url($permalink) . "</loc>\n";
        $xml .= '    <lastmod>' . esc_html($lastmod) . "</lastmod>\n";
        $xml .= "    <changefreq>weekly</changefreq>\n";
        $xml .= "    <priority>0.8</priority>\n";
        $xml .= "  </url>\n";
    }

    $xml .= '</urlset>';
    return $xml;
}

// 5. Add a preview page under Tools → View Sitemap XML
function theme_sitemap_admin_menu()
{
    add_submenu_page(
        'tools.php',
        'View Sitemap XML',
        'View Sitemap XML',
        'manage_options',
        'theme-view-sitemap',
        'theme_render_sitemap_admin'
    );
}
add_action('admin_menu', 'theme_sitemap_admin_menu');

function theme_render_sitemap_admin()
{
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }
    $xml = theme_generate_sitemap();
    echo '<div class="wrap">';
    echo '<h1>Sitemap XML Preview</h1>';
    echo '<p>Below is the generated sitemap. You can copy or inspect its content.</p>';
    echo '<pre style="white-space: pre-wrap; word-wrap: break-word; background: #f9f9f9; padding: 1em; border: 1px solid #ddd;">';
    echo esc_html($xml);
    echo '</pre>';
    echo '</div>';
}

// 6. (Optional) Add a <link> in head pointing to sitemap.xml
add_action('wp_head', function () {
    echo '<link rel="sitemap" type="application/xml" title="Sitemap" href="' . esc_url(home_url('/sitemap.xml')) . '" />' . "\n";
});


function custom_show_page_rank()
{
    // Stub: replace with real logic or manual value
    $rank = get_post_meta(get_the_ID(), '_manual_page_rank', true);
    if ($rank) {
        echo '<div class="page-rank">Page Rank: ' . esc_html($rank) . '</div>';
    }
}
// Hook in front of content
add_action('the_content', function ($content) {
    if (is_singular() && in_the_loop() && is_main_query()) {
        ob_start();
        custom_show_page_rank();
        $before = ob_get_clean();
        return $before . $content;
    }
    return $content;
});

add_filter('the_content', function ($content) {
    if (is_singular() && in_the_loop() && is_main_query()) {
        $score = calculate_seo_score(get_the_ID());
        $html = '<div class="seo-score" style="background:#e6f4ea;padding:10px;border-left:5px solid #28a745;margin-bottom:10px;">';
        $html .= '<strong>SEO Score:</strong> ' . $score . '%';
        $html .= '</div>';
        return $html . $content;
    }
    return $content;
});
function develegance_customize_register($wp_customize)
{
    // === Typography Section ===
    $wp_customize->add_section('develegance_typography_section', [
        'title' => __('Typography Settings', 'develegance'),
        'priority' => 30,
    ]);

    // Body font settings
    $wp_customize->add_setting('develegance_font_size', ['default' => '16px', 'transport' => 'refresh']);
    $wp_customize->add_control('develegance_font_size', [
        'label' => __('Font Size', 'develegance'),
        'section' => 'develegance_typography_section',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('develegance_line_height', ['default' => '1.6', 'transport' => 'refresh']);
    $wp_customize->add_control('develegance_line_height', [
        'label' => __('Line Height', 'develegance'),
        'section' => 'develegance_typography_section',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('develegance_letter_spacing', ['default' => '0.3px', 'transport' => 'refresh']);
    $wp_customize->add_control('develegance_letter_spacing', [
        'label' => __('Letter Spacing', 'develegance'),
        'section' => 'develegance_typography_section',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('develegance_text_color', ['default' => '#333333', 'transport' => 'refresh']);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'develegance_text_color', [
        'label' => __('Text Color', 'develegance'),
        'section' => 'develegance_typography_section',
        'settings' => 'develegance_text_color',
    ]));

    // Headings h1–h6 font size and color
    for ($i = 1; $i <= 6; $i++) {
        $wp_customize->add_setting("develegance_h{$i}_font_size", ['default' => '', 'transport' => 'refresh']);
        $wp_customize->add_control("develegance_h{$i}_font_size", [
            'label' => __("H{$i} Font Size (e.g. 36px)", 'develegance'),
            'section' => 'develegance_typography_section',
            'type' => 'text',
        ]);

        $wp_customize->add_setting("develegance_h{$i}_color", ['default' => '', 'transport' => 'refresh']);
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, "develegance_h{$i}_color", [
            'label' => __("H{$i} Color", 'develegance'),
            'section' => 'develegance_typography_section',
            'settings' => "develegance_h{$i}_color",
        ]));
    }

    $wp_customize->add_section('google_map_section', array(
        'title' => __('Google Map', 'develegance'),
        'priority' => 130,
    ));

    $wp_customize->add_setting('google_map_embed_code', array(
        'default' => '',
        'sanitize_callback' => 'develegance_allow_iframe_map',
    ));

    $wp_customize->add_control('google_map_embed_code', array(
        'label' => __('Google Map Embed Code', 'develegance'),
        'section' => 'google_map_section',
        'type' => 'textarea',
    ));

}
add_action('customize_register', 'develegance_customize_register');

function develegance_allow_iframe_map($input)
{
    return wp_kses($input, array(
        'iframe' => array(
            'src' => true,
            'width' => true,
            'height' => true,
            'frameborder' => true,
            'allowfullscreen' => true,
            'loading' => true,
            'referrerpolicy' => true,
            'style' => true,
        ),
    ));
}
add_filter('theme_mod_google_map_embed_code', 'develegance_allow_iframe_map');



function develegance_typography_styles()
{
    $font_size = get_theme_mod('develegance_font_size', '16px');
    $line_height = get_theme_mod('develegance_line_height', '1.6');
    $letter_spacing = get_theme_mod('develegance_letter_spacing', '0.3px');
    $text_color = get_theme_mod('develegance_text_color', '#333333');

    echo "<style>
        body {
            font-size: {$font_size} !important;
            line-height: {$line_height} !important;
            letter-spacing: {$letter_spacing} !important;
            color: {$text_color} !important;
        }";

    // Headings h1–h6 styles
    for ($i = 1; $i <= 6; $i++) {
        $h_font_size = get_theme_mod("develegance_h{$i}_font_size");
        $h_color = get_theme_mod("develegance_h{$i}_color");

        if ($h_font_size || $h_color) {
            echo "h{$i} {";
            if ($h_font_size)
                echo "font-size: {$h_font_size} !important;";
            if ($h_color)
                echo "color: {$h_color} !important;";
            echo "}";
        }
    }

    echo "</style>";
}
add_action('wp_head', 'develegance_typography_styles');

//color custome
function mytheme_setup()
{
    // Support for Custom Background
    add_theme_support('custom-background', array(
        'default-color' => 'ffffff',
        'default-image' => '',
        'default-repeat' => 'no-repeat',
        'default-position-x' => 'center',
        'default-attachment' => 'scroll',
    ));

    // Support for Custom Header
    add_theme_support('custom-header', array(
        'default-image' => '',
        'width' => 1200,
        'height' => 400,
        'flex-height' => true,
        'flex-width' => true,
        'header-text' => false,
        'uploads' => true,
    ));

    // Support for Site Title & Tagline
    add_theme_support('title-tag');

    // Support for Custom Logo
    add_theme_support('custom-logo', array(
        'height' => 100,
        'width' => 400,
        'flex-height' => true,
        'flex-width' => true,
    ));

    // Support for Featured Images
    add_theme_support('post-thumbnails');

    // Live preview support
    add_theme_support('customize-selective-refresh-widgets');


    // =============================
    // Register Menus
    // =============================
    register_nav_menus(array(
        'top-menu' => 'Top Menu Location',
        'mobile-menu' => 'Mobile Menu Location',
        'footer-menu' => 'Footer Menu Location',
    ));
}
add_action('after_setup_theme', 'mytheme_setup');


// Theme Colors & background color (optional)
function mytheme_customize_register($wp_customize)
{
    // === Section: Theme Colors ===
    $wp_customize->add_section('theme_color_section', array(
        'title' => __('Theme Colors', 'mytheme'),
        'priority' => 30,
    ));

    // === Helper function to register each color ===
    function mytheme_add_color_setting($wp_customize, $id, $label, $default)
    {
        $wp_customize->add_setting($id, array(
            'default' => $default,
            'transport' => 'refresh',
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $id, array(
            'label' => __($label, 'mytheme'),
            'section' => 'theme_color_section',
            'settings' => $id,
        )));
    }

    // === Add All Colors ===
    mytheme_add_color_setting($wp_customize, 'primary_theme_color', 'Primary Color', '#2563eb');
    mytheme_add_color_setting($wp_customize, 'secondary_theme_color', 'Secondary Color', '#0f172a');
    mytheme_add_color_setting($wp_customize, 'tertiary_theme_color', 'Tertiary Color', '#0f172a');
    mytheme_add_color_setting($wp_customize, 'text_color', 'Text Color', '#1e293b');
    mytheme_add_color_setting($wp_customize, 'light_gray', 'Light Gray', '#f8fafc');
    mytheme_add_color_setting($wp_customize, 'dark_gray', 'Dark Gray', '#334155');
    mytheme_add_color_setting($wp_customize, 'white_color', 'White Color', '#ffffff');
    mytheme_add_color_setting($wp_customize, 'hover_dark', 'Hover Dark Color', '#1d4ed8');
    mytheme_add_color_setting($wp_customize, 'hover_light', 'Hover Light Color', '#facc15');
    mytheme_add_color_setting($wp_customize, 'accent_color', 'Accent Color', '#0ea5e9');
    mytheme_add_color_setting($wp_customize, 'muted_text', 'Muted Text Color', '#64748b');

    // === Section: Typography ===
    $wp_customize->add_section('theme_typography_section', array(
        'title' => __('Typography', 'mytheme'),
        'priority' => 40,
    ));

    $wp_customize->add_setting('theme_font_family', array(
        'default' => '-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji"',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control('theme_font_family', array(
        'label' => __('Font Family', 'mytheme'),
        'section' => 'theme_typography_section',
        'settings' => 'theme_font_family',
        'type' => 'select',
        'choices' => array(
            '-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji"' => 'System Default',
            'Roboto, sans-serif' => 'Roboto',
            'Open Sans, sans-serif' => 'Open Sans',
            'Poppins, sans-serif' => 'Poppins',
            'Montserrat, sans-serif' => 'Montserrat',
            'Lato, sans-serif' => 'Lato',
            'Nunito, sans-serif' => 'Nunito',
            'Raleway, sans-serif' => 'Raleway',
            'Merriweather, serif' => 'Merriweather',
            'Playfair Display, serif' => 'Playfair Display',
            'Ubuntu, sans-serif' => 'Ubuntu',
            'Source Sans Pro, sans-serif' => 'Source Sans Pro',
            'Josefin Sans, sans-serif' => 'Josefin Sans',
            'Work Sans, sans-serif' => 'Work Sans',
            'Oswald, sans-serif' => 'Oswald',
            'Quicksand, sans-serif' => 'Quicksand',
        ),
    ));

       
// ----------------------
// Add Customizer Options for About Us Page
// ----------------------
    // Create a new section in the customizer
    $wp_customize->add_section('about_us_section', [
        'title'    => __('About Us Page', 'mytheme'),
        'priority' => 30,
    ]);

    // Page Title setting
    $wp_customize->add_setting('aboutus_title', ['default' => 'About Us']);
    $wp_customize->add_control('aboutus_title', [
        'label'   => __('Page Title'),
        'section' => 'about_us_section',
        'type'    => 'text',
    ]);

    // Company Introduction setting
    $wp_customize->add_setting('aboutus_intro');
    $wp_customize->add_control('aboutus_intro', [
        'label'   => __('Company Introduction'),
        'section' => 'about_us_section',
        'type'    => 'textarea',
    ]);

    // Mission Statement setting
    $wp_customize->add_setting('aboutus_mission');
    $wp_customize->add_control('aboutus_mission', [
        'label'   => __('Mission Statement'),
        'section' => 'about_us_section',
        'type'    => 'textarea',
    ]);

    // Vision Statement setting
    $wp_customize->add_setting('aboutus_vision');
    $wp_customize->add_control('aboutus_vision', [
        'label'   => __('Vision Statement'),
        'section' => 'about_us_section',
        'type'    => 'textarea',
    ]);

        // Leadership Section (like testimonial-style team)
    for ($i = 1; $i <= 2; $i++) {
        $wp_customize->add_setting("leader_img_$i");
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "leader_img_$i", [
            'label' => __("Leader $i Image"),
            'section' => 'about_us_section',
        ]));

        $wp_customize->add_setting("leader_name_$i");
        $wp_customize->add_control("leader_name_$i", [
            'label' => __("Leader $i Name & Title"),
            'section' => 'about_us_section',
            'type' => 'text',
        ]);

        $wp_customize->add_setting("leader_desc_$i");
        $wp_customize->add_control("leader_desc_$i", [
            'label' => __("Leader $i Description"),
            'section' => 'about_us_section',
            'type' => 'textarea',
        ]);
    }

    // Company Stat 1 (e.g., 100+ clients)
    $wp_customize->add_setting('aboutus_stat_1');
    $wp_customize->add_control('aboutus_stat_1', [
        'label'   => __('Stat 1'),
        'section' => 'about_us_section',
        'type'    => 'text',
    ]);

    // Company Stat 2 (e.g., 10 years experience)
    $wp_customize->add_setting('aboutus_stat_2');
    $wp_customize->add_control('aboutus_stat_2', [
        'label'   => __('Stat 2'),
        'section' => 'about_us_section',
        'type'    => 'text',
    ]);

    // Add company background photo and line overlay for leadership section
$wp_customize->add_setting('leadership_bg_img');
$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'leadership_bg_img', [
    'label'    => __('Leadership Section Background Image'),
    'section'  => 'about_us_section',
    'settings' => 'leadership_bg_img',
]));

$wp_customize->add_setting('leadership_bg_text', ['default' => '']);
$wp_customize->add_control('leadership_bg_text', [
    'label'    => __('Text Line on Background Image'),
    'section'  => 'about_us_section',
    'type'     => 'text',
]);
// Awards Section Title
$wp_customize->add_setting('awards_section_title', ['default' => 'Our Awards']);
$wp_customize->add_control('awards_section_title', [
    'label' => __('Awards Section Title'),
    'section' => 'about_us_section',
    'type' => 'text',
]);

// Awards Gallery - Add up to 6 Awards
for ($i = 1; $i <= 6; $i++) {
    $wp_customize->add_setting("award_image_$i");
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "award_image_$i", [
        'label' => __("Award $i Image"),
        'section' => 'about_us_section',
    ]));

    $wp_customize->add_setting("award_title_$i");
    $wp_customize->add_control("award_title_$i", [
        'label' => __("Award $i Title"),
        'section' => 'about_us_section',
        'type' => 'text',
    ]);
}
// Social Works Section Title
$wp_customize->add_setting('social_section_title', ['default' => 'Our Social Works']);
$wp_customize->add_control('social_section_title', [
    'label' => __('Social Works Section Title'),
    'section' => 'about_us_section',
    'type' => 'text',
]);

// Social Works Images (up to 6)
for ($i = 1; $i <= 6; $i++) {
    $wp_customize->add_setting("social_image_$i");
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "social_image_$i", [
        'label' => __("Social Work Image $i"),
        'section' => 'about_us_section',
    ]));
}

}
add_action('customize_register', 'mytheme_customize_register');


// Output CSS variables in :root
function mytheme_custom_color_css()
{
    $primary = get_theme_mod('primary_theme_color', '#2563eb');
    $secondary = get_theme_mod('secondary_theme_color', '#0f172a');
    $tertiary = get_theme_mod('tertiary_theme_color', '#0f172a');
    $text = get_theme_mod('text_color', '#1e293b');
    $light_gray = get_theme_mod('light_gray', '#f8fafc');
    $dark_gray = get_theme_mod('dark_gray', '#334155');
    $white = get_theme_mod('white_color', '#ffffff');
    $hover_dark = get_theme_mod('hover_dark', '#1d4ed8');
    $hover_light = get_theme_mod('hover_light', '#facc15');
    $accent = get_theme_mod('accent_color', '#0ea5e9');
    $muted = get_theme_mod('muted_text', '#64748b');
    $font_family = get_theme_mod('theme_font_family', '-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji"');
    ?>
    <style type="text/css">
        :root {
            --primary-color:
                <?php echo esc_html($primary); ?>
            ;
            --secondary-color:
                <?php echo esc_html($secondary); ?>
            ;
            --bg-color-3:
                <?php echo esc_html($tertiary); ?>
            ;
            --text-color:
                <?php echo esc_html($text); ?>
            ;
            --light-gray:
                <?php echo esc_html($light_gray); ?>
            ;
            --dark-gray:
                <?php echo esc_html($dark_gray); ?>
            ;
            --white:
                <?php echo esc_html($white); ?>
            ;
            --hover-dark:
                <?php echo esc_html($hover_dark); ?>
            ;
            --hover-light:
                <?php echo esc_html($hover_light); ?>
            ;
            --accent-color:
                <?php echo esc_html($accent); ?>
            ;
            --muted-text:
                <?php echo esc_html($muted); ?>
            ;
            --font-family:
                <?php echo esc_html($font_family); ?>
            ;
        }

        body {
            font-family: var(--font-family);
        }
    </style>
    <?php
}
add_action('wp_head', 'mytheme_custom_color_css');



function mytheme_custom_font_css()
{
    $default_font = '-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji"';
    $font = get_theme_mod('theme_font_family', $default_font);

    // Register fonts
    if (strpos($font, 'Roboto') !== false) {
        wp_enqueue_style('google-font-roboto', 'https://fonts.googleapis.com/css2?family=Roboto&display=swap', false);
    } elseif (strpos($font, 'Open Sans') !== false) {
        wp_enqueue_style('google-font-open-sans', 'https://fonts.googleapis.com/css2?family=Open+Sans&display=swap', false);
    } elseif (strpos($font, 'Poppins') !== false) {
        wp_enqueue_style('google-font-poppins', 'https://fonts.googleapis.com/css2?family=Poppins&display=swap', false);
    } elseif (strpos($font, 'Montserrat') !== false) {
        wp_enqueue_style('google-font-montserrat', 'https://fonts.googleapis.com/css2?family=Montserrat&display=swap', false);
    } elseif (strpos($font, 'Lato') !== false) {
        wp_enqueue_style('google-font-lato', 'https://fonts.googleapis.com/css2?family=Lato&display=swap', false);
    } elseif (strpos($font, 'Nunito') !== false) {
        wp_enqueue_style('google-font-nunito', 'https://fonts.googleapis.com/css2?family=Nunito&display=swap', false);
    } elseif (strpos($font, 'Raleway') !== false) {
        wp_enqueue_style('google-font-raleway', 'https://fonts.googleapis.com/css2?family=Raleway&display=swap', false);
    } elseif (strpos($font, 'Merriweather') !== false) {
        wp_enqueue_style('google-font-merriweather', 'https://fonts.googleapis.com/css2?family=Merriweather&display=swap', false);
    } elseif (strpos($font, 'Playfair Display') !== false) {
        wp_enqueue_style('google-font-playfair', 'https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap', false);
    } elseif (strpos($font, 'Ubuntu') !== false) {
        wp_enqueue_style('google-font-ubuntu', 'https://fonts.googleapis.com/css2?family=Ubuntu&display=swap', false);
    } elseif (strpos($font, 'Source Sans Pro') !== false) {
        wp_enqueue_style('google-font-source-sans', 'https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap', false);
    } elseif (strpos($font, 'Josefin Sans') !== false) {
        wp_enqueue_style('google-font-josefin', 'https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap', false);
    } elseif (strpos($font, 'Work Sans') !== false) {
        wp_enqueue_style('google-font-work-sans', 'https://fonts.googleapis.com/css2?family=Work+Sans&display=swap', false);
    } elseif (strpos($font, 'Oswald') !== false) {
        wp_enqueue_style('google-font-oswald', 'https://fonts.googleapis.com/css2?family=Oswald&display=swap', false);
    } elseif (strpos($font, 'Quicksand') !== false) {
        wp_enqueue_style('google-font-quicksand', 'https://fonts.googleapis.com/css2?family=Quicksand&display=swap', false);
    }

    // Output font variable
    echo '<style>:root { --font-family: ' . esc_html($font) . '; }</style>';
}
add_action('wp_head', 'mytheme_custom_font_css');




function lansinfosys_customize_register($wp_customize)
{

    // Section
    $wp_customize->add_section('lansinfosys_header_top_section', array(
        'title' => __('Top Header Settings', 'lansinfosys'),
        'priority' => 10,
    ));

    // Show/Hide Toggle
    $wp_customize->add_setting('lansinfosys_show_topbar', array(
        'default' => true,
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('lansinfosys_show_topbar', array(
        'label' => __('Display Top Header', 'lansinfosys'),
        'section' => 'lansinfosys_header_top_section',
        'type' => 'checkbox',
    ));

    // Timing Details
    $wp_customize->add_setting('lansinfosys_timing', array('default' => 'Open 24 hrs'));
    $wp_customize->add_control('lansinfosys_timing', array(
        'label' => __('Timing Info', 'lansinfosys'),
        'section' => 'lansinfosys_header_top_section',
        'type' => 'text',
    ));

    // Phone
    $wp_customize->add_setting('lansinfosys_phone', array('default' => '+91 94534545459'));
    $wp_customize->add_control('lansinfosys_phone', array(
        'label' => __('Phone Number', 'lansinfosys'),
        'section' => 'lansinfosys_header_top_section',
        'type' => 'text',
    ));

    // Email
    $wp_customize->add_setting('lansinfosys_email', array('default' => 'lansinfosys@gmail.com'));
    $wp_customize->add_control('lansinfosys_email', array(
        'label' => __('Email Address', 'lansinfosys'),
        'section' => 'lansinfosys_header_top_section',
        'type' => 'email',
    ));

    // Appointment Button Text
    $wp_customize->add_setting('lansinfosys_appointment_text', array('default' => 'Visit Once'));
    $wp_customize->add_control('lansinfosys_appointment_text', array(
        'label' => __('Appointment Button Text', 'lansinfosys'),
        'section' => 'lansinfosys_header_top_section',
        'type' => 'text',
    ));

    // Appointment Button Link
    $wp_customize->add_setting('lansinfosys_appointment_link', array('default' => home_url('/get-quotation/')));
    $wp_customize->add_control('lansinfosys_appointment_link', array(
        'label' => __('Appointment Button URL', 'lansinfosys'),
        'section' => 'lansinfosys_header_top_section',
        'type' => 'url',
    ));
    $wp_customize->add_section('header_carousel_section', array(
        'title' => __('Header Carousel Images', 'lansinfosys'),
        'priority' => 20,
    ));

    // Add image, tag, and subtag fields for 3 slides
    for ($i = 1; $i <= 4; $i++) {
        // Image
        $wp_customize->add_setting("carousel_slide_$i", array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "carousel_slide_{$i}_control", array(
            'label' => __("Slide $i Image", 'lansinfosys'),
            'section' => 'header_carousel_section',
            'settings' => "carousel_slide_$i",
        )));

        // Tag/Title
        $wp_customize->add_setting("carousel_slide_{$i}_title", array(
            'default' => "Slide $i Title",
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control("carousel_slide_{$i}_title", array(
            'label' => __("Slide $i Title (Tag)", 'lansinfosys'),
            'section' => 'header_carousel_section',
            'type' => 'text',
        ));

        // Subtag/Subheading
        $wp_customize->add_setting("carousel_slide_{$i}_subtag", array(
            'default' => "Slide $i Subtitle",
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control("carousel_slide_{$i}_subtag", array(
            'label' => __("Slide $i Subtag Line", 'lansinfosys'),
            'section' => 'header_carousel_section',
            'type' => 'text',
        ));
    }

}
add_action('customize_register', 'lansinfosys_customize_register');

function lansinfosys_display_header_top()
{
    if (get_theme_mod('lansinfosys_show_topbar', true)) {
        ?>
        <div class="header-top">
            <div class="container">
                <div class="row header_top_inner d-flex">
                    <div class="col-md-9 d-flex text-left contact-info">
                        <div class="d-flex header_icon">
                            <div class="icon mr-2 d-flex timings">
                                <span class="icon_img"><i class="icon-topbar fas fa-clock"></i></span>
                            </div>
                            <span
                                class="text"><?php echo esc_html(get_theme_mod('lansinfosys_timing', 'Open 24 hrs')); ?></span>
                        </div>
                        <div class="d-flex header_icon">
                            <div class="icon mr-2 d-flex phone">
                                <span class="icon_img"><i class="icon-topbar fas fa-mobile-alt"></i></span>
                            </div>
                            <span class="text"><?php echo esc_html(get_theme_mod('lansinfosys_phone')); ?></span>
                        </div>
                        <div class="d-flex header_icon">
                            <div class="icon mr-2 d-flex email">
                                <span class="icon_img"><i class="icon-topbar fas fa-envelope-open-text"></i></span>
                            </div>
                            <a href="mailto:<?php echo esc_attr(get_theme_mod('lansinfosys_email')); ?>"
                                class="text"><?php echo esc_html(get_theme_mod('lansinfosys_email')); ?></a>
                        </div>
                    </div>
                    <div class="col-md-3 text-right appoint-btn">
                        <a class="button apointmnet_btn"
                            href="<?php echo esc_url(get_theme_mod('lansinfosys_appointment_link', home_url('/get-quotation/'))); ?>"
                            style="text-decoration: none;">
                            <?php echo esc_html(get_theme_mod('lansinfosys_appointment_text', 'Get a Quote')); ?>
                        </a>

                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
;

function lansinfosys_customize_footer_columns($wp_customize)
{

    // SECTION: Footer Content
    $wp_customize->add_section('lansinfosys_footer_columns', array(
        'title' => __('Footer Column Content', 'lansinfosys'),
        'priority' => 170,
    ));

    // Column 1 - Logo (Image)
    $wp_customize->add_setting('lansinfosys_footer_logo');
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'lansinfosys_footer_logo', array(
        'label' => __('Footer Logo', 'lansinfosys'),
        'section' => 'lansinfosys_footer_columns',
        'settings' => 'lansinfosys_footer_logo',
    )));

    // Site Tagline
    $wp_customize->add_setting('lansinfosys_footer_tagline', array('default' => 'We build your digital dreams'));
    $wp_customize->add_control('lansinfosys_footer_tagline', array(
        'label' => __('Footer Tagline', 'lansinfosys'),
        'section' => 'lansinfosys_footer_columns',
        'type' => 'text',
    ));

    // Column 2 - Address, Phone, Email
    $wp_customize->add_setting('lansinfosys_footer_address', array('default' => '123 Business Street, Pune'));
    $wp_customize->add_control('lansinfosys_footer_address', array(
        'label' => __('Address', 'lansinfosys'),
        'section' => 'lansinfosys_footer_columns',
        'type' => 'textarea',
    ));

    $wp_customize->add_setting('lansinfosys_footer_phone', array('default' => '+91 94534545459'));
    $wp_customize->add_control('lansinfosys_footer_phone', array(
        'label' => __('Phone Number', 'lansinfosys'),
        'section' => 'lansinfosys_footer_columns',
        'type' => 'text',
    ));

    $wp_customize->add_setting('lansinfosys_footer_email', array('default' => 'info@example.com'));
    $wp_customize->add_control('lansinfosys_footer_email', array(
        'label' => __('Email', 'lansinfosys'),
        'section' => 'lansinfosys_footer_columns',
        'type' => 'email',
    ));

    // Column 3 - Headline
    // Newsletter Tagline 
    $wp_customize->add_setting('lansinfosys_footer_newsletter_tagline', array(
        'default' => 'Subscribe for latest updates',
    ));
    $wp_customize->add_control('lansinfosys_footer_newsletter_tagline', array(
        'label' => __('Newsletter Tagline', 'lansinfosys'),
        'section' => 'lansinfosys_footer_columns',
        'type' => 'text',
    ));

}
add_action('customize_register', 'lansinfosys_customize_footer_columns');
register_nav_menus(array(
    'footer-menu' => __('Footer Bottom Menu', 'lansinfosys'),
    'footer-links-menu' => __('Footer Quick Links Menu', 'lansinfosys'),
));






function lansinfosys_customize_header_taglines($wp_customize)
{
    $wp_customize->add_section('lansinfosys_header_section', array(
        'title' => __('Header Taglines', 'lansinfosys'),
        'priority' => 10,
    ));

    // Main Tagline
    $wp_customize->add_setting('lansinfosys_header_tagline', array(
        'default' => 'Building Digital Solutions That Matter',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('lansinfosys_header_tagline', array(
        'label' => __('Main Tagline', 'lansinfosys'),
        'section' => 'lansinfosys_header_section',
        'type' => 'text',
    ));

    // Subtagline
    $wp_customize->add_setting('lansinfosys_header_subtagline', array(
        'default' => 'Trusted by Businesses Since 2005',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('lansinfosys_header_subtagline', array(
        'label' => __('Subtagline (Under Main Tagline)', 'lansinfosys'),
        'section' => 'lansinfosys_header_section',
        'type' => 'text',
    ));
}
add_action('customize_register', 'lansinfosys_customize_header_taglines');



function lansinfosys_prefooter_advanced_customize($wp_customize)
{

    // 🌟 Create Main Panel
    $wp_customize->add_panel('lansinfosys_prefooter_panel', array(
        'title' => __('Main Page Blocks', 'lansinfosys'),
        'priority' => 105,
    ));

    // Block 1: Services Section
    $wp_customize->add_section('lansinfosys_services_section', array(
        'title' => __('Block 1 - Services', 'lansinfosys'),
        'panel' => 'lansinfosys_prefooter_panel',
    ));
    $wp_customize->add_setting('enable_block_1', array('default' => true));
    $wp_customize->add_control('enable_block_1', array(
        'label' => 'Enable Services Block',
        'type' => 'checkbox',
        'section' => 'lansinfosys_services_section',
    ));
    $wp_customize->add_setting('pre_footer_services_bg', array('default' => ''));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'pre_footer_services_bg', array(
        'label' => 'Services Background',
        'section' => 'lansinfosys_services_section',
    )));
    $wp_customize->add_setting('pre_footer_services_text', array('default' => "Web Development\nSEO\nMobile Apps"));
    $wp_customize->add_control('pre_footer_services_text', array(
        'label' => 'Services List',
        'type' => 'textarea',
        'section' => 'lansinfosys_services_section',
    ));

    // Block 2: Reserved for Future or Works
    // Block 2: Works Section
    $wp_customize->add_section('lansinfosys_works_section', array(
        'title' => __('Block 2 - Works/What We Offer', 'lansinfosys'),
        'panel' => 'lansinfosys_prefooter_panel',
    ));

    $wp_customize->add_setting('enable_block_2', array('default' => true));
    $wp_customize->add_control('enable_block_2', array(
        'label' => 'Enable Works Block',
        'type' => 'checkbox',
        'section' => 'lansinfosys_works_section',
    ));

    $wp_customize->add_setting('pre_footer_works_heading', array('default' => 'Our Services'));
    $wp_customize->add_control('pre_footer_works_heading', array(
        'label' => 'Main Heading',
        'type' => 'text',
        'section' => 'lansinfosys_works_section',
    ));

    $wp_customize->add_setting('pre_footer_works_subheading', array('default' => 'We provide top-notch services'));
    $wp_customize->add_control('pre_footer_works_subheading', array(
        'label' => 'Subheading',
        'type' => 'textarea',
        'section' => 'lansinfosys_works_section',
    ));

    for ($i = 1; $i <= 3; $i++) {
        $wp_customize->add_setting("works_{$i}_icon");
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "works_{$i}_icon", array(
            'label' => "Work {$i} Icon",
            'section' => 'lansinfosys_works_section',
        )));

        $wp_customize->add_setting("works_{$i}_title", array('default' => "Service {$i}"));
        $wp_customize->add_control("works_{$i}_title", array(
            'label' => "Work {$i} Title",
            'type' => 'text',
            'section' => 'lansinfosys_works_section',
        ));

        $wp_customize->add_setting("works_{$i}_desc", array('default' => "Short description about service {$i}."));
        $wp_customize->add_control("works_{$i}_desc", array(
            'label' => "Work {$i} Description",
            'type' => 'textarea',
            'section' => 'lansinfosys_works_section',
        ));

        $wp_customize->add_setting("works_{$i}_link", array('default' => "#"));
        $wp_customize->add_control("works_{$i}_link", array(
            'label' => "Work {$i} Link",
            'type' => 'url',
            'section' => 'lansinfosys_works_section',
        ));
    }

    // Block 3: Projects Section
    $wp_customize->add_section('lansinfosys_projects_section', array(
        'title' => __('Block 3 - Projects', 'lansinfosys'),
        'panel' => 'lansinfosys_prefooter_panel',
    ));
    $wp_customize->add_setting('enable_block_3', array('default' => true));
    $wp_customize->add_control('enable_block_3', array(
        'label' => 'Enable Projects Block',
        'type' => 'checkbox',
        'section' => 'lansinfosys_projects_section',
    ));
    $wp_customize->add_setting('pre_footer_projects_title', array('default' => 'Latest Projects'));
    $wp_customize->add_control('pre_footer_projects_title', array(
        'label' => 'Projects Title',
        'type' => 'text',
        'section' => 'lansinfosys_projects_section',
    ));
    for ($i = 1; $i <= 6; $i++) {
        $wp_customize->add_setting("project_{$i}_name", array('default' => "Project {$i}"));
        $wp_customize->add_control("project_{$i}_name", array(
            'label' => "Project {$i} Name",
            'type' => 'text',
            'section' => 'lansinfosys_projects_section',
        ));
        $wp_customize->add_setting("project_{$i}_image");
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "project_{$i}_image", array(
            'label' => "Project {$i} Image",
            'section' => 'lansinfosys_projects_section',
        )));
    }

    // Block 4: About Us Section
    $wp_customize->add_section('lansinfosys_about_section', array(
        'title' => __('Block 4 - About Us', 'lansinfosys'),
        'panel' => 'lansinfosys_prefooter_panel',
    ));
    $wp_customize->add_setting('enable_block_4', array('default' => true));
    $wp_customize->add_control('enable_block_4', array(
        'label' => 'Enable About Us Block',
        'type' => 'checkbox',
        'section' => 'lansinfosys_about_section',
    ));
    $wp_customize->add_setting('pre_footer_about_logo');
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'pre_footer_about_logo', array(
        'label' => 'Company Logo',
        'section' => 'lansinfosys_about_section',
    )));
    $wp_customize->add_setting('pre_footer_about_desc', array('default' => 'We are a tech company...'));
    $wp_customize->add_control('pre_footer_about_desc', array(
        'label' => 'Description',
        'type' => 'textarea',
        'section' => 'lansinfosys_about_section',
    ));
    $wp_customize->add_setting('pre_footer_about_phone', array('default' => '+91 9876543210'));
    $wp_customize->add_control('pre_footer_about_phone', array(
        'label' => 'Phone Number',
        'type' => 'text',
        'section' => 'lansinfosys_about_section',
    ));
    $wp_customize->add_setting('pre_footer_about_address', array('default' => '123 Tech Street, Pune'));
    $wp_customize->add_control('pre_footer_about_address', array(
        'label' => 'Address',
        'type' => 'textarea',
        'section' => 'lansinfosys_about_section',
    ));
    $wp_customize->add_setting('pre_footer_about_email', array('default' => 'info@company.com'));
    $wp_customize->add_control('pre_footer_about_email', array(
        'label' => 'Email ID',
        'type' => 'text',
        'section' => 'lansinfosys_about_section',
    ));

    // Block 5: Leadership Section
    $wp_customize->add_section('lansinfosys_leadership_section', array(
        'title' => __('Block 5 - Leadership Team', 'lansinfosys'),
        'panel' => 'lansinfosys_prefooter_panel',
    ));
    $wp_customize->add_setting('enable_block_5', array('default' => true));
    $wp_customize->add_control('enable_block_5', array(
        'label' => 'Enable Leadership Block',
        'type' => 'checkbox',
        'section' => 'lansinfosys_leadership_section',
    ));
    $wp_customize->add_setting('pre_footer_team_title', array('default' => 'Leadership Team'));
    $wp_customize->add_control('pre_footer_team_title', array(
        'label' => 'Team Title',
        'type' => 'text',
        'section' => 'lansinfosys_leadership_section',
    ));
    for ($i = 1; $i <= 5; $i++) {
        $wp_customize->add_setting("team_member_{$i}_photo");
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "team_member_{$i}_photo", array(
            'label' => "Team Member {$i} Photo",
            'section' => 'lansinfosys_leadership_section',
        )));
        $wp_customize->add_setting("team_member_{$i}_name", array('default' => "Name {$i}"));
        $wp_customize->add_control("team_member_{$i}_name", array(
            'label' => "Team Member {$i} Name",
            'type' => 'text',
            'section' => 'lansinfosys_leadership_section',
        ));
        $wp_customize->add_setting("team_member_{$i}_role", array('default' => "Role {$i}"));
        $wp_customize->add_control("team_member_{$i}_role", array(
            'label' => "Team Member {$i} Role",
            'type' => 'text',
            'section' => 'lansinfosys_leadership_section',
        ));
    }

    // Block 6: Clients Section
    $wp_customize->add_section('lansinfosys_clients_section', array(
        'title' => __('Block 6 - Clients', 'lansinfosys'),
        'panel' => 'lansinfosys_prefooter_panel',
    ));
    $wp_customize->add_setting('enable_block_6', array('default' => true));
    $wp_customize->add_control('enable_block_6', array(
        'label' => 'Enable Clients Block',
        'type' => 'checkbox',
        'section' => 'lansinfosys_clients_section',
    ));
    $wp_customize->add_setting('pre_footer_clients_title', array('default' => 'Our Clients'));
    $wp_customize->add_control('pre_footer_clients_title', array(
        'label' => 'Clients Title',
        'type' => 'text',
        'section' => 'lansinfosys_clients_section',
    ));
    for ($i = 1; $i <= 6; $i++) {
        $wp_customize->add_setting("client_{$i}_logo");
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "client_{$i}_logo", array(
            'label' => "Client {$i} Logo",
            'section' => 'lansinfosys_clients_section',
        )));
        $wp_customize->add_setting("client_{$i}_name", array('default' => "Client {$i}"));
        $wp_customize->add_control("client_{$i}_name", array(
            'label' => "Client {$i} Name",
            'type' => 'text',
            'section' => 'lansinfosys_clients_section',
        ));
    }

    // Block 7: Customer Reviews Section
    $wp_customize->add_section('lansinfosys_reviews_section', array(
        'title' => __('Block 7 - Customer Reviews', 'lansinfosys'),
        'panel' => 'lansinfosys_prefooter_panel',
    ));

    $wp_customize->add_setting('enable_block_7', array('default' => true));
    $wp_customize->add_control('enable_block_7', array(
        'label' => 'Enable Customer Reviews Block',
        'type' => 'checkbox',
        'section' => 'lansinfosys_reviews_section',
    ));

    $wp_customize->add_setting('pre_footer_reviews_title', array('default' => 'Customer Reviews'));
    $wp_customize->add_control('pre_footer_reviews_title', array(
        'label' => 'Reviews Block Title',
        'type' => 'text',
        'section' => 'lansinfosys_reviews_section',
    ));

    for ($i = 1; $i <= 5; $i++) {
        $wp_customize->add_setting("review_{$i}_image");
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "review_{$i}_image", array(
            'label' => "Review {$i} Image",
            'section' => 'lansinfosys_reviews_section',
        )));

        $wp_customize->add_setting("review_{$i}_message", array('default' => "Review {$i} message..."));
        $wp_customize->add_control("review_{$i}_message", array(
            'label' => "Review {$i} Message",
            'type' => 'textarea',
            'section' => 'lansinfosys_reviews_section',
        ));

        $wp_customize->add_setting("review_{$i}_stars", array('default' => 5));
        $wp_customize->add_control("review_{$i}_stars", array(
            'label' => "Review {$i} Star Rating (1-5)",
            'type' => 'number',
            'input_attrs' => array('min' => 1, 'max' => 5),
            'section' => 'lansinfosys_reviews_section',
        ));

        $wp_customize->add_setting("review_{$i}_client_name", array('default' => "Client {$i}"));
        $wp_customize->add_control("review_{$i}_client_name", array(
            'label' => "Review {$i} Client Name",
            'type' => 'text',
            'section' => 'lansinfosys_reviews_section',
        ));
    }


}
add_action('customize_register', 'lansinfosys_prefooter_advanced_customize');


function theme_enqueue_carousel_assets()
{
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'theme_enqueue_carousel_assets');



function lansinfosys_enqueue_bootstrap()
{
    // Bootstrap 5
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array(), null, true);

    // Your main theme stylesheet (make sure it comes after Bootstrap)
    wp_enqueue_style('theme-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'lansinfosys_enqueue_bootstrap');

// Register top-menu
function develegance_register_menus()
{
    register_nav_menus(array(
        'top-menu' => __('Top Menu', 'develegance'),
    ));
}
add_action('after_setup_theme', 'develegance_register_menus');

// Add Bootstrap nav classes
function develegance_bootstrap_nav_classes($classes, $item, $args)
{
    if ($args->theme_location === 'top-menu') {
        $classes[] = 'nav-item';
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'develegance_bootstrap_nav_classes', 10, 3);

function develegance_bootstrap_nav_link_classes($atts, $item, $args)
{
    if ($args->theme_location === 'top-menu') {
        $atts['class'] = 'nav-link';
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'develegance_bootstrap_nav_link_classes', 10, 3);





