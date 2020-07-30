( function( $, _ ) {
	var submit               = $( document.getElementById( 'submit' ) ),
		notificationArea     = $( document.getElementById( 'trusttxt-notification-area' ) ),
		notificationTemplate = wp.template( 'trusttext-notice' ),
		editor               = wp.CodeMirror.fromTextArea( document.getElementById( 'trusttxt_content' ), {
			lineNumbers: true,
			mode: 'shell'
		} );

	submit.on( 'click', function( e ){
		e.preventDefault();

		var	textarea    = $( document.getElementById( 'trusttxt_content' ) ),
			notices     = $( '.trusttxt-notice' ),
			submit_wrap = $( 'p.submit' ),
			saveSuccess = false,
			spinner     = submit_wrap.find( '.spinner' );

		submit.attr( 'disabled', 'disabled' );
		spinner.addClass( 'is-active' );

		// clear any existing messages
		notificationArea.hide();
		notices.remove();

		// Copy the code mirror contents into form for submission.
		textarea.val( editor.getValue() );

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: ajaxurl,
			data: $( '.trusttxt-settings-form' ).serialize(),
			success: function( r ) {
				var templateData = {};

				spinner.removeClass( 'is-active' );

				if ( 'undefined' !== typeof r.sanitized ) {
					textarea.val( r.sanitized );
				}

				if ( 'undefined' !== typeof r.saved && r.saved ) {
					saveSuccess = true;
				} else {
					templateData.errors = {
						'error_message': trusttxt.unknown_error
					}
				}

				if ( 'undefined' !== typeof r.errors && r.errors.length > 0 ) {
					templateData.errors = {
						'error_message': trusttxt.error_message,
						'errors':        r.errors
					}
				}

				// Refresh after a successful save, otherwise show the error message.
				if ( saveSuccess ) {
					document.location = document.location + '&trust_txt_saved=1';
				} else {
					notificationArea.html( notificationTemplate( templateData ) ).show();
				}

			}
		})
	});

	$( '.wrap' ).on( 'click', '#trusttxt-ays-checkbox', function( e ) {
		if ( true === $( this ).prop( 'checked' ) ) {
			submit.removeAttr( 'disabled' );
		} else {
			submit.attr( 'disabled', 'disabled' );
		}
	} );

	editor.on( 'change', function() {
		$( '.trusttxt-ays' ).remove();
		submit.removeAttr( 'disabled' );
	} );

} )( jQuery, _ );
