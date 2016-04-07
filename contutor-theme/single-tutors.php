<?php
/*
*Template Name: Tutor Profile Page
*/
get_header();

$current_id = get_the_ID();
$custom_fields = get_post_custom($current_id);

$fname = $custom_fields['first_name'][0];
$lname = $custom_fields['last_name'][0];
$rate = $custom_fields['rate'][0];
$description = $custom_fields['description'][0];
$photo = get_field('profile_photo', $current_id);
$tut_email = $custom_fields['email'][0];
$languages = get_the_terms($current_id, 'language');
$focus = get_term_by('id',$custom_fields['program'][0],'program');
$courses = get_the_terms($current_id, 'course');
$slug = $post->post_name;
$twitter = $custom_fields['twitter'][0];
$facebook = $custom_fields['facebook'][0];
$instagram = $custom_fields['instagram'][0];
$linkedin = $custom_fields['linkedin'][0];
$youtube = $custom_fields['youtube'][0];
$pinterest = $custom_fields['pinterest'][0];

$result = ''; //result from succesfully sending the chat form

/* Script for checking and sending the contact form to the tutor */
if (isset($_POST["chat-submit"])) {

    $name = sanitize_text_field($_POST['chat-name']);
    $email = sanitize_email($_POST['chat-email']);
    $message = esc_textarea($_POST['chat-message']);
    $phone = sanitize_text_field($_POST['chat-phone']);

    $from = 'Contutor Chat Form'; 
    $to = $tut_email; 
    $subject = 'Message from ' . $name . ' on Contutor';

    $body = "From: $name\n E-Mail: $email\n Phone: $phone\n Message:\n $message";
    $headers = 'From: Contutor <donotreply@contutor.ca>';

    if (wp_mail($to, $subject, $body, $headers)) {
        $result='<div class="alert alert-success">Thank You! ' . $fname . ' will be in touch</div>';
    } else {
        $result='<div class="alert alert-danger">Sorry there was an error sending your message. Please try again later</div>';
    }
}
?>

<div class="container-fluid tutor-top"></div>
    
<div class="container-fluid tutor-mid">
    <!-- desktop arrangement -->
    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2 hidden-xs text-left">
            <div class="language-btns">
                <?php 
                    foreach($languages as $language){
                        ?>
                        <a href="http://contutor.ca/language/<?php echo $language->slug ?>/" class="btn btn-language btn-block" role="button"><?php echo $language->name ?></a>
                        <?php
                    }
                ?>
            </div>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 text-center">
            <a href="<?php echo $photo ?>"><img src="<?php echo $photo ?>" class="img-circle profile-pic" alt="<?php echo $fname ?>" width="200" height="200"></a>
            <h1 class="profile-name"><?php echo $fname . " " . $lname ?></h1>
            <p class="profile-major"><?php echo $focus->name ?></p>
            <p class="profile-description"><?php echo $description ?></p>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 hidden-xs text-right">
            <a href="#chatModal" data-toggle="modal" class="btn btn-contact" style="width:100%" role="button"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> Chat</a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2 text-left hidden-xs">
            <p class="profile-likes"><?php echo get_simple_likes_button($current_id); ?></p>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 text-center hidden-xs">
            <?php 
                foreach($courses as $course){
                    ?>
                    <a href="http://contutor.ca/course/<?php echo $course->slug ?>/" class="btn btn-course" role="button"><?php echo $course->slug ?></a>
                    <?php
                }
            ?>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 text-right hidden-xs">
            <p class="profile-rate">$<?php echo $rate ?> /Hour</p>
        </div>
    </div>
    
    <!-- mobile re-arrangement -->
    <div class="row visible-xs text-center">
        <div class="col-xs-12">
            <?php 
                foreach($courses as $course){
                    ?>
                    <a href="http://contutor.ca/course/<?php echo $course->slug ?>/" class="btn btn-course" role="button"><?php echo $course->slug ?></a>
                    <?php
                }
            ?>
        </div>
    </div>
    <div class="row visible-xs profile-mobile">
        <div class="col-xs-4 text-center">
            <p class="profile-likes"><?php echo get_simple_likes_button($current_id); ?></p>
        </div>
        <div class="col-xs-4 text-center">
            <a href="#chatModal" data-toggle="modal" class="btn btn-contact-sm" role="button"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> Chat</a>
        </div>
        <div class="col-xs-4 text-center">
            <p class="profile-rate" style="font-size:20px">$<?php echo $rate ?> /Hour</p>
        </div>
    </div>
    
    <!-- Result of sending form -->
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2">
            <?php echo $result; ?>
        </div>
    </div>
    
</div>
    
<!-- Comment Section -->
<div class="container-fluid tutor-bot">
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 col-lg-offset-2 col-md-offset-2">
            <h1 class="comment-title">Write a comment about <?php echo $fname ?></h1>
            <?php comments_template(); /* calls comments.php */ ?>
        </div>
    </div>
</div>

<!-- Social Media Section -->
<?php if($linkedin != '' or $facebook != '' or $twitter != '' or $instagram != '' or $youtube != '' or $pinterest != ''): /* checks if any social media was inputted */?>
<section class="con-section con-section--relative con-section--fixed-size" id="social-buttons2-84" style="background-color:#ffffff;">
    <div class="con-section__container container">
        <div class="con-header con-header--inline row">
            <div class="col-sm-4">
                <h3 class="con-header__text" style="text-transform:uppercase">Follow <?php echo $fname ?></h3>
            </div>
            <div class="con-social-icons con-social-icons--style-1 col-sm-8 text-right">
                
                <?php if($twitter != ''): ?>
                <a class="con-social-icons__icon socicon-bg-twitter" target="_blank" href="https://twitter.com/<?php echo $twitter ?>">
                    <i class="socicon socicon-twitter"></i>
                </a>
                <?php endif; ?>
                
                <?php if($instagram != ''): ?>
                <a class="con-social-icons__icon socicon-bg-instagram" target="_blank" href="https://www.instagram.com/<?php echo $instagram ?>">
                    <i class="socicon socicon-instagram"></i>
                </a>
                <?php endif; ?>
                
                <?php if($facebook != ''): ?>
                <a class="con-social-icons__icon socicon-bg-facebook" target="_blank" href="https://www.facebook.com/<?php echo $facebook ?>">
                    <i class="socicon socicon-facebook"></i>
                </a> 
                <?php endif; ?>
                
                <?php if($youtube != ''): ?>
                <a class="con-social-icons__icon socicon-bg-youtube" target="_blank" href="https://www.youtube.com/channel/<?php echo $youtube ?>">
                    <i class="socicon socicon-youtube"></i>
                </a> 
                <?php endif; ?>
                
                <?php if($pinterest != ''): ?>
                <a class="con-social-icons__icon socicon-bg-pinterest" target="_blank" href="https://www.pinterest.com/<?php echo $pinterest ?>">
                    <i class="socicon socicon-pinterest"></i>
                </a> 
                <?php endif; ?>
                
                <?php if($linkedin != ''): ?>
                <a class="con-social-icons__icon socicon-bg-linkedin" target="_blank" href="https://www.linkedin.com/in/<?php echo $linkedin ?>">
                    <i class="socicon socicon-linkedin"></i>
                </a>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Pop-up modal for contacting the tutor -->
<div id="chatModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Send a message to <?php echo $fname ?></h3>
      </div>
      <div class="modal-body">
        <form class="form-horizontal col-sm-12" method="post" role="form" action="<?php esc_url( $_SERVER['REQUEST_URI'] )?>">
            <div class="form-group">
              <label>Name</label>
              <input required="true" name="chat-name" class="form-control required" placeholder="Your name" data-placement="top" data-trigger="manual" data-content="Must be at least 3 characters long, and must only contain letters." type="text">
            </div>
            <div class="form-group">
                <label>Message</label>
                <textarea required="true" name="chat-message" class="form-control" placeholder="Your message here.." data-placement="top" data-trigger="manual"></textarea>
            </div>
            <div class="form-group">
                <label>E-Mail</label>
                <input required="true" name="chat-email" class="form-control email" placeholder="email@you.com" data-placement="top" data-trigger="manual" data-content="Must be a valid e-mail address" type="text">
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input name="chat-phone" class="form-control phone" placeholder="999-999-9999" data-placement="top" data-trigger="manual" data-content="Must be a valid phone number (999-999-9999)" type="text">
            </div>
          <div class="form-group">
              <button type="submit" name="chat-submit" class="btn btn-contact pull-right">Contact!</button> 
              <p class="help-block pull-left text-danger hide" id="form-error">&nbsp; The form is not valid. </p>
          </div>
        </form>
      </div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div><!-- /.modal-->

<?php
get_footer();
?>

<style>
    .modal-footer {
        border-top: none;
    }
    .alert:before{
        content: none;
    }
</style>