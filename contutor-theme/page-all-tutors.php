<?php
/**
 * @package WordPress
 * @subpackage Contutor
 * @since Contutor 1.0
 */

/* Page of all tutors */

get_header();
?>

<!-- Page Title -->
<section class="con-page-title">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <h1>Tutors</h1>
            </div>
        </div>
    </div>
</section>


<!-- Tutor panels -->
<section class="con-tutor-panel">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="row">
                    
                    <?php
                    $args = array( 
                        'numberposts'     => -1,
                        'orderby'          => 'date',
                        'order'            => 'DESC',
                        'post_type'        => 'tutors',
                        'post_status'      => 'publish',
                    );
                    $tutors = get_posts($args);
                    foreach($tutors as $tutor){

                        //Get all relavant meta data from the posts
                        $current_id = $tutor->ID;
                        $custom_fields = get_post_custom($current_id);
                        $fname = $custom_fields['first_name'][0];
                        $lname = $custom_fields['last_name'][0];
                        $photo = get_field('profile_photo', $current_id);
                        $email = $custom_fields['email'][0];
                        $languages = get_the_term_list($current_id, 'language', '', ' / ');
                        $focus = get_term_by('id',$custom_fields['program'][0],'program');
                        $courses = get_the_term_list($current_id, 'course', '', ' / ');
                        $stars = tutor_like_count($current_id);
                        $comments = wp_count_comments($current_id);
                        $approved_comments = $comments->approved;
                        $slug = $tutor->post_name;

                        //Inserts the panel
                        insert_tutor($slug, $photo, $fname, $lname, $focus, $email, $courses, $languages, $stars, $approved_comments);
                        
                        //var_dump($tutor);
                        //echo '<br>';
                    }
                    ?>
                    
               
                    
                </div>
            </div>
        </div>
        
        <!-- Google Ad -->
        <div class="row">
            <div class="col-lg-8 col-md-8 col-lg-offset-2 col-md-offset-2">
                <br />
                <?php get_contutor_ad(); ?>
                <br />
            </div>
        </div>
        
    </div>
</section>

<?php
get_footer();
?>
