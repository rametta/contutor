(function( $ ) {
	'use strict';
	$(document).on('click', '.tutor-button', function() {
		var button = $(this);
		var post_id = button.attr('data-post-id');
		var security = button.attr('data-nonce');
		var iscomment = button.attr('data-iscomment');
		var allbuttons;
		if ( iscomment === '1' ) { /* Comments can have same id */
			allbuttons = $('.tutor-comment-button-'+post_id);
		} else {
			allbuttons = $('.tutor-button-'+post_id);
		}
		var loader = allbuttons.next('#tutor-loader');
		if (post_id !== '') {
			$.ajax({
				type: 'POST',
				url: tutorLikes.ajaxurl,
				data : {
					action : 'process_tutor_like',
					post_id : post_id,
					nonce : security,
					is_comment : iscomment
				},	
				success: function(response){
					var icon = response.icon;
					var count = response.count;
					allbuttons.html(icon+count);
					if(response.status === 'unliked') {
						var like_text = tutorLikes.like;
						allbuttons.prop('title', like_text);
						allbuttons.removeClass('liked');
					} else {
						var unlike_text = tutorLikes.unlike;
						allbuttons.prop('title', unlike_text);
						allbuttons.addClass('liked');
					}
					loader.empty();					
				}
			});
			
		}
		return false;
	});
})( jQuery );
