<?php
/**
 * Rara Journal Theme Customizer
 *
 * @package Rara_Journal
 */

function rara_journal_customizer_theme_info( $wp_customize ) {
    
    $wp_customize->add_section( 'theme_info', 
        array(
            'title'    => __( 'Information Links', 'rara-journal' ),
            'priority' => 5,
        )
    );

    /** Important Links */
    $wp_customize->add_setting( 'theme_info_theme',
        array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $theme_info = '<ul>';
    $theme_info .= sprintf( __( '<li>View demo: %1$sClick here.%2$s</li>', 'rara-journal' ),  '<a href="' . esc_url( 'https://rarathemes.com/previews/?theme=rara-journal' ) . '" target="_blank">', '</a>' );
    $theme_info .= sprintf( __( '<li>View documentation: %1$sClick here.%2$s</li>', 'rara-journal' ),  '<a href="' . esc_url( 'https://docs.rarathemes.com/docs/rara-journal/' ) . '" target="_blank">', '</a>' );
    $theme_info .= sprintf( __( '<li>Theme info: %1$sClick here.%2$s</li>', 'rara-journal' ),  '<a href="' . esc_url( 'https://rarathemes.com/wordpress-themes/rara-journal/' ) . '" target="_blank">', '</a>' );
    $theme_info .= sprintf( __( '<li>Support ticket: %1$sClick here.%2$s</li>', 'rara-journal' ),  '<a href="' . esc_url( 'https://rarathemes.com/support-ticket/' ) . '" target="_blank">', '</a>' );
    $theme_info .= sprintf( __( '<li>More WordPress Themes: %1$sClick here.%2$s</li>', 'rara-journal' ),  '<a href="' . esc_url( 'https://rarathemes.com/wordpress-themes/' ) . '" target="_blank">', '</a>' );
    $theme_info .= '</ul>';

    $wp_customize->add_control( new Rara_Journal_Theme_Info( $wp_customize,
        'theme_info_theme',
            array(
                'label'       => __( 'Important Links' , 'rara-journal' ),
                'section'     => 'theme_info',
                'description' => $theme_info
            )
        )
    );

}
add_action( 'customize_register', 'rara_journal_customizer_theme_info' );


if( class_exists( 'WP_Customize_Control' ) ){

    class Rara_Journal_Theme_Info extends WP_Customize_Control
    {
        public function render_content(){ ?>
            <span class="customize-control-title">
                <?php echo esc_html( $this->label ); ?>
            </span>
    
            <?php if( $this->description ){ ?>
                <span class="description customize-control-description">
                <?php echo wp_kses_post($this->description); ?>
                </span>
            <?php }
        }
    }//editor close
    
}//class close

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function rara_journal_customize_register( $wp_customize ) {

    /* Option list of all post */	
    $rarajournal_options_posts = array();
    $rarajournal_options_posts_obj = get_posts('posts_per_page=-1');
    $rarajournal_options_posts[''] = __( 'Choose Post', 'rara-journal' );
    foreach ( $rarajournal_options_posts_obj as $rarajournal_posts ) {
    	$rarajournal_options_posts[$rarajournal_posts->ID] = $rarajournal_posts->post_title;
    }
    
    /* Option list of all categories */
    $rarajournal_args = array(
	   'type'                     => 'post',
	   'orderby'                  => 'name',
	   'order'                    => 'ASC',
	   'hide_empty'               => 1,
	   'hierarchical'             => 1,
	   'taxonomy'                 => 'category'
    ); 
    $rarajournal_option_categories = array();
    $rarajournal_category_lists = get_categories( $rarajournal_args );
    $rarajournal_option_categories[''] = __( 'Choose Category', 'rara-journal' );
    foreach( $rarajournal_category_lists as $rarajournal_category ){
        $rarajournal_option_categories[$rarajournal_category->term_id] = $rarajournal_category->name;
    }
    
    /** Default Settings */    
    $wp_customize->add_panel( 
        'wp_default_panel',
         array(
            'priority' => 10,
            'capability' => 'edit_theme_options',
            'theme_supports' => '',
            'title' => __( 'Default Settings', 'rara-journal' ),
            'description' => __( 'Default section provided by WordPress customizer.', 'rara-journal' ),
        ) 
    );
    
    $wp_customize->get_section( 'title_tagline' )->panel     = 'wp_default_panel';
    $wp_customize->get_section( 'colors' )->panel            = 'wp_default_panel';
    $wp_customize->get_section( 'header_image' )->panel      = 'wp_default_panel';
    $wp_customize->get_section( 'background_image' )->panel  = 'wp_default_panel';
    $wp_customize->get_section( 'static_front_page' )->panel = 'wp_default_panel'; 
    
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
    
    /** Default Settings Ends */
        
    /** Slider Settings */
    $wp_customize->add_section(
        'rara_journal_slider_settings',
        array(
            'title' => __( 'Slider Settings', 'rara-journal' ),
            'priority' => 30,
            'capability' => 'edit_theme_options',
        )
    );
    
    /** Enable/Disable Slider */
    $wp_customize->add_setting(
        'rara_journal_ed_slider',
        array(
            'default' => '',
            'sanitize_callback' => 'rara_journal_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'rara_journal_ed_slider',
        array(
            'label' => __( 'Enable Home Page Slider', 'rara-journal' ),
            'section' => 'rara_journal_slider_settings',
            'type' => 'checkbox',
        )
    );
    
    /** Enable/Disable Slider Auto Transition */
    $wp_customize->add_setting(
        'rara_journal_slider_auto',
        array(
            'default' => '1',
            'sanitize_callback' => 'rara_journal_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'rara_journal_slider_auto',
        array(
            'label' => __( 'Enable Slider Auto Transition', 'rara-journal' ),
            'section' => 'rara_journal_slider_settings',
            'type' => 'checkbox',
        )
    );
    
    /** Enable/Disable Slider Loop */
    $wp_customize->add_setting(
        'rara_journal_slider_loop',
        array(
            'default' => '1',
            'sanitize_callback' => 'rara_journal_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'rara_journal_slider_loop',
        array(
            'label' => __( 'Enable Slider Loop', 'rara-journal' ),
            'section' => 'rara_journal_slider_settings',
            'type' => 'checkbox',
        )
    );
    
    /** Enable/Disable Slider Pager */
    $wp_customize->add_setting(
        'rara_journal_slider_pager',
        array(
            'default' => '1',
            'sanitize_callback' => 'rara_journal_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'rara_journal_slider_pager',
        array(
            'label' => __( 'Enable Slider Pager', 'rara-journal' ),
            'section' => 'rara_journal_slider_settings',
            'type' => 'checkbox',
        )
    );
    
    /** Enable/Disable Slider Caption */
    $wp_customize->add_setting(
        'rara_journal_slider_caption',
        array(
            'default' => '1',
            'sanitize_callback' => 'rara_journal_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'rara_journal_slider_caption',
        array(
            'label' => __( 'Enable Slider Caption', 'rara-journal' ),
            'section' => 'rara_journal_slider_settings',
            'type' => 'checkbox',
        )
    );
        
    /** Slider Animation */
    $wp_customize->add_setting(
        'rara_journal_slider_animation',
        array(
            'default' => 'slide',
            'sanitize_callback' => 'rara_journal_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'rara_journal_slider_animation',
        array(
            'label' => __( 'Choose Slider Animation', 'rara-journal' ),
            'section' => 'rara_journal_slider_settings',
            'type' => 'select',
            'choices' => array(
                'fade' => __( 'Fade', 'rara-journal' ),
                'slide' => __( 'Slide', 'rara-journal' ),
            )
        )
    );
    
    /** Slider Speed */
    $wp_customize->add_setting(
        'rara_journal_slider_speed',
        array(
            'default' => '7000',
            'sanitize_callback' => 'rara_journal_sanitize_number_absint',
        )
    );
    
    $wp_customize->add_control(
        'rara_journal_slider_speed',
        array(
            'label' => __( 'Slider Speed', 'rara-journal' ),
            'section' => 'rara_journal_slider_settings',
            'type' => 'text',
        )
    );
    
    /** Animation Speed */
    $wp_customize->add_setting(
        'rara_journal_animation_speed',
        array(
            'default' => '600',
            'sanitize_callback' => 'rara_journal_sanitize_number_absint',
        )
    );
    
    $wp_customize->add_control(
        'rara_journal_animation_speed',
        array(
            'label' => __( 'Animation Speed', 'rara-journal' ),
            'section' => 'rara_journal_slider_settings',
            'type' => 'text',
        )
    );
    
    /** Select Category */
    $wp_customize->add_setting(
        'rara_journal_slider_cat',
        array(
            'default' => '',
            'sanitize_callback' => 'rara_journal_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'rara_journal_slider_cat',
        array(
            'label' => __( 'Choose Slider Category', 'rara-journal' ),
            'section' => 'rara_journal_slider_settings',
            'type' => 'select',
            'choices' => $rarajournal_option_categories,
        )
    );
    /** Slider Settings Ends */
    
    /** Social Settings */
    $wp_customize->add_section(
        'rara_journal_social_settings',
        array(
            'title' => __( 'Social Settings', 'rara-journal' ),
            'description' => __( 'Leave blank if you do not want to show the social link.', 'rara-journal' ),
            'priority' => 30,
            'capability' => 'edit_theme_options',
        )
    );
    
    /** Enable/Disable Social Links*/
    $wp_customize->add_setting(
        'rara_journal_social_ed',
        array(
            'default' => '1',
            'sanitize_callback' => 'rara_journal_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'rara_journal_social_ed',
        array(
            'label' => __( 'Enable Social Links', 'rara-journal' ),
            'section' => 'rara_journal_social_settings',
            'type' => 'checkbox',
        )
    );

    /** Facebook Button Url */
    $wp_customize->add_setting(
        'rara_journal_button_url_fb',
        array( 
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'rara_journal_button_url_fb',
        array(
            'label' => __( 'Facebook Page Url', 'rara-journal' ),
            'section' => 'rara_journal_social_settings',
            'type' => 'text',
        )
    );
    
    /** Twiter Button Url */
    $wp_customize->add_setting(
        'rara_journal_button_url_tw',
        array( 
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'rara_journal_button_url_tw',
        array(
            'label' => __( 'Twitter Page Url', 'rara-journal' ),
            'section' => 'rara_journal_social_settings',
            'type' => 'text',
        )
    );

    /** Linkedin Button Url */
    $wp_customize->add_setting(
        'rara_journal_button_url_ln',
        array( 
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'rara_journal_button_url_ln',
        array(
            'label' => __( 'Linkedin Page Url', 'rara-journal' ),
            'section' => 'rara_journal_social_settings',
            'type' => 'text',
        )
    );
    
    /** Rss Button Url */
    $wp_customize->add_setting(
        'rara_journal_button_url_rss',
        array( 
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'rara_journal_button_url_rss',
        array(
            'label' => __( 'Rss Feed Url', 'rara-journal' ),
            'section' => 'rara_journal_social_settings',
            'type' => 'text',
        )
    );

    /**  Google Plus Button Url */
    $wp_customize->add_setting(
        'rara_journal_button_url_gp',
        array( 
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'rara_journal_button_url_gp',
        array(
            'label' => __( 'Google Plus Url', 'rara-journal' ),
            'section' => 'rara_journal_social_settings',
            'type' => 'text',
        )
    );

    /**  Youtube Button Url */
    $wp_customize->add_setting(
        'rara_journal_button_url_yt',
        array( 
            'default' =>'',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'rara_journal_button_url_yt',
        array(
            'label' => __( 'Youtube Url', 'rara-journal' ),
            'section' => 'rara_journal_social_settings',
            'type' => 'text',
        )
    );

    /**  Pinterest Button Url */
    $wp_customize->add_setting(
        'rara_journal_button_url_pin',
        array( 
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'rara_journal_button_url_pin',
        array(
            'label' => __( 'Pinterest Url', 'rara-journal' ),
            'section' => 'rara_journal_social_settings',
            'type' => 'text',
        )
    );

    /**  instagram Button Url */
    $wp_customize->add_setting(
        'rara_journal_button_url_ins',
        array( 
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'rara_journal_button_url_ins',
        array(
            'label' => __( 'Instagram Url', 'rara-journal' ),
            'section' => 'rara_journal_social_settings',
            'type' => 'text',
        )
    );
    /** Social Settings Ends */

    /** Post Settings */
    $wp_customize->add_section(
        'rara_journal_post_settings',
        array(
            'title' => __( 'Post Settings', 'rara-journal' ),
            'priority' => 40,
            'capability' => 'edit_theme_options',
        )
    );
    /** Read More Button Setting */
    $wp_customize->add_setting(
        'rara_journal_post_read_more',
        array(
            'default' => __( 'Continue Reading', 'rara-journal' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'rara_journal_post_read_more',
        array(
            'label' => __( 'Posts Read More Button', 'rara-journal' ),
            'section' => 'rara_journal_post_settings',
            'type' => 'text',
        )
    );
    /** Post Setting Ends */

    /**
     * Sanitization Functions
     * 
     * @link https://github.com/WPTRT/code-examples/blob/master/customizer/sanitization-callbacks.php 
     */
    function rara_journal_sanitize_checkbox( $checked ){
        // Boolean check.
        return ( ( isset( $checked ) && true == $checked ) ? true : false );
    }
     
    function rara_journal_sanitize_select( $input, $setting ){
        // Ensure input is a slug.
        $input = sanitize_key( $input );
        
        // Get list of choices from the control associated with the setting.
        $choices = $setting->manager->get_control( $setting->id )->choices;
        
        // If the input is a valid key, return it; otherwise, return the default.
        return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
    }

    function rara_journal_sanitize_number_absint( $number, $setting ) {
        // Ensure $number is an absolute integer (whole number, zero or greater).
        $number = absint( $number );
        
        // If the input is an absolute integer, return it; otherwise, return the default
        return ( $number ? $number : $setting->default );
    }
}
add_action( 'customize_register', 'rara_journal_customize_register' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function rara_journal_customize_preview_js() {
	wp_enqueue_script( 'rara_journal_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'rara_journal_customize_preview_js' );

