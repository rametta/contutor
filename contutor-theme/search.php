<?php

/* This page shows results for when searches are done within the search bar */

get_header(); 

$s = get_search_query();
$args = array(
    'numberposts' => -1,
    's' => $s,
    'post_type' => 'tutors',
    'post_status' => 'publish',
);

$the_query = get_posts( $args );

?>

<div class="container large-padding-top">
    <div class="row">
        <div class="col-lg-8 col-md-8 col-lg-offset-2 col-md-offset-2">
            <header class="page-header text-center">
                <h1 class="page-title"><?php printf( __( 'Search Results for : %s', 'shape' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
            </header>
            
            <?php 
            
            if ( $the_query ){
                
                foreach($the_query as $tutor) {

                    $current_id = $tutor->ID;
                    $custom_fields = get_post_custom($current_id);
                    $fname = $custom_fields['first_name'][0];
                    $lname = $custom_fields['last_name'][0];
                    $photo = get_field('profile_photo', $current_id);
                    $email = $custom_fields['email'][0];
                    $languages = get_the_term_list($current_id, 'language', '', ' / ');
                    $courses = get_the_term_list($current_id, 'course', '', ' / ');
                    $stars = tutor_like_count($current_id);
                    $comments = wp_count_comments($current_id);
                    $approved_comments = $comments->approved;
                    $slug = $tutor->post_name;
                    $focus = get_term_by('id',$custom_fields['program'][0],'program');

                    //Inserts the panel
                    insert_tutor($slug, $photo, $fname, $lname, $focus, $email, $courses, $languages, $stars, $approved_comments);
                    
                }
    
            } else {
    
                echo '<div class="text-center" style="margin-bottom:340px;">We\'re sorry. Nothing matched you\'re search. Please try again.</div>';
    
            }
            
            ?>
            
        </div>
    </div>
</div>

<?php get_footer(); ?>