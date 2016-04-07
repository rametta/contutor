<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package WordPress
 * @subpackage Contutor
 * @since Contutor 1.0
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php echo 'Comments are closed'; ?></p>
	<?php endif; ?>

	<?php
        $fields = array(
            'author' =>
                '<p class="comment-form-author"><label for="author"></label> ' . ( $req ? '<span class="required"> </span>' : '' ) .
                '<input placeholder="Name *" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
            
            'email' =>
                '<p class="comment-form-email"><label for="email"></label> ' .
                ( $req ? '<span class="required"> </span>' : '' ) .
                '<input placeholder="Email *" id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
                '" size="30"' . $aria_req . ' /></p>',
        );
    
		comment_form( array(
			'title_reply_before' => '',
			'title_reply_after'  => '',
            'title_reply' => '',
            'comment_field' => '<p class="comment-form-comment"><textarea placeholder="Message *"class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="comment" name="comment" aria-required="true"></textarea></p>',
            'label_submit' => 'Send',
            'fields' => apply_filters( 'comment_form_default_fields', $fields ),
		) );
	?>
    
    <?php if ( have_comments() ) : ?>
		<?php the_comments_navigation(); ?>
		<ol class="comment-list">
            <?php foreach ($comments as $comment) : ?>
                <li <?php echo $oddcomment; ?>id="comment-<?php comment_ID() ?>">
                    <span class="author-name"><?php comment_author() ?></span>
                    <?php if ($comment->comment_approved == '0') : ?>
                    <em class="pending-approval"> pending approval</em>
                    <?php endif; ?>
                    
                    <span class="comment-box">
                        <?php comment_text() ?>
                    </span>
                    
                </li>
            <?php
                /* Changes every other comment to a different class */
                $oddcomment = ( empty( $oddcomment ) ) ? 'class="comments-alt" ' : '';
            ?>
            <?php endforeach; ?>
		</ol><!-- .comment-list -->
		<?php //the_comments_navigation(); ?>
	<?php endif; // Check for have_comments(). ?>
</div><!-- .comments-area -->

<style>
    #comment{
        border:none;
        height:200px;
        -webkit-box-shadow: 6px 6px 25px 1px rgba(50,50,50,0.1);
        -moz-box-shadow: 6px 6px 25px 1px rgba(50,50,50,0.1);
        box-shadow: 6px 6px 25px 1px rgba(50,50,50,0.1);
        padding: 10px 10px 10px 10px;
        margin-bottom: 20px;
    }
    #comment:focus{
        outline-color: #921a35;
    }
    #author, #email{
        border:none;
        -webkit-box-shadow: 6px 6px 25px 1px rgba(50,50,50,0.1);
        -moz-box-shadow: 6px 6px 25px 1px rgba(50,50,50,0.1);
        box-shadow: 6px 6px 25px 1px rgba(50,50,50,0.1);
        padding-left: 8px;
    }
    #author:focus, #email:focus{
        outline-color: #921a35;
    }
    .submit{
        background-color: #921a35;
        border: 1px solid #efefef;
        border-radius: 25px;
        color:#ffffff;
        text-transform: uppercase;
        text-align:center;
        padding: 5px 25px 5px 25px;
        -webkit-box-shadow: 4px 4px 25px 1px rgba(50,50,50,0.1);
        -moz-box-shadow: 4px 4px 25px 1px rgba(50,50,50,0.1);
        box-shadow: 4px 4px 25px 1px rgba(50,50,50,0.1);
    }
    .submit:hover{
        background-color: #ffffff;
        color:#921a35;
    }
    .comment-list{
        list-style-type: none;
        margin: 20px 0px 0px 0px;
        padding: 0;
        font-size: 16px;
    }
    .comment-list li{
        margin-bottom: 15px;
    }
    .comment-box{
        background-color: #ffffff;
        margin: 2px 10px 2px 10px;
    }
    .comment-box p{
        display:inline;
        padding: 3px 10px 3px 10px;
        text-align: justify;
    }
    .comments-alt{
        
    }
    .author-name{
        color:#921a35;
        text-transform: capitalize;
    }
    .pending-approval{
        color:darkgray;
        text-transform: uppercase;
    }
    .logged-in-as a{
        color: #a23e54;
        text-decoration: none;
        opacity: .5;
    }
    ::-webkit-input-placeholder { /* WebKit, Blink, Edge */
    color:    #d6d6d6;
    }
    :-moz-placeholder { /* Mozilla Firefox 4 to 18 */
       color:    #d6d6d6;
       opacity:  1;
    }
    ::-moz-placeholder { /* Mozilla Firefox 19+ */
       color:    #d6d6d6;
       opacity:  1;
    }
    :-ms-input-placeholder { /* Internet Explorer 10-11 */
       color:    #d6d6d6;
    }
    :placeholder-shown { /* Standard (https://drafts.csswg.org/selectors-4/#placeholder) */
      color:    #d6d6d6;
    }
</style>
