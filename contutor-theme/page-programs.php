<?php

get_header();

$programs = get_terms('program');

if ($programs) {
?>
    <br />
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-lg-offset-2 col-md-offset-2">
                <h3 style="letter-spacing:0;">Here are all the programs we offer!</h3>
                <div class="list-group">
                    <?php
                    foreach($programs as $program) {
                        if($program->count > 0 && $program->name != "Uncategorized") {
                            echo '<a class="list-group-item" href="' . get_category_link( $program->term_id ) . '" title="' . sprintf( __( "View all tutors in %s" ), $program->name ) . '" ' . '>' . $program->name.'<span class="badge">' . $program->count . '</span></a>';
                        }
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