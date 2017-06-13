//US Foundation Google Analytics:
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-92774608-1', 'auto');
ga('send', 'pageview');

function nl2br (str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

//To be used in text auto complete
var emojies = [
	  '+1', '-1', '100', '1234', '8ball', 'a', 'ab', 'abc', 'abcd', 'accept',
	  'aerial_tramway', 'airplane', 'alarm_clock', 'alien', 'ambulance', 'anchor',
	  'angel', 'anger', 'angry', 'anguished', 'ant', 'apple', 'aquarius', 'aries',
	  'arrow_backward', 'arrow_double_down', 'arrow_double_up', 'arrow_down',
	  'arrow_down_small', 'arrow_forward', 'arrow_heading_down',
	  'arrow_heading_up', 'arrow_left', 'arrow_lower_left', 'arrow_lower_right',
	  'arrow_right', 'arrow_right_hook', 'arrow_up', 'arrow_up_down',
	  'arrow_up_small', 'arrow_upper_left', 'arrow_upper_right',
	  'arrows_clockwise', 'arrows_counterclockwise', 'art', 'articulated_lorry',
	  'astonished', 'athletic_shoe', 'atm', 'b', 'baby', 'baby_bottle',
	  'baby_chick', 'baby_symbol', 'back', 'baggage_claim', 'balloon',
	  'ballot_box_with_check', 'bamboo', 'banana', 'bangbang', 'bank', 'bar_chart',
	  'barber', 'baseball', 'basketball', 'bath', 'bathtub', 'battery', 'bear',
	  'bee', 'beer', 'beers', 'beetle', 'beginner', 'bell', 'bento', 'bicyclist',
	  'bike', 'bikini', 'bird', 'birthday', 'black_circle', 'black_joker',
	  'black_large_square', 'black_medium_small_square', 'black_medium_square',
	  'black_nib', 'black_small_square', 'black_square_button', 'blossom',
	  'blowfish', 'blue_book', 'blue_car', 'blue_heart', 'blush', 'boar', 'boat',
	  'bomb', 'book', 'bookmark', 'bookmark_tabs', 'books', 'boom', 'boot',
	  'bouquet', 'bow', 'bowling', 'bowtie', 'boy', 'bread', 'bride_with_veil',
	  'bridge_at_night', 'briefcase', 'broken_heart', 'bug', 'bulb',
	  'bullettrain_front', 'bullettrain_side', 'bus', 'busstop',
	  'bust_in_silhouette', 'busts_in_silhouette', 'cactus', 'cake', 'calendar',
	  'calling', 'camel', 'camera', 'cancer', 'candy', 'capital_abcd', 'capricorn',
	  'car', 'card_index', 'carousel_horse', 'cat', 'cat2', 'cd', 'chart',
	  'chart_with_downwards_trend', 'chart_with_upwards_trend', 'checkered_flag',
	  'cherries', 'cherry_blossom', 'chestnut', 'chicken', 'children_crossing',
	  'chocolate_bar', 'christmas_tree', 'church', 'cinema', 'circus_tent',
	  'city_sunrise', 'city_sunset', 'cl', 'clap', 'clapper', 'clipboard',
	  'clock1', 'clock10', 'clock1030', 'clock11', 'clock1130', 'clock12',
	  'clock1230', 'clock130', 'clock2', 'clock230', 'clock3', 'clock330',
	  'clock4', 'clock430', 'clock5', 'clock530', 'clock6', 'clock630', 'clock7',
	  'clock730', 'clock8', 'clock830', 'clock9', 'clock930', 'closed_book',
	  'closed_lock_with_key', 'closed_umbrella', 'cloud', 'clubs', 'cn',
	  'cocktail', 'coffee', 'cold_sweat', 'collision', 'computer', 'confetti_ball',
	  'confounded', 'confused', 'congratulations', 'construction',
	  'construction_worker', 'convenience_store', 'cookie', 'cool', 'cop',
	  'copyright', 'corn', 'couple', 'couple_with_heart', 'couplekiss', 'cow',
	  'cow2', 'credit_card', 'crescent_moon', 'crocodile', 'crossed_flags',
	  'crown', 'cry', 'crying_cat_face', 'crystal_ball', 'cupid', 'curly_loop',
	  'currency_exchange', 'curry', 'custard', 'customs', 'cyclone', 'dancer',
	  'dancers', 'dango', 'dart', 'dash', 'date', 'de', 'deciduous_tree',
	  'department_store', 'diamond_shape_with_a_dot_inside', 'diamonds',
	  'disappointed', 'disappointed_relieved', 'dizzy', 'dizzy_face',
	  'do_not_litter', 'dog', 'dog2', 'dollar', 'dolls', 'dolphin', 'door',
	  'doughnut', 'dragon', 'dragon_face', 'dress', 'dromedary_camel', 'droplet',
	  'dvd', 'e-mail', 'ear', 'ear_of_rice', 'earth_africa', 'earth_americas',
	  'earth_asia', 'egg', 'eggplant', 'eight', 'eight_pointed_black_star',
	  'eight_spoked_asterisk', 'electric_plug', 'elephant', 'email', 'end',
	  'envelope', 'envelope_with_arrow', 'es', 'euro', 'european_castle',
	  'european_post_office', 'evergreen_tree', 'exclamation', 'expressionless',
	  'eyeglasses', 'eyes', 'facepunch', 'factory', 'fallen_leaf', 'family',
	  'fast_forward', 'fax', 'fearful', 'feelsgood', 'feet', 'ferris_wheel',
	  'file_folder', 'finnadie', 'fire', 'fire_engine', 'fireworks',
	  'first_quarter_moon', 'first_quarter_moon_with_face', 'fish', 'fish_cake',
	  'fishing_pole_and_fish', 'fist', 'five', 'flags', 'flashlight',
	  'floppy_disk', 'flower_playing_cards', 'flushed', 'foggy', 'football',
	  'footprints', 'fork_and_knife', 'fountain', 'four', 'four_leaf_clover', 'fr',
	  'free', 'fried_shrimp', 'fries', 'frog', 'frowning', 'fu', 'fuelpump',
	  'full_moon', 'full_moon_with_face', 'game_die', 'gb', 'gem', 'gemini',
	  'ghost', 'gift', 'gift_heart', 'girl', 'globe_with_meridians', 'goat',
	  'goberserk', 'godmode', 'golf', 'grapes', 'green_apple', 'green_book',
	  'green_heart', 'grey_exclamation', 'grey_question', 'grimacing', 'grin',
	  'grinning', 'guardsman', 'guitar', 'gun', 'haircut', 'hamburger', 'hammer',
	  'hamster', 'hand', 'handbag', 'hankey', 'hash', 'hatched_chick',
	  'hatching_chick', 'headphones', 'hear_no_evil', 'heart', 'heart_decoration',
	  'heart_eyes', 'heart_eyes_cat', 'heartbeat', 'heartpulse', 'hearts',
	  'heavy_check_mark', 'heavy_division_sign', 'heavy_dollar_sign',
	  'heavy_exclamation_mark', 'heavy_minus_sign', 'heavy_multiplication_x',
	  'heavy_plus_sign', 'helicopter', 'herb', 'hibiscus', 'high_brightness',
	  'high_heel', 'hocho', 'honey_pot', 'honeybee', 'horse', 'horse_racing',
	  'hospital', 'hotel', 'hotsprings', 'hourglass', 'hourglass_flowing_sand',
	  'house', 'house_with_garden', 'hurtrealbad', 'hushed', 'ice_cream',
	  'icecream', 'id', 'ideograph_advantage', 'imp', 'inbox_tray',
	  'incoming_envelope', 'information_desk_person', 'information_source',
	  'innocent', 'interrobang', 'iphone', 'it', 'izakaya_lantern',
	  'jack_o_lantern', 'japan', 'japanese_castle', 'japanese_goblin',
	  'japanese_ogre', 'jeans', 'joy', 'joy_cat', 'jp', 'key', 'keycap_ten',
	  'kimono', 'kiss', 'kissing', 'kissing_cat', 'kissing_closed_eyes',
	  'kissing_heart', 'kissing_smiling_eyes', 'koala', 'koko', 'kr', 'lantern',
	  'large_blue_circle', 'large_blue_diamond', 'large_orange_diamond',
	  'last_quarter_moon', 'last_quarter_moon_with_face', 'laughing', 'leaves',
	  'ledger', 'left_luggage', 'left_right_arrow', 'leftwards_arrow_with_hook',
	  'lemon', 'leo', 'leopard', 'libra', 'light_rail', 'link', 'lips', 'lipstick',
	  'lock', 'lock_with_ink_pen', 'lollipop', 'loop', 'loudspeaker', 'love_hotel',
	  'love_letter', 'low_brightness', 'm', 'mag', 'mag_right', 'mahjong',
	  'mailbox', 'mailbox_closed', 'mailbox_with_mail', 'mailbox_with_no_mail',
	  'man', 'man_with_gua_pi_mao', 'man_with_turban', 'mans_shoe', 'maple_leaf',
	  'mask', 'massage', 'meat_on_bone', 'mega', 'melon', 'memo', 'mens', 'metal',
	  'metro', 'microphone', 'microscope', 'milky_way', 'minibus', 'minidisc',
	  'mobile_phone_off', 'money_with_wings', 'moneybag', 'monkey', 'monkey_face',
	  'monorail', 'moon', 'mortar_board', 'mount_fuji', 'mountain_bicyclist',
	  'mountain_cableway', 'mountain_railway', 'mouse', 'mouse2', 'movie_camera',
	  'moyai', 'muscle', 'mushroom', 'musical_keyboard', 'musical_note',
	  'musical_score', 'mute', 'nail_care', 'name_badge', 'neckbeard', 'necktie',
	  'negative_squared_cross_mark', 'neutral_face', 'new', 'new_moon',
	  'new_moon_with_face', 'newspaper', 'ng', 'nine', 'no_bell', 'no_bicycles',
	  'no_entry', 'no_entry_sign', 'no_good', 'no_mobile_phones', 'no_mouth',
	  'no_pedestrians', 'no_smoking', 'non-potable_water', 'nose', 'notebook',
	  'notebook_with_decorative_cover', 'notes', 'nut_and_bolt', 'o', 'o2',
	  'ocean', 'octocat', 'octopus', 'oden', 'office', 'ok', 'ok_hand', 'ok_woman',
	  'older_man', 'older_woman', 'on', 'oncoming_automobile', 'oncoming_bus',
	  'oncoming_police_car', 'oncoming_taxi', 'one', 'open_book',
	  'open_file_folder', 'open_hands', 'open_mouth', 'ophiuchus', 'orange_book',
	  'outbox_tray', 'ox', 'package', 'page_facing_up', 'page_with_curl', 'pager',
	  'palm_tree', 'panda_face', 'paperclip', 'parking', 'part_alternation_mark',
	  'partly_sunny', 'passport_control', 'paw_prints', 'peach', 'pear', 'pencil',
	  'pencil2', 'penguin', 'pensive', 'performing_arts', 'persevere',
	  'person_frowning', 'person_with_blond_hair', 'person_with_pouting_face',
	  'phone', 'pig', 'pig2', 'pig_nose', 'pill', 'pineapple', 'pisces', 'pizza',
	  'point_down', 'point_left', 'point_right', 'point_up', 'point_up_2',
	  'police_car', 'poodle', 'poop', 'post_office', 'postal_horn', 'postbox',
	  'potable_water', 'pouch', 'poultry_leg', 'pound', 'pouting_cat', 'pray',
	  'princess', 'punch', 'purple_heart', 'purse', 'pushpin',
	  'put_litter_in_its_place', 'question', 'rabbit', 'rabbit2', 'racehorse',
	  'radio', 'radio_button', 'rage', 'rage1', 'rage2', 'rage3', 'rage4',
	  'railway_car', 'rainbow', 'raised_hand', 'raised_hands', 'raising_hand',
	  'ram', 'ramen', 'rat', 'recycle', 'red_car', 'red_circle', 'registered',
	  'relaxed', 'relieved', 'repeat', 'repeat_one', 'restroom',
	  'revolving_hearts', 'rewind', 'ribbon', 'rice', 'rice_ball', 'rice_cracker',
	  'rice_scene', 'ring', 'rocket', 'roller_coaster', 'rooster', 'rose',
	  'rotating_light', 'round_pushpin', 'rowboat', 'ru', 'rugby_football',
	  'runner', 'running', 'running_shirt_with_sash', 'sa', 'sagittarius',
	  'sailboat', 'sake', 'sandal', 'santa', 'satellite', 'satisfied', 'saxophone',
	  'school', 'school_satchel', 'scissors', 'scorpius', 'scream', 'scream_cat',
	  'scroll', 'seat', 'secret', 'see_no_evil', 'seedling', 'seven', 'shaved_ice',
	  'sheep', 'shell', 'ship', 'shipit', 'shirt', 'shit', 'shoe', 'shower',
	  'signal_strength', 'six', 'six_pointed_star', 'ski', 'skull', 'sleeping',
	  'sleepy', 'slot_machine', 'small_blue_diamond', 'small_orange_diamond',
	  'small_red_triangle', 'small_red_triangle_down', 'smile', 'smile_cat',
	  'smiley', 'smiley_cat', 'smiling_imp', 'smirk', 'smirk_cat', 'smoking',
	  'snail', 'snake', 'snowboarder', 'snowflake', 'snowman', 'sob', 'soccer',
	  'soon', 'sos', 'sound', 'space_invader', 'spades', 'spaghetti', 'sparkle',
	  'sparkler', 'sparkles', 'sparkling_heart', 'speak_no_evil', 'speaker',
	  'speech_balloon', 'speedboat', 'squirrel', 'star', 'star2', 'stars',
	  'station', 'statue_of_liberty', 'steam_locomotive', 'stew', 'straight_ruler',
	  'strawberry', 'stuck_out_tongue', 'stuck_out_tongue_closed_eyes',
	  'stuck_out_tongue_winking_eye', 'sun_with_face', 'sunflower', 'sunglasses',
	  'sunny', 'sunrise', 'sunrise_over_mountains', 'surfer', 'sushi', 'suspect',
	  'suspension_railway', 'sweat', 'sweat_drops', 'sweat_smile', 'sweet_potato',
	  'swimmer', 'symbols', 'syringe', 'tada', 'tanabata_tree', 'tangerine',
	  'taurus', 'taxi', 'tea', 'telephone', 'telephone_receiver', 'telescope',
	  'tennis', 'tent', 'thought_balloon', 'three', 'thumbsdown', 'thumbsup',
	  'ticket', 'tiger', 'tiger2', 'tired_face', 'tm', 'toilet', 'tokyo_tower',
	  'tomato', 'tongue', 'top', 'tophat', 'tractor', 'traffic_light', 'train',
	  'train2', 'tram', 'triangular_flag_on_post', 'triangular_ruler', 'trident',
	  'triumph', 'trolleybus', 'trollface', 'trophy', 'tropical_drink',
	  'tropical_fish', 'truck', 'trumpet', 'tshirt', 'tulip', 'turtle', 'tv',
	  'twisted_rightwards_arrows', 'two', 'two_hearts', 'two_men_holding_hands',
	  'two_women_holding_hands', 'u5272', 'u5408', 'u55b6', 'u6307', 'u6708',
	  'u6709', 'u6e80', 'u7121', 'u7533', 'u7981', 'u7a7a', 'uk', 'umbrella',
	  'unamused', 'underage', 'unlock', 'up', 'us', 'v', 'vertical_traffic_light',
	  'vhs', 'vibration_mode', 'video_camera', 'video_game', 'violin', 'virgo',
	  'volcano', 'vs', 'walking', 'waning_crescent_moon', 'waning_gibbous_moon',
	  'warning', 'watch', 'water_buffalo', 'watermelon', 'wave', 'wavy_dash',
	  'waxing_crescent_moon', 'waxing_gibbous_moon', 'wc', 'weary', 'wedding',
	  'whale', 'whale2', 'wheelchair', 'white_check_mark', 'white_circle',
	  'white_flower', 'white_large_square', 'white_medium_small_square',
	  'white_medium_square', 'white_small_square', 'white_square_button',
	  'wind_chime', 'wine_glass', 'wink', 'wolf', 'woman', 'womans_clothes',
	  'womans_hat', 'womens', 'worried', 'wrench', 'x', 'yellow_heart', 'yen',
	  'yum', 'zap', 'zero', 'zzz'
	];



function parents(grandpa_id){
	grandpa_id = parseInt(grandpa_id);
	//A PHP version of this function is in us_helper.php
	switch(grandpa_id) {
    case 1:
    	return '@';
        break;
    case 3:
    	return '#';
        break;
    case 4:
    	return '?';
        break;
    case 43:
    	return '!';
        break;
    default:
    	return null;
	}
}

//Expands search input when focused
function pop_search_open(){
	 var win_width = $(window).width();
	 if(win_width>720){win_width=720;}
	 $( ".search-block" ).css('width',(win_width-125)+'px');
}

function resetCoreInputs(){
	$( "#addnode" ).blur().val("");
	$( "#mainsearch" ).blur().val("");
}

var algolia_index,client,algolia_loaded;
algolia_loaded = 0;
function load_algolia(index_name='nodes'){
	if(algolia_loaded){
		return false;
	}
	algolia_loaded = 1; //Do not load again.
	client = algoliasearch('49OCX1ZXLJ', 'ca3cf5f541daee514976bc49f8399716');
	algolia_index = client.initIndex(index_name);
}



function editHeightControl(){
	if(/<[a-z][\s\S]*>/i.test($(".node_details textarea").val())){
		//This means this text contains HTML, show smaller font:
		$(".node_details textarea").addClass('codefont');
	}
	
	$('.node_details').on( 'change keyup keydown paste cut', 'textarea', function (){
	    $(this).height(0).height(this.scrollHeight);
	}).find( 'textarea' ).change();
}



$( document ).ready(function() {
	
	//Disable enter submission on top search:
	$('#searchForm').on('keyup keypress', function(e) {
		  var keyCode = e.keyCode || e.which;
		  if (keyCode === 13) {
		    e.preventDefault();
		    return false;
		  }
	});
	
	//The hover of link settings
	$( ".node_details" ).hover(function() {
		$( this ).addClass('show_child');
	}).mouseleave(function() {
		$( this ).removeClass('show_child');
	});
	
	//Loaded by default for easier use:
	$('[data-toggle="tooltip"]').tooltip();
	
	//Prevent Node creation form submission
	$("#addnodeform").submit(function(e){
        e.preventDefault();
    });
	
	//By default we do not load until user goes to search box:
	$( ".autosearch" ).focus(function() {
		load_algolia();
	});
	
	//Header search specific functions for UI and autocomplete result selection:
	$( "#mainsearch" ).on('autocomplete:selected', function(event, suggestion, dataset) {
		location.replace("/"+suggestion.node_id+'?from=search');
	}).focus(function() {
		pop_search_open();
		//Handle window resize on focus:
		$( window ).resize(function() { pop_search_open(); });
	}).focusout(function() {
		//default width:
		$( ".search-block" ).css('width','120px');
	}).autocomplete({ hint: false, keyboardShortcuts: ['s'] }, [{
	    source: function(q, cb) {
	      algolia_index.search(q, { hitsPerPage: 7 }, function(error, content) {
	        if (error) {
	          cb([]);
	          return;
	        }
	        cb(content.hits, content);
	      });
	    },
	    /*displayKey: 'value',*/
	    displayKey: function(suggestion) { return "" },
	    templates: {
	      suggestion: function(suggestion) {
	         return parents(parseInt(suggestion.grandpa_id)) + suggestion._highlightResult.value.value;
	      },
	    }
	}]);
	
	//Initiate Sortable for moderators and for certain parents
	//TODO move logic to #SortableNodes
	if(user_data['is_mod']){
		//New Node search box call to action: 
		$( "#addnode" ).on('autocomplete:selected', function(event, suggestion, dataset) {
			//Link nodes together:
			link_node(suggestion.node_id, (parents(parseInt(suggestion.grandpa_id))+suggestion.value) );
		}).autocomplete({ hint: false, keyboardShortcuts: ['a'] }, [{
		    source: function(q, cb) {
			      algolia_index.search(q, { hitsPerPage: 7 }, function(error, content) {
			        if (error) {
			          cb([]);
			          return;
			        }
			        
			        cb(content.hits, content);
			      });
			    },
			    displayKey: function(suggestion) { return "" },
			    templates: {
			      suggestion: function(suggestion) {
			         return '<span class="suggest-prefix"><span class="glyphicon glyphicon-link" aria-hidden="true"></span> Link to</span> '+parents(parseInt(suggestion.grandpa_id)) + suggestion._highlightResult.value.value;
			      },
			      header: function(data) {
			    	  if(!data.isEmpty){
			    		  return '<a href="javascript:create_node(\''+data.query+'\')" class="add_node"><span class="suggest-prefix"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create</span> '+parents(node[0]['grandpa_id'])+data.query+' <span class="boldbadge badge pink-bg-light" style="float:none; display:inline-block; margin-bottom:5px;">DIRECT OUT<span class="glyphicon glyphicon-arrow-up rotate45" aria-hidden="true"></span></span> <span class="grey">from '+node[0]['sign']+node[0]['value'].replace(/\s/g, '')+'</span></a>';
			    	  }
			      },
			      empty: function(data) {
		    		  	  return '<a href="javascript:create_node(\''+data.query+'\')" class="add_node"><span class="suggest-prefix"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Create</span> '+parents(node[0]['grandpa_id'])+data.query+'</a>';
			      },
			    }
		}]).keypress(function (e) {
	        var code = (e.keyCode ? e.keyCode : e.which);
	        if (code == 13) {
	        	create_node($( "#addnode" ).val());
	            return true;
	        }
	    });
	}
});


function load_textcomplete(objHandler){

	//Load search
	load_algolia();
	
	var lastQuery = '';
	$(objHandler).textcomplete([
		 {
		    // #3 - Regular expression used to trigger the autocomplete dropdown
		    match: /(^|\s)\|\|(\w*(?:\S*\w*))$/,
		    // #4 - Function called at every new keystroke
		    search: function(query, callback) {
		      lastQuery = query;
		      algolia_index.search(lastQuery, { hitsPerPage: 5 })
		        .then(function searchSuccess(content) {
		          if (content.query === lastQuery) {
		            callback(content.hits);
		          }
		        })
		        .catch(function searchFailure(err) {
		        	console.error(err);
		        });
		    },
		    // #5 - Template used to display each result obtained by the Algolia API
		    template: function (hit) {
		      // Returns the highlighted version of the name attribute
		      return '<b>'+hit._highlightResult.grandpa_sign.value +'</b>'+ hit._highlightResult.value.value;
		    },
		    // #6 - Template used to display the selected result in the textarea
		    replace: function (hit) {
		      return ' ||'+hit.node_id + ' ';
		    }
		 }
	], {
	    onKeydown: function (e, commands) {
	        if (e.ctrlKey && e.keyCode === 74) { // CTRL-J
	            return commands.KEY_ENTER;
	        }
	    }
	});
}

//Set some global values for editing:
var main_val, parent_val, parent_html, key_global, id_global, original_parent_val;

function edit_link(key,id){
	//TODO: CSS tweaks so when edit button is hit, no change in position is noticed acrossed popular browsers
	//Start by closing all open edits.
	//We do this to encourage focus on a single task at a time.
	$( ".node_details" ).each(function( index ) {
		//Only close if its open:
		if($( this ).attr('edit-mode')=='1'){
			discard_link_edit($( this ).attr('data-link-index'),$( this ).attr('data-link-id'));
		}
	});
	
	//Enter edit mode for this link:
	$('#link'+id).attr('edit-mode','1');
	
	//Set variables:
	//Get main value from core node:
	for(var i in node) {
	    if(node[i]['id']==id){
	    	//Does not consider PHP templating in load_node.php
	    	main_val = node[i]['value'];
	    	break;
	    }
	}	
	
	parent_val = $.trim($('#link'+id+" .parentTopLink .anchor").text());
	parent_html = $('#link'+id+" .node_top_node").html();
	key_global = key;
	id_global = id;
	original_parent_val = $('#tl'+id).text();
	
	//Hide potential followupContent:
	$('.followupContent').hide();
	
	//Yellow bg & visible metadata:
	$('#link'+id).addClass('edit_mode').addClass('show_child_edit');	
	//Create the Cancel href:
	var cancel_href = 'javascript:discard_link_edit(' + key + ',' + id + ');';
	//Repurpose original edit link:
	$('#link'+id+" .edit_link").attr('href',cancel_href);
	
	//Add buttons:
	//Additional fixed buttons:
	var save_button = '<a class="btn btn-primary btn-sm a_save" href="javascript:save_link_updated(' + key + ',' + id + ');" role="button">Save</a>';
	var cancel_button = '<a class="a_cancel" href="'+cancel_href+'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</a>';
	//Create action buttons:
	$('#link'+id+" .hover>div").append('<div class="action_buttons">'+save_button+cancel_button+'</div">');
	
	//Make primary value editable:
	var height = (  $('#link'+id+" .node_h1").height() <24 ? 24 : $('#link'+id+" .node_h1").height() );
	$('#link'+id+" .node_h1").html('<textarea class="mainEditTextarea" style="height:'+height+'px; font-weight:'+( key==0 ? 'bold' : 'normal')+';"></textarea>');
	//$('#link'+id+" .node_h1 textarea").addClass('');
	
	
	if(parseInt($( '#link'+id ).attr('is-direct'))){
		//This is a direct link, bind text changes
		$('#link'+id+" .node_h1 textarea").bind('input propertychange', function() {
			$('#tl'+id).text($('#link'+id+" .node_h1 textarea").val());
		});
	} else {
		
		//This is a indirect Gem
		//Append textcomplete only for non-direct gems
		//Inform users that doubleLine is enabled:
		load_textcomplete('.mainEditTextarea');
	}
		
	
	//Wire the enter key on the textarea to save
	$('#link'+id+" .node_h1 textarea").keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        //Ctrl+Enter to save:
        if (e.ctrlKey && code>=10 && code<=13) {
        	save_link_updated(key,id);
            return true;
        }
    });
	
	//Set value and focus:
	$('#link'+id+" .node_h1 textarea").focus().val(main_val);
	//Adjust textarea height:
	editHeightControl();
	
	
	//Make parent link editable only if:
	if(key==0 && !parents(node[0]['node_id'])){
		
		$('#link'+id+' .node_top_node').html('<input type="text" id="editparent" class="autosearch" value="'+parent_val+'" />');
		
		//Loadup search engine if not already:
		load_algolia();
		
		//Enable the edit auto search:
		$( '#link'+id+' #editparent' ).on('autocomplete:selected', function(event, suggestion, dataset) {
			//Set the value in a hidden field:
			$( '#link'+id ).attr('new-parent-id' , suggestion.node_id);
			//No more editing:
			$( '#link'+id+" #editparent" ).remove();
			//Show placeholder until real update happens upon submission:
			$( '#link'+id+" .node_top_node").html( '<a href="/'+suggestion.node_id+'">'+parents(parseInt(suggestion.grandpa_id)) + suggestion.value.replace(/\W/g, '')+'</a> <span class="edit_warning not_saved">(Not saved yet)</span>' );
		}).autocomplete({ hint: false }, [{
		    source: function(q, cb) {
		      algolia_index.search(q, { hitsPerPage: 7 }, function(error, content) {
		        if (error) {
		          cb([]);
		          return;
		        }
		        cb(content.hits, content);
		      });
		    },
		    /*displayKey: 'value',*/
		    displayKey: function(suggestion) { return "" },
		    templates: {
		      suggestion: function(suggestion) {
		         return parents(parseInt(suggestion.grandpa_id)) + suggestion.value;
		      },
		    }
		}]);
	}
}



var lastToggleId = 0;
//Wire the enter key on the textarea to save
$(document).keyup(function(e) {
	var code = (e.keyCode ? e.keyCode : e.which);
    if (code==27) {
    	//In case its being edited:
    	if(id_global>0){
    		discard_link_edit(key_global, id_global);
    	} else if(lastToggleId>0){
    		//Close last open:
    		toggleValue(lastToggleId);
    	}   	
    	
    	//In case the focus is on these inputs:
    	resetCoreInputs();
    }
});

function toggleValue(link_id){
	if(lastToggleId!=link_id){
		lastToggleId = link_id;
	} else {
		lastToggleId = 0;
	}
	
	$('#linkval'+link_id).toggle();
	
	if($( '.gh'+link_id ).hasClass( "glyphicon-triangle-bottom" )){
		$( '#link'+link_id+' .parentLink' ).removeClass('zoom-out').addClass('zoom-in');
		$( '.gh'+link_id ).removeClass('glyphicon-triangle-bottom');
		$( '.gh'+link_id ).addClass('glyphicon-triangle-right');
	} else if($( '.gh'+link_id ).hasClass( "glyphicon-triangle-right" )){
		$( '#link'+link_id+' .parentLink' ).removeClass('zoom-in').addClass('zoom-out');
		$( '.gh'+link_id ).removeClass('glyphicon-triangle-right');
		$( '.gh'+link_id ).addClass('glyphicon-triangle-bottom');
	}
}

function discard_link_edit(key,id,keep_parent=false,origin='default'){
	//Reset global variables:
	key_global = 0;
	id_global = 0;
	
	//Exit edit mode:
	$('#link'+id).attr('edit-mode','0');
	
	//Revert the link editing back to default:
	$('#link'+id).removeClass('edit_mode').removeClass('show_child_edit');
	
	//Repurpose original edit link:
	$('#link'+id+" .edit_link").attr('href','javascript:edit_link(' + key + ',' + id + ');');
	//Remove buttons:
	$('#link'+id+" .action_buttons").remove();
	
	//Show potential followupContent:
	$('.followupContent').fadeIn();
	
	if(origin!='save'){
		//Resent parent value
		$('#tl'+id).text(original_parent_val);
	}
	
	//Remove TextComplete, if any
	$('#link'+id+' .mainEditTextarea').textcomplete('destroy');
	
	//Reset input fields back to defualt values:
	$('#link'+id+" .node_h1").html(nl2br(main_val));
	if(key==0 && !parents(node[0]['node_id']) && !keep_parent){
		$('#link'+id+" .node_top_node").html(parent_html);
	}
	
	//Also close possible delete warnings:
	cancel_delete_link(key,id);
}

function toggleSort(doEnable,sortType){
	
	if(sortType=='child'){
		if(doEnable && $('#sortIsOn').hasClass('sortTypechild')){
			return false;
		}
		var main_class = 'is_children';
		var colorClass = 'pink';
		var oppositeIsOn = $('#sortIsOn').hasClass('sortTypeparent');
	} else if(sortType=='parent') {
		if(doEnable && $('#sortIsOn').hasClass('sortTypeparent')){
			return false;
		}
		var main_class = 'is_parents';
		var colorClass = 'blue';
		var oppositeIsOn = $('#sortIsOn').hasClass('sortTypechild');
	}
	
	
	if(doEnable){
		
		if(oppositeIsOn){
			//We need to do some adjustments:
			$('#sortIsOn').remove();
			$('.'+(sortType=='child' ? 'is_parents' : 'is_children' )+' .glyphicon-sort').remove();
		}
		
		//Remove any existing handlers:
		$('.glyphicon-sort').remove();
		
		//Enable sort:
		$('#secondNav').append('<li role="presentation" id="sortIsOn" class="li_setting pull-right disabled sortType'+sortType+'"><a href="javascript:void(0)" class="disabled '+colorClass+'-bg"><span class="glyphicon glyphicon glyphicon-sort sort-handle" aria-hidden="true"></span> Sort On</a></li>');
		$('.'+main_class+' .sortconf').before('<span class="glyphicon glyphicon glyphicon-sort '+colorClass+'" aria-hidden="true"></span>');
		$('.parentTopLink>.glyphicon-sort').remove(); //Removes the sort for top node as that is not sortable!
		
		$( ".list-group" ).sortable({
			items: ".child-node",
			handle: ".list-group-item-heading", //The entire href line. .sort-handle is the icon.
			update: function( event, ui ) {
				//Set processing status:
				$( ".sortconf" ).html('<span class="saving"><img src="/img/loader.gif" /></span>');
				//Fetch new sort:
				var new_sort = [];
				var sort_rank = 0;
				$( ".child-node" ).each(function() {
					//We would later use this to update DB:
					if($( this ).attr('data-link-index')>0){ //This excludes TOP link from getting into sort, as it should remain TOP.
						new_sort[sort_rank] = $( this ).attr('data-link-id');
						sort_rank++;
					}
				});
				
				//Update backend:
				$.post("/api/update_sort", {node_id:node[0]['node_id'], new_sort:new_sort, sortType:sortType}, function(data) {
					//Update UI to confirm with user:
					$( ".sortconf" ).html(data);
					
					//Disapper in a while:
					setTimeout(function() {
				        $(".sortconf>span").fadeOut();
				    }, 3000);
			    });
			}
	    });
		
	} else {
		//Disable sort:
		$('#sortIsOn').remove();
		$('.'+main_class+' .glyphicon-sort').remove();
		$( ".list-group" ).sortable( "destroy" );
	}
}


function nav2nd(focus_nav){
	$('.nav-pills li').removeClass('active');
	$('.nav-pills .li_'+focus_nav).addClass('active');
	
	if(focus_nav=='all'){
		$(".node_details").show();
	} else {
		$(".node_details").hide();
		$(".is_top, .is_"+focus_nav).show();
	}
	
	//Enable sortable for child/parents:
	if(user_data['is_mod']){
		if(focus_nav=='children'){
			//toggleSort(0,'parent');
			toggleSort(1,'child');
		} else if(focus_nav=='parents'){
			toggleSort(1,'parent');
			//toggleSort(0,'child');
		} else {
			toggleSort(0,'parent');
			toggleSort(0,'child');
		}
	}
}

function restrat_sort(){
	//Restarts sort to include newly added items, if filter is sortable:
	if($('#secondNav .li_parents').hasClass( "active" )){
		//IN Active
		toggleSort(0,'child');
		toggleSort(1,'parent');
	} else if($('#secondNav .li_children').hasClass( "active" )){
		//OUT Active
		toggleSort(0,'parent');
		toggleSort(1,'child');
	}
}

function delete_link(key,id){
	
	//TODO Implement more stats on what would be deleted!
	$('#link'+id+" .a_delete").attr('href','javascript:cancel_delete_link(' + key + ',' + id + ');');
	
	//Determine direction:
	var is_inward = (key==0 || node[key]['node_id']==node[0]['node_id']);
	
	if(is_inward){
		parent_val = $.trim($('#link'+id+" .parentTopLink .anchor").text());
	} else {
		parent_val = node[0]['sign']+node[0]['value'];
	}
	
	//TODO: The descriptions here can be improved to be more clear
	var do_search = 0;
	var link_count = ( key==0 ? node[key]['link_count'] : node[key]['parents'][0]['link_count'] );
	var direct_out_count = ( key==0 ? node[key]['direct_out_count'] : node[key]['parents'][0]['direct_out_count'] );
	if(node[key]['ui_parent_rank']==1 && link_count>0){
		
		if(direct_out_count>0){
			
			//This has more links to it, give the user some options:
			var del_box = '<b style="color:#fe3c3c">Remove entire pattern:</b><br /><ul style="list-style:decimal; margin-left:-20px;">'
				+'<li>Move '+direct_out_count+' DIRECT OUTs to <span class="setdelparentcontainer" node-id="'+( is_inward ? node[0]['parent_id'] : node[0]['node_id'] )+'"><input type="text" class="autosearch setdeleteparent" value="'+parent_val+'" /></span>: <a href="javascript:delete_link_confirmed(' + key + ',' + id + ', -3)"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>Delete & Move</a></li>'
				+'<li>Remove all OUT Gems & all dependant DIRECT OUTs: <a href="javascript:delete_link_confirmed(' + key + ',' + id + ', -4)"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>Go Nuclear!</a></li>'
				+ '</ul>';
			
			do_search = 1;
			
		} else {
			var del_box = '<b style="color:#fe3c3c">You are about to delete this entire Pattern:</b><br /><b>Confirm:</b> <a href="javascript:delete_link_confirmed(' + key + ',' + id + ', -2)"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>Delete</a>';
		}
		
	} else {
		var del_box = '<b>Confirm:</b> '
			+'<a href="javascript:delete_link_confirmed(' + key + ',' + id + ', -1)"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>Delete</a>';
	}
	
	$('#link'+id+' .node_stats').append('<div id="delete_confirm">' + del_box + ' or <a href="javascript:cancel_delete_link(' + key + ',' + id + ')" style="color:#999;"><u>cancel</u></a></div>');

	//Update Algolia now:
	if(do_search){
		load_algolia();
		
		//Enable searching for a new parent:
		$( '#link'+id+' .setdeleteparent' ).on('autocomplete:selected', function(event, suggestion, dataset) {
			//Set new id:
			$( '#link'+id+' .setdelparentcontainer' ).attr('node-id' , suggestion.node_id);
			
			//Set HTML without any further editing options:
			$( '#link'+id+' .setdelparentcontainer' ).html(parents(parseInt(suggestion.grandpa_id)) + suggestion.value.replace(/\W/g, ''));
			
		}).autocomplete({ hint: false }, [{
		    source: function(q, cb) {
		      algolia_index.search(q, { hitsPerPage: 7 }, function(error, content) {
		        if (error) {
		          cb([]);
		          return;
		        }
		        cb(content.hits, content);
		      });
		    },
		    /*displayKey: 'value',*/
		    displayKey: function(suggestion) { return "" },
		    templates: {
		      suggestion: function(suggestion) {
		         return parents(parseInt(suggestion.grandpa_id)) + suggestion._highlightResult.value.value;
		      },
		    }
		}]);
		
		//Adjust CSS:
		$( '#link'+id+' .algolia-autocomplete' ).attr('style','position: relative; display:inline; direction: ltr;');
	}
}
function cancel_delete_link(key,id){
	$('#link'+id+" .a_delete").attr('href','javascript:delete_link(' + key + ',' + id + ');');
	$('#link'+id+' .node_stats #delete_confirm').remove();
}
function delete_link_confirmed(key,id,type){
	
	var is_inward = ( (key==0 || node[key]['node_id']==node[0]['node_id']) ? 1 : 0 );
	
	//See helper function action_type_descriptions() for "type" index
	//Prepare data for processing:
	var input_data = {
			is_inward:is_inward,
			key:key,
			id:id,
			new_parent_id: ( type==-3 ? parseInt($('#link'+id+' .setdelparentcontainer').attr('node-id')) : 0 ),
			type:type,
	};
	
	//Show processing:
	$('#link'+id).html('<span class="saving"><img src="/img/loader.gif" /> Removing...</span>');
	
	//Update backend:
	$.post("/api/delete", input_data, function(data) {
		if(key>0){
			
			//Update UI to confirm with user:
			$('#link'+id).html(data);
			//Disapper in a while:
			setTimeout(function() {
				$('#link'+id).fadeOut();
		    }, 3000);
			
		} else {
			//Redirect to parent node as the entire node has been deleted:
			location.replace("/"+( input_data['new_parent_id']>0 ? input_data['new_parent_id'] : node[0]['parent_id'] )+'?from='+node[0]['node_id']);			
		}
    });	
}




function save_link_updated(key,id){
	//Define variables:
	var new_value = $( '#link'+id+' .node_h1 textarea' ).val();
	var new_parent_id = parseInt($( '#link'+id ).attr('new-parent-id')); //This is optional!
	
	//Exit edit mode:
	discard_link_edit(key,id,(new_parent_id>0),'save');
	if(new_parent_id>0){
		$('#link'+id+' .not_saved').remove(); //For the parent
	}
	
	//Update main values:
	$('#link'+id+" .node_h1").html(nl2br(new_value));
	
	//Show processing in UI:
	$('#link'+id+" .hover>div").append('<span class="action_buttons saving"><img src="/img/loader.gif" /> Adding <img src="/img/gem/diamond_48.png" width="20" class="light" />...</span>');

	//Prepare data for processing:
	var input_data = {
			original_node_id:node[0]['node_id'],
			key:key,
			id:id,
			new_parent_id:new_parent_id,
			new_value:new_value,
	};
	
	//Update backend:
	$.post("/api/update_link", input_data, function(data) {
		//Update UI to confirm with user:
		
		if( !data.message ){
			
			//Means an error message:
			$('#link'+id+" .hover>div").html(data);
			
			//Disapper in a while:
			setTimeout(function() {
				$('#link'+id+" .hover>div>span").fadeOut();
		    }, 3000);
			
		} else if(key==0 && data.new_parent_id>0){
			
			//User changed the parent, lets redirect to new one:
			location.replace( "/"+data.new_parent_id+'?from='+node[0]['node_id'] );
			
		} else {
			
			$('#link'+id).replaceWith(data.message);
			
			//Update Toggle:
			$('[data-toggle="tooltip"]').tooltip();
	
			//Expand current node:
			node = data.node;
			
			//Reset SORT setting, if filtering IN or OUT:
			restrat_sort();
		}
    });
}



function create_node(node_name){
	if(node_name.length<1){
		return false;
	}
	
	//Reset:
	resetCoreInputs();
	
	//Re-focus on Add-box, asap:
	$( "#addnode" ).focus();
	
	//Show loader:
	$( '<div class="list-group-item loading-node"><img src="/img/loader.gif" /> Adding <img src="/img/gem/diamond_48.png" width="20" class="light" />...</div>' ).insertBefore( ".list_input" );
	
	var input_data = {
		grandpa_id:node[0]['grandpa_id'],
		parent_id:node[0]['node_id'],
		value:node_name,
	};
	
	//Create node:
	$.post("/api/create_node", input_data, function(data) {
		//Update UI to confirm with user:
		
		$( ".loading-node" ).remove();
		$( data.message ).insertBefore( ".list_input" );
		
		//Flash our new diamond:
		//TODO implemenet data.link_id and make it shine for both add/link
		
		//Update Toggle:
		$('[data-toggle="tooltip"]').tooltip();

		//Expand current node:
		node = data.node;
		
		//Reset SORT setting, if filtering IN or OUT:
		restrat_sort();
		
		//Reset:
		resetCoreInputs();
		$('#addnode').focus();
    });
}




function link_node(child_node_id,new_name){
	
	child_node_id = parseInt(child_node_id);
	if(child_node_id<1){
		return false;
	}
	
	if($('#linkNodeModal').length>0) {
		$('#linkNodeModal').remove();
	}
	
	
	$('body').append('<div class="modal fade" id="linkNodeModal" tabindex="-1" role="dialog">'
  +'<div class="modal-dialog" role="document">'
    +'<div class="modal-content">'
    	+'<div class="modal-header">'
    		+'<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
    		+'<h3 class="modal-title">Add <img src="/img/gem/diamond_48.png" width="20" class="light" style="margin-right:2px;"> from '+parents(node[0]['grandpa_id'])+node[0]['value']+'</h3>'
    	+'</div>'
      +'<div class="modal-body">'
      
      
      +'<div class="form-group">'
      +'<label>Optional Value:</label>'
      +'<textarea id="newVal" tabindex="1" class="form-control" rows="3"></textarea>'
      +'</div>'
      
      //Introduction to Flow:
      +'<p>Choose Flow <span class="glyphicon glyphicon-info-sign hastt" aria-hidden="true" title="Flow moves from OUT towards IN. IN represents the parent/originator while OUT represents the child/dependant. Exploring nodes require one to follow its OUTs all the way down." data-placement="bottom" data-toggle="tooltip"></span>:</p>'

      
      +'<div class="form-group">'
	      + '<label class="radio">'
	      + '<input type="radio" tabindex="4" name="parentNodeName" id="originalValue" checked="checked"> '
	      + new_name
	      + ' <span class="boldbadge badge pink-bg">OUT <span class="glyphicon glyphicon-arrow-up rotate45" aria-hidden="true"></span></span>'
	      + '</label>'
	      
	      + '<label class="radio">'
	      + '<input type="radio" tabindex="2" name="parentNodeName" id="childValue"> '
	      + new_name
	      + ' <span class="boldbadge badge blue-bg">IN <span class="glyphicon glyphicon-arrow-right rotate45" aria-hidden="true"></span></span>'
	      + '</label>'
	  +'</div>'
	  
	  
        
      +'</div>'
      +'<div class="modal-footer">'
        +'<button type="button" id="modalCancel" class="btn btn-default" data-dismiss="modal">Cancel</button>'
        +'<button type="button" tabindex="3" id="modalSubmit" class="btn btn-primary"><span class="glyphicon glyphicon-link" aria-hidden="true"></span> Link</button>'
      +'</div>'
    +'</div><!-- /.modal-content -->'
  +'</div><!-- /.modal-dialog -->'
+'</div><!-- /.modal -->');
	
	
	
	
	
	$('#linkNodeModal').modal('show').on('shown.bs.modal', function () {
		
		//Focus on the optional value field:
		$("#newVal").focus();
		
		//Enable textComplete:
		load_textcomplete('#newVal');
		
		//wire in key code for saving:
		$('#newVal').keypress(function (e) {
	        var code = (e.keyCode ? e.keyCode : e.which);
	        //Ctrl+Enter to save:
	        if (e.ctrlKey && code>=10 && code<=13) {
	        	$( "#modalSubmit" ).click();
	        }
	    });
		
	});
	
	
	//Setup listeners:
	$( "#modalCancel" ).click(function() {
		//Reset:
		resetCoreInputs();
		
		//Re-focus on Add-box, asap:
		$( "#addnode" ).focus();
	});
	
	//Show tooltips:
	$('[data-toggle="tooltip"]').tooltip();
	
	//Listen for submission:
	$( "#modalSubmit" ).click(function() {
				
		var input_data = {
			parent_id: parseInt( $('#originalValue').is(":checked") ? node[0]['node_id'] : child_node_id ),
			child_node_id: parseInt( $('#originalValue').is(":checked") ? child_node_id : node[0]['node_id'] ),
			normal_parenting: ( $('#originalValue').is(":checked") ? 1 : 0 ),
			value: $("#newVal").val(),
		};
		
		//Show message:
		var message = '<div class="list-group-item loading-node"><img src="/img/loader.gif" /> Adding <img src="/img/gem/diamond_48.png" width="20" class="light" />...</div>';
		if(input_data['normal_parenting']){
			$( message ).insertBefore( ".list_input" );
		} else {
			$( message ).insertAfter( $(".lastIN").last() );
		}
		
		
		//Scroll if needed:
		if(!input_data['normal_parenting']){
			//This is OUT, which means we should scroll to the top.
			$('html, body').animate({
		        scrollTop: $(".lastIN").last().offset().top
		    }, 500);
		}
		
		
		$('#linkNodeModal').modal('hide');
		
		//Create node:
		$.post("/api/link_node", input_data, function(data) {
			
			//Update UI to confirm with user:
			$( ".loading-node" ).remove();
			
			//Reset:
			resetCoreInputs();
			
			if(input_data['normal_parenting']){
				$( data.message ).insertBefore( ".list_input" );
				//Re-focus on Add-box, asap:
				$( "#addnode" ).focus();
			} else {
				$( data.message ).insertAfter( $(".lastIN").last() );
			}
			
			//Update Toggle:
			$('[data-toggle="tooltip"]').tooltip();
			
			//Reset SORT setting, if filtering IN or OUT:
			restrat_sort();
			
			//Expand current node:
			node = data.node;
			//TODO Append newly added data to node var in JS. Later when we move app to JS framework
			
			
	    });
	});
}