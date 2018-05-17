jQuery(document).ready(function($) {
	$('#noo_generate_xml').on('click', function(event) {
		event.preventDefault();
		// console.log('sd');
		$(this).find('span').removeClass('glyphicon-cloud-download').addClass('noo_reload');
		var data = {
			action : 'load_xml_indedd',
			nonce : ImportIndeed.xml_nonce
		};
		$.post(ImportIndeed.ajax_url, data, function( info, status ) {
			if ( status == 'success' && info != "-1" ) {

				$('#noo_generate_xml span').removeClass('noo_reload').addClass('glyphicon-ok');
				// console.log(info);
				var request = new XMLHttpRequest();
				request.open("GET", info, true);
				request.onreadystatechange = function(){
				    if (request.readyState == 4) {
				        if (request.status == 200 || request.status == 0) {
				            myXML = request.responseXML;
				        }
				    }
				};
				request.send();
				var win = window.open( info, '_blank' ); 
				win.focus();
			} else {
				window.location.reload();
			}
		});
	});

	// === <<< event load more
		$('.posts-loop-content').on('click', '.loadmore_job', function(event) {
			event.preventDefault();
			// === <<< set icon loading
				var $this = $(this);
				$this.hide().siblings('.noo-loader').show();

			// === <<< var
				var public_id = $(this).data( 'public-id' );
				var indeed_query = $(this).data( 'query' );
				var indeed_localtion = $(this).data( 'localtion' );
				var indeed_job_type = $(this).data( 'job-type' );
				var indeed_country = $(this).data( 'country' );
				var limit = $(this).data( 'limit' );
				// var min = $(this).data( 'min' );
				var max = $(this).data( 'max' );
			
			// === <<< set job per page
				// $(this).data( 'min', max );
				$(this).data( 'max', max + limit );
			
			// === <<< set url
				var url = "http://api.indeed.com/ads/apisearch?publisher=" + public_id + "&q=" + indeed_query + "&l=" + indeed_localtion + "&sort=&radius=&st=&jt=" + indeed_job_type + "&start=" + max + "&limit=" + limit + "&fromage=&filter=&latlong=1&co=" + indeed_country + "&v=2";
		
			// === <<< process
				var data = {
					action : 'load_job_item',
					url : url,
					public_id : public_id,
					indeed_query : indeed_query,
					indeed_localtion : indeed_localtion,
					indeed_job_type : indeed_job_type,
					indeed_country : indeed_country,
					start : max,
					limit : limit,
					nonce : ImportIndeed.loadmore_nonce
				};
				$.post(ImportIndeed.ajax_url, data, function( list, status ) {
					if ( status == 'success' && list != "-1" ) {

						// === <<< remove icon loading
							$this.siblings('.noo-loader').hide();
							$this.show();
						// === <<< Add list
						$this.closest('.posts-loop-content').find('.list_loadmore_job').append( list );

					} else {
						window.location.reload();
					}
				});

		});
});