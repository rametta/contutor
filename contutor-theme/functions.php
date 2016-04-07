<?php
/* All functions for the contutor theme */
/* Some features may depend on functions declared in the contutor plugin functions.php file */

/* Custom Contutor WP Hooks and Filters */

    //customizer theme color
    add_action( 'customize_register', 'theme_color_customize_register');

    //customizer custom menu
    add_action( 'init', 'register_contutor_menu');
    
    //custom login and register screen modifiers for logo
    add_action( 'login_enqueue_scripts', 'login_logo' );
    add_filter( 'login_headerurl', 'login_logo_url' );
    add_filter( 'login_headertitle', 'login_logo_url_title' );

    //backend and admin adjustments
    add_filter('admin_footer_text', 'contutor_footer_admin');
    add_action( 'admin_bar_menu', 'remove_wp_logo', 999 );

    //removing emoji support for reduced load times
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );

    //tutor like system
    add_action( 'wp_enqueue_scripts', 'contutor_enqueue_scripts' );
    add_action( 'wp_ajax_nopriv_process_tutor_like', 'process_tutor_like' );
    add_action( 'wp_ajax_process_tutor_like', 'process_tutor_like' );


/* Theme Color Customizer */
function theme_color_customize_register( $wp_customize ) 
{
    $wp_customize->add_section( 'contutor_theme_section' , array(
        'title'    => __( 'Theme', 'contutor' ),
        'priority' => 30
    ) );   

    $wp_customize->add_setting( 'contutor_theme_setting' , array(
        'default'   => '#921a35', /* default concordia red tint */
        'transport' => 'refresh',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
        'label'    => __( 'Theme Color', 'contutor_theme_color' ),
        'section'  => 'contutor_theme_section',
        'settings' => 'contutor_theme_setting',
    ) ) );
}

/* Register Menus */
function register_contutor_menu() {
  register_nav_menu('contutor-menu',__( 'Contutor Menu' ));
}

/* CSS for login logo */
function login_logo() { ?>
    <style type="text/css">
        .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/img/c-logo.png);
            padding-bottom: 30px;
        }
    </style>
<?php }

/* Link for login screen */
function login_logo_url() {
    return home_url();
}

/* Title attribute for login logo */
function login_logo_url_title() {
    return 'Contutor';
}

/* Replace admin footer text */
function contutor_footer_admin(){
    echo '<span>Developed by <a href="http://www.contutor.ca" target="_blank">Contutor</a> in Montreal.</span>';
}

/* Remove WP admin logo */
function remove_wp_logo($wp_admin_bar){
	$wp_admin_bar->remove_node( 'wp-logo' );
}

/* Inserts a tutor panel */
function insert_tutor($slug, $photo, $fname, $lname, $focus, $contact_slug, $courses, $languages, $stars, $comments){
    ?>
<div class="col-sm-6 col-md-4">
    <div class="thumbnail">
        <a href="<?php echo 'http://contutor.ca/tutors/' . $slug ?>"><img width="500" height="500" src="<?php echo $photo ?>" alt="<?php echo $fname . ' ' . $lname?>"></a>
        <div class="caption">

            <h3><?php echo $fname . '<br />' . $lname ?></h3>
            <h6><?php echo $focus->name ?></h6>

            <span class="badge badge-maroon"><span class="glyphicon glyphicon-star" aria-hidden="true"></span> <?php echo $stars ?></span>
            <span class="badge"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> <?php echo $comments ?></span>

            <br/>

            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    View <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo 'http://contutor.ca/tutors/' . $slug ?>"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
                    <li><a href="<?php echo 'http://contutor.ca/tutors/' . $slug . '#commentform' ?>">Contact</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a data-toggle="tooltip" data-placement="right" title="<?php echo strip_tags($courses) ?>">Courses</a></li>
                    <li><a data-toggle="tooltip" data-placement="right" title="<?php echo strip_tags($languages) ?>">Languages</a></li>
                </ul>
            </div>

        </div>
    </div>
</div>
<?php
}//end insert panel function


/* Like system functions begin here */

/* Enqueues scripts */
function contutor_enqueue_scripts() {
	wp_enqueue_script( 'simple-likes-public-js', get_template_directory_uri() . '/js/contutorLikes.js', array( 'jquery' ), '0.5', false );
	wp_localize_script( 'simple-likes-public-js', 'tutorLikes', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'like' => __( 'Like', 'contutor' ),
		'unlike' => __( 'Unlike', 'contutor' )
	) ); 
}

/* Returns static like count of tutor */
function tutor_like_count($tutor_id){
    $like_count = get_post_meta( $tutor_id, "_post_like_count", true );
    $like_count = ( isset( $like_count ) && is_numeric( $like_count ) ) ? $like_count : 0;
    return $like_count;
}

/* Processes all likes */
function process_tutor_like() {
	// Security
	$nonce = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : 0;
	if ( !wp_verify_nonce( $nonce, 'simple-likes-nonce' ) ) {
		exit( __( 'Not permitted', 'contutor' ) );
	}
	// Test if javascript is disabled
	$disabled = ( isset( $_REQUEST['disabled'] ) && $_REQUEST['disabled'] == true ) ? true : false;
	// Test if this is a comment
	$is_comment = ( isset( $_REQUEST['is_comment'] ) && $_REQUEST['is_comment'] == 1 ) ? 1 : 0;
	// Base variables
	$post_id = ( isset( $_REQUEST['post_id'] ) && is_numeric( $_REQUEST['post_id'] ) ) ? $_REQUEST['post_id'] : '';
	$result = array();
	$post_users = NULL;
	$like_count = 0;
	// Get plugin options
	if ( $post_id != '' ) {
		$count = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_comment_like_count", true ) : get_post_meta( $post_id, "_post_like_count", true ); // like count
		$count = ( isset( $count ) && is_numeric( $count ) ) ? $count : 0;
		if ( !already_liked( $post_id, $is_comment ) ) { // Like the post
			if ( is_user_logged_in() ) { // user is logged in
				$user_id = get_current_user_id();
				$post_users = post_user_likes( $user_id, $post_id, $is_comment );
				if ( $is_comment == 1 ) {
					// Update User & Comment
					$user_like_count = get_user_option( "_comment_like_count", $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					update_user_option( $user_id, "_comment_like_count", ++$user_like_count );
					if ( $post_users ) {
						update_comment_meta( $post_id, "_user_comment_liked", $post_users );
					}
				} else {
					// Update User & Post
					$user_like_count = get_user_option( "_user_like_count", $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					update_user_option( $user_id, "_user_like_count", ++$user_like_count );
					if ( $post_users ) {
						update_post_meta( $post_id, "_user_liked", $post_users );
					}
				}
			} else { // user is anonymous
				$user_ip = tutor_get_ip();
				$post_users = post_ip_likes( $user_ip, $post_id, $is_comment );
				// Update Post
				if ( $post_users ) {
					if ( $is_comment == 1 ) {
						update_comment_meta( $post_id, "_user_comment_IP", $post_users );
					} else { 
						update_post_meta( $post_id, "_user_IP", $post_users );
					}
				}
			}
			$like_count = ++$count;
			$response['status'] = "liked";
			$response['icon'] = get_liked_icon();
		} else { // Unlike the post
			if ( is_user_logged_in() ) { // user is logged in
				$user_id = get_current_user_id();
				$post_users = post_user_likes( $user_id, $post_id, $is_comment );
				// Update User
				if ( $is_comment == 1 ) {
					$user_like_count = get_user_option( "_comment_like_count", $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					if ( $user_like_count > 0 ) {
						update_user_option( $user_id, "_comment_like_count", --$user_like_count );
					}
				} else {
					$user_like_count = get_user_option( "_user_like_count", $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					if ( $user_like_count > 0 ) {
						update_user_option( $user_id, '_user_like_count', --$user_like_count );
					}
				}
				// Update Post
				if ( $post_users ) {	
					$uid_key = array_search( $user_id, $post_users );
					unset( $post_users[$uid_key] );
					if ( $is_comment == 1 ) {
						update_comment_meta( $post_id, "_user_comment_liked", $post_users );
					} else { 
						update_post_meta( $post_id, "_user_liked", $post_users );
					}
				}
			} else { // user is anonymous
				$user_ip = tutor_get_ip();
				$post_users = post_ip_likes( $user_ip, $post_id, $is_comment );
				// Update Post
				if ( $post_users ) {
					$uip_key = array_search( $user_ip, $post_users );
					unset( $post_users[$uip_key] );
					if ( $is_comment == 1 ) {
						update_comment_meta( $post_id, "_user_comment_IP", $post_users );
					} else { 
						update_post_meta( $post_id, "_user_IP", $post_users );
					}
				}
			}
			$like_count = ( $count > 0 ) ? --$count : 0; // Prevent negative number
			$response['status'] = "unliked";
			$response['icon'] = get_unliked_icon();
		}
		if ( $is_comment == 1 ) {
			update_comment_meta( $post_id, "_comment_like_count", $like_count );
			update_comment_meta( $post_id, "_comment_like_modified", date( 'Y-m-d H:i:s' ) );
		} else { 
			update_post_meta( $post_id, "_post_like_count", $like_count );
			update_post_meta( $post_id, "_post_like_modified", date( 'Y-m-d H:i:s' ) );
		}
		$response['count'] = get_like_count( $like_count );
		$response['testing'] = $is_comment;
		if ( $disabled == true ) {
			if ( $is_comment == 1 ) {
				wp_redirect( get_permalink( get_the_ID() ) );
				exit();
			} else {
				wp_redirect( get_permalink( $post_id ) );
				exit();
			}
		} else {
			wp_send_json( $response );
		}
	}
}

/* Checks if user or IP address already liked that tutor */
function already_liked( $post_id, $is_comment ) {
	$post_users = NULL;
	$user_id = NULL;
	if ( is_user_logged_in() ) { // user is logged in
		$user_id = get_current_user_id();
		$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_liked" ) : get_post_meta( $post_id, "_user_liked" );
		if ( count( $post_meta_users ) != 0 ) {
			$post_users = $post_meta_users[0];
		}
	} else { // user is anonymous
		$user_id = tutor_get_ip();
		$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_IP" ) : get_post_meta( $post_id, "_user_IP" ); 
		if ( count( $post_meta_users ) != 0 ) { // meta exists, set up values
			$post_users = $post_meta_users[0];
		}
	}
	if ( is_array( $post_users ) && in_array( $user_id, $post_users ) ) {
		return true;
	} else {
		return false;
	}
}

/* Returns the like button */
function get_simple_likes_button( $post_id, $is_comment = NULL ) {
	$is_comment = ( NULL == $is_comment ) ? 0 : 1;
	$output = '';
	$nonce = wp_create_nonce( 'simple-likes-nonce' ); // Security
	if ( $is_comment == 1 ) {
		$post_id_class = esc_attr( ' tutor-comment-button-' . $post_id );
		$comment_class = esc_attr( ' tutor-comment' );
		$like_count = get_comment_meta( $post_id, "_comment_like_count", true );
		$like_count = ( isset( $like_count ) && is_numeric( $like_count ) ) ? $like_count : 0;
	} else {
		$post_id_class = esc_attr( ' tutor-button-' . $post_id );
		$comment_class = esc_attr( '' );
		$like_count = get_post_meta( $post_id, "_post_like_count", true );
		$like_count = ( isset( $like_count ) && is_numeric( $like_count ) ) ? $like_count : 0;
	}
	$count = get_like_count( $like_count );
	$icon_empty = get_unliked_icon();
	$icon_full = get_liked_icon();
	// Liked/Unliked Variables
	if ( already_liked( $post_id, $is_comment ) ) {
		$class = esc_attr( ' liked' );
		$title = __( 'Unlike', 'contutor' );
		$icon = $icon_full;
	} else {
		$class = '';
		$title = __( 'Like', 'contutor' );
		$icon = $icon_empty;
	}
	$output = '<span class="tutor-wrapper"><a href="' . admin_url( 'admin-ajax.php?action=process_tutor_like' . '&nonce=' . $nonce . '&post_id=' . $post_id . '&disabled=true&is_comment=' . $is_comment ) . '" class="tutor-button' . $post_id_class . $class . $comment_class . '" data-nonce="' . $nonce . '" data-post-id="' . $post_id . '" data-iscomment="' . $is_comment . '" title="' . $title . '">' . $icon . $count . '</a></span>';
	return $output;
}

/* Returns array of user ID's who have liked the tutor then adds the new user to the array */
function post_user_likes( $user_id, $post_id, $is_comment ) {
	$post_users = '';
	$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_liked" ) : get_post_meta( $post_id, "_user_liked" );
	if ( count( $post_meta_users ) != 0 ) {
		$post_users = $post_meta_users[0];
	}
	if ( !is_array( $post_users ) ) {
		$post_users = array();
	}
	if ( !in_array( $user_id, $post_users ) ) {
		$post_users['user-' . $user_id] = $user_id;
	}
	return $post_users;
}

/* Returns array of IP addresses who have liked the tutor then adds the new IP address to the array */
function post_ip_likes( $user_ip, $post_id, $is_comment ) {
	$post_users = '';
	$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_IP" ) : get_post_meta( $post_id, "_user_IP" );
	// Retrieve post information
	if ( count( $post_meta_users ) != 0 ) {
		$post_users = $post_meta_users[0];
	}
	if ( !is_array( $post_users ) ) {
		$post_users = array();
	}
	if ( !in_array( $user_ip, $post_users ) ) {
		$post_users['ip-' . $user_ip] = $user_ip;
	}
	return $post_users;
}

/* Gets the IP */
function tutor_get_ip() {
	if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) && ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = ( isset( $_SERVER['REMOTE_ADDR'] ) ) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
	}
	$ip = filter_var( $ip, FILTER_VALIDATE_IP );
	$ip = ( $ip === false ) ? '0.0.0.0' : $ip;
	return $ip;
}

/* Returns the like button */
function get_liked_icon() {
	$icon = '<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>';
	return $icon;
}

/* Returns the unlike button */
function get_unliked_icon() {
	$icon = '<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>';
	return $icon;
}

/* Formats like count for large numbers */
function tutor_format_count( $number ) {
	$precision = 2;
	if ( $number >= 1000 && $number < 1000000 ) {
		$formatted = number_format( $number/1000, $precision ).'K';
	} else if ( $number >= 1000000 && $number < 1000000000 ) {
		$formatted = number_format( $number/1000000, $precision ).'M';
	} else if ( $number >= 1000000000 ) {
		$formatted = number_format( $number/1000000000, $precision ).'B';
	} else {
		$formatted = $number; // Number is less than 1000
	}
	$formatted = str_replace( '.00', '', $formatted );
	return $formatted;
}

/* Returns the like count of the tutor */
function get_like_count( $like_count ) {
	$like_text = 0;
	if ( is_numeric( $like_count ) && $like_count > 0 ) { 
		$number = tutor_format_count( $like_count );
	} else {
		$number = $like_text;
	}
	$count = '<span class="tutor-count"> ' . $number . '</span>';
	return $count;
}

/* Like system functions end here */


/* Inserts a responsive google ad that inherits size of parent container when called */
/* Linked to Jason Rametta's adsense account */
function get_contutor_ad() {
?>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-9670538647625923"
         data-ad-slot="7222059697"
         data-ad-format="auto"></ins>
    <script>
    (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
<?php              
}



?>