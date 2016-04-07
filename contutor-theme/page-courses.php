<?php

get_header();

$courses = get_terms('course');

if($courses){
?>

    <br />
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-lg-offset-2 col-md-offset-2">
                <h3 style="letter-spacing:0;">Here are all the courses we currently have tutors registered in!</h3>
                <div class="list-group">
                    <?php
                    foreach($courses as $course){
                        echo '<a class="list-group-item" href="' . get_term_link($course->slug, 'course') . '" title="' . sprintf( __( "View all Tutors in %s" ), $course->name ) . '" >' . $course->name . '<span class="badge">' . $course->count . '</span></a>';
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

<?php 
}

get_footer();
?>