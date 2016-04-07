<?php
/**
 * Plugin Name: Contutor Tutors
 * Plugin URI: http://Contutor.ca
 * Description: This plugin installs the tutor custom post type, taxonomies, and meta data in the backend
 * Version: 1.0.0
 * Author: Jason Rametta
 */

/* HOOKS AND FILTERS */
//custom post type for tutors vim test
add_action('init', 'tutor_post_type', 0);
//custom taxonomy for courses
add_action( 'init', 'course_taxonomy', 0 );
//custom taxonomy for programs
add_action( 'init', 'program_taxonomy', 0 );
//custom taxonomy for languages
add_action( 'init', 'language_taxonomy', 0 );
//admin columns for CPT tutors
add_filter( 'manage_edit-tutors_columns', 'edit_tutors_columns' ) ;
//add content to admin columns for CPT tutors table
add_action( 'manage_tutors_posts_custom_column', 'manage_tutors_columns', 10, 2 );
//allows post types to have featured images
add_theme_support ( 'post-thumbnails', array('post', 'page') );
//register tutor custom role in database on plugin activation
register_activation_hook( __FILE__, 'add_roles_on_plugin_activation' );
//2 hooks to modify slug for CPT tutor on save and update
add_action( 'save_post_tutors', 'modify_slug', 10, 3 );
add_action( 'wp_insert_post_tutors', 'modify_slug', 10, 3 );


/* FUNCTIONS */
/* Tutor Custom Post Type */
function tutor_post_type(){
    $labels = array(
        'name' => 'Tutor',
        'singular_name' => 'Tutor',
        'add_new' => 'Add Item',
        'all_items' => 'All Items',
        'menu_name' => 'Tutors',
        'add_new_item' => 'Add Item',
        'edit_item' => 'Edit Item',
        'new_item' => 'New Item',
        'view_item' => 'View Item',
        'search_item' => 'Search Tutor',
        'not_found' => 'No items found',
        'not_found_in_trash' => 'No items found in trash',
        'parent_item_colon' => 'Parent Item'
    );
    
    $args = array(
        'label' => 'Tutors',
        'description' => 'Concordia Tutors',
        'supports' => array('editor', 'comments', 'thumbnail'),
        'taxonomies' => array('course', 'program'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 3,
        'menu_icon' => plugins_url( 'contutor-menu-icon.png', __FILE__ ),
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
    );
    
    register_post_type('tutors', $args);
    
}

/* Course Custom Taxonomy */
function course_taxonomy(){
    $labels = array(
        'name' => 'Courses',
        'singular_name' => 'Course',
        'search_items' => 'Search Courses',
        'all_items' => 'All Courses',
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => 'Edit Course',
        'update_item' => 'Update Course',
        'add_new_item' => 'Add New Course',
        'new_item_name' => 'New Course Name',
        'separate_items_with_commas' => 'Separate courses with commas',
        'add_or_remove_items' => 'Add or remove courses',
        'choose_from_most_used' => 'Choose from most used courses',
        'not_found' => 'No courses found',
        'menu_name' => 'Courses',
    );

    $args = array(
        'hierarchical' => false,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'update_count_callback' =>  '_update_post_term_count',
        'query_var' => true,
        'rewrite' => array('slug' => 'course'),
    );
    
    register_taxonomy('course', 'tutors', $args);
}

/* Program Custom Taxonomy */
function program_taxonomy(){
    $labels = array(
        'name' => 'Programs',
        'singular_name' => 'Program',
        'search_items' => 'Search Programs',
        'all_items' => 'All Programs',
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => 'Edit Program',
        'update_item' => 'Update Program',
        'add_new_item' => 'Add New Program',
        'new_item_name' => 'New Program Name',
        'menu_name' => 'Programs',
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'update_count_callback' =>  '_update_post_term_count',
        'query_var' => true,
        'rewrite' => array('slug' => 'program'),
    );
    
    register_taxonomy('program', 'tutors', $args);
}

/* Language Custom Taxonomy */
function language_taxonomy(){
    $labels = array(
        'name' => 'Languages',
        'singular_name' => 'Language',
        'search_items' => 'Search Languages',
        'all_items' => 'All Languages',
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => 'Edit Language',
        'update_item' => 'Update Language',
        'add_new_item' => 'Add New Language',
        'new_item_name' => 'New Language Name',
        'menu_name' => 'Languages',
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'update_count_callback' =>  '_update_post_term_count',
        'query_var' => true,
        'rewrite' => array('slug' => 'language'),
    );
    
    register_taxonomy('language', 'tutors', $args);
}

/* Displays these columns in the admin CPT tutor table */
function edit_tutors_columns( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'firstname' => 'First Name',
		'lastname' => 'Last Name',
		'programs' => 'Programs',
		'courses' => 'Courses',
        'profile-photo' => 'Photo',
        'comments' => '<span class="vers comment-grey-bubble" title="Comments"><span class="screen-reader-text">Comments</span></span>',
        'date' => 'Date',
	);

	return $columns;
}

/* Adds content to columns in admin CPT tutor table */
function manage_tutors_columns( $column, $post_id ) {
	global $post;

	switch( $column ) {

		/* If displaying the 'firstname' column. */
		case 'firstname' :

			/* Get the post meta. */
			$firstname = get_post_meta( $post_id, 'first_name', true);

			/* If no first name is found, output a default message. */
			if ( empty( $firstname ) ){
                echo 'Unknown';
            } else {
                //Display content as a link that refrences to the edit post for that tutor
                echo sprintf( '<a href="%s">%s</a>',
				    esc_url( add_query_arg( array( 'post' => $post_id, 'title' => $term->slug ), 'post.php' ) ) . '&action=edit', $firstname);
            };

			break;
            
        case 'lastname' :

			/* Get the post meta. */
			$lastname = get_post_meta( $post_id, 'last_name', true );

			/* If no last name is found, output a default message. */
			if ( empty( $lastname ) ){
                echo 'Unknown';
            } else {
                //Display content as a link that refrences to the edit post for that tutor
                echo sprintf( '<a href="%s">%s</a>',
				    esc_url( add_query_arg( array( 'post' => $post_id, 'title' => $term->slug ), 'post.php' ) ) . '&action=edit', $lastname);
            };

			break;

		/* If displaying the 'courses' column. */
		case 'courses' :

			/* Get the courses for the post. */
			$terms = get_the_terms( $post_id, 'course' );

			/* If terms were found. */
			if ( !empty( $terms ) ) {

				$out = array();

				/* Loop through each term, linking to the 'edit posts' page for the specific term. */
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'course' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'course', 'display' ) )
					);
				}

				/* Join the terms, separating them with a comma. */
				echo join( ', ', $out );
			}

			/* If no terms were found, output a default message. */
			else {
				_e( 'No Courses' );
			}

			break;
        
        /* If displaying the 'programs' column. */
		case 'programs' :

			/* Get the programs for the post. */
			$terms = get_the_terms( $post_id, 'program' );

			/* If terms were found. */
			if ( !empty( $terms ) ) {

				$out = array();

				/* Loop through each term, linking to the 'edit posts' page for the specific term. */
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'program' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'program', 'display' ) )
					);
				}

				/* Join the terms, separating them with a comma. */
				echo join( ', ', $out );
			}

			/* If no terms were found, output a default message. */
			else {
				_e( 'No Programs' );
			}

			break;
        
        case 'profile-photo' :
            
            /* Displays profile photo for tutor */
            $profile_photo = get_field('profile_photo', $post_id);
            if ($profile_photo) {
                echo '<img width="100" src="' . $profile_photo . '" />';
            }
            
            break;

		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}

/* Creates the tutor custom role */
function add_roles_on_plugin_activation() {
       add_role( 'tutor', 'Tutor', array( 
		'read' => true, 
		'edit_posts' => true,
		'delete_posts' => false
	) );
   }


/* Modifies the tutors post title (slug) when created or updated */
function modify_slug(){
    
    //disable action to prevent infinite loop
    remove_action( 'save_post_tutors', 'modify_slug', 10, 3 );
    remove_action( 'wp_insert_post_tutors', 'modify_slug', 10, 3 );
    
	$current_id = get_the_id();
	$custom_fields = get_post_custom($current_id);
	$fname = $custom_fields['first_name'][0];
	$lname = $custom_fields['last_name'][0];
	$new_name = $fname . '-' . $lname;
    $new_title = $fname . ' ' . $lname;

    // Update the current post
    $my_post = array(
      'ID'     => $current_id,
      'post_name'   => $new_name,
      'post_title' => $new_title,
    );

    // Update the post into the database
    wp_update_post( $my_post );
    
    //add action back to registry
    add_action( 'save_post_tutors', 'modify_slug', 10, 3 );
    add_action( 'wp_insert_post_tutors', 'modify_slug', 10, 3 );
};
?>