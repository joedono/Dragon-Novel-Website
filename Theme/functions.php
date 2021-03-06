<?php
/**
 * Novel Dragon functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Novel_Dragon
 */

if ( ! function_exists( 'novel_dragon_setup' ) ) :

class Dragon_Novel_Walker_Nav_Menu extends Walker_Nav_Menu {
  
  public function start_lvl(&$output, $depth = 0, $args = array()) {
    if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
      $t = '';
      $n = '';
    } else {
      $t = "\t";
      $n = "\n";
    }
    $indent = str_repeat( $t, $depth );
    
    if($depth == 0) {
      $output .= "{$n}{$indent}<ul class=\"sub-menu dropdown-menu\">{$n}";
    } else {
      $output .= "{$n}{$indent}<ul class=\"sub-menu dropdown-menu\">{$n}";
    }
  }
  
  public function end_lvl( &$output, $depth = 0, $args = array() ) {
    if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
      $t = '';
      $n = '';
    } else {
      $t = "\t";
      $n = "\n";
    }
    $indent = str_repeat( $t, $depth );
    $output .= "$indent</ul>{$n}";
  }
  
  public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
    $args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );
    
    $atts = array();
    $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
    $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
    $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
    $atts['href']   = ! empty( $item->url )        ? $item->url        : '';

    $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
    
    $title = apply_filters( 'the_title', $item->title, $item->ID );
    $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
    
    $attributes = "";
    foreach($atts as $attr => $value) {
      if(!empty($value)) {
        $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
        $attributes .= ' ' . $attr . '="' . $value . '"';
      }
    }
    
    $list_attributes = "";
    $dropdown_caret = "";
    if($this->has_children) {
      $list_attributes = " class=\"dropdown\" ";
      $attributes .= " class=\"dropdown-toggle\" data-toggle=\"dropdown\" ";
      $dropdown_caret = "<span class=\"caret\"></span>";
    }
    
    $output .= "<li $list_attributes><a $attributes>$title $dropdown_caret</a>";
  }
  
  public function end_el( &$output, $item, $depth = 0, $args = array() ) {
    if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
      $t = '';
      $n = '';
    } else {
      $t = "\t";
      $n = "\n";
    }
    $indent = str_repeat( $t, $depth );
    $output .= "</li>{$n}";
  }
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function novel_dragon_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Novel Dragon, use a find and replace
	 * to change 'novel-dragon' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'novel-dragon', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'menu-1' => esc_html__( 'Primary', 'novel-dragon' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'novel_dragon_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif;
add_action( 'after_setup_theme', 'novel_dragon_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function novel_dragon_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'novel_dragon_content_width', 640 );
}
add_action( 'after_setup_theme', 'novel_dragon_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function novel_dragon_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'novel-dragon' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'novel-dragon' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'novel_dragon_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function novel_dragon_scripts() {
  wp_enqueue_style( 'novel-dragon-bootstrap', get_template_directory_uri() . '/css/vendor/bootstrap.min.css', array(), '20151215', 'all' );
  wp_enqueue_style( 'novel-dragon-bootstrap-theme', get_template_directory_uri() . '/css/vendor/bootstrap-theme.min.css', array(), '20151215', 'all' );
  wp_enqueue_style( 'novel-dragon-bootstrap-theme', get_template_directory_uri() . '/css/vendor/normalize.css', array(), '20151215', 'all' );
	wp_enqueue_style( 'novel-dragon-style', get_stylesheet_uri() );

	wp_enqueue_script( 'novel-dragon-modernizer', get_template_directory_uri() . '/js/vendor/modernizr-2.8.3.min.js', array(), '20151215', true );
	wp_enqueue_script( 'novel-dragon-jquery', get_template_directory_uri() . '/js/vendor/jquery-3.2.0.min.js', array(), '20151215', true );
  wp_enqueue_script( 'novel-dragon-bootstrap', get_template_directory_uri() . '/js/vendor/bootstrap.min.js', array(), '20151215', true );
  wp_enqueue_script( 'novel-dragon-plugins', get_template_directory_uri() . '/js/plugins.js', array(), '20151215', true );
  wp_enqueue_script( 'novel-dragon-main', get_template_directory_uri() . '/js/main.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'novel_dragon_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
