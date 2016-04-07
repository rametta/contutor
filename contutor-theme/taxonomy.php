<?php
get_header();

/* The current page data */
$queried_object = get_queried_object();

/* Query to retrive all published tutor posts where course = chosen term */
$args = array(
    'numberposts'      => -1,
    'orderby'          => 'date',
    'order'            => 'DESC',
    'post_type'        => 'tutors',
    'post_status'      => 'publish',
    'suppress_filters' => true,
    'tax_query' => array(
        array(
            'taxonomy' => $queried_object->taxonomy,
            'field'    => 'slug',
            'terms'    => $term,
        ),
    ),
);

/* All relavant posts */
$posts_array = get_posts( $args );

?>

<div class="container large-padding-top">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <!-- Taxonomy name and description -->
	    <h1><?php echo $queried_object->name . ' - ' . strtoupper($queried_object->slug)?></h1>
            <p><?php echo $queried_object->description ?></p>
            <!-- Tutors registered in this taxonomy -->
            <div class="row">
                <?php
                foreach($posts_array as $tutor){

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
                }
                ?>
            </div>
        </div>
    </div>
</div>

<br />
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <?php get_contutor_ad() ?>
        </div>
    </div>
</div>
<br />

<?php get_footer(); ?>