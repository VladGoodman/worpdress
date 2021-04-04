<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Rara_Journal
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */

function rara_journal_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}
	
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
	
    // Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}
    
    // Adds a class of custom-background-color to sites with a custom background color.
    if ( get_background_color() != 'ffffff' ) {
		$classes[] = 'custom-background-color';
	}
     
    if( !( is_active_sidebar( 'right-sidebar' ) ) ) {
        $classes[] = 'full-width'; 
    }
    
    if( is_single() ){
        $classes[] = 'post'; 
    }
    
    if( is_archive() ){
        $classes[] = 'category'; 
    }
    
    if( is_page() ){
        $sidebar_layout = rara_journal_sidebar_layout();
        if( $sidebar_layout == 'no-sidebar' )
        $classes[] = 'full-width';
    }
	return $classes;
}
add_filter( 'body_class', 'rara_journal_body_classes' );

/**
 * Archive Header Layout
 */
function rara_journal_archive_header_layout (){ ?>
	<?php if ( is_category() ) : ?>
		<div class="page-header">
			<div class="container">
				<h1 class="page-title"><?php foreach((get_the_category()) as $cat) { echo esc_html( $cat->cat_name . ' ' ); } ?></h1>
				<span><?php esc_html_e('Category','rara-journal'); ?></span>
			</div>
		</div>	
	<?php elseif ( is_tag() ) : ?>	
		<div class="page-header">
			<div class="container">
				<h1 class="page-title"><?php the_archive_title();?></h1>
				<span><?php esc_html_e('Tag','rara-journal'); ?></span>
			</div>
		</div>
	<?php elseif ( is_author() ) : ?>	
		<div class="page-header">
			<div class="container">
				<h1 class="page-title"><?php the_author_posts_link(); ?></h1>
				<span><?php esc_html_e('Author','rara-journal'); ?></span>
			</div>
		</div>
	<?php else : ?>	
		<div class="page-header">
			<div class="container">
				<h1 class="page-title"><?php echo trim(single_month_title(' ',false));?></h1>
				<span><?php esc_html_e('Archive','rara-journal'); ?></span>
			</div>
		</div>    
    <?php endif; 
} 

add_action( 'rara_journal_archive_header', 'rara_journal_archive_header_layout' );


/**
 * Search header for Search page
*/
function rara_journal_search_header(){
    
    if( is_search() ){ 
        global $wp_query;    
    ?>
    <h2 class="page-title"><?php printf( esc_html__( 'Search Results', 'rara-journal' ), get_search_query() ); ?></h2>
    	<span>
    		<?php printf( esc_html__( '%s Results Found','rara-journal' ), $wp_query->found_posts ); ?>
    	</span>
    <?php
    }
}
add_action( 'rara_journal_header', 'rara_journal_search_header' );
 
 /**
 * 
 * @link http://www.altafweb.com/2011/12/remove-specific-tag-from-php-string.html
*/
function rara_journal_strip_single( $tag, $string ){
    $string=preg_replace('/<'.$tag.'[^>]*>/i', '', $string);
    $string=preg_replace('/<\/'.$tag.'>/i', '', $string);
    return $string;
}

/**
 * Callback function for Comment List *
 * 
 * @link https://codex.wordpress.org/Function_Reference/wp_list_comments 
 */
 
function rara_journal_theme_comment($comment, $args, $depth) {
	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
	<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	
    <footer class="comment-meta">

        <div class="comment-author vcard">
            <?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
            <?php printf( __( '<b class="fn"><a href="<?php get_the_author(); ?>">%s</a></b>', 'rara-journal' ), get_comment_author_link() ); ?>
        </div>
        <?php if ( $comment->comment_approved == '0' ) : ?>
            <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'rara-journal' ); ?></em>
            <br />
        <?php endif; ?>
    
        <div class="comment-metadata commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time datetime="<?php comment_date(); ?>">
            <?php
                /* translators: 1: date, 2: time */
                echo esc_html( get_comment_date() ); ?></time></a><?php edit_comment_link( __( '(Edit)', 'rara-journal' ), '  ', '' );
            ?>
        </div>

    </footer>
    
    <div class="comment-content"><?php comment_text(); ?></div>

	<div class="reply">
	<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php
}

/**
 * Call Back Function for Home page Slider
 */
function rara_journal_slider_cb(){
    $rarajournal_slider_caption = get_theme_mod( 'rara_journal_slider_caption', '1' );
    $rarajournal_slider_cat = get_theme_mod( 'rara_journal_slider_cat' );
    
    if( $rarajournal_slider_cat ){
        $slider_qry = new WP_Query( array( 
            'post_type'             => 'post', 
            'post_status'           => 'publish',
            'posts_per_page'        => -1,                    
            'cat'                   => $rarajournal_slider_cat,
            'ignore_sticky_posts'   => true
        ) );
        if( $slider_qry->have_posts() ){
            echo ' <div class="slider hidden" id="banner-slider"><ul id="lightSlider">';
            
            while( $slider_qry->have_posts()) {
                $slider_qry->the_post();
                if( has_post_thumbnail() ){
                ?>
    			<li>
                    <a href ="<?php the_permalink(); ?>" >
    				    <?php 
                        the_post_thumbnail( 'rara-journal-slider' ); ?>
                        <?php if($rarajournal_slider_caption){ ?>           
                    <div class="image-holder">
    					<p><?php the_title(); ?></p>
 				    </div>
                  <?php } ?> 
              </a>  
    			</li>
                <?php 
                }
            } 
            echo '</ul></div>';
            wp_reset_postdata(); 
        }
    }   
 } 
 add_action( 'rara_journal_slider', 'rara_journal_slider_cb' );
 
 /**
  * Function for Social Icons 
  */
function rara_journal_social_link(){

    $rarajournal_button_url_fb = get_theme_mod( 'rara_journal_button_url_fb' );
    $rarajournal_button_url_tw = get_theme_mod( 'rara_journal_button_url_tw' );
    $rarajournal_button_url_ln = get_theme_mod( 'rara_journal_button_url_ln' );
    $rarajournal_button_url_rss = get_theme_mod( 'rara_journal_button_url_rss' );
    $rarajournal_button_url_gp = get_theme_mod( 'rara_journal_button_url_gp' );
    $rarajournal_button_url_pi = get_theme_mod( 'rara_journal_button_url_pin' );
    $rarajournal_button_url_yt = get_theme_mod( 'rara_journal_button_url_yt' );
    $rarajournal_button_url_ins = get_theme_mod( 'rara_journal_button_url_ins' );
    ?>
		<ul class="social-networks">
			 <?php if( $rarajournal_button_url_fb ){?>
			<li><a href="<?php echo esc_url( $rarajournal_button_url_fb ) ?>"><i class="fab fa-facebook-f"></i></a></li>
			<?php } if( $rarajournal_button_url_tw ){?>
			<li><a href="<?php echo esc_url( $rarajournal_button_url_tw ) ?>"><i class="fab fa-twitter"></i></a></li>
			<?php } if( $rarajournal_button_url_ln ){?>
			<li><a href="<?php echo esc_url( $rarajournal_button_url_ln ) ?>"><i class="fab fa-linkedin-in"></i></a></li>
			<?php } if( $rarajournal_button_url_rss ){?>
			<li><a href="<?php echo esc_url( $rarajournal_button_url_rss ) ?>"><i class="fas fa-rss"></i></a></li>
			<?php } if( $rarajournal_button_url_gp ){?>
			<li><a href="<?php echo esc_url( $rarajournal_button_url_gp ) ?>"><i class="fab fa-google-plus"></i></a></li>
			<?php } if( $rarajournal_button_url_yt ){?>
			<li><a href="<?php echo esc_url( $rarajournal_button_url_yt ) ?>"><i class="fab fa-youtube"></i></a></li>
			<?php } if( $rarajournal_button_url_pi ){?>
			<li><a href="<?php echo esc_url( $rarajournal_button_url_pi ) ?>"><i class="fab fa-pinterest-p"></i></a></li>
			<?php } if( $rarajournal_button_url_ins ){?>
			<li><a href="<?php echo esc_url( $rarajournal_button_url_ins ) ?>"><i class="fab fa-instagram"></i></a></li>
			<?php } ?>
			</ul>

 <?php 
} 

/**
 *Filter For Archive Title
 */
function rara_journal_archive_title( $title ) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = get_the_author();
    }
    return $title;

}
add_filter( 'get_the_archive_title', 'rara_journal_archive_title' );

if ( ! function_exists( 'rara_journal_excerpt_more' ) ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... * 
 */
function rara_journal_excerpt_more( $more ) {
	return is_admin() ? $more : ' &hellip; ';
}
add_filter( 'excerpt_more', 'rara_journal_excerpt_more' );
endif;

if ( ! function_exists( 'rara_journal_excerpt_length' ) ) :
/**
 * Changes the default 55 character in excerpt 
*/
function rara_journal_excerpt_length( $length ) {
	return is_admin() ? $length : 60;
}
add_filter( 'excerpt_length', 'rara_journal_excerpt_length', 999 );
endif;

/**
 * Return sidebar layouts for pages
*/
function rara_journal_sidebar_layout(){
    global $post;
    
    if( get_post_meta( $post->ID, 'rara_journal_sidebar_layout', true ) ){
        return esc_html( get_post_meta( $post->ID, 'rara_journal_sidebar_layout', true ) );    
    }else{
        return 'right-sidebar';
    }
}

/**
 * Footer Credits 
*/
function rara_journal_footer_credit(){
      
    $text  = '<div class="site-info"><div class="container"><p>';
    $text .= esc_html__( 'Copyright &copy; ', 'rara-journal' ) . date_i18n( esc_html__( 'Y', 'rara-journal' ) ); 
    $text .= ' <a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a> &verbar; ';
    $text .= esc_html__( 'Rara Journal by: ', 'rara-journal' );
    $text .= '<a href="' . esc_url( 'https://rarathemes.com/' ) .'" rel="nofollow" target="_blank">' . esc_html__( 'Rara Theme', 'rara-journal' ) . '</a> &verbar; ';
    $text .= sprintf( esc_html__( 'Powered by: %s', 'rara-journal' ), '<a href="'. esc_url( __( 'https://wordpress.org/', 'rara-journal' ) ) .'" target="_blank">WordPress</a>' );
    if( function_exists( 'get_the_privacy_policy_link' ) ){
        $text .= ' &verbar; ' . get_the_privacy_policy_link();    
    }
    $text .= '</p></div></div>';
    echo apply_filters( 'rara_journal_footer_text', $text );    
}
add_action( 'rara_journal_footer', 'rara_journal_footer_credit' );

if( ! function_exists( 'rara_journal_commentd_fields' ) ) :
/**
 * Change Comment form default fields i.e. author, email & url.
 * https://blog.josemcastaneda.com/2016/08/08/copy-paste-hurting-theme/
*/
function rara_journal_commentd_fields( $fields ){    
    // get the current commenter if available
    $commenter = wp_get_current_commenter();
 
    // core functionality
    $req      = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $required = ( $req ? " required" : '' );
    $author   = ( $req ? __( 'Name*', 'rara-journal' ) : __( 'Name', 'rara-journal' ) );
    $email    = ( $req ? __( 'Email*', 'rara-journal' ) : __( 'Email', 'rara-journal' ) );
 
    // Change just the author field
    $fields['author'] = '<p class="comment-form-author"><label class="screen-reader-text" for="author">' . esc_html__( 'Name', 'rara-journal' ) . '<span class="required">*</span></label><input id="author" name="author" placeholder="' . esc_attr( $author ) . '" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . $required . ' /></p>';
    
    $fields['email'] = '<p class="comment-form-email"><label class="screen-reader-text" for="email">' . esc_html__( 'Email', 'rara-journal' ) . '<span class="required">*</span></label><input id="email" name="email" placeholder="' . esc_attr( $email ) . '" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . $required. ' /></p>';
    
    $fields['url'] = '<p class="comment-form-url"><label class="screen-reader-text" for="url">' . esc_html__( 'Website', 'rara-journal' ) . '</label><input id="url" name="url" placeholder="' . esc_attr__( 'Website', 'rara-journal' ) . '" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>'; 
    
    return $fields;    
}
endif;
add_filter( 'comment_form_default_fields', 'rara_journal_commentd_fields' );

if( ! function_exists( 'rara_journal_change_comment_form' ) ) :
/**
 * Change Comment Form defaults
 * https://blog.josemcastaneda.com/2016/08/08/copy-paste-hurting-theme/
*/
function rara_journal_change_comment_form( $defaults ){    
    $defaults['comment_field'] = '<p class="comment-form-comment"><label class="screen-reader-text" for="comment">' . esc_html__( 'Comment', 'rara-journal' ) . '</label><textarea id="comment" name="comment" placeholder="' . esc_attr__( 'Comment', 'rara-journal' ) . '" cols="45" rows="8" aria-required="true" required></textarea></p>';
    
    return $defaults;    
}
endif;
add_filter( 'comment_form_defaults', 'rara_journal_change_comment_form' );

if( ! function_exists( 'wp_body_open' ) ) :
/**
 * Fire the wp_body_open action.
 * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
*/
function wp_body_open() {
	/**
	 * Triggered after the opening <body> tag.
    */
	do_action( 'wp_body_open' );
}
endif;

if( ! function_exists( 'rara_journal_get_image_sizes' ) ) :
/**
 * Get information about available image sizes
 */
function rara_journal_get_image_sizes( $size = '' ) {
 
    global $_wp_additional_image_sizes;
 
    $sizes = array();
    $get_intermediate_image_sizes = get_intermediate_image_sizes();
 
    // Create the full array with sizes and crop info
    foreach( $get_intermediate_image_sizes as $_size ) {
        if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
            $sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
            $sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
            $sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );
        } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
            $sizes[ $_size ] = array( 
                'width' => $_wp_additional_image_sizes[ $_size ]['width'],
                'height' => $_wp_additional_image_sizes[ $_size ]['height'],
                'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
            );
        }
    } 
    // Get only 1 size if found
    if ( $size ) {
        if( isset( $sizes[ $size ] ) ) {
            return $sizes[ $size ];
        } else {
            return false;
        }
    }
    return $sizes;
}
endif;

if( ! function_exists( 'rara_journal_mobile_primary_navigation' ) ) :
/**
 * Primary Mobile Navigation
 */
function rara_journal_mobile_primary_navigation(){ ?>
    <div class="mobile-menu-wrapper">
        <nav id="mobile-site-navigation" class="main-navigation mobile-navigation">        
            <div class="primary-menu-list main-menu-modal cover-modal" data-modal-target-string=".main-menu-modal">
                <button class="close close-main-nav-toggle" data-toggle-target=".main-menu-modal" data-toggle-body-class="showing-main-menu-modal" aria-expanded="false" data-set-focus=".main-menu-modal"></button>
                <div class="mobile-menu" aria-label="<?php esc_attr_e( 'Mobile', 'rara-journal' ); ?>">
                    <?php
                        wp_nav_menu( 
                            array( 
                                'theme_location' => 'primary',
                                'menu_id'        => 'mobile-primary-menu',
                                'menu_class'     => 'nav-menu main-menu-modal',
                                'fallback_cb'    => false,
                            ) 
                        );
                    ?>
                </div>
            </div>
        </nav><!-- #mobile-site-navigation -->
    </div>
    <?php
}
endif;

if( ! function_exists( 'rara_journal_primary_navigation' ) ) :
/**
 * Primary Navigation
 */
function rara_journal_primary_navigation(){ ?>
    <nav id="site-navigation" class="main-navigation" role="navigation">
        <?php 
        wp_nav_menu( 
            array( 
                'theme_location' => 'primary',
                'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                'fallback_cb'    => false,
            ) 
        ); 
        ?>
    </nav>
    <?php
}
endif;

if( ! function_exists( 'rara_journal_mobile_secondary_navigation' ) ) :
/**
 * Secondary Mobile Navigation
 */
function rara_journal_mobile_secondary_navigation(){ ?>
    <div class="mobile-secondary-menu-wrapper">
        <nav id="secondary-menu" class="top-menu mobile-navigation">        
            <div class="secondary-menu-list menu-modal cover-modal" data-modal-target-string=".menu-modal">
                <button class="close close-nav-toggle" data-toggle-target=".menu-modal" data-toggle-body-class="showing-menu-modal" aria-expanded="false" data-set-focus=".menu-modal"></button>
                <div class="mobile-menu" aria-label="<?php esc_attr_e( 'Mobile', 'rara-journal' ); ?>">
                    <?php
                        wp_nav_menu( 
                            array( 
                                'theme_location' => 'secondary',
                                'container'      => false,
                                'menu_id'        => 'secondary-menu',
                                'menu_class'     => 'nav-menu menu-modal',
                                'fallback_cb'    => false,
                            ) 
                        );
                    ?>
                </div>
            </div>
        </nav><!-- #mobile-site-navigation -->
    </div>
    <?php
}
endif;

if( ! function_exists( 'rara_journal_secondary_navigation' ) ) :
/**
 * Secondary Navigation
 */
function rara_journal_secondary_navigation(){ ?>
    <nav class="top-menu secondary-nav" id="secondary-menu">
        <?php
        wp_nav_menu( 
            array( 
                'theme_location' => 'secondary',
                'container' => false,
                'fallback_cb'  => false,
            ) 
        ); 
        ?>
    </nav>
    <?php
}
endif;

if ( ! function_exists( 'rara_journal_get_fallback_svg' ) ) :    
/**
 * Get Fallback SVG
*/
function rara_journal_get_fallback_svg( $post_thumbnail ) {
    if( ! $post_thumbnail ){
        return;
    }
    
    $image_size = rara_journal_get_image_sizes( $post_thumbnail );
     
    if( $image_size ){ ?>
        <div class="svg-holder">
             <svg class="fallback-svg" viewBox="0 0 <?php echo esc_attr( $image_size['width'] ); ?> <?php echo esc_attr( $image_size['height'] ); ?>" preserveAspectRatio="none">
                    <rect width="<?php echo esc_attr( $image_size['width'] ); ?>" height="<?php echo esc_attr( $image_size['height'] ); ?>" style="fill:#e0dfdf;"></rect>
            </svg>
        </div>
        <?php
    }
}
endif;

if( ! function_exists( 'rara_journal_fonts_url' ) ) :
/**
 * Register custom fonts.
 */
function rara_journal_fonts_url() {
    $fonts_url = '';

    /*
    * translators: If there are characters in your language that are not supported
    * by Lustria, translate this to 'off'. Do not translate into your own language.
    */
    $lustria = _x( 'on', 'Lustria font: on or off', 'rara-journal' );
    
    /*
    * translators: If there are characters in your language that are not supported
    * by Lato, translate this to 'off'. Do not translate into your own language.
    */
    $lato = _x( 'on', 'Lato font: on or off', 'rara-journal' );

    if ( 'off' !== $lustria || 'off' !== $lato ) {
        $font_families = array();

        if( 'off' !== $lustria ){
            $font_families[] = 'Lustria';
        }

        if( 'off' !== $lato ){
            $font_families[] = 'Lato:400,700';
        }

        $query_args = array(
            'family'  => urlencode( implode( '|', $font_families ) ),
            'display' => urlencode( 'fallback' ),
        );

        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    }

    return esc_url( $fonts_url );
}
endif;