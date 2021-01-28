$( document ).ready( function()
{
	$.fn.json_admin = function( action, variables, callback )
	{
		var data = 'action=' + encodeURIComponent( action ) + ( variables == '' ? '' : '&' ) + variables;

		$.ajax( {
			type: 'POST',
			url: 'json.php',
			dataType: 'JSON',
			data: 'admin=true&token=' + token + '&' + data,
			success: function( response )
			{
				callback( response );
			},
			error: function( jqXHR, textStatus, errorThrown )
			{
				if ( textStatus == 'parsererror' )
				{
					var response 			= new Object();
					response.success		= 0;
					response.error_code		= '#Error#';
					response.error_message	= 'The server returned an invalid response.\n' +
											  'Action: ' + action + '\n' +
											  'Response: ' + jqXHR.responseText;
					callback( response );
				}
			}
		} );
	}

	$.fn.update_settings = function()
	{
		$.fn.json_admin( 'UpdateSettings', $( '#settings_addedit :input' ).serialize(), function( response )
		{
			if ( !response.success )
			{
				return $.fn.error( response.error_message );
			}

			alert( 'Updated' );
		} );
	}

	$.fn.sort = function( action, sort, callback )
	{
		var field 		= $( '#' + sort );
		var direction 	= field.attr( 'direction' );

		field.attr( 'direction', ( direction == 'asc' ) ? 'desc' : 'asc' );

		$.fn.json_admin( action, 'sort=' + encodeURIComponent( sort ) + '&direction=' + encodeURIComponent( direction ), callback );
	}

	$.fn.sort_user_callback = function( response )
	{
		if ( !response.success )
		{
			return $.fn.error( response.error_message );
		}

		var users 	= response.data;
		var div 	= $( '#users_loading' ).text( '' );

		$.each( users, function( key, user )
		{
			var fieldset = $( '<fieldset/>', {
								html:
									$( '<legend/>', {
										text: user.name
									} )
							} );
			$( '<div/>' ).append(
					$( '<a/>', {
						href: 'javascript:;',
						text: user.email,
					} ).bind( 'click', function() { $.fn.editUser( user ); } ) ).append( ' - ' ).append( 
					$( '<a/>', { 'href': 'javascript:;', 'text': 'Delete' } ).bind( 'click', function() { $.fn.deleteUser( user ); } ) ).append( ( user.active == 0 ? ' - Inactive' : '' ) )
					.appendTo( fieldset );

			$( '<div/>', { 'text': 'Last Active: ' + user.last_on } ).appendTo( fieldset );
			$( '<div/>', { 'text': 'Record: ' + user.wins + ' - ' + user.losses	} ).appendTo( fieldset );
			$( '<div/>', { 'text': 'Remaining: ' + user.remaining + ' remaining picks' } ).appendTo( fieldset );
			$( '<div/>', { 'html': 'Current Place: ' + user.current_place } ).appendTo( fieldset );
			$( '<div/>', { 'text': 'Paid: '	} ).append( $( '<a/>', {
															'id':	'paid' + user.id,
															'href': 'javascript:;',
															'text': ( user.paid ? 'Yes' : 'No' ) } ).bind( 'click', function() { $.fn.update_users( user ); } ) ).appendTo( fieldset );

			$( '<div/>', { 'text': '# of Failed Logins: ' + user.failed_logins } ).appendTo( fieldset );
			$( '<div/>', { 'text': '# of Active Sessions: ' + user.active_sessions + ' - ' } ).
			append(
				$( '<a/>', { 'href': 'javascript:;', 'text': 'Login' } ).bind( 'click', function() { $.fn.login( user ); } )
			).append( ' - ' ).append(
				$( '<a/>', { 'href': 'javascript:;', 'text': 'Logout' } ).bind( 'click', function() { $.fn.logout( user ); } ) ).appendTo( fieldset );

			fieldset.appendTo( div );
		} );
	}

	$.fn.deleteUser = function( user )
	{
		var password;

		if ( !confirm( "Are you absolutely sure you want to delete " + user.name + "?\n\nThis action cannot be undone." ) )
		{
			return;
		}

		password = prompt( "Please enter your password" );

		$.fn.json_admin( 'DeleteUser', 'password=' + encodeURIComponent( password ) + '&user_id=' + encodeURIComponent( user.id ), function( response )
		{
			if ( !response.success )
			{
				return $.fn.error( response.error_message );
			}

			location.reload();
		} );
	}

	$.fn.editUser = function( user )
	{
		$.fn.modalShow( 'user_edit', function() { $.fn.update_user( user ); }, function() { $.fn.hide_edit_user(); } );
		$( '#user_edit_cancel' ).bind( 'click', function() { $.fn.hide_edit_user(); } );
		$( '#user_edit_update' ).bind( 'click', function() { $.fn.update_user( user ); } );
		$( '#user_edit_first_name' ).val( user.fname );
		$( '#user_edit_last_name' ).val( user.lname );
		$( '#user_edit_message' ).val( user.message );
	}

	$.fn.update_user = function( user )
	{
		if ( $( '#user_edit_first_name' ).val().trim().length == 0 )
		{
			$( '#user_edit_first_name' ).focus();
			return $.fn.error( 'First name cannot be blank' );
		}

		if ( $( '#user_edit_last_name' ).val().trim().length == 0 )
		{
			$( '#user_edit_last_name' ).focus();
			return $.fn.error( 'Last name cannot be blank' );
		}

		$.fn.json_admin( 'UpdateUser', 'user_id=' + encodeURIComponent( user.id ) + '&first_name=' + encodeURIComponent( $( '#user_edit_first_name' ).val() ) + '&last_name=' + encodeURIComponent( $( '#user_edit_last_name' ).val() ) + '&message=' + encodeURIComponent( $( '#user_edit_message' ).val() ), function( response )
		{
			if ( !response.success )
			{
				return $.fn.error( response.error_message );
			}

			$.fn.hide_edit_user();
			$.fn.sort( 'LoadUsers', 'name', $.fn.sort_user_callback );
		} );
	}

	$.fn.hide_edit_user = function()
	{
		$.fn.modalHide( 'user_edit' );
	}

	$.fn.activate_deactivate_user_hide = function()
	{
		$.fn.modalHide( 'activate_deactivate_user' );
	}

	$.fn.login = function( user )
	{
		if ( !confirm( 'Are you sure you want to log in as ' + user.name + '?' ) )
		{
			return false;
		}

		$.fn.json_admin( 'LoginUser', 'user_id=' + encodeURIComponent( user.id ), function( response )
		{
			if ( !response.success )
			{
				return $.fn.error( response.error_message );
			}

			window.location = '/';
		} );
	}

	$.fn.logout = function( user )
	{
		if ( !confirm( 'Are you sure you want to log out ' + user.name + '?' ) )
		{
			return false;
		}

		$.fn.json_admin( 'LogoutUser', 'user_id=' + encodeURIComponent( user.id ), function( response )
		{
			if ( !response.success )
			{
				return $.fn.error( response.error_message );
			}
		} );
	}

	$.fn.update_users = function( user )
	{
		$.fn.json_admin( 'UpdatePaidUser', 'user_id=' + encodeURIComponent( user.id ), function( response )
		{
			if ( !response.success )
			{
				return $.fn.error( response.error_message );
			}

			$( '#paid' + user.id ).text( ( user.paid ? 'No' : 'Yes' ) );
		} );
	}

	var games = new Array();

	$.fn.load_games = function()
	{
		$.fn.json_admin( 'LoadGames', '', function( response )
		{
			if ( !response.success )
			{
				return $.fn.error( response.error_message );
			}

			var weeks 	= response.data;
			var div		= $( '#weeks_loading' ).text( '' );
			var key		= 0;

			$.each( weeks, function( i, week )
			{
				$( '<p/>', {
					id: 'week' + week.id,
					html: $( '<a/>', {
								href: 'javascript:;',
								text: 'Week ' + week.id
							} ).bind( 'click', function() { $.fn.show_games( week.id ); } )
				} ).appendTo( div );

				var div_games = $( '<div/>', { id: 'games_week' + week.id, 'class': 'edit_games' } ).hide();

				$.each( week.games, function( i, game )
				{
					var game_date = new Date( game.date );

					games[ key ] 		= game;
					games[ key ].key	= key;
					key++;

					$( '<p/>', {
						style: ( game.winner ? 'text-decoration: line-through;' : '' ),
						html: $( '<a/>', {
							href: 'javascript:;',
							html: game.awayTeam + ' <b>vs.</b> ' + game.homeTeam
						} ).bind( 'click', function() { $.fn.edit_games( game ); } )
					} ).append( ' - ' + game_date.toDateString() + ' ' + game_date.toLocaleTimeString() ).appendTo( div_games );
				} );

				div_games.insertAfter( $( '#week' + week.id ).show() );
			} );
		} );
	}

	$.fn.toggle_games = function()
	{
		$( '#scores, #games' ).slideToggle();
		$( '#scored' ).val( ( $( '#scored' ).val() == 'true' ) ? 'false' : 'true' );
	}

	$.fn.show_games = function( week_id ) { $( '#games_week' + week_id ).toggle(); }

	$.fn.edit_games = function( game )
	{
		$( '#games_addedit_cancel' ).unbind( 'click' );
		$( '#games_addedit_update' ).unbind( 'click' );
		$( '#games_addedit_switch' ).unbind( 'click' );

		if ( game.key - 1 > 0 && games[ game.key - 1 ].week != game.week )
		{
			$.fn.show_games( games[ game.key - 1 ].week );
			$.fn.show_games( game.week );
		}

		var date 	= new Date( game.date );
		var now 	= new Date();

		if ( now > date )
		{
			$( '#scores' ).show();
			$( '#games' ).hide();
			$( '#scored' ).val( 'true' );
		} else {
			$( '#games' ).show();
			$( '#scores' ).hide();
			$( '#scored' ).val( 'false' );
		}

		$( '#games_addedit_away' ).val( game.away );
		$( '#games_addedit_home' ).val( game.home );
		$( '#games_addedit_month' ).val( date.getMonth() + 1 );
		$( '#games_addedit_day' ).val( date.getDate() );
		$( '#games_addedit_year' ).val( date.getFullYear() );
		$( '#games_addedit_hour' ).val( date.getHours() );
		$( '#games_addedit_minute' ).val( date.getMinutes() );
		$( '#scores table tr:eq( 0 ) td:first b' ).html( game.awayTeam );
		$( '#scores table tr:eq( 1 ) td:first b' ).text( game.homeTeam );
		$( '#games_addedit_week' ).val( game.week );
		$( '#games_addedit_switch' ).bind( 'click', function() { $.fn.toggle_games(); } );
		$( '#games_addedit_cancel' ).bind( 'click', function() { $.fn.hide_games(); } );
		$( '#games_addedit_update' ).bind( 'click', function() { $.fn.update_games( game ); } ).val( 'Update Game' );
		$.fn.modalShow( 'games_addedit', function() { $.fn.update_games( game ); }, function() { $.fn.hide_games(); } );

		$( '#games_addedit_away_score' ).val( game.awayScore ).select();
		$( '#games_addedit_home_score' ).val( game.homeScore );

		$( document ).bind( 'keydown', function( e )
		{
			switch( e.keyCode )
			{
				case 37:
					if ( ( game.key - 1 ) < 0 )
					{
						break;
					}

					$( document ).unbind( 'keydown' );
					return $.fn.edit_games( games[ game.key - 1 ] );

				case 39:
					if ( ( game.key + 1 ) >= games.length )
					{
						break;
					}

					$( document ).unbind( 'keydown' );
					return $.fn.edit_games( games[ game.key + 1 ] );

				default:
					break;
			}
		} );
	}

	$.fn.hide_games = function()
	{
		$( document ).unbind( 'keydown' );
		$( '#games_addedit_cancel' ).unbind( 'click' );
		$( '#games_addedit_update' ).unbind( 'click' );
		$.fn.modalHide( 'games_addedit' );
	}

	$.fn.update_games = function( game )
	{
		var scored 	= $( '#scored' ).val();
		var data 	= ( scored == 'true' ) ? $( '#scores :input' ).serialize() : $( '#games :input' ).serialize();

		$.fn.json_admin( 	'UpdateGame',
					'game_id=' + encodeURIComponent( game.id ) +
					'&scored=' + encodeURIComponent( scored ) +
					'&' + data,
					function( response )
					{
						if ( !response.success )
						{
							return $.fn.error( response.error_message );
						}

						games[ game.key ] 		= response.data;
						games[ game.key ].key	= game.key;

						if ( ( game.key + 1 ) < games.length )
						{
							console.log( games[ game.key ] );
							$.fn.edit_games( games[ game.key + 1 ] );

							return false;
						}
					} );
	}

	$.fn.load_weeks = function()
	{
		$.fn.json_admin( 'LoadWeeks', '', function( response )
		{
			if ( !response.success )
			{
				return $.fn.error( response.error_message );
			}

			var weeks 	= response.data;
			var div		= $( '#weeks_loading' ).text( '' );

			$.each( weeks, function( key, week )
			{
				var fieldset = $( '<fieldset/>', { html: $( '<legend/>', { text: 'Week ' + week.id } ) } );
				$( '<div/>', {
					'html':
						$( '<a/>', {
							'href': 'javascript:;',
							'text': ( ( week.locked ) ? 'Unlock Now' : 'Lock Now' )
						} ).bind( 'click', function() { $.fn.toggleWeek( week.id ); } )
				} ).appendTo( fieldset );
				$( '<div/>', { 'text': 'Status: ' + ( week.locked ? 'Locked' : 'Unlockced' ) } ).appendTo( fieldset );
				$( '<div/>', { 'text': 'Date: ' + week.formatted_date } ).appendTo( fieldset );
				fieldset.appendTo( div );
			} );
		} );
	}

	$.fn.toggleWeek = function( week_id )
	{
		$.fn.json_admin( 'LockWeek', 'week_id=' + encodeURIComponent( week_id ), function( response )
		{
			if ( !response.success )
			{
				return $.fn.error( response.error_message );
			}

			$.fn.load_weeks();
		} );
	}

	$.fn.load_news = function()
	{
		$.fn.json_admin( 'LoadNews', '', function( response )
		{
			if ( !response.success )
			{
				return $.fn.error( response.error_message );
			}

			var news	= response.data;
			var div		= $( '#news_loading' ).text( '' );

			$.each( news, function( key, news )
			{
				 var test = $( '<p/>', {
					html:
						$( '<a/>', {
							href: 'javascript:;',
							text: news.title
						} ).bind( 'click', function() { $.fn.edit_news( news ); } )
				} ).appendTo( div );
			} );
		} );
	}

	$.fn.add_news = function()
	{
		$( '#news_addedit_message' ).val( '' );
		$( '#news_addedit_cancel' ).bind( 'click', function() { $.fn.hide_news(); } );
		$( '#news_addedit_delete' ).bind( 'click', function() { return false; } ).hide();
		$( '#news_addedit_update' ).bind( 'click', function() { $.fn.insert_news(); } ).val( 'Add News' );
		$( '#news_addedit_active' ).attr( 'checked', true );
		$.fn.modalShow( 'news_addedit', function() { $.fn.insert_news(); }, function() { $.fn.hide_news(); } );
		$( '#news_addedit_title' ).val( '' ).focus();
	}

	$.fn.insert_news = function()
	{
		$.fn.json_admin( 'InsertNews', $( '#news_addedit :input' ).serialize(), function( response )
		{
			if ( !response.success )
			{
				return $.fn.error( response.error_message );
			}

			$.fn.hide_news();
		} );
	}

	$.fn.edit_news = function( news )
	{
		$( '#news_addedit_title' ).val( news.title );
		$( '#news_addedit_message' ).val( news.news );
		$( '#news_addedit_cancel' ).bind( 'click', function() { $.fn.hide_news(); } );
		$( '#news_addedit_delete' ).bind( 'click', function() { $.fn.delete_news( news.id ); } ).show();
		$( '#news_addedit_update' ).bind( 'click', function() { $.fn.update_news( news.id ); } ).val( 'Update News' );
		$( '#' + ( ( news.active ) ? 'news_addedit_active' : 'news_addedit_inactive' ) ).attr( 'checked', true );
		$.fn.modalShow( 'news_addedit', function() { $.fn.update_news( news.id ); }, function() { $.fn.hide_news(); } );
	}

	$.fn.update_news = function( news_id )
	{
		$.fn.json_admin( 'UpdateNews', 'news_id=' + encodeURIComponent( news_id ) + '&' + $( '#news_addedit :input' ).serialize(), function( response )
		{
			if ( !response.success )
			{
				return $.fn.error( response.error_message );
			}

			$.fn.hide_news();
		} );
	}

	$.fn.delete_news = function( news_id )
	{
		if ( !confirm( 'Are you sure you want to delete this news article? This action cannot be undone.' ) )
		{
			return false;
		}

		$.fn.json_admin( 'DeleteNews', 'news_id=' + encodeURIComponent( news_id ), function( response )
		{
			if ( !response.success )
			{
				return $.fn.error( response.error_message );
			}

			$.fn.hide_news();
		} );
	}

	$.fn.hide_news = function()
	{
		$( '#news_addedit input[type=\'button\']' ).unbind( 'click' );
		$.fn.modalHide( 'news_addedit' );
		$.fn.load_news();
	}

	$.fn.load_polls = function()
	{
		$.fn.json_admin( 'LoadPolls', '', function( response )
		{
			if ( !response.success )
			{
				return $.fn.error( response.error_message );
			}

			var polls 	= response.data;
			var div		= $( '#polls_loading' ).text( '' );

			$.each( polls, function( key, poll )
			{
				var fieldset 	= $( '<fieldset/>', { html: $( '<legend/>', { text: 'Poll #' + poll.id } ) } );
				$( '<div/>', {
					'html':
						$( '<a/>', {
							'href': 'javascript:;',
							'text': poll.question
						} ).bind( 'click', function() { $.fn.edit_poll( poll ); } )
				} ).appendTo( fieldset );
				$( '<div/>', { 'text': 'Status: ' + ( poll.active ? 'Active' : 'Inactive' ) } ).appendTo( fieldset );
				$( '<div/>', { 'text': 'Added: ' + poll.date } ).appendTo( fieldset );
				$( '<div/>', { 'text': 'Total Votes: ' + poll.total_votes } ).appendTo( fieldset );

				fieldset.appendTo( div );
			} );
		} );
	}

	$.fn.add_poll = function()
	{
		$( '#polls_addedit_delete' ).hide();
		$( '#polls_addedit_dialog' ).text( 'Add Poll' );
		$( '#polls_addedit_question' ).val( '' );
		$( '#polls_addedit_active' ).attr( 'checked', true );
		$( '#polls_addedit_cancel' ).bind( 'click', function() { $.fn.hide_poll(); } );
		$( '#polls_addedit_delete' ).bind( 'click', function() { return false; } );
		$( '#polls_addedit_update' ).bind( 'click', function() { $.fn.insert_poll(); } ).val( 'Add Poll' );
		$.fn.add_poll_answer( null );
		$.fn.modalShow( 'polls_addedit', $.fn.insert_poll, $.fn.hide_poll );
		$( '#polls_addedit_question' ).focus();
	}

	$.fn.insert_poll = function()
	{
		$.fn.json_admin( 'InsertPoll', $( '#polls_addedit :input' ).serialize(), function( response )
		{
			if ( !response.success )
			{
				return $.fn.error( response.error_message );
			}

			$.fn.hide_poll();
		} );
	}

	$.fn.hide_poll = function()
	{
		$.fn.modalHide( 'polls_addedit' );
		$( '.poll-answers' ).remove();
		$( '#polls_addedit input[type=\'button\']' ).unbind( 'click' );
		$.fn.load_polls();
	}

	$.fn.delete_poll = function( poll_id )
	{
		if ( !confirm( 'Are you sure you want to delete this poll and all of its corresponding data? This action cannot be undone.' ) )
		{
			return false;
		}

		$.fn.json_admin( 'DeletePoll', 'poll_id=' + encodeURIComponent( poll_id ), function( response )
		{
			if ( !response.success )
			{
				return $.fn.error( response.error_message );
			}

			$.fn.hide_poll();
		} );
	}

	$.fn.add_poll_answer = function( answer )
	{
		var tr				= $( '<tr/>', { 'class': 'poll-answers' } );
		var array_id		= 0;
		var array_value		= ( answer == null ) ? '' : answer.answer;

		if ( answer )
		{
			array_id = answer.id;
		}
		else
		{
			$.each( $( '.poll-answers :input[name*=\'answers\']' ), function( key, value )
			{
				array_id = parseInt( ( $( value ).attr( 'answer_id' ) > array_id ) ? $( value ).attr( 'answer_id' ) : array_id );
			} );

			array_id += 1;
		}

		$( '<td/>', { html: '<b>Answer: ' } ).appendTo( tr );
		$( '<td/>', {
			html:
				$( '<input/>', {
					type: 'text',
					id: 'answer' + array_id,
					name: 'answers[' + ( array_id ) + ']',
					value: array_value,
					answer_id: array_id
				} )
		} ).append( $( '<a/>', { href: 'javascript:;', text: 'Remove' } ).bind( 'click', function() { $( tr ).remove(); } ) ).appendTo( tr );
		$( '#polls_addedit table tr:first' ).after( tr );
		$( '#answer' + array_id ).focus();
	}

	$.fn.edit_poll = function( poll )
	{
		$( '#' + ( ( poll.active ) ? 'polls_addedit_active' : 'polls_addedit_inactive' ) ).attr( 'checked', true );
		$( '#polls_addedit_update' ).bind( 'click', function() { $.fn.update_poll( poll.id ); } ).val( 'Update Poll' );
		$( '#polls_addedit_cancel' ).bind( 'click', function() { $.fn.hide_poll() } );
		$( '#polls_addedit_delete' ).bind( 'click', function() { $.fn.delete_poll( poll.id ); } ).show();
		$( '#polls_addedit_dialog' ).text( 'Edit Poll' );
		$( '#polls_addedit_question' ).val( poll.question );
		$.each( poll.answers, function( key, answer ) {	$.fn.add_poll_answer( answer );	} );
		$.fn.modalShow( 'polls_addedit', function() { $.fn.update_poll( poll.id ) }, function() { $.fn.hide_poll(); } );
	}

	$.fn.update_poll = function( poll_id )
	{
		var data = 'poll_id=' + encodeURIComponent( poll_id ) + '&' + $( '#polls_addedit :input' ).serialize();

		$.fn.json_admin( 'UpdatePoll', data, function( response )
		{
			if ( !response.success )
			{
				return $.fn.error( response.error_message );
			}

			$.fn.hide_poll();
			$.fn.load_poll();
		} );
	}

	$.fn.modalShow = function( element_id, onenter, onesc )
	{
		if ( $( '.modal' ).length == 1 )
		{
			return true;
		}

		var element = $( '#' + element_id );
		var content	= $( '.content' );
		var position = content.position();

		element.css( 'top', position.top + 10 );
		element.css( 'left', position.left );
		element.css( 'min-width' , content.width() + 'px' );
		element.show();

		//$( 'html, body' ).animate( { scrollTop: 0 }, 'slow' );

		$( '<div/>', {
			'class': 'modal',
			'style': 'display: block; width: ' + $( document ).width() + 'px; height: ' + $( document ).height() + 'px;'
		} ).prependTo( '.content' );

		$( window ).bind( 'resize', function() { $.fn.modalResize() } );
		$( 'body' ).bind( 'keydown', function( e )
		{
			switch( e.keyCode )
			{
				case 13:
					if ( $( 'input, textarea' ).is( ':focus' ) )
					{
						break;
					}

					if ( typeof onenter == 'function' )
					{
						onenter();
						return false;
					}

					break;

				case 27:
					if ( typeof onesc == 'function'  )
					{
						onesc();
						return false;
					}

					break;
			}

			return true;
		} );
	}

	$.fn.modalHide = function( element_id )
	{
		$( window ).unbind( 'resize' );
		$( 'body' ).unbind( 'keydown' );
		$( '.modal' ).remove();
		$( '#' + element_id ).hide();
	}

	$.fn.modalResize = function()
	{
		$( '.modal' ).css( { width: $( document ).width() + 'px', height: $( document ).height() + 'px' } );
	}

	$.fn.create_weeks = function()
	{
		$.fn.json_admin( 'weeks_create', '', function( response )
		{
			if ( !response.success )
			{
				return $.fn.error( response.error_message );
			}

			$.fn.load_weeks();
		} );
	}
} );
