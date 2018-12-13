<?php


function echo_next_u($page, $limit, $in__children_count)
{
    //We have more child entities than what was listed here.
    //Give user a way to access them:
    echo '<a class="load-more list-group-item" href="javascript:void(0);" onclick="u_load_next_page(' . $page . ')">';

    //Right content:
    echo '<span class="pull-right" style="margin-right: 6px;"><span class="badge badge-secondary"><i class="fas fa-search-plus"></i></span></span>';

    //Regular section:
    $max_entities = (($page + 1) * $limit);
    $max_entities = ($max_entities > $in__children_count ? $in__children_count : $max_entities);
    echo 'Load ' . (($page * $limit) + 1) . '-' . $max_entities . ' from ' . $in__children_count . ' total';

    echo '</a>';
}

function echo_social_profiles($social_profiles)
{
    $ui = null;
    foreach ($social_profiles as $sp) {
        $ui .= '<a href="' . $sp['url'] . '" target="_blank" class="social-link"><i class="' . $sp['fa_icon'] . '"></i></a>';
    }
    return $ui;
}


function echo_min_from_sec($sec_int)
{
    $sec_int = intval($sec_int);
    $min = 0;
    $sec = fmod($sec_int, 60);
    if ($sec_int >= 60) {
        $min = floor($sec_int / 60);
    }
    return ($min ? $min . 'm' : '') . ($sec ? ($min ? ' ' : '') . $sec . 's' : '');
}

function echo_content_url($x_clean_url, $x_type)
{
    if ($x_type == 4) {
        return '<img src="' . $x_clean_url . '" style="max-width:100%" />';
    } elseif ($x_type == 3) {
        return '<audio controls><source src="' . $x_clean_url . '" type="audio/mpeg"></audio>';
    } elseif ($x_type == 2) {
        return '<video width="100%" onclick="this.play()" controls><source src="' . $x_clean_url . '" type="video/mp4"></video>';
    } elseif ($x_type == 5) {
        return '<a href="' . $x_clean_url . '" class="btn btn-primary" target="_blank"><i class="fas fa-cloud-download"></i> Download File</a>';
    } else {
        return false;
    }
}

function echo_embed($url, $full_message = null, $return_array = false, $start_sec = 0, $end_sec = 0)
{


    /*
     * Detects if a URL is from a website that we support an embed widget for it
     *
     * NOTE: Changes to this function requires us to re-calculate all current
     *       values for tr_en_type_id as this could change the equation for those
     *       link types. Change with care...
     *
     * */

    $clean_url = null;
    $embed_html_code = null;
    $prefix_message = null;

    if (!$full_message) {
        $full_message = $url;
    }

    //See if $url has a valid embed video in it, and transform it if it does:
    if (substr_count($url, 'youtube.com/watch?v=') == 1 || substr_count($url, 'youtu.be/') == 1 || substr_count($url, 'youtube.com/embed/') == 1) {

        //Seems to be youtube:
        if (substr_count($url, 'youtube.com/embed/') == 1) {

            //We might have start and end here too!
            $video_id = trim(one_two_explode('youtube.com/embed/', '?', $url));

        } elseif (substr_count($url, 'youtube.com/watch?v=') == 1) {

            $video_id = trim(one_two_explode('youtube.com/watch?v=', '&', $url));

        } elseif (substr_count($url, 'youtu.be/') == 1) {

            $video_id = trim(one_two_explode('youtu.be/', '?', $url));

        }

        //This should be 11 characters!
        if (strlen($video_id) == 11) {

            //Set the Clean URL:
            $clean_url = 'https://www.youtube.com/watch?v=' . $video_id;

            //Inform Master that this video has been sliced:
            if ($start_sec || $end_sec) {
                $embed_html_code .= '<div class="video-prefix"><i class="fab fa-youtube"></i> Watch ' . (($start_sec && $end_sec) ? 'this <b>' . echo_min_from_sec(($end_sec - $start_sec)) . '</b> video clip' : 'from <b>' . ($start_sec ? echo_min_from_sec($start_sec) : 'start') . '</b> to <b>' . ($end_sec ? echo_min_from_sec($end_sec) : 'end') . '</b>') . ':</div>';
            }

            $embed_html_code .= '<div class="yt-container video-sorting" style="margin-top:5px;"><iframe src="//www.youtube.com/embed/' . $video_id . '?theme=light&color=white&keyboard=1&autohide=2&modestbranding=1&showinfo=0&rel=0&iv_load_policy=3&start=' . $start_sec . ($end_sec ? '&end=' . $end_sec : '') . '" frameborder="0" allowfullscreen class="yt-video"></iframe></div>';

        }

    } elseif (substr_count($url, 'vimeo.com/') == 1) {

        //Seems to be Vimeo:
        $video_id = trim(one_two_explode('vimeo.com/', '?', $url));

        //This should be an integer!
        if (intval($video_id) == $video_id) {
            $clean_url = 'https://vimeo.com/' . $video_id;
            $embed_html_code = '<div class="yt-container video-sorting" style="margin-top:5px;"><iframe src="https://player.vimeo.com/video/' . $video_id . '?title=0&byline=0" class="yt-video" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
        }

    } elseif (substr_count($url, 'wistia.com/medias/') == 1) {

        //Seems to be Wistia:
        $video_id = trim(one_two_explode('wistia.com/medias/', '?', $url));
        $clean_url = trim(one_two_explode('', '?', $url));
        $embed_html_code = '<script src="https://fast.wistia.com/embed/medias/' . $video_id . '.jsonp" async></script><script src="https://fast.wistia.com/assets/external/E-v1.js" async></script><div class="wistia_responsive_padding video-sorting" style="padding:56.25% 0 0 0;position:relative;"><div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;"><div class="wistia_embed wistia_async_' . $video_id . ' seo=false videoFoam=true" style="height:100%;width:100%">&nbsp;</div></div></div>';

    }

    if ($return_array) {

        //Return all aspects of this parsed URL:
        return array(
            'status' => ($embed_html_code ? 1 : 0),
            'embed_code' => $embed_html_code,
            'clean_url' => $clean_url,
        );

    } else {
        //Just return the embed code:
        if ($embed_html_code) {
            return trim(str_replace($url, $embed_html_code, $full_message));
        } else {
            //Not matched with an embed rule:
            return false;
        }
    }
}


function echo_i($i, $en_name = null, $fb_format = false)
{

    //HACK: Make these two variables inter-changeable:
    if (isset($i['tr_in_child_id']) && $i['tr_in_child_id'] > 0 && !isset($i['tr_in_child_id'])) {
        $i['tr_in_child_id'] = $i['tr_in_child_id'];
    } elseif (isset($i['tr_in_child_id']) && $i['tr_in_child_id'] > 0 && !isset($i['tr_in_child_id'])) {
        $i['tr_in_child_id'] = $i['tr_in_child_id'];
    }

    $CI =& get_instance();
    $button_url = (isset($i['button_url']) ? $i['button_url'] : null);
    $button_title = (isset($i['button_title']) ? $i['button_title'] : null);
    $command = null;
    $is_intent = ($CI->uri->segment(1) == 'intents');
    $is_entity = ($CI->uri->segment(1) == 'entities');
    $is_public = (!$is_intent && !$is_entity); //The public landing pages that users use to get started
    $is_focus_entity = ($is_entity && $CI->uri->segment(2) == $i['tr_en_parent_id']);
    $ui = null;
    $original_cs = array();


    if ($fb_format) {
        //This is what will be returned to be sent via messenger:
        $fb_message = array();
    } else {
        //HTML format:
        $i['tr_content'] = nl2br($i['tr_content']);
        $ui .= '<div class="i_content">';
    }

    //Is it being displayed under entities? Show the original intent as well:
    if ($is_entity && !$fb_format) {

        $original_cs = $CI->Db_model->in_fetch(array(
            'in_id' => $i['tr_in_child_id'],
        ));
        if (count($original_cs) > 0) {

            $ui .= '<div class="entities-msg">';
            $ui .= '<span class="pull-right" style="margin:6px 10px 0 0;">';
            $ui .= '<span data-toggle="tooltip" title="This is the ' . echo_ordinal($i['tr_order']) . ' message for this intent" data-placement="left" class="underdot" style="padding-bottom:4px;">' . echo_ordinal($i['tr_order']) . '</span> ';
            $ui .= '<span>' . echo_status('tr_status', $i['tr_status'], 1, 'left') . '</span> ';
            $ui .= '<a href="/intents/' . $i['tr_in_child_id'] . '#loadmessages-' . $i['tr_in_child_id'] . '"><span class="badge badge-primary" style="display:inline-block; margin-left:3px; width:40px;"><i class="fas fa-sign-out-alt rotate90"></i></span></a>';
            $ui .= '</span>';
            $ui .= '<h4><i class="fas fa-hashtag" style="font-size:1em;"></i> ' . $original_cs[0]['in_outcome'] . '</h4>';
            $ui .= '<div>';

        }
    }


    //Does this have a entity reference?
    if (isset($i['tr_en_parent_id']) && $i['tr_en_parent_id'] > 0) {

        //This message has a referenced entity
        //See if that entity has a URL:
        $us = $CI->Db_model->en_fetch(array(
            'en_id' => $i['tr_en_parent_id'],
        ), array('skip_en__parents', 'u__urls'));

        if (count($us) > 0) {

            //Is there a slice command?
            if ($fb_format) {

                //Show an option to open action plan:
                $i['tr_content'] = str_replace('@' . $i['tr_en_parent_id'], $us[0]['en_name'], $i['tr_content']);

                if (substr_count($i['tr_content'], '/slice') > 0) {
                    $time_range = explode(':', one_two_explode('/slice:', ' ', $i['tr_content']), 2);
                    $i['tr_content'] = str_replace('/slice:' . $time_range[0] . ':' . $time_range[1], '', $i['tr_content']);
                }

            } else {

                //HTML Format:
                $time_range = array();
                $button_title = 'Open Entity';
                $button_url = '/entities/' . $us[0]['en_id'] . '?skip_header=1'; //To loadup the entity
                $embed_html_code = null;

                if (substr_count($i['tr_content'], '/slice') > 0) {

                    $time_range = explode(':', one_two_explode('/slice:', ' ', $i['tr_content']), 2);

                    //Try finding a compatible URL for slicing:
                    foreach ($us[0]['u__urls'] as $x) {
                        if ($x['x_type'] == 1 && substr_count($x['x_url'], 'youtube.com') > 0) {
                            $embed_html_code = '<div style="margin-top:7px;">' . echo_embed($x['x_clean_url'], $x['x_clean_url'], false, $time_range[0], $time_range[1]) . '</div>';
                            break;
                        }
                    }

                    //Remove slice command:
                    $i['tr_content'] = str_replace('/slice:' . $time_range[0] . ':' . $time_range[1], '', $i['tr_content']);


                } elseif (!$is_focus_entity) {

                    //So we did not have a slice command and this is an HTML request for a non-entity page
                    //Note: The reason we don't need these for entities is that they already list all URLs with embed codes, so no need to repeat
                    //Let's see if we have any other embeddable content that we can append to message:

                    foreach ($us[0]['u__urls'] as $x) {
                        //Find all the ways we could use this URL:
                        if ($x['x_type'] == 0) {
                            if ($is_public) {
                                //Replace the name:

                            } else {
                                //Regular website:
                                $embed_html_code .= '<div style="margin-top:7px;"><a href="' . $x['x_url'] . '" target="_blank"><span class="url_truncate"><i class="fas fa-atlas" style="margin-right:3px;"></i>' . echo_clean_url($x['x_url']) . '</span></a></div>';
                            }
                        } elseif ($x['x_type'] == 1) {
                            $embed_html_code .= '<div style="margin-top:7px;">' . echo_embed($x['x_clean_url'], $x['x_clean_url']) . '</div>';
                        } elseif ($x['x_type'] > 1) {
                            $embed_html_code .= '<div style="margin-top:7px;">' . echo_content_url($x['x_clean_url'], $x['x_type']) . '</div>';
                        }
                    }
                }


                if ($is_intent || ($is_entity && !$is_focus_entity)) {

                    //HTML format:
                    $i['tr_content'] = str_replace('@' . $i['tr_en_parent_id'], ' <a href="javascript:void(0);" onclick="url_modal(\'' . $button_url . '\')">' . $us[0]['en_name'] . '</a>', $i['tr_content']);

                } else {

                    //HTML format:
                    //TODO Fetch text description from parent entity notes
                    $entity_title = (0 ? '<span data-toggle="tooltip" title="' . 'notes here' . '" data-placement="top" class="underdot">' . $us[0]['en_name'] . '</span>' : $us[0]['en_name']);
                    $i['tr_content'] = str_replace('@' . $i['tr_en_parent_id'], $entity_title . ' ', $i['tr_content']);

                }

                //Did we have an embed code to be attached?
                if ($embed_html_code) {
                    $i['tr_content'] .= $embed_html_code;
                }

            }

        }
    }


    //Do we have any commands?
    if ($en_name && substr_count($i['tr_content'], '/firstname') > 0) {
        //Tweak the name:
        $command = '/firstname';
        $i['tr_content'] = str_replace('/firstname', one_two_explode('', ' ', $en_name), $i['tr_content']);
    }


    if (substr_count($i['tr_content'], '/open_actionplan') > 0 && isset($i['tr_tr_parent_id']) && $i['tr_tr_parent_id'] > 0 && isset($i['tr_in_child_id']) && $i['tr_in_child_id'] > 0) {
        $button_title = 'Open in 🚩Action Plan';
        $command = '/open_actionplan';
        $button_url = 'https://mench.com/my/actionplan/' . $i['tr_tr_parent_id'] . '/' . $i['tr_in_child_id'] . '?is_from_messenger=1';
    } elseif (substr_count($i['tr_content'], '/open_myaccount') > 0) {
        $button_title = 'Open 👤 My Account';
        $command = '/open_myaccount';
        $button_url = 'https://mench.com/my/account?is_from_messenger=1';
    }


    if (substr_count($i['tr_content'], '/typing') > 0) {
        $command = '/typing';
        if ($fb_format) {
            //TODO include sender actions https://developers.facebook.com/docs/messenger-platform/send-messages/sender-actions/
        } else {
            //HTML format:
            $i['tr_content'] = str_replace($command, '<img src="/img/typing.gif" height="35px" />', $i['tr_content']);
        }
    }


    if (substr_count($i['tr_content'], '/resetpassurl') > 0 && isset($i['tr_en_child_id'])) {
        //append their My Account Button/URL:
        $timestamp = time();
        $button_title = '👉 Set New Password';
        $button_url = 'https://mench.com/my/reset_pass?en_id=' . $i['tr_en_child_id'] . '&timestamp=' . $timestamp . '&p_hash=' . md5($i['tr_en_child_id'] . $CI->config->item('password_salt') . $timestamp);
        $command = '/resetpassurl';
    }


    if ($command || $button_url) {

        //Append the button to the message:
        if ($fb_format) {

            //Remove the command from the message:
            $i['tr_content'] = trim(str_replace($command, '', $i['tr_content']));

            //Return Messenger array:
            $fb_message = array(
                'attachment' => array(
                    'type' => 'template',
                    'payload' => array(
                        'template_type' => 'button',
                        'text' => $i['tr_content'],
                        'buttons' => array(
                            array(
                                'title' => $button_title,
                                'type' => 'web_url',
                                'url' => $button_url,
                                'webview_height_ratio' => 'tall',
                                'webview_share_button' => 'hide',
                                'messenger_extensions' => true,
                            ),
                        ),
                    ),
                ),
                'metadata' => 'system_logged', //Prevents from duplicate logging via the echo webhook
            );

        } else {


            if ($button_url && $button_title) {
                //HTML format replaces the button with the command:
                $i['tr_content'] = trim(str_replace($command, '<div class="msg" style="padding-top:15px;"><a href="' . $button_url . '" target="_blank"><b>' . $button_title . '</b></a></div>', $i['tr_content']));
            }

            //Return HTML code:
            $ui .= '<div class="msg">' . $i['tr_content'] . '</div>';

        }

    } else {

        //Regular without any special commands in it!
        //Now return the template:
        if ($fb_format) {

            //Messenger array:
            $fb_message = array(
                'text' => $i['tr_content'],
                'metadata' => 'system_logged', //Prevents from duplicate logging via the echo webhook
            );

            //Should we append a Quick reply to this message?
            if (isset($i['quick_replies']) && count($i['quick_replies']) > 0) {
                $fb_message['quick_replies'] = $i['quick_replies'];
            }

        } else {
            //HTML format:
            $ui .= '<div class="msg">' . $i['tr_content'] . '</div>';
        }

    }


    //Log transaction if Facebook and return:
    if ($fb_format) {

        if (count($fb_message) > 0) {
            //Return Facebook Message to be sent out:
            return $fb_message;
        } else {
            //Should not happen!
            return false;
        }

    } else {

        //This must be HTML if we're still here, return:
        if (count($original_cs) > 0) {
            $ui .= '</div></div>';
        }

        $ui .= '</div>';
        return $ui;

    }
}


function echo_message($i)
{

    $CI =& get_instance();
    $tr_content_max = $CI->config->item('tr_content_max');
    $en_all_4485 = $CI->config->item('en_all_4485');

    $ui = '';
    $ui .= '<div class="list-group-item is-msg is_level2_sortable all_msg msg_' . $i['tr_en_type_id'] . '" id="ul-nav-' . $i['tr_id'] . '" iid="' . $i['tr_id'] . '">';
    $ui .= '<div style="overflow:visible !important;">';

    //Type & Delivery Method:
    $ui .= '<div class="edit-off text_message" id="msg_body_' . $i['tr_id'] . '" style="margin:2px 0 0 0;">';
    $ui .= echo_i($i);
    $ui .= '</div>';


    //Text editing:
    $ui .= '<textarea onkeyup="changeMessageEditing(' . $i['tr_id'] . ')" name="tr_content" id="message_body_' . $i['tr_id'] . '" class="edit-on hidden msg msgin algolia_search" placeholder="Write Message..." style="margin-top: 4px;">' . $i['tr_content'] . '</textarea>';

    //Editing menu:
    $ui .= '<ul class="msg-nav">';

    $ui .= '<li class="edit-off msg_status" style="margin: 0 1px 0 -1px;">' . echo_status('tr_en_type_id', $i['tr_en_type_id'], 1, 'right') . '</li>';
    $ui .= '<li class="edit-on hidden"><span id="charNumEditing' . $i['tr_id'] . '">0</span>/' . $tr_content_max . '</li>';

    $ui .= '<li class="edit-off" style="margin: 0 0 0 8px;"><span class="on-hover"><i class="fas fa-bars sort_message" iid="' . $i['tr_id'] . '" style="color:#2f2739;"></i></span></li>';
    $ui .= '<li class="edit-off" style="margin-right: 10px; margin-left: 6px;"><span class="on-hover"><a href="javascript:i_archive(' . $i['tr_id'] . ');"><i class="fas fa-trash-alt" style="margin:0 7px 0 5px;"></i></a></span></li>';
    $ui .= '<li class="edit-off" style="margin-left:-4px;"><span class="on-hover"><a href="javascript:msg_start_edit(' . $i['tr_id'] . ',' . $i['tr_en_type_id'] . ');"><i class="fas fa-pen-square"></i></a></span></li>';
    //Right side reverse:
    $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-primary" href="javascript:message_save_updates(' . $i['tr_id'] . ',' . $i['tr_en_type_id'] . ');" style="text-decoration:none; font-weight:bold; padding: 1px 8px 4px;"><i class="fas fa-check"></i></a></li>';
    $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-hidden" href="javascript:msg_cancel_edit(' . $i['tr_id'] . ');"><i class="fas fa-times" style="color:#2f2739"></i></a></li>';

    //Show drop down for message type adjustment:
    $ui .= '<li class="pull-right edit-on hidden">';
    $ui .= '<select id="en_all_4485_'.$i['tr_id'].'">';
    foreach ($en_all_4485 as $tr_en_type_id => $value) {
        $ui .= '<option value="'.$tr_en_type_id.'">'.$value['en_name'].'</option>';
    }
    $ui .= '</select>';
    $ui .= '</li>';

    $ui .= '<li class="pull-right edit-updates"></li>'; //Show potential errors

    $ui .= '</ul>';

    $ui .= '</div>';
    $ui .= '</div>';

    return $ui;
}


function echo_cover($u, $img_class = null, $return_anyways = false, $tooltip_content = null)
{
    if ($u['u_cover_x_id'] > 0 && isset($u['x_url'])) {
        return '<img src="' . $u['x_url'] . '" class="' . $img_class . '" ' . $tooltip_content . ' />';
    } elseif ($return_anyways) {
        return '<i class="fas fa-at" ' . $tooltip_content . ' ></i>';
    } else {
        return null;
    }
}

function echo_link($text)
{
    return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1" target="_blank"><u>$1</u></a>', $text);
}

function echo_number($number, $micro = true, $fb_format = false)
{

    //Let's see if we need to apply special formatting:
    $formatting = null;

    if ($number > 0 && $number < 1) {

        $original_format = $number; //Keep as is

        //Decimal number, format based on decimal points:
        if ($number < 0.000001) {
            $formatting = array(
                'multiplier' => 1000000000,
                'decimals' => 0,
                'micro_1' => 'n',
                'micro_0' => ' Nano',
            );
        } elseif ($number < 0.001) {
            $formatting = array(
                'multiplier' => 1000000,
                'decimals' => 0,
                'micro_1' => 'µ',
                'micro_0' => ' Micro',
            );
        } elseif ($number < 0.01) {
            $formatting = array(
                'multiplier' => 100000,
                'decimals' => 0,
                'micro_1' => 'm',
                'micro_0' => ' Milli',
            );
        } else {
            //Must be cents
            $formatting = array(
                'multiplier' => 100,
                'decimals' => 0,
                'micro_1' => 'c',
                'micro_0' => ' Cent',
            );
        }

    } elseif ($number>=1000) {

        $original_format = number_format($number); //Add commas

        if ($number >= 1000000000) {
            $formatting = array(
                'multiplier' => (1 / 1000000000),
                'decimals' => 1,
                'micro_1' => 'B',
                'micro_0' => ' Billion',
            );
        } elseif ($number >= 10000000) {
            $formatting = array(
                'multiplier' => (1 / 1000000),
                'decimals' => 0,
                'micro_1' => 'M',
                'micro_0' => ' Million',
            );
        } elseif ($number >= 1000000) {
            $formatting = array(
                'multiplier' => (1 / 1000000),
                'decimals' => 1,
                'micro_1' => 'M',
                'micro_0' => ' Million',
            );
        } elseif ($number >= 10000) {
            $formatting = array(
                'multiplier' => (1 / 1000),
                'decimals' => 0,
                'micro_1' => 'k',
                'micro_0' => ' Thousand',
            );
        } elseif ($number >= 1000) {
            $formatting = array(
                'multiplier' => (1 / 1000),
                'decimals' => 1,
                'micro_1' => 'k',
                'micro_0' => ' Thousand',
            );
        }

    }


    if ($formatting) {

        //See what to show:
        $rounded = round(($number * $formatting['multiplier']), $formatting['decimals']);
        $append = $formatting['micro_' . (int)$micro] . (!$micro ? echo__s($rounded) : '');

        if ($fb_format) {
            //Messaging format, show using plain text:
            return $rounded . $append . ' (' . $original_format . ')';
        } else {
            //HTML, so we can show Tooltip:
            return '<span title="' . $original_format . '" data-toggle="tooltip" data-placement="top" class="underdot">' . $rounded . $append . '</span>';
        }

    } else {

        return $number;

    }
}


function echo_e($e)
{

    $CI =& get_instance();

    //Display the item
    $ui = '<div class="list-group-item">';

    //Right content:
    $ui .= '<span class="pull-right">';

    //Show user notification level:
    $ui .= ' <span>' . echo_status('tr_status', $e['tr_status'], true, 'left') . '</span> ';

    //Lets go through all references to see what is there:
    foreach ($CI->config->item('engagement_references') as $engagement_field => $er) {
        if (intval($e[$engagement_field]) > 0) {
            //Yes we have a value here:
            $ui .= echo_object($er['object_code'], $e[$engagement_field], $engagement_field, $er['name']);
        }
    }

    if (strlen($e['tr_metadata']) > 0) {
        $ui .= '<a href="/adminpanel/li_list_blob/' . $e['tr_id'] . '" class="badge badge-primary grey" target="_blank" data-toggle="tooltip" title="Analyze Engagement JSON Blob in a new window" data-placement="left"><i class="fas fa-search-plus"></i></a>';
    }

    $ui .= '</span>';

    //What type of main content do we have, if any?
    $main_content = null;
    $main_content_title = null;
    if (strlen($e['tr_content']) > 0) {
        $main_content = format_tr_content($e['tr_content']);
    } elseif ($e['e_tr_id'] > 0) {
        //Fetch message conent:
        $matching_messages = $CI->Db_model->i_fetch(array(
            'tr_id' => $e['e_tr_id'],
        ));
        if (count($matching_messages) > 0) {
            $main_content_title = ' Message #' . $e['e_tr_id'];
            $main_content = echo_i($matching_messages[0]);
        }
    }


    $ui .= '<b>' . str_replace('Log ', '', $e['in_outcome']) . '</b>';
    $ui .= ' <span data-toggle="tooltip" data-placement="right" title="' . $e['tr_timestamp'] . ' Engagement #' . $e['tr_id'] . '" style="font-size:0.8em;">' . echo_diff_time(strtotime($e['tr_timestamp'])) . ' ago</span> ';
    $ui .= $main_content_title;

    //Do we have a message?
    $ui .= '<div class="e-msg ' . ($main_content && strlen($main_content) > 0 ? '' : 'hidden') . '">';
    $ui .= $main_content;
    $ui .= '</div>';


    $ui .= '</div>';

    return $ui;
}

function echo_w_matrix($w)
{

    //Assumes w_stats has been added to w_fetch so we can display proper stats here...

    $CI =& get_instance();
    //This function will be called from 3 areas:
    $is_intent = ($CI->uri->segment(1) == 'intents');
    $is_entity = ($CI->uri->segment(1) == 'entities');
    $is_adminpanel = ($CI->uri->segment(1) == 'adminpanel');
    $w_title = ''; //Build as we go depending on which view is loaded...


    //Display the item
    $ui = '<div class="list-group-item" id="w_div_' . $w['tr_id'] . '">';

    //Right content:
    $ui .= '<span class="pull-right">';

    //Show subscription time:
    $ui .= ' <span data-toggle="tooltip" data-placement="top" title="Master initiated subscription on ' . $w['w_timestamp'] . '" style="font-size:0.8em;">' . echo_diff_time($w['w_timestamp']) . '</span> ';


    //Show user notification level:
    $ui .= ' <span>' . echo_status('tr_status', $w['tr_status'], true, 'left') . '</span> ';


    //Then customize based on request location:
    if ($is_intent || $is_adminpanel) {

        //Show user who has subscribed:
        $user_ws = $CI->Db_model->w_fetch(array(
            'tr_en_parent_id' => $w['tr_en_parent_id'],
        ));

        if (!isset($w['en__parents'])) {
            //Fetch parents at this point:
            $w['en__parents'] = $CI->Db_model->tr_parent_fetch(array(
                'tr_en_child_id' => $w['tr_en_parent_id'],
                'tr_status >=' => 0, //Pending or Active
                'en_status >=' => 0, //Pending or Active
            ));
        }

        $w_title .= echo_cover($w, 'micro-image', 1) . ' ';
        $w_title .= '<span class="en_name en_name_' . $w['en_id'] . '">' . $w['en_name'] . '</span>';
        //Loop through parents and show those that have en_icon set:
        foreach ($w['en__parents'] as $in_u) {
            if (strlen($in_u['en_icon']) > 0) {
                $w_title .= ' &nbsp;<span data-toggle="tooltip" title="' . $in_u['en_name'] . (strlen($in_u['tr_content']) > 0 ? ': ' . $in_u['tr_content'] : '') . '" data-placement="top" class="en_icon_child_' . $in_u['en_id'] . '">' . $in_u['en_icon'] . '</span>';
            }
        }

        //Engagements made by subscriber:
        $ui .= '<a href="#wengagements-' . $w['tr_en_parent_id'] . '-' . $w['tr_id'] . '" onclick="load_u_engagements(' . $w['tr_en_parent_id'] . ',' . $w['tr_id'] . ')" class="badge badge-secondary" style="width:40px; margin-right:2px;" data-toggle="tooltip" data-placement="left" title="' . $w['w_stats']['e_all_count'] . ' engagements"><span class="btn-counter">' . $w['w_stats']['e_all_count'] . ($w['w_stats']['e_all_count'] == $CI->config->item('max_counter') ? '+' : '') . '</span><i class="fas fa-atlas"></i></a>';

        //Link to subscriber, but count total subscriptions first:
        $ui .= '<a href="/entities/' . $w['tr_en_parent_id'] . '" class="badge badge-secondary" style="width:40px; margin-right:2px;" data-toggle="tooltip" data-placement="top" title="Master has ' . count($user_ws) . ' total subsciptions"><span class="btn-counter">' . count($user_ws) . '</span><i class="fas fa-sign-out-alt rotate90"></i></a>';

    }


    //Number of intents in Master Action Plan:
    $ui .= '<a href="#wactionplan-' . $w['tr_id'] . '-' . $w['tr_en_parent_id'] . '" onclick="load_w_actionplan(' . $w['tr_id'] . ',' . $w['tr_en_parent_id'] . ')" class="badge badge-primary" style="width:40px; margin-right:2px;" data-toggle="tooltip" data-placement="left" title="' . $w['w_stats']['k_count_done'] . '/' . ($w['w_stats']['k_count_done'] + $w['w_stats']['k_count_undone']) . ' intents are marked as complete. Click to open Action Plan."><span class="btn-counter">' . (($w['w_stats']['k_count_undone'] + $w['w_stats']['k_count_done']) > 0 ? number_format(($w['w_stats']['k_count_done'] / ($w['w_stats']['k_count_undone'] + $w['w_stats']['k_count_done']) * 100), 0) . '%' : '0%') . '</span><i class="fas fa-flag" style="font-size:0.85em;"></i></a>';


    if ($is_entity || $is_adminpanel) {

        //Link to subscription's main intent:
        $intent_ws = $CI->Db_model->w_fetch(array(
            'tr_in_child_id' => $w['in_id'],
        ));
        $ui .= '<a href="/intents/' . $w['in_id'] . '" class="badge badge-primary" style="width:40px; margin-right:2px;" data-toggle="tooltip" data-placement="left" title="Open subscribed intention to ' . $w['in_outcome'] . ' with ' . count($intent_ws) . ' subscriptions"><span class="btn-counter">' . count($intent_ws) . '</span><i class="fas fa-sign-in-alt"></i></a>';

        $w_title .= ($is_adminpanel ? '<div style="margin: 3px 0 0 3px;"><i class="fas fa-hashtag"></i> ' : '');
        $w_title .= '<span class="w_intent_' . $w['tr_id'] . '">' . $w['in_outcome'] . '</span>';
        $w_title .= ($is_adminpanel ? '</div>' : '');
    }


    $ui .= '</span>';


    //Start with subscription status:
    $ui .= $w_title;
    $ui .= '</div>';


    return $ui;
}


function echo_w_masters($w)
{
    $ui = '<a href="/my/actionplan/' . $w['tr_id'] . '/' . $w['tr_in_child_id'] . '" class="list-group-item">';
    $ui .= '<span class="pull-right">';
    $ui .= '<span class="badge badge-primary"><i class="fas fa-angle-right"></i></span>';
    $ui .= '</span>';
    $ui .= echo_status('tr_status', $w['tr_status'], 1, 'right');
    $ui .= ' ' . $w['in_outcome'];
    $ui .= '  ' . $w['in__tree_in_count'];
    $ui .= ' &nbsp;<i class="fas fa-clock"></i> ' . echo_hours_range($w, 1);
    $ui .= '</a>';
    return $ui;
}


function echo_k_matrix($k)
{

    //NOTE: Assumes the subscription, its intent and entity subscriber are loaded in $k

    $CI =& get_instance();

    //Fetch some additional subscription stats:
    $user_ws = $CI->Db_model->w_fetch(array(
        'tr_en_parent_id' => $k['en_id'],
    ));
    $intent_ws = $CI->Db_model->w_fetch(array(
        'tr_in_child_id' => $k['in_id'],
    ));


    //Display the item
    $ui = '<div class="list-group-item">';

    //Right content:
    $ui .= '<span class="pull-right">';

    //Show submission time:
    $ui .= ' <span data-toggle="tooltip" data-placement="top" title="Submitted on  ' . $k['tr_timestamp'] . '" style="font-size:0.8em;">' . echo_diff_time($k['tr_timestamp']) . '</span> ';


    //Link to subscriber, but count total subscriptions first:
    $ui .= '<a href="/entities/' . $k['en_id'] . '" target="_parent" class="badge badge-secondary" style="width:40px; margin-right:2px;" data-toggle="tooltip" data-placement="left" title="Open Subscriber ' . $k['en_name'] . ' with ' . count($user_ws) . ' subscriptions"><span class="btn-counter">' . count($user_ws) . '</span><i class="fas fa-sign-out-alt rotate90"></i></a>';

    //Link to subscription's main intent:
    $ui .= '<a href="/intents/' . $k['in_id'] . '" target="_parent" class="badge badge-primary" style="width:40px;" data-toggle="tooltip" data-placement="left" title="Open subscribed intention to ' . $k['in_outcome'] . ' with ' . count($intent_ws) . ' subscriptions"><span class="btn-counter">' . count($intent_ws) . '</span><i class="fas fa-sign-in-alt"></i></a>';

    $ui .= '</span>';

    //Show user who has subscribed:
    $ui .= echo_cover($k, 'micro-image', 1) . ' ';
    $ui .= $k['en_name'];
    $ui .= echo_status('tr_status', $k['tr_status'], true, 'top') . ' ' . $k['in_outcome'];

    if (strlen($k['tr_content']) > 0) {
        $ui .= '<div class="e-msg ">' . $k['tr_content'] . '</div>';
    }

    $ui .= '</div>';


    return $ui;
}


function echo_k($k, $is_parent, $in_is_any_tr_in_parent_id = 0)
{

    $ui = '<a href="' . ($in_is_any_tr_in_parent_id ? '/my/choose_any_path/' . $k['tr_id'] . '/' . $in_is_any_tr_in_parent_id . '/' . $k['in_id'] . '/' . md5($k['tr_id'] . 'kjaghksjha*(^' . $k['in_id'] . $in_is_any_tr_in_parent_id) : '/my/actionplan/' . $k['tr_tr_parent_id'] . '/' . $k['in_id']) . '" class="list-group-item">';

    //Different pointer position based on direction:
    if ($is_parent) {
        $ui .= '<span class="pull-left">';
        $ui .= '<span class="badge badge-primary fr-bgd"><i class="fas fa-angle-left"></i></span>';
        $ui .= '</span>';
    } else {
        $ui .= '<span class="pull-right">';
        $ui .= '<span class="badge badge-primary fr-bgd">' . ($in_is_any_tr_in_parent_id ? 'Select <i class="fas fa-check-circle"></i>' : '<i class="fas fa-angle-right"></i>') . '</span>';
        $ui .= '</span>';

        //For children show icon:
        if ($in_is_any_tr_in_parent_id) {
            //Radio button to indicate a single selection:
            $ui .= '<span class="status-label" style="padding-bottom:1px;"><i class="fal fa-circle"></i> </span>';
        } else {
            //Proper status:
            $ui .= echo_status('tr_status', $k['tr_status'], 1, 'right');
        }
    }

    $ui .= ' ' . $k['in_outcome'];
    if (strlen($k['tr_content']) > 0) {
        $ui .= ' <i class="fas fa-edit"></i> ' . htmlentities($k['tr_content']);
    }

    $ui .= '</a>';

    return $ui;
}


function echo_clean_url($url)
{
    return rtrim(str_replace('http://', '', str_replace('https://', '', str_replace('www.', '', $url))), '/');
}


//This also has an equal Javascript function echo_js_hours() which we want to make sure has more/less the same logic:
function echo_hours($seconds, $micro = false)
{
    if ($seconds <= 0) {
        return '0' . ($micro ? 'm' : ' Minutes ');
    } elseif ($seconds <= 5400) {
        return round($seconds / 60) . ($micro ? 'm' : ' Minutes');
    } else {
        //Roundup the hours:
        $hours = round($seconds / 3600);
        return $hours . ($micro ? 'h' : ' Hour' . echo__s($hours));
    }
}

function echo_contents($c, $fb_format = 0)
{

    //Do we have anything to return?
    if (strlen($c['in__tree_contents']) <= 0) {
        return false;
    }


    //Make initial variables:
    $c['in__tree_contents'] = unserialize($c['in__tree_contents']);

    if (count($c['in__tree_contents']) < 1) {
        return false;
    }

    $all_count = 0;
    foreach ($c['in__tree_contents'] as $type_en_id => $current_us) {
        $all_count += count($current_us);
    }

    if ($all_count > 0) {

        $visible_ppl = 3;
        $type_count = 0;
        $type_all_count = count($c['in__tree_contents']);
        $CI =& get_instance();
        $en_all_3000 = $CI->config->item('en_all_3000');
        $has_matrix_access = auth(array(1308), 0);
        //More than 3:
        $text_overview = '';
        foreach ($c['in__tree_contents'] as $type_id => $current_us) {

            if ($type_count > 0) {
                if (($type_count + 1) >= $type_all_count) {
                    $text_overview .= ' &';
                } else {
                    $text_overview .= ',';
                }
            }

            //Show category:
            $cat_contribution = count($current_us) . ' ' . $en_all_3000[$type_id]['en_name'] . echo__s(count($current_us));
            if ($fb_format) {

                $text_overview .= ' ' . $cat_contribution;

            } else {

                $text_overview .= ' <span class="show_type_' . $type_id . '"><a href="javascript:void(0);" onclick="$(\'.show_type_' . $type_id . '\').toggle()" style="text-decoration:underline; display:inline-block;">' . $cat_contribution . '</a></span><span class="show_type_' . $type_id . '" style="display:none;">';

                //We only show details on our website's HTML landing pages:
                $count = 0;
                foreach ($current_us as $u) {

                    if ($count > 0) {
                        if (($count + 1) >= count($current_us)) {
                            $text_overview .= ' &';
                        } else {
                            $text_overview .= ',';
                        }
                    }

                    $text_overview .= ' ';

                    if ($has_matrix_access) {
                        $text_overview .= '<a href="/entities/' . $u['en_id'] . '">';
                    }

                    //TODO fetch text parent entity notes to share description.
                    if (0) {
                        //Has description, show it here:
                        $text_overview .= ' <span data-toggle="tooltip" title="' . 'notes here' . '" data-placement="top" class="underdot">' . $u['en_name'] . '</span>';
                    } else {
                        //Just the name:
                        $text_overview .= $u['en_name'];
                    }

                    if ($has_matrix_access) {
                        $text_overview .= '</a>';
                    }
                    $count++;
                }
                $text_overview .= '</span>';
            }
            $type_count++;
        }
    }

    //Return results:
    if ($all_count == 0) {
        return false;
    }


    $pitch = 'Action Plan references' . $text_overview . ' from industry experts.';
    if ($fb_format) {
        return '📚 ' . $pitch . "\n";
    } else {
        //HTML format
        $id = 'ContentReferences';
        return '<div class="panel-group" id="open' . $id . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $id . '">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open' . $id . '" href="#collapse' . $id . '" aria-expanded="false" aria-controls="collapse' . $id . '">
                        <i class="fas" style="transform:none !important;">📚</i> ' . $all_count . ' Reference' . echo__s($all_count) . '<i class="fas fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse' . $id . '" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading' . $id . '">
                <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">' . $pitch . '</div>
            </div>
        </div></div>';
    }
}

function echo_pa_lets()
{
    $options = array(
        'You can give me a command by starting a sentence with "Lets", for example: [Lets land a dream job], [Lets book new interviews] or [Lets create a great resume].',
        'You can command me using "Lets", for example: [Lets create a cover letter] or [Lets do better at interviews].', //[Lets get hired],
    );
    return $options[rand(0, (count($options) - 1))];
}



function echo_pa_saved()
{
    //Informs the user that their answer is saved!
    $options = array(
        'Got it 👍',
        'Noted',
        'Ok sweet',
        'Nice answer',
        'Nice 👍',
        'Gotcha 🙌',
        'Fabulous',
        'Confirmed',
        '👌',
    );
    return $options[rand(0, (count($options) - 1))];
}

function echo_pa_oneway()
{
    //Informs the user that the PA cannot speak, unless you give it a specific command like Lets
    $options = array(
        'I am not designed to respond to custom text messages. I can understand you only when you choose one of the multiple-choice options I provide.',
        'What was that? I would only understand if you choose one of the multiple-choice options I provide.',
        'I did not get that as I cannot respond to your text messages. Select multiple-choice option to continue...',
    );
    return $options[rand(0, (count($options) - 1))];
}

function echo_costs($c, $fb_format = 0)
{

    if ($c['in__tree_max_cost'] <= 0) {
        return false;
    } elseif (round($c['in__tree_max_cost']) == round($c['in__tree_min_cost']) || $c['in__tree_min_cost'] == 0) {
        //Single price:
        $price_range = '$' . round($c['in__tree_max_cost']) . ' USD';
    } else {
        //Price range:
        $price_range = 'between $' . round($c['in__tree_min_cost']) . ' to $' . round($c['in__tree_max_cost']) . ' USD';
    }


    $pitch = 'Action Plan recommends ' . $price_range . ' in third-party product purchases.';
    if ($fb_format) {
        return '💸 ' . $pitch . "\n";
    } else {
        //HTML format
        $id = 'CostForcast';
        return '<div class="panel-group" id="open' . $id . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $id . '">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open' . $id . '" href="#collapse' . $id . '" aria-expanded="false" aria-controls="collapse' . $id . '">
                        <i class="fas" style="transform:none !important;">💸</i> ' . ucwords($price_range) . '<i class="fas fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse' . $id . '" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading' . $id . '">
                <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">' . $pitch . '</div>
            </div>
        </div></div>';
    }
}

function echo_intent_overview($c, $fb_format = 0)
{

    $pitch = 'Action Plan contains ' . $c['in__tree_in_count'] . ' insights that will help you ' . $c['in_outcome'] . '.';

    if ($fb_format) {
        return '🚩 ' . $pitch . "\n";
    } else {
        //HTML format
        $id = 'IntentOverview';
        return '<div class="panel-group" id="open' . $id . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $id . '">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open' . $id . '" href="#collapse' . $id . '" aria-expanded="false" aria-controls="collapse' . $id . '">
                    <i class="fas" style="transform:none !important;">💡</i> ' . $c['in__tree_in_count'] . ' Insights<i class="fas fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                </a>
            </h4>
        </div>
        <div id="collapse' . $id . '" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading' . $id . '">
            <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">' . $pitch . '</div>
        </div>
    </div></div>';
    }
}

function echo_completion_estimate($c, $fb_format = 0)
{
    $pitch = 'Action Plan estimates that it will take ' . strtolower(echo_hours_range($c)) . ' to ' . $c['in_outcome'] . '.';
    if ($fb_format) {
        return '⏰ ' . $pitch . "\n";
    } else {
        //HTML format
        $id = 'EstimatedTime';
        return '<div class="panel-group" id="open' . $id . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $id . '">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open' . $id . '" href="#collapse' . $id . '" aria-expanded="false" aria-controls="collapse' . $id . '">
                        <i class="fas" style="transform:none !important;">⏰</i> ' . ucwords(echo_hours_range($c)) . '<i class="fas fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse' . $id . '" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading' . $id . '">
                <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">' . $pitch . '</div>
            </div>
        </div></div>';
    }
}

function echo_experts($c, $fb_format = 0)
{

    //Do we have any intents?
    if (strlen($c['in__tree_experts']) <= 0) {
        return false;
    }

    //Make initial variables:
    $c['in__tree_experts'] = unserialize($c['in__tree_experts']);
    $all_count = count($c['in__tree_experts']);
    if ($all_count == 0) {
        //Should never happen since strlen($c['in__tree_experts'])>0
        return false;
    }


    $visible_html = 4; //Landing page, beyond this is hidden and visible with a click
    $visible_bot = 10; //Plain text style, but beyond this is cut out!
    $has_matrix_access = auth(array(1308), 0);
    $text_overview = '';

    foreach ($c['in__tree_experts'] as $count => $u) {

        $is_last_fb_item = ($fb_format && $count >= $visible_bot);

        if ($count > 0) {
            if (($count + 1) >= $all_count || $is_last_fb_item) {
                $text_overview .= ' &';
                if ($is_last_fb_item) {
                    $text_overview .= ' ' . ($all_count - $visible_bot) . ' more!';
                    break;
                }
            } else {
                $text_overview .= ',';
            }
        }

        $text_overview .= ' ';

        if ($fb_format) {

            //Just the name:
            $text_overview .= $u['en_name'];

        } else {

            //HTML Format:
            if ($has_matrix_access) {
                $text_overview .= '<a href="/entities/' . $u['en_id'] . '">';
            }

            //TODO Share parent entity link notes/urls
            if (0) {
                //Has description, show it here:
                $text_overview .= ' <span data-toggle="tooltip" title="' . 'nots here' . '" data-placement="top" class="underdot" style="display:inline-block;">' . $u['en_name'] . '</span>';
            } else {
                //Just the name:
                $text_overview .= $u['en_name'];
            }

            if ($has_matrix_access) {
                $text_overview .= '</a>';
            }

            if (($count + 1) == $visible_html && ($all_count - $visible_html) > 0) {
                $text_overview .= '<span class="show_more_' . $c['in_id'] . '"> & <a href="javascript:void(0);" onclick="$(\'.show_more_' . $c['in_id'] . '\').toggle()" style="text-decoration:underline;">' . ($all_count - $visible_html) . ' more</a>.</span><span class="show_more_' . $c['in_id'] . '" style="display:none;">';
            }
        }
    }

    if (!$fb_format && ($count + 1) >= $visible_html) {
        //Close the span:
        $text_overview .= '.</span>';
    } elseif ($fb_format && !$is_last_fb_item) {
        //Close the span:
        $text_overview .= '.';
    }


    $pitch = 'Action Plan quotes ' . $all_count . ' industry expert' . echo__s($all_count) . ($all_count == 1 ? ':' : ' including') . $text_overview;
    if ($fb_format) {
        return '🎓 ' . $pitch . "\n";
    } else {
        //HTML format
        $id = 'IndustryExperts';
        return '<div class="panel-group" id="open' . $id . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $id . '">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open' . $id . '" href="#collapse' . $id . '" aria-expanded="false" aria-controls="collapse' . $id . '">
                        <i class="fas" style="transform:none !important;">🎓</i> ' . $all_count . ' Industry Expert' . echo__s($all_count) . '<i class="fas fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse' . $id . '" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading' . $id . '">
                <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">
                    ' . $pitch . ' <span style="font-size: 1em !important;">They are not affiliated with Mench, yet their work has been referenced by our training team.</span>
                </div>
            </div>
        </div></div>';
    }
}


function echo_hours_range($c, $micro = false)
{

    if ($c['in__tree_max_seconds'] == $c['in__tree_min_seconds']) {
        //Exactly the same, show a single value:
        return echo_hours($c['in__tree_max_seconds'], $micro);
    } elseif ($c['in__tree_min_seconds'] < 3600) {
        if ($c['in__tree_min_seconds'] < 7200 && $c['in__tree_max_seconds'] < 10800 && ($c['in__tree_max_seconds'] - $c['in__tree_min_seconds']) > 1800) {
            $is_minutes = true;
        } elseif ($c['in__tree_min_seconds'] < 36000) {
            $is_minutes = false;
            $hours_decimal = 1;
        } else {
            //Number too large to matter, just treat as one:
            return echo_hours($c['in__tree_max_seconds'], $micro);
        }
    } else {
        $is_minutes = false;
        $hours_decimal = 0;
    }

    //Generate hours range:
    $ui_time = ($is_minutes ? round($c['in__tree_min_seconds'] / 60) : round(($c['in__tree_min_seconds'] / 3600), $hours_decimal));
    $ui_time .= '-';
    $ui_time .= ($is_minutes ? round($c['in__tree_max_seconds'] / 60) : round(($c['in__tree_max_seconds'] / 3600), $hours_decimal));
    $ui_time .= ($is_minutes ? ($micro ? 'm' : ' Minutes') : ($micro ? 'h' : ' Hours'));

    //Generate UI to return:
    return $ui_time;
}



function echo_object($object, $id, $engagement_field, $button_type)
{


    //Loads the name (and possibly URL) for $object with id=$id
    $CI =& get_instance();
    $id = intval($id);

    if ($id > 0) {
        if ($object == 'in') {

            $is_parent = ($engagement_field == 'tr_in_parent_id' ? true : false);

            //Fetch intent/Step:
            $intents = $CI->Db_model->in_fetch(array(
                'in_id' => $id,
            ));
            if (isset($intents[0])) {
                if (!$button_type) {
                    //Plain view:
                    return '<a href="https://mench.com/intents/' . $intents[0]['in_id'] . '">' . $intents[0]['in_outcome'] . '</a>';
                } else {
                    return '<a href="/intents/' . $intents[0]['in_id'] . '" target="_parent" class="badge badge-primary" style="width:40px;" data-toggle="tooltip" data-placement="left" title="' . $button_type . ': ' . stripslashes($intents[0]['in_outcome']) . '"><i class="' . ($is_parent ? 'fas fa-sign-in-alt' : 'fas fa-sign-out-alt rotate90') . '"></i></a> ';

                }
            }
        } elseif ($object == 'i') {

            if (!$button_type) {
                //Plain view:
                return '#' . $id;
            } else {
                return NULL;
            }

        } elseif ($object == 'w') {

            $trs = $CI->Db_model->w_fetch(array(
                'tr_id' => $id,
            ), array('in'));
            if (count($trs) > 0) {
                if (!$button_type) {
                    //Plain view:
                    return '<a href="https://mench.com/intents/' . $trs[0]['tr_in_child_id'] . '">' . $trs[0]['in_outcome'] . '</a>';
                } else {
                    //TODO replace with Action Plan flag that would show master progress and load up their action plan...
                    return '<a href="/intents/' . $trs[0]['tr_in_child_id'] . '" target="_parent" class="badge badge-primary" style="width:40px;" data-toggle="tooltip" data-placement="left" title="Subscribed to ' . $trs[0]['in_outcome'] . ' [Subscription #' . $id . ']"><i class="fas fa-comment-plus"></i></a> ';
                }
            }

        } elseif ($object == 'en') {

            $matching_users = $CI->Db_model->en_fetch(array(
                'en_id' => $id,
            ));
            if (count($matching_users) > 0) {
                if (!$button_type) {
                    //Plain view:
                    return '<a href="https://mench.com/entities/' . $id . '" title="Entity ID ' . $id . '">' . $matching_users[0]['en_name'] . '</a>';
                } else {
                    return '<a href="/entities/' . $id . '" target="_parent" class="badge badge-secondary" style="width:40px;" data-toggle="tooltip" data-placement="left" title="' . $button_type . ': ' . stripslashes($matching_users[0]['en_name']) . '">' . echo_cover($matching_users[0], 'profile-icon2', true) . '</a> ';
                }
            }

        } elseif ($object == 'x' && $id > 0) {

            $matching_urls = $CI->Old_model->x_fetch(array(
                'x_id' => $id,
            ));
            if (isset($matching_urls[0])) {
                if (!$button_type) {
                    //Plain view:
                    return '<a href="' . $matching_urls[0]['x_url'] . '" target="_blank" title="Reference ID ' . $id . '">' . echo_clean_url($matching_urls[0]['x_url']) . '</a>';
                } else {
                    return '<a href="' . $matching_urls[0]['x_url'] . '" target="_blank" class="badge badge-secondary" style="width:40px;">' . echo_status('x_status', $matching_urls[0]['x_status'], true, 'top') . '</a> ';
                }
            }

        }
        //We would not do the other engagement types...
    }

    //Still here? Return default:
    if ($id > 0) {
        if (!$button_type) {
            //Plain view:
            return '#' . $id;
        } else {
            return '<span class="badge badge-primary grey" data-toggle="tooltip" data-placement="left" title="' . $button_type . ' #' . $id . '"><i class="fas fa-question-circle"></i></span> ';
        }
    } else {
        return NULL;
    }
}


function echo_diff_time($t, $second_time = null)
{
    if (!$second_time) {
        $second_time = time(); //Now
    } else {
        $second_time = strtotime(substr($second_time, 0, 19));
    }

    $time = $second_time - (is_int($t) ? $t : strtotime(substr($t, 0, 19))); // to get the time since that moment
    $is_future = ($time < 0);
    $time = abs($time);
    $tokens = array(
        31536000 => 'Year',
        2592000 => 'Month',
        604800 => 'Week',
        86400 => 'Day',
        3600 => 'Hour',
        60 => 'Minute',
        1 => 'Second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit && $unit > 1) continue;
        if ($unit >= 2592000 && fmod(($time / $unit), 1) >= 0.33 && fmod(($time / $unit), 1) <= .67) {
            $numberOfUnits = number_format(($time / $unit), 1);
        } else {
            $numberOfUnits = number_format(($time / $unit), 0);
        }

        if ($numberOfUnits < 1 && $unit == 1) {
            $numberOfUnits = 1; //Change "0 seconds" to "1 second"
        }

        return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
    }
}

function echo_time($t, $format = 0, $adjust_seconds = 0)
{
    if (!$t) {
        return 'NOW';
    }

    $timestamp = (is_numeric($t) ? $t : strtotime(substr($t, 0, 19))) + $adjust_seconds; //Added this last part to consider the end of days for dates
    $year = (date("Y") == date("Y", $timestamp));
    if ($format == 0) {
        return date(($year ? "M j, g:i a" : "M j, Y, g:i a"), $timestamp);
    } elseif ($format == 1) {
        return date(($year ? "j M" : "j M Y"), $timestamp);
    } elseif ($format == 2) {
        return date(($year ? "D M j " : "j M Y"), $timestamp);
    } elseif ($format == 3) {
        return $timestamp;
    } elseif ($format == 4) {
        return date(($year ? "M j" : "M j Y"), $timestamp);
    } elseif ($format == 5) {
        return date(($year ? "D M j" : "D M j Y"), $timestamp);
    } elseif ($format == 6) {
        return date("Y/m/d", $timestamp);
    } elseif ($format == 7) {
        return date(($year ? "D M j, g:i a" : "D M j, Y, g:i a"), $timestamp);
    } elseif ($format == 8) {
        return date(($year ? "M j" : "M j Y"), $timestamp);
    }
}


function echo_status($object = null, $status = null, $micro_status = false, $data_placement = 'bottom')
{

    //IF you make any changes, make sure to also reflect in the echo_status.php as well
    $CI =& get_instance();
    $status_index = $CI->config->item('object_statuses');

    //Return results:
    if (is_null($object)) {

        //Everything
        return $status_index;

    } elseif (is_null($status)) {

        //Object Specific
        if (is_array($object) && count($object) > 0) {
            return $object;
        } else {
            return (isset($status_index[$object]) ? $status_index[$object] : false);
        }

    } else {

        $status = intval($status);
        if (is_array($object) && count($object) > 0) {
            $result = $object[$status];
        } else {
            $result = $status_index[$object][$status];
        }

        if (!$result) {
            //Could not find matching item
            return false;
        } else {
            //We have two skins for displaying statuses:
            if (is_null($data_placement) && $micro_status) {
                return (isset($result['s_icon']) ? '<i class="' . $result['s_icon'] . ' initial"></i> ' : '<i class="fas fa-sliders-h initial"></i> ');
            } else {
                return '<span class="status-label" ' . ((isset($result['s_desc']) || $micro_status) && !is_null($data_placement) ? 'data-toggle="tooltip" data-placement="' . $data_placement . '" title="' . ($micro_status ? $result['s_name'] : '') . (isset($result['s_desc']) ? ($micro_status ? ': ' : '') . $result['s_desc'] : '') . '" style="border-bottom:1px dotted #444; padding-bottom:1px; line-height:140%;"' : 'style="cursor:pointer;"') . '>' . (isset($result['s_icon']) ? '<i class="' . $result['s_icon'] . ' initial"></i>' : '<i class="fas fa-sliders-h initial"></i>') . ' ' . ($micro_status ? '' : $result['s_name']) . '</span>';
            }

        }
    }
}

function echo_featured_c($c)
{
    $ui = '<a href="/' . $c['in_id'] . '" class="list-group-item">';

    $ui .= '<span class="pull-right">';
    $ui .= '<span class="badge badge-primary fr-bgd"><i class="fas fa-angle-right"></i></span>';
    $ui .= '</span>';

    $ui .= $c['in_outcome'];
    $ui .= '<span style="font-size:0.8em; font-weight:300; margin-left:5px; display:inline-block;">';
    //$ui .= ( $c['in__tree_in_count']>0 ? '<span style="padding-right:5px;"><i class="fas fa-lightbulb-on"></i>'.$c['in__tree_in_count'].'</span>' : '' );
    $ui .= '<span><i class="fas fa-clock"></i>' . echo_hours_range($c, false) . '</span>';
    $ui .= '</span>';
    $ui .= '</a>';
    return $ui;
}

function echo_mili($microtime)
{
    $time = $microtime / 1000;
    echo date("Y-m-d H:i:s", floor($time)) . '.' . one_two_explode('.', '', $time);
}


function echo_c($c, $level, $c_parent_id = 0, $is_parent = false)
{

    $CI =& get_instance();
    $udata = $CI->session->userdata('user');

    //Count engagements for this intent:
    $e_count = count($CI->Db_model->tr_fetch(array(
        '(tr_in_parent_id=' . $c['in_id'] . ' OR tr_in_child_id=' . $c['in_id'] . ')' => null,
    ), array(), $CI->config->item('max_counter')));

    //Count subscription caches for this intent link:
    $k_stats = array(
        'k_all' => 0,
        'k_completed' => 0,
    );

    //Fetch K stats:
    $k_stat_fetch = $CI->Db_model->tr_fetch(array(
        'tr_in_child_id' => $c['in_id'],
    ), array('cr'), 0, 0, array(), 'tr_status, COUNT(tr_id) as cr_count', 'tr_status');
    foreach ($k_stat_fetch as $trs) {
        $k_stats['k_all'] += $trs['cr_count'];
        //Calculate real completion:
        if ($trs['tr_status'] >= 2) {
            $k_stats['k_completed'] += $trs['cr_count'];
        }
    }


    if ($level == 1) {

        //Bootcamp Outcome:
        $ui = '<div class="list-group-item">';

    } else {

        //ATTENTION: DO NOT CHANGE THE ORDER OF data-link-id & intent-id AS the sorting logic depends on their exact position to sort!
        //CHANGE WITH CAUTION!
        $ui = '<div id="cr_' . $c['tr_id'] . '" data-link-id="' . $c['tr_id'] . '" tr_status="' . $c['tr_status'] . '" cr_condition_min="' . $c['cr_condition_min'] . '" cr_condition_max="' . $c['cr_condition_max'] . '" intent-id="' . $c['in_id'] . '" parent-intent-id="' . $c_parent_id . '" intent-level="' . $level . '" class="list-group-item ' . ($level == 3 ? 'is_level3_sortable' : 'is_level2_sortable') . ' intent_line_' . $c['in_id'] . '">';

    }


    //Right content
    $ui .= '<span class="pull-right" style="' . ($level < 3 ? 'margin-right: 8px;' : '') . '">';

    //Show Intent Link conditional status: (The intent link status is either Published or Removed, which would make it invisible)
    if ($level > 1) {
        $ui .= '<span class="tr_status_' . $c['tr_id'] . '">' . echo_status('tr_status', $c['tr_status'], true, 'left') . '</span> ';
    }

    //Always show intent status:
    $ui .= '<span class="in_status_' . $c['in_id'] . '">' . echo_status('in', $c['in_status'], true, 'left') . '</span> ';


    //Show submission stats
    if ($k_stats['k_all'] > 0) {
        //Show link to load these intents in user subscriptions:
        $ui .= '<a href="#loadactionplans-' . $c['in_id'] . '" onclick="in_actionplans_load(' . $c['in_id'] . ')" class="badge badge-primary" style="width:40px; margin-right:2px;" data-toggle="tooltip" title="' . $k_stats['k_completed'] . '/' . $k_stats['k_all'] . ' marked as complete across all Action Plans" data-placement="top"><span class="btn-counter">' . round($k_stats['k_completed'] / $k_stats['k_all'] * 100) . '%</span><i class="fas fa-flag" style="font-size:0.85em;"></i></a>';
    }

    if ($e_count > 0) {
        //Show link to load these engagements:
        $ui .= '<a href="#loadlinks-' . $c['in_id'] . '" onclick="in_tr_load(' . $c['in_id'] . ')" class="badge badge-primary" style="width:40px; margin-right:2px;"><span class="btn-counter">' . $e_count . ($e_count == $CI->config->item('max_counter') ? '+' : '') . '</span><i class="fas fa-atlas"></i></a>';
    }

    $ui .= '<a href="#loadmessages-' . $c['in_id'] . '" onclick="in_messages_load(' . $c['in_id'] . ')" class="msg-badge-' . $c['in_id'] . ' badge badge-primary ' . ($c['in__messages_count'] == 0 ? 'grey' : '') . '" style="width:40px;"><span class="btn-counter messages-counter-' . $c['in_id'] . '">' . $c['in__messages_count'] . '</span><i class="fas fa-comment-dots"></i></a>';

    //Show total tree time here:
    $ui .= '<a class="badge badge-primary" onclick="in_modify_load(' . $c['in_id'] . ',' . (isset($c['tr_id']) ? $c['tr_id'] : 0) . ')" style="margin:-2px -8px 0 2px; width:40px;" href="#loadmodify-' . $c['in_id'] . '-' . (isset($c['tr_id']) ? $c['tr_id'] : 0) . '"><span class="btn-counter slim-time t_estimate_' . $c['in_id'] . '" tree-max-seconds="' . $c['in__tree_max_seconds'] . '" intent-seconds="' . $c['in_seconds'] . '">' . echo_hours($c['in__tree_max_seconds'], true) . '</span><i class="fas fa-cog"></i></a> &nbsp;';

    //Show link to travel down the tree:
    //TODO Disable link if level 1 to reduce confusion as users cannot click on it?
    $ui .= '&nbsp;<a href="/intents/' . $c['in_id'] . '" class="tree-badge-' . $c['in_id'] . ' badge badge-primary ' . ($c['in__tree_in_count'] <= 1 ? 'grey' : '') . '" style="display:inline-block; margin-right:-1px; width:40px;"><span class="btn-counter children-counter-' . $c['in_id'] . ' ' . ($is_parent && $level == 2 ? 'inb-counter' : '') . '">' . $c['in__tree_in_count'] . '</span><i class="' . ($is_parent && $level <= 2 ? 'fas fa-sign-in-alt' : 'fas fa-sign-out-alt rotate90') . '"></i></a> ';

    //Keep an eye out for inner message counter changes:
    $ui .= '</span> ';


    $c_settings = ' c_require_url_to_complete="' . $c['c_require_url_to_complete'] . '" c_require_notes_to_complete="' . $c['c_require_notes_to_complete'] . '" c_cost_estimate="' . $c['c_cost_estimate'] . '" in_status="' . $c['in_status'] . '" c_points="' . $c['c_points'] . '" c_trigger_statements="' . $c['c_trigger_statements'] . '" in_is_any="' . $c['in_is_any'] . '" ';


    //Show intent type:
    $ui .= '<i class="in_is_any_icon' . $c['in_id'] . ' ' . ($c['in_is_any'] ? 'fas fa-code-merge' : 'fas fa-sitemap') . '" style="font-size:0.9em; width:28px; padding-right:3px; text-align:center;"></i> ';

    //Sorting & Then Left Content:
    if ($level > 1 && (!$is_parent || $level == 3)) {
        $ui .= '<i class="fas fa-bars"></i> &nbsp;';
    }


    //Build points UI if any:
    $extra_ui = '';
    $extra_ui .= '<span class="ui_c_points_' . $c['in_id'] . '" style="display:inline-block; margin-left:5px;">';
    if ($c['c_points'] > 0) {
        $extra_ui .= '<i class="fas fa-weight" style="margin-right: 2px;"></i>' . $c['c_points'];
    }
    $extra_ui .= '</span> ';

    $extra_ui .= '<span class="ui_c_require_notes_to_complete_' . $c['in_id'] . '">';
    if (intval($c['c_require_notes_to_complete'])) {
        $extra_ui .= '<i class="fas fa-pencil"></i>';
    }
    $extra_ui .= '</span> ';

    $extra_ui .= '<span class="ui_c_require_url_to_complete_' . $c['in_id'] . '">';
    if (intval($c['c_require_url_to_complete'])) {
        $extra_ui .= '<i class="fas fa-link"></i>';
    }
    $extra_ui .= '</span> ';

    $extra_ui .= '<span class="ui_c_cost_estimate_' . $c['in_id'] . '">';
    if ($c['c_cost_estimate'] > 0) {
        $extra_ui .= '<i class="fas fa-usd-circle" style="margin-right:2px; display:inline-block;"></i>' . $c['c_cost_estimate'];
    }
    $extra_ui .= '</span> ';


    if ($level == 1) {

        //Bootcamp Outcome:
        $ui .= '<span><b id="b_objective" style="font-size: 1.3em;">';
        $ui .= '<span class="in_outcome_' . $c['in_id'] . '" ' . $c_settings . '>' . $c['in_outcome'] . '</span>';
        $ui .= '</b></span>';
        $ui .= ' <span class="obj-id underdot" data-toggle="tooltip" data-placement="top" title="Intent ID">#' . $c['in_id'] . '</span>';

        //Give option to update the cache:
        $ui .= ' <a href="/cron/intent_sync/' . $c['in_id'] . '/1?redirect=/' . $c['in_id'] . '" onclick="turn_off()" data-toggle="tooltip" title="Updates Intent tree cache which controls landing page counters for intent, hours, content types and industry expert" data-placement="top"><i class="fas fa-sync-alt"></i></a>';

        //Show Landing Page URL:
        $ui .= ' <a href="/' . $c['in_id'] . '" data-toggle="tooltip" title="Open Landing Page with Intent tree overview & Messenger subscription button" data-placement="top"><i class="fas fa-shopping-cart"></i></a>';

        $ui .= $extra_ui;

        //Expand trigger statements:
        $ui .= '<div class="c_trigger_statements_' . $c['in_id'] . '" style="margin-top:2px;">' . nl2br($c['c_trigger_statements']) . '</div>';

    } elseif ($level == 2) {

        //Task:
        $ui .= '<span class="inline-level">';

        $ui .= '<a href="javascript:ms_toggle(' . $c['tr_id'] . ');"><i id="handle-' . $c['tr_id'] . '" class="fal fa-plus-square"></i></a> &nbsp;';

        if (!$is_parent) {
            $ui .= '<span class="inline-level-' . $level . '">#' . $c['tr_order'] . '</span>';
        }
        $ui .= '</span>';

        $ui .= '<span id="title_' . $c['tr_id'] . '" class="cdr_crnt tree_title in_outcome_' . $c['in_id'] . (strlen($c['c_trigger_statements']) > 0 ? ' has-desc ' : '') . '" children-rank="' . $c['tr_order'] . '" ' . $c_settings . ' data-toggle="tooltip" data-placement="right" title="' . $c['c_trigger_statements'] . '">' . $c['in_outcome'] . '</span> ';

        $ui .= $extra_ui;

    } elseif ($level == 3) {

        //Steps
        $ui .= '<span class="inline-level inline-level-' . $level . '">#' . $c['tr_order'] . '</span>';
        $ui .= '<span id="title_' . $c['tr_id'] . '" class="tree_title in_outcome_' . $c['in_id'] . (strlen($c['c_trigger_statements']) > 0 ? ' has-desc ' : '') . '" children-rank="' . $c['tr_order'] . '" ' . $c_settings . ' data-toggle="tooltip" data-placement="right" title="' . $c['c_trigger_statements'] . '">' . $c['in_outcome'] . '</span> ';

        $ui .= $extra_ui;

    }


    //Any Tree?
    if ($level == 2) {

        $ui .= '<div id="list-cr-' . $c['tr_id'] . '" class="cr-class-' . $c['tr_id'] . ' list-group step-group hidden list-level-3" intent-id="' . $c['in_id'] . '">';
        //This line enables the in-between list moves to happen for empty lists:
        $ui .= '<div class="is_level3_sortable dropin-box" style="height:1px;">&nbsp;</div>';


        if (isset($c['in__active_children']) && count($c['in__active_children']) > 0) {
            foreach ($c['in__active_children'] as $key => $sub_intent) {
                $ui .= echo_c($sub_intent, ($level + 1), $c['in_id'], $is_parent);
            }
        }


        //Step Input field:
        $ui .= '<div class="list-group-item list_input new-step-input">
            <div class="input-group">
                <div class="form-group is-empty"  style="margin: 0; padding: 0;"><form action="#" onsubmit="c_js_new(' . $c['in_id'] . ',3);" intent-id="' . $c['in_id'] . '"><input type="text" class="form-control autosearch intentadder-level-3 algolia_search bottom-add" maxlength="' . $CI->config->item('in_outcome_max') . '" id="addintent-cr-' . $c['tr_id'] . '" intent-id="' . $c['in_id'] . '" placeholder="Add #Intent"></form></div>
                <span class="input-group-addon" style="padding-right:8px;">
                    <span data-toggle="tooltip" title="or press ENTER ;)" data-placement="top" onclick="c_js_new(' . $c['in_id'] . ',3);" class="badge badge-primary pull-right" intent-id="' . $c['in_id'] . '" style="cursor:pointer; margin: 13px -6px 1px 13px;">
                        <div><i class="fas fa-plus"></i></div>
                    </span>
                </span>
            </div>
        </div>';


        $ui .= '</div>';
    }


    $ui .= '</div>';
    return $ui;

}


function echo_u($u, $level, $is_parent = false)
{

    $CI =& get_instance();
    $udata = $CI->session->userdata('user');
    $status_index = $CI->config->item('object_statuses');
    $tr_id = (isset($u['tr_id']) ? $u['tr_id'] : 0);
    $ui = null;


    $ui .= '<div id="u_' . $u['en_id'] . '" entity-id="' . $u['en_id'] . '" entity-status="' . $u['en_status'] . '" is-parent="' . ($is_parent ? 1 : 0) . '" class="list-group-item u-item u__' . $u['en_id'] . ' ' . ($level == 1 ? 'top_entity' : 'tr_' . $u['tr_id']) . '">';

    //Hidden fields to store dynamic value!
    $ui .= '<span class="en_icon_val_' . $u['en_id'] . ' hidden">' . $u['en_icon'] . '</span>';
    if ($tr_id > 0) {
        $ui .= '<span class="tr_content_val_' . $tr_id . ' hidden">' . $u['tr_content'] . '</span>';
    }


    //Right content:
    $ui .= '<span class="pull-right">';

    //Start by showing entity status:
    $ui .= '<span class="en_status_' . $u['en_id'] . '">' . echo_status('en', $u['en_status'], true, 'left') . '</span> ';

    //Count messages:
    $messages = $CI->Db_model->i_fetch(array(
        'tr_status >=' => 0,
        'tr_en_parent_id' => $u['en_id'], //Referenced content in messages
    ));

    //Check total key engagement for this user:
    $e_count = count($CI->Db_model->tr_fetch(array(
        '(tr_en_parent_id=' . $u['en_id'] . ' OR  tr_en_child_id=' . $u['en_id'] . ')' => null,
        '(tr_en_type_id NOT IN (' . join(',', $CI->config->item('exclude_es')) . '))' => null,
    ), array(), $CI->config->item('max_counter')));
    if ($e_count > 0) {
        //Show the engagement button:
        $ui .= '<a href="#wengagements-' . $u['en_id'] . '" onclick="load_u_engagements(' . $u['en_id'] . ')" class="badge badge-secondary" style="width:40px; margin-right:2px;" data-toggle="tooltip" data-placement="left" title="' . $e_count . ' entity engagements"><span class="btn-counter">' . $e_count . ($e_count == $CI->config->item('max_counter') ? '+' : '') . '</span><i class="fas fa-atlas"></i></a>';
    }


    $ui .= '<' . (count($messages) > 0 ? 'a href="#loadmessages-' . $u['en_id'] . '" onclick="u_load_messages(' . $u['en_id'] . ')" class="badge badge-secondary"' : 'span class="badge badge-secondary grey"') . ' style="width:40px;">' . (count($messages) > 0 ? '<span class="btn-counter">' . count($messages) . '</span>' : '') . '<i class="fas fa-comment-dots"></i></' . (count($messages) > 0 ? 'a' : 'span') . '>';


    $ui .= '<a href="#loadmodify-' . $u['en_id'] . '-' . $tr_id . '" onclick="u_load_modify(' . $u['en_id'] . ',' . $tr_id . ')" class="badge badge-secondary" style="margin:-2px -6px 0 2px; width:40px;">' . ($u['en_trust_score'] > 0 ? '<span class="btn-counter" data-toggle="tooltip" data-placement="left" title="Engagement Score">' . echo_number($u['en_trust_score']) . '</span>' : '') . '<i class="fas fa-cog" style="font-size:0.9em; width:28px; padding-right:3px; text-align:center;"></i></a> &nbsp;';

    $ui .= '<a class="badge badge-secondary" href="/entities/' . $u['en_id'] . '" style="display:inline-block; margin-right:6px; width:40px; margin-left:1px;">' . (isset($u['in__children_count']) && $u['in__children_count'] > 0 ? '<span class="btn-counter ' . ($level == 1 ? 'li-children-count' : '') . '">' . $u['in__children_count'] . '</span>' : '') . '<i class="' . ($is_parent ? 'fas fa-sign-in-alt' : 'fas fa-sign-out-alt rotate90') . '"></i></a>';

    $ui .= '</span>';


    if ($level == 1) {

        //Regular section:
        $ui .= echo_cover($u, 'profile-icon2');
        $ui .= '<b id="u_title" class="en_name en_name_' . $u['en_id'] . '">' . $u['en_name'] . '</b>';

        $ui .= ' <span class="obj-id underdot" data-toggle="tooltip" data-placement="top" title="Entity ID">@' . $u['en_id'] . '</span>';

        //Google search:
        //$ui .= ' &nbsp;<a href="https://www.google.com/search?q=' . urlencode($u['en_name']) . '" target="_blank" data-toggle="tooltip" title="Search on Google" data-placement="top"><i class="fab fa-google"></i></a>';

    } else {

        //Regular section:
        $ui .= echo_cover($u, 'micro-image', true) . ' ';
        $ui .= '<span class="en_name en_name_' . $u['en_id'] . '">' . $u['en_name'] . '</span>';

    }

    $ui .= ' <span class="en_icon_ui_' . $u['en_id'] . (strlen($u['en_icon']) == 0 ? ' hidden ' : '') . '" data-toggle="tooltip" title="Parent Icon" data-placement="top">&nbsp;[' . $u['en_icon'] . ']</span>';

    if (!isset($u['en__parents'])) {
        //Fetch parents at this point:
        $u['en__parents'] = $CI->Db_model->tr_parent_fetch(array(
            'tr_en_child_id' => $u['en_id'],
            'tr_status >=' => 0, //Pending or Active
            'en_status >=' => 0, //Pending or Active
        ));
    }

    //Loop through parents and show those that have en_icon set:
    foreach ($u['en__parents'] as $in_u) {
        if (strlen($in_u['en_icon']) > 0) {
            $ui .= ' &nbsp;<a href="/entities/' . $in_u['en_id'] . '" data-toggle="tooltip" title="' . $in_u['en_name'] . (strlen($in_u['tr_content']) > 0 ? ' = ' . $in_u['tr_content'] : '') . '" data-placement="top" class="en_icon_child_' . $in_u['en_id'] . '">' . $in_u['en_icon'] . '</a>';
        }
    }

    //Does it have a UR value?
    if ($tr_id > 0) {
        //show the link box for updating:
        $ui .= ' <span class="tr_content tr_content_' . $tr_id . '">' . echo_link($u['tr_content']) . '</span>';

        //How about a URL in the message?
        /*
        $ui .= '<a href="'.$x['x_url'].'" target="_blank" '.( strlen($x['x_url'])>0 && !($x['x_url']==$x['x_url']) ? '' : '' ).'>';
        $ui .= '<span class="url_truncate">'.echo_clean_url($x['x_url']).'</span>';
        $ui .= '<i class="fas fa-external-link-square"></i></a>';

        //Can we display this URL?
        if($x['x_type']==1){
            $ui .= '<div style="margin-top:7px;">'.echo_embed($x['x_clean_url'],$x['x_clean_url']).'</div>';
        } elseif($x['x_type']>1){
            $ui .= '<div style="margin-top:7px;">'.echo_content_url($x['x_clean_url'],$x['x_type']).'</div>';
        }
        */
    }

    $ui .= '</div>';

    return $ui;

}


function echo_json($array)
{
    header('Content-Type: application/json');
    echo json_encode($array);
}


function echo_ordinal($number)
{
    $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
    if (($number % 100) >= 11 && ($number % 100) <= 13) {
        return $number . 'th';
    } else {
        return $number . $ends[$number % 10];
    }
}

function echo__s($count, $is_es = 0)
{
    return ($count == 1 ? '' : ($is_es ? 'es' : 's'));
}

