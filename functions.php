<?php 
/* require minikit */
require_once('inc/minikit.php');
require_once('inc/shortcodes.php');
//require_once('inc/custom_posts.php');
require_once('inc/contact_form.php');

//Log In Logo
function custom_login_logo() { ?>
	<style type="text/css">
		body.login div#login h1 a {
			background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/stepp-footer.png');
			background-position: center;
			background-size: 200px;
			width: 310px;
			height: 70px;
			margin-bottom: 1.5;
		}
	</style>
	<?php } 

 add_action( 'login_enqueue_scripts', 'custom_login_logo' ); 

 function my_acf_init() {
	acf_update_setting('google_api_key', 'AIzaSyD92C39_T4NEluUc3vO1YLgsj2EZIf7P9o');
}

add_action('acf/init', 'my_acf_init');

//register_taxonomy( 'position', 
register_taxonomy( 'position', 
	array('team'), 
	array('hierarchical' => true,
		'labels' => array(
			'name' => __('Position'),
		),
		'public' => true,
		'show_admin_column' => true, 
		'show_ui' => true,
		'query_var' => true
	)
);

function register_acf_options_pages() {

    // Check function exists.
    if( !function_exists('acf_add_options_page') )
        return;

    // register options page.
    $option_page = acf_add_options_page(array(
        'page_title'    => __('Options')
    ));
}

// Hook into acf initialization.
add_action('acf/init', 'register_acf_options_pages');

class MinikitTheme extends Minikit {

	public $jquery_ver = '1.11.3';
	
	function __construct() {
		
		/* hide admin bar */
		//add_filter('show_admin_bar', '__return_false');
		
		// initial stuff
		add_action('after_setup_theme', array($this, 'theme_support'), 25);
		
		// register & enqueue jquery from google CDN
		add_action('wp_enqueue_scripts', array($this, 'load_jquery'), 999);

		//Imre Properties Production
		add_filter('imre_production', '__return_false');
		
		// load local jquery fallback
//		add_action('wp_footer', array($this, 'load_jquery_fallback'), 1000);
		
		// register & enqueue other scripts
		add_action('wp_enqueue_scripts', array($this, 'register_scripts'), 1001);
		
		// register & enqueue other scripts
		add_action('wp_enqueue_scripts', array($this, 'register_styles'), 1001);
		
		// remove admin menu items
		//add_action('admin_menu', array($this, 'remove_admin_menus'));
		
		// register menus
		$this->register_menus();
		
		// register sidebars
		$this->register_sidebars();
		
		// image sizes
		$this->image_sizes();
		
	}
	
	function image_sizes() {
	
		// default thumb size
		set_post_thumbnail_size(125, 125, true);

		add_image_size('small', 500, 500, true);

		add_image_size('medium', 800, 800, true);

		add_image_size('portrait', 800, 700, array( 'left', 'top' ) );

		add_image_size('large', 1200, 800, true);
	
		/* set a primary crop size */
		add_image_size('300x200', 300, 200, true);
		
	}
	
	function theme_support() {
		add_theme_support( 'custom-header', array(
			'width' => 1920,
			'height' => 1280
		) );
	}
	
	function load_jquery() {
		if(!is_admin()) {
			// deregister wp jquery first
			wp_deregister_script('jquery');
			// register new script
			wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/'.$this->jquery_ver.'/jquery.min.js', array(), null, true);
			// enqueue script
			wp_enqueue_script('jquery');
		}
	}
	
	function load_jquery_fallback() {
		if(wp_script_is('jquery', 'done')) {
			echo '<script>window.jQuery || document.write(\'<script src="'.THEME_URI.'/js/vendor/jquery-'.$this->jquery_ver.'.min.js"><\/script>\')</script>';
		}
	}
	
	function register_scripts() {
		if(!is_admin()) {

			// slick slider
			wp_enqueue_script('slick', THEME_URI . ('/js/slick.min.js'), array('jquery'), null, true);

			// AoS
			wp_enqueue_script('aos', THEME_URI . ('/js/aos.min.js'), array('jquery'), null, true);

			// Moment
			wp_enqueue_script('moment', THEME_URI . ('/js/moment.min.js'), array('jquery'), null, true);
			
			// register jquery, modernizr & main.js
			wp_register_script('main', THEME_URI . ('/js/main.js'), array('jquery'), null, true);

			//Properties
			
			if(is_post_type_archive('property') || is_page('823') ) {
				wp_register_script( 'properties', THEME_URI . ('/build/properties.js'), array('jquery'), null, true);
				wp_enqueue_script('properties');
			}

			if(is_post_type_archive('property') || is_page('878') ) {
				wp_register_script( 'properties', THEME_URI . ('/build/propertiesclosed.js'), array('jquery'), null, true);
				wp_enqueue_script('properties');
			}
	
			// enque scripts		
			wp_enqueue_script('main');
		}	
	}
	
	function register_styles() {
		if(!is_admin()) {
		
			wp_register_style('style', THEME_URI . ('/css/style.css'), array(), '', 'all');
			
			wp_enqueue_style('style');
		}
	}

	function get_versioned_file($path) {

		/*
		Add the following line in .htaccess
		
		# rewrite rule for versioned css/js
		RewriteRule ^(.*)/(css|js)/(.*).([0-9]{12}).(css|js)$ /$1/$2/$3.$5 [L,NC]
		
		*/

		// get URI
		$uri = THEME_DIR . $path;
		if (file_exists($uri)) {

			// get pathinfo
			$pi = pathinfo($path);

			// get timestamp
			$timestamp = date('ymdHis',filemtime($uri));

			// set new versioned path
			$path = $pi['dirname'].'/'.$pi['filename'].'.'.$timestamp.'.'.$pi['extension'];
		}

		return THEME_URI.$path;
	}
	
	function remove_admin_menus() {
	
		global $menu;
		
		$restricted = array(__('Dashboard'), __('Media'), __('Links'), __('Comments'));
		
		end ($menu);
		
		while (prev($menu)){
			$value = explode(' ',$menu[key($menu)][0]);
			if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
		}
	}
	
	function register_menus() {
	
		register_nav_menus(
			array(
				'primary' => __( 'Primary'),
				'footer' => __( 'Footer')
			)
		);
		
	}
	
	function register_sidebars() {
	
		register_sidebar(array(
			'id' => 'sidebar',
			'name' => 'Sidebar',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		));
		
	}
}

//Add Extra Fields in General Tab (Properties)
function imre_property_tab_fields($tabs) {
	array_splice($tabs['general'], 5, 0, array(
		
		array(
			'key' => 'price_per_unit',
			'name' => 'price_per_uni',
			'label' => 'Price Per Unit',
			'type' => 'number'
		),
		array(
			'key' => 'price_per_sf',
			'name' => 'price_per_sf',
			'label' => 'Price Per SF',
			'type' => 'number'
		),
		array (
			'key' => 'current_cap_rate',
			'label' => 'Current Cap Rate',
			'name' => 'current_cap_rat',
			'type' => 'text'
		),
		array (
			'key' => 'current_grm',
			'label' => 'Current GRM',
			'name' => 'current_grm',
			'type' => 'text'
		),
		array (
			'key' => 'market_cap_rate',
			'label' => 'Market Cap Rate',
			'name' => 'market_cap_rate',
			'type' => 'text'
		),
		array (
			'key' => 'market_grm',
			'label' => 'Market GRM',
			'name' => 'market_grm',
			'type' => 'text'
		),
		array (
			'key' => 'stabalized_cap_rate',
			'label' => 'Stabalized Cap Rate',
			'name' => 'stabalized_cap_rate',
			'type' => 'text'
		),
		array (
			'key' => 'stabalized_grm',
			'label' => 'Stabalized GRM',
			'name' => 'stabalized_grm',
			'type' => 'text'
		),
		array (
			'key' => 'proforma_cap_rate',
			'label' => 'Proforma Cap Rate',
			'name' => 'proforma_cap_rate',
			'type' => 'text'
		),
		array (
			'key' => 'proforma_grm',
			'label' => 'Proforma GRM',
			'name' => 'proforma_grm',
			'type' => 'text'
		),
		array (
			'key' => 'year_one_cap_rate',
			'label' => 'Year One Cap Rate',
			'name' => 'year_one_cap_rate',
			'type' => 'text'
		),
		array (
			'key' => 'year_one_grm',
			'label' => 'Year One GRM',
			'name' => 'year_one_grm',
			'type' => 'text'
		),
		array(
			'key' => 'highlights',
			'label' => 'Highlights',
			'name' => 'highlights',
			'type' => 'wysiwyg',
			'tabs' => 'visual',
			'toolbar' => 'full',
			'media_upload' => 0
		),
		array(
			'key' => 'virtual_tour',
			'label' => 'Virtual Tour',
			'name' => 'virtual_tour',
			'type' => 'url'
		),
	
));
	return $tabs;
}

add_filter('imre_property_tab_fields', 'imre_property_tab_fields');


new MinikitTheme();


function ws_register_images_field() {
	register_rest_field( 
		'post',
		'images',
		array(
			'get_callback'    => 'ws_get_images_urls',
			'update_callback' => null,
			'schema'          => null,
		)
	);
}

add_action( 'rest_api_init', 'ws_register_images_field' );

function ws_get_images_urls( $object, $field_name, $request ) {
	$medium = wp_get_attachment_image_src( get_post_thumbnail_id( $object->id ), 'medium' );
	$medium_url = $medium['0'];

	$large = wp_get_attachment_image_src( get_post_thumbnail_id( $object->id ), 'large' );
	$large_url = $large['0'];

	return array(
		'medium' => $medium_url,
		'large'  => $large_url,
	);
}

function set_custom_excerpt_length(){
	return 20;
 }
 add_filter('excerpt_length', 'set_custom_excerpt_length', 11);

function custom_excerpt_more_link($more){
	return '<a href="' . get_the_permalink() . '"</a>';
  }
  
  add_filter('excerpt_more', 'custom_excerpt_more_link');


  function custom_numeric_posts_nav() {
 
    if( is_singular() )
        return;
 
    global $wp_query;
 
    /** Stop execution if there's only 1 page */
    if( $wp_query->max_num_pages <= 1 )
        return;
 
    $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
    $max   = intval( $wp_query->max_num_pages );
 
    /** Add current page to the array */
    if ( $paged >= 1 )
        $links[] = $paged;
 
    /** Add the pages around the current page to the array */
    if ( $paged >= 3 ) {
        $links[] = $paged - 1;
        $links[] = $paged - 2;
    }
 
    if ( ( $paged + 2 ) <= $max ) {
        $links[] = $paged + 2;
        $links[] = $paged + 1;
    }
 
    echo '<div class="navigation"><ul>' . "\n";
 
    /** Previous Post Link */
    if ( get_previous_posts_link() )
        printf( '<li>%s</li>' . "\n", get_previous_posts_link() );
 
    /** Link to first page, plus ellipses if necessary */
    if ( ! in_array( 1, $links ) ) {
        $class = 1 == $paged ? ' class="active"' : '';
 
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );
 
        if ( ! in_array( 2, $links ) )
            echo '<li>…</li>';
    }
 
    /** Link to current page, plus 2 pages in either direction if necessary */
    sort( $links );
    foreach ( (array) $links as $link ) {
        $class = $paged == $link ? ' class="active"' : '';
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
    }
 
    /** Link to last page, plus ellipses if necessary */
    if ( ! in_array( $max, $links ) ) {
        if ( ! in_array( $max - 1, $links ) )
            echo '<li>…</li>' . "\n";
 
        $class = $paged == $max ? ' class="active"' : '';
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
    }
 
    /** Next Post Link */
    if ( get_next_posts_link() )
        printf( '<li>%s</li>' . "\n", get_next_posts_link() );
 
    echo '</ul></div>' . "\n";
}

function get_custom_cat_template($single_template) {
	global $post;
 
	   if ( in_category( 'covid' )) {
		  $single_template = dirname( __FILE__ ) . '/covid-single.php';
	   }
	return $single_template;
 }
 
 add_filter( "single_template", "get_custom_cat_template" ) ;

add_action( 'wp_footer', 'mycustom_wp_footer' );
 
function mycustom_wp_footer() {
?>
<script type="text/javascript">
document.addEventListener( 'wpcf7mailsent', function( event ) {
    window.location.href = "https://steppcommercial.com/thank-you/";
}, false );
</script>
<?php
}

/* OPTIONAL */

/* load basic widgets */
// require_once('minikit/widgets/minikit-image-widget.php');