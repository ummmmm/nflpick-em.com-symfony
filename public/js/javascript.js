$( document ).ready( function(){
	$.fn.json = function( path, data, callback )
	{
		$.ajax( {
			type	: 'POST',
			url		: path,
			processData: false,
			data	: JSON.stringify( data ),
			success	: function( response, status )
			{
				callback( response );
			},
			error	: function( jqXHR, textStatus, errorThrown )
			{
				var response 			= new Object();
				response.success		= 0;
				response.error_code		= '#Error#';
				response.error_message	= 'The server returned an invalid response.\n' +
										  'Response: ' + jqXHR.responseText;

				if ( textStatus != 'error' )
				{
					callback( response );
				}
			}
		} );
	}

	$.fn.load_weeklyrecords = function()
	{
		$.fn.json( 'LoadWeeklyRecords', '', function( response )
		{
			if ( !response.success )
			{
				return $.fn.error( response.error_message );
			}

			var div		= $( '#loading_weeklyrecords' ).text( '' );
			var users	= response.data;

			$.each( users, function( key, user )
			{
				var p = $( '<p/>', { 'id': 'user' + user.id } );
				$( '<a/>', { 'href': 'javascript:;', 'text': user.name } ).bind( 'click', function() { $.fn.show_weeklyrecords( user ); } ).appendTo( p );

				p.appendTo( div );
			} );
		} );
	}

	$.fn.show_weeklyrecords = function ( user )
	{
		if ( $( '#records' + user.id ).length == 1 )
		{
			$( '#records' + user.id ).remove();

			return;
		}

		var div = $( '<div/>', { 'id': 'records' + user.id } );

		$.each( user.weeks, function( key, week )
		{
			$( '<div/>', { 'text': 'Week ' + week.id + ': ' + week.wins + ' wins - ' + week.losses + ' losses' } ).appendTo( div );
		} );

		$( '<div/>', { 'text': 'Total Wins = ' + user.total_wins + ' ' + 'Total Losses = ' + user.total_losses } ).appendTo( div );

		div.appendTo( $( '#user' + user.id ) );
	}

	$.fn.picks_build_link = function( team, week_id, game_id, winner_id, loser_id )
	{
		return $( '<a/>', { 'href': 'javascript:;', 'text': team } ).bind( 'click', function() { $.fn.makePicks( week_id, game_id, winner_id, loser_id ); } );
	}

	$.fn.picks_build_record = function( wins, losses, ties )
	{
		return $( '<span/>', { 'class': 'record', 'text': ' (' + wins + ' - ' + losses + ( ( ties == 0 ) ? '' : ' - ' + ties ) + ')' } )
	}

	$.fn.load_picks = function( week_id )
	{
		$.fn.json( '/picks/week/' + week_id + '/load', null , function( response )
		{
			if ( !response.success )
			{
				return $.fn.error( response.error_message );
			}

			var week 	= response.data;
			var div		= $( '#picks_loading' ).text( '' );
			var days	= new Array( 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' );

			$.each( response.data.games, function( key, record )
			{
				var game_date = new Date( record.game.start );

				if ( $( '#day_' + days[ game_date.getDay() ] ).length == 0 )
				{
					$( '<h1/>', {
						'id'	: 'day_' + days[ game_date.getDay() ],
						'class'	: 'picks_day',
						'text'	: days[ game_date.getDay() ]
					} ).appendTo( div );

					$( '<span/>', {
						'class'	: 'picks_date',
						'text'	: record.game.date_formatted
					} ).appendTo( div );
				}

				var past		= ( week.locked || record.game.past ) ? true : false;
				var pick_class 	= ( past ) ? 'past' : ( ( record.pick === null ) ? 'notMade' : 'made' );
				var pick 		= $( '<div/>', { 'id': 'picks' + record.game.id, 'class': 'make_picks ' + pick_class } );
				var status		= $( '<div/>', { 'id': 'status' + record.game.id } );

				if ( past )
				{
					pick.append( record.game.away.name );
				} else {
					$.fn.picks_build_link( record.game.away.name, week.id, record.game.id, record.game.away, record.game.home ).appendTo( pick );
				}

				$.fn.picks_build_record( record.game.away.wins, record.game.away.losses, record.game.away.ties ).appendTo( pick );
				$( '<b/>', { 'text': ' vs. ' } ).appendTo( pick );

				if ( past )
				{
					pick.append( record.game.home.name );
				} else {
					$.fn.picks_build_link( record.game.home.name, week.id, record.game.id, record.game.home.id, record.game.away.id ).appendTo( pick );
				}

				$.fn.picks_build_record( record.game.home.wins, record.game.home.losses, record.game.home.ties ).appendTo( pick );
				pick.append( '<br />' + record.game.home.stadium + ' - ' + record.game.time_formatted );

				if ( record.game.pick != null )
				{
					var winner 	= ( record.pick.winner_pick == record.game.home.id ) ? record.game.home.name : record.game.away.name;
					var loser	= ( record.pick.loser_pick == record.game.home.id ) ? record.game.home.name : record.game.away.name;

					status.html( 'You have picked the <b>' + winner + '</b> to beat the <b>' + loser + '</b>' );
				}

				status.appendTo( pick );
				pick.appendTo( div );
			} );
		} );
	}

	var highlighting = false;

	$.fn.highlightPicks = function( userid, week )
	{
		var usertd			= $( 'td[userid=' + userid + ']' );
		var highlightpicks 	= $( '#highlightpicks' );

		if ( userid == 0 && highlighting === true )
		{
			$( 'td' ).removeClass( 'highlight' );
			highlightpicks.text( 'Highlight Picks On' );
			highlighting = false;
			return;
		}

		if ( usertd.hasClass( 'highlight' ) )
		{
			usertd.removeClass( 'highlight' );

			if ( $( 'td.highlight' ).length == 0 )
			{
				highlighting = false;
				highlightpicks.text( 'Highlight Picks On' );
			}

			return;
		}

		$.fn.json( 'HighlightPicks', 'userid=' + encodeURIComponent( userid ) + '&week=' + encodeURIComponent( week ), function( response )
		{
			if ( !response.success )
			{
				return $.fn.error( response.error_message );
			}
			else if ( !response.data )
			{
				return;
			}

			var userid, gameid, games, users = response.data;

			for( i = 0; i < users.length; i++ )
			{
				userid 	= users[ i ].userid;
				games	= users[ i ].games;

				for( j = 0; j < games.length; j++ )
				{
					$( 'td[userid=' + userid + '][gameid=' + games[ j ] + ']' ).addClass( 'highlight' );
				}
			}
			highlighting = true;
			highlightpicks.text( 'Highlight Picks Off' );
		} );
	}

	$( '.jquery-abbreviations, .jquery-userWeekly_leaders_by_week, .jquery-userWeekly_leaders, .jquery-polls_showVotes, .jquery-add_byeTeam, .jquery-editGame_updateScore' ).bind( 'click', function()
	{
		var field = "#"+$(this).attr("show");

		if ($(this).attr("open") == "true"){
			$(this).removeAttr("open");
			$(field).slideUp();
		}else{
			$(this).attr("open","true");
			$(field).slideDown();
		}
	} );

	$.fn.emailPicks = function( week )
	{
		$.fn.json( 'EmailPicks', 'week=' + encodeURIComponent( week ), function( response )
		{
			if ( !response.success )
			{
				return $.fn.error( response.error_message );
			}

			$( '#jquery-picks_emailSent' ).slideUp( 'normal', function()
			{
				$( this ).text( response.data ).slideDown();
			} );
		} );
	}

	$.fn.makePicks = function( week, gameid, winner, loser )
	{
		$.fn.json(	'MakePicks',
					'week=' + encodeURIComponent( week ) +
					'&gameid=' + encodeURIComponent( gameid ) +
					'&winner=' + encodeURIComponent( winner ) +
					'&loser=' + encodeURIComponent( loser ),
					function( response )
					{
						if ( !response.success )
						{
							return $.fn.error( response.error_message );
						}

						$( '#remainingPicks' ).text( response.data.remaining );
						$( '#picks' + gameid ).animate( { 'background-color': '#FFFFE0' }, 600 );
						$( '#status' + gameid ).html( response.data.message );
					}
		);
	}

	$.fn.load_polls = function()
	{
		$.fn.json( 'LoadPolls', '', function( response )
		{
			if ( !response.success )
			{
				return;
			}

			$.fn.build_polls( 'loading_polls', response.data );
		} );
	}

	$.fn.load_poll = function()
	{
		$.fn.json( 'LoadPolls', 'nav=1', function( response )
		{
			if ( !response.success )
			{
				return;
			}

			$.fn.build_polls( 'loading_polls_nav', response.data );
		} );
	}

	$.fn.build_polls = function( element, polls )
	{
		var div = $( '#' + element ).text( '' );
		$.each( polls, function( key, poll )
		{
			$( '<div/>', { 'class': 'poll_question', 'text': poll.question } ).appendTo( div );

			$.each( poll.answers, function( key, answer )
			{
				if ( poll.voted )
				{
					var answer_total 	= answer.total_votes;
					var answer_percentage	= ( poll.total_votes ) ? Math.round( ( answer_total / poll.total_votes ) * 100 ) : 0;

					$( '<span/>', { 'class': 'poll_answer', 'text': answer.answer } ).append( ' - ' + answer_total + ' ' + ( answer_total == 1 ? 'Vote' : 'Votes' ) + ' - ' + answer_percentage + '%' ).appendTo( div );
					$( '<div/>', { 	'class': 'poll_percentage_container',
									'html':
										$( '<div/>', { 'class': 'poll_percentage', 'style': 'width: ' + answer_percentage + '%;', 'html': '&nbsp;' } ) } ).appendTo( div );
				} else {
					$( '<div/>', { 	'class': 'poll_answer',
									'html':
										$( '<input/>', { 'type': 'radio', 'value': answer.id, 'name': 'answer'} )
					} ).append( ' ' + answer.answer ).appendTo( div );
				}
			} );

			if ( !poll.voted )
			{
				$( '<div/>', { 'style': 'text-align: center;', 'html': $( '<input/>', { 'type': 'button', 'value': 'Cast Vote' } ).bind( 'click', function() { $.fn.cast_vote( poll.id ); } ) } ).appendTo( div );
			}
		} );
	}

	$.fn.cast_vote = function( poll_id )
	{
		var answer = $( 'input:radio[name=answer]:checked' ).val();

		if ( typeof answer === 'undefined' )
		{
			return $.fn.error( 'Select a poll answer' );
		}

		$.fn.json( 'VotePoll', 'poll_id=' + encodeURIComponent( poll_id ) + '&answer_id=' + encodeURIComponent( answer ), function( response )
		{
			if ( !response.success )
			{
				if ( response.error_code == 'already_voted' )
				{
					return $.fn.load_poll();
				}

				return $.fn.error( response.error_message );
			}

			$.fn.load_poll();
		} );
	}

	$.fn.error = function( message )
	{
		alert( message );
		return true;
	}

	$( '#logout' ).click( function( e ) {
		if ( !confirm( 'Are you sure you want to log out?' ) )
		{
			e.preventDefault();
		}
	} );
});
