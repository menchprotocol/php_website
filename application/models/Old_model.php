<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Old_model extends CI_Model
{

    //This model handles all DB calls from our local database.

    function __construct()
    {
        parent::__construct();
    }



    function en_match_metadata($key, $value)
    {

        //Uses the en_metadata variable in config to determine if the current item has a valid entity parent or not.
        $en_user_metadata = array(
            'en_countries' => array(
                'vg' => 4121,
                'vi' => 4122,
                'wf' => 4123,
                'eh' => 4124,
                'ye' => 4125,
                'zm' => 4126,
                'zw' => 4127,
                'vn' => 4120,
                'ua' => 4112,
                'ug' => 4111,
                'tc' => 4109,
                'tv' => 4110,
                'vu' => 4118,
                've' => 4119,
                'uz' => 4117,
                'uy' => 4116,
                'us' => 4115,
                'gb' => 4114,
                'ae' => 4113,
                'tm' => 4108,
                'tr' => 4107,
                'tn' => 4106,
                'tt' => 4105,
                'to' => 4104,
                'tk' => 4103,
                'tg' => 4102,
                'tl' => 4101,
                'th' => 4100,
                'tz' => 4099,
                'tj' => 4098,
                'tw' => 4097,
                'sy' => 4096,
                'ch' => 4095,
                'se' => 4094,
                'sz' => 4093,
                'sj' => 4092,
                'sr' => 4091,
                'sd' => 4090,
                'lk' => 4089,
                'es' => 4088,
                'gs' => 4087,
                'sa' => 4075,
                'sn' => 4076,
                'za' => 4086,
                'st' => 4074,
                'so' => 4085,
                'sb' => 4084,
                'si' => 4083,
                'sk' => 4081,
                'sg' => 4080,
                'sl' => 4079,
                'sc' => 4078,
                'rs' => 4077,
                'kn' => 4068,
                'sm' => 4073,
                'ws' => 4072,
                'vc' => 4071,
                'pm' => 4070,
                'lc' => 4069,
                'sh' => 4067,
                'rw' => 4066,
                'ru' => 4065,
                'ro' => 4064,
                're' => 4063,
                'ps' => 4052,
                'qa' => 4062,
                'pr' => 4061,
                'pt' => 4060,
                'pl' => 4059,
                'pn' => 4058,
                'ph' => 4057,
                'pe' => 4056,
                'py' => 4055,
                'pg' => 4054,
                'pa' => 4053,
                'ni' => 4042,
                'pw' => 4051,
                'pk' => 4050,
                'om' => 4049,
                'no' => 4048,
                'mp' => 4047,
                'nf' => 4046,
                'nu' => 4045,
                'ng' => 4044,
                'ne' => 4043,
                'nz' => 4041,
                'mn' => 4030,
                'nc' => 4040,
                'nl' => 4039,
                'np' => 4038,
                'nr' => 4037,
                'na' => 4036,
                'mm' => 4035,
                'mz' => 4034,
                'ma' => 4033,
                'ms' => 4032,
                'me' => 4031,
                'ml' => 4019,
                'mc' => 4029,
                'md' => 4028,
                'fm' => 4027,
                'mx' => 4026,
                'yt' => 4025,
                'mu' => 4024,
                'mr' => 4023,
                'mq' => 4022,
                'mh' => 4021,
                'mt' => 4020,
                'lr' => 4008,
                'mv' => 4018,
                'my' => 4017,
                'mk' => 4014,
                'mo' => 4013,
                'lu' => 4012,
                'mg' => 4015,
                'lt' => 4011,
                'li' => 4010,
                'ly' => 4009,
                'mw' => 4016,
                'ls' => 4007,
                'kz' => 3997,
                'ke' => 3998,
                'ki' => 3999,
                'kp' => 4000,
                'kr' => 4001,
                'kw' => 4002,
                'kg' => 4003,
                'la' => 4004,
                'lv' => 4005,
                'lb' => 4006,
                'je' => 3995,
                'jp' => 3994,
                'jm' => 3993,
                'it' => 3992,
                'il' => 3991,
                'im' => 3990,
                'ie' => 3989,
                'iq' => 3988,
                'jo' => 3996,
                'hm' => 3979,
                'gy' => 3977,
                'ht' => 3978,
                'ir' => 3987,
                'id' => 3986,
                'in' => 3985,
                'is' => 3984,
                'hu' => 3983,
                'hk' => 3982,
                'hn' => 3981,
                'va' => 3980,
                'gp' => 3971,
                'gw' => 3976,
                'gn' => 3975,
                'gg' => 3974,
                'gt' => 3973,
                'gu' => 3972,
                'gd' => 3970,
                'gl' => 3969,
                'gr' => 3968,
                'gi' => 3967,
                'gh' => 3966,
                'fo' => 3955,
                'de' => 3965,
                'ge' => 3964,
                'gm' => 3963,
                'ga' => 3962,
                'tf' => 3961,
                'pf' => 3960,
                'gf' => 3959,
                'fr' => 3958,
                'fi' => 3957,
                'fj' => 3956,
                'do' => 3946,
                'fk' => 3954,
                'et' => 3953,
                'ee' => 3952,
                'er' => 3951,
                'gq' => 3950,
                'dj' => 3944,
                'sv' => 3949,
                'eg' => 3948,
                'ec' => 3947,
                'dm' => 3945,
                'cr' => 3937,
                'hr' => 3939,
                'ci' => 3938,
                'cu' => 3940,
                'ck' => 3936,
                'cd' => 3935,
                'cg' => 3934,
                'km' => 3933,
                'co' => 3932,
                'dk' => 3943,
                'cz' => 3942,
                'cy' => 3941,
                'cm' => 3922,
                'kh' => 3921,
                'cl' => 3928,
                'cn' => 3929,
                'cx' => 3930,
                'cc' => 3931,
                'ca' => 3923,
                'td' => 3927,
                'cf' => 3926,
                'ky' => 3925,
                'cv' => 3924,
                'io' => 3916,
                'br' => 3915,
                'bi' => 3920,
                'bf' => 3919,
                'bg' => 3918,
                'bn' => 3917,
                'bv' => 3914,
                'bw' => 3913,
                'ba' => 3912,
                'bo' => 3911,
                'bt' => 3910,
                'at' => 3899,
                'bm' => 3909,
                'bj' => 3908,
                'bz' => 3907,
                'be' => 3906,
                'by' => 3905,
                'bb' => 3904,
                'bd' => 3903,
                'bh' => 3902,
                'bs' => 3901,
                'az' => 3900,
                'ag' => 3894,
                'aq' => 3893,
                'ai' => 3892,
                'ao' => 3891,
                'ad' => 3890,
                'as' => 3889,
                'dz' => 3888,
                'am' => 3896,
                'aw' => 3897,
                'au' => 3898,
                'ar' => 3895,
                'al' => 3887,
                'ax' => 3886,
                'af' => 3885,
            ),
            'en_languages' => array(
                'bp' => 4514,
                'yo' => 3636,
                'ji' => 3637,
                'zu' => 3638,
                'xh' => 3635,
                'ur' => 3629,
                'cy' => 3633,
                'uk' => 3628,
                'tw' => 3627,
                'tt' => 3626,
                'ts' => 3625,
                'to' => 3623,
                'tr' => 3624,
                'vo' => 3632,
                'vi' => 3631,
                'wo' => 3634,
                'uz' => 3630,
                'sv' => 3612,
                'tl' => 3622,
                'tk' => 3621,
                'ti' => 3620,
                'th' => 3619,
                'tg' => 3618,
                'te' => 3617,
                'ta' => 3616,
                'bo' => 3615,
                'tn' => 3614,
                'sw' => 3613,
                'su' => 3611,
                'sg' => 3600,
                'st' => 3610,
                'ss' => 3609,
                'sr' => 3608,
                'so' => 3607,
                'sn' => 3606,
                'sm' => 3605,
                'sl' => 3604,
                'sk' => 3603,
                'si' => 3602,
                'sh' => 3601,
                'sd' => 3599,
                'om' => 3587,
                'sa' => 3598,
                'gd' => 3597,
                'es' => 3596,
                'ru' => 3595,
                'ro' => 3594,
                'rm' => 3593,
                'qu' => 3592,
                'pt' => 3591,
                'ps' => 3590,
                'pl' => 3589,
                'pa' => 3588,
                'mr' => 3580,
                'oc' => 3586,
                'no' => 3585,
                'ne' => 3584,
                'na' => 3583,
                'mt' => 3582,
                'mo' => 3579,
                'mn' => 3578,
                'ml' => 3577,
                'mk' => 3576,
                'mi' => 3575,
                'ms' => 3581,
                'lt' => 3572,
                'kn' => 3563,
                'ko' => 3564,
                'ks' => 3565,
                'ku' => 3566,
                'ky' => 3567,
                'rw' => 3568,
                'la' => 3569,
                'ln' => 3570,
                'lo' => 3571,
                'lv' => 3573,
                'mg' => 3574,
                'rn' => 3562,
                'kk' => 3561,
                'jw' => 3560,
                'ja' => 3559,
                'is' => 3558,
                'in' => 3557,
                'ik' => 3556,
                'ie' => 3555,
                'ia' => 3554,
                'ga' => 3553,
                'it' => 3552,
                'iw' => 3551,
                'fr' => 3539,
                'hu' => 3550,
                'hi' => 3549,
                'ha' => 3548,
                'ka' => 3546,
                'kl' => 3547,
                'gu' => 3545,
                'gn' => 3544,
                'gl' => 3543,
                'el' => 3542,
                'de' => 3541,
                'fy' => 3540,
                'eo' => 3533,
                'fo' => 3538,
                'fj' => 3537,
                'fi' => 3536,
                'fa' => 3535,
                'et' => 3534,
                'nl' => 3532,
                'da' => 3531,
                'km' => 3530,
                'hr' => 3529,
                'cs' => 3528,
                'co' => 3527,
                'ca' => 3526,
                'zh' => 3525,
                'my' => 3524,
                'eu' => 3523,
                'dz' => 3522,
                'br' => 3521,
                'bn' => 3520,
                'bi' => 3519,
                'bh' => 3518,
                'bg' => 3517,
                'be' => 3516,
                'ba' => 3515,
                'hy' => 3514,
                'sq' => 3513,
                'az' => 3512,
                'ay' => 3511,
                'as' => 3510,
                'am' => 3509,
                'af' => 3508,
                'ab' => 3507,
                'aa' => 3506,
                'ar' => 3505,
                'en' => 3504,
            ),
            'en_timezones' => array(
                '9' => 3497,
                '8' => 3496,
                '6' => 3494,
                '7' => 3495,
                '12' => 3501,
                '11' => 3500,
                '10' => 3499,
                '9.5' => 3498,
                '5' => 3493,
                '3.5' => 3490,
                '4' => 3491,
                '4.5' => 3492,
                '1' => 3487,
                '-4' => 3481,
                '-3.5' => 3482,
                '-3' => 3483,
                '-2' => 3484,
                '-1' => 3485,
                '0' => 3486,
                '2' => 3488,
                '3' => 3489,
                '-11' => 3473,
                '-4.5' => 3480,
                '-5' => 3479,
                '-6' => 3478,
                '-7' => 3477,
                '-8' => 3476,
                '-9' => 3475,
                '-10' => 3474,
            ),
            'en_gender' => array(
                'f' => 3292,
                'm' => 3291,
            ),
        );

        //Is this a timezone? We might need some adjustments if so...
        if ($key == 'en_timezones') {
            $valid_halfs = array(-4, -3, 3, 4, 9); //These are timezones with half values so far
            $decimal = fmod(doubleval($value), 1);
            if (!($decimal == 0)) {
                $whole = intval(str_replace('.' . $decimal, '', $value));
                if (in_array(intval($whole), $valid_halfs)) {
                    $value = $whole + ($whole < 0 ? -0.5 : +0.5);
                } else {
                    $value = round(doubleval($value));
                }
            }
        }

        if (isset($en_user_metadata[$key][strtolower($value)])) {
            //Found it, return entity ID:
            return $en_user_metadata[$key][strtolower($value)];
        } else {
            //Ooops, this value did not exist! Notify the admin so we can look into this:
            $this->Database_model->fn___tr_create(array(
                'tr_content' => 'en_match_metadata() failed to find cached variable [' . $key . ']=[' . $value . ']. Look into the cron/en_metadata() function and update this accordingly.',
                'tr_en_type_id' => 4246, //Platform Error
            ));
            return false;
        }
    }




    function u_fetch($match_columns, $join_objects = array(), $limit = 0, $limit_offset = 0, $order_columns = array('u__e_score' => 'DESC'), $select = '*', $group_by = null)
    {

        //Fetch the target entities:
        $this->db->select($select);
        $this->db->from('tb_entities u');
        $this->db->join('tb_entity_urls x', 'x.x_id = u_cover_x_id', 'left'); //Fetch the cover photo if >0
        foreach ($match_columns as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
        }
        if ($group_by) {
            $this->db->group_by($group_by);
        }
        foreach ($order_columns as $key => $value) {
            $this->db->order_by($key, $value);
        }
        if ($limit > 0) {
            $this->db->limit($limit, $limit_offset);
        }

        $q = $this->db->get();
        $res = $q->result_array();

        return $res;
    }


    function x_fetch($match_columns, $join_objects = array(), $order_columns = array(), $limit = 0)
    {
        //Fetch the target entities:
        $this->db->select('*');
        $this->db->from('tb_entity_urls x');
        if (in_array('en', $join_objects)) {
            $this->db->join('tb_entities u', 'u_id=x.x_u_id', 'left');
        }
        foreach ($match_columns as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
        }

        if (count($order_columns) > 0) {
            foreach ($order_columns as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }

        if ($limit > 0) {
            $this->db->limit($limit);
        }

        $q = $this->db->get();
        $res = $q->result_array();

        return $res;
    }


    function ur_child_fetch($match_columns, $join_objects = array(), $limit = 0, $limit_offset = 0, $select = '*', $group_by = null, $order_columns = array(
        'u__e_score' => 'DESC',
    ))
    {

        //Missing anything?
        $this->db->select($select);
        $this->db->from('tb_entities u');
        $this->db->join('tb_entity_links ur', 'ur_child_u_id = u_id');
        $this->db->join('tb_entity_urls x', 'x.x_id = u_cover_x_id', 'left'); //Fetch the cover photo if >0
        foreach ($match_columns as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
        }

        if ($group_by) {
            $this->db->group_by($group_by);
        }
        foreach ($order_columns as $key => $value) {
            $this->db->order_by($key, $value);
        }

        if ($limit > 0) {
            $this->db->limit($limit, $limit_offset);
        }

        $q = $this->db->get();
        $res = $q->result_array();

        return $res;
    }


    function cr_child_fetch($match_columns, $join_objects = array())
    {

        //Missing anything?
        $this->db->select('*');
        $this->db->from('tb_intents c');
        $this->db->join('tb_intent_links cr', 'cr_child_c_id = c_id');
        foreach ($match_columns as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
        }
        $this->db->order_by('cr_child_rank', 'ASC');
        $q = $this->db->get();
        $return = $q->result_array();

        //Return the package:
        return $return;
    }

    function cr_parents_fetch($match_columns, $join_objects = array())
    {
        //Missing anything?
        $this->db->select('*');
        $this->db->from('tb_intents c');
        $this->db->join('tb_intent_links cr', 'cr_parent_c_id = c_id');
        foreach ($match_columns as $key => $value) {
            $this->db->where($key, $value);
        }
        $q = $this->db->get();
        $return = $q->result_array();

        return $return;
    }


    function c_fetch($match_columns, $fetch_child_levels = 0, $join_objects = array(), $order_columns = array(), $limit = 0, $limit_offset = 0, $select = '*', $group_by = null)
    {

        //The basic fetcher for intents
        $this->db->select($select);
        $this->db->from('tb_intents c');
        foreach ($match_columns as $key => $value) {
            $this->db->where($key, $value);
        }

        if ($group_by) {
            $this->db->group_by($group_by);
        }
        if (count($order_columns) > 0) {
            foreach ($order_columns as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }
        if ($limit > 0) {
            $this->db->limit($limit, $limit_offset);
        }
        $q = $this->db->get();
        $ins = $q->result_array();

        //Return everything that was collected:
        return $ins;
    }



    function i_fetch($match_columns, $limit = 0, $join_objects = array(), $order_columns = array(
        'i_rank' => 'ASC',
    ))
    {

        $this->db->select('*');
        $this->db->from('tb_intent_messages i');
        $this->db->join('tb_intents c', 'i_c_id = c_id');
        foreach ($match_columns as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
        }
        if ($limit > 0) {
            $this->db->limit($limit);
        }

        foreach ($order_columns as $key => $value) {
            $this->db->order_by($key, $value);
        }

        $this->db->order_by('i_rank');
        $q = $this->db->get();
        return $q->result_array();
    }



    function w_fetch($match_columns, $join_objects = array(), $order_columns = array('w_c_rank' => 'ASC'), $limit = 0)
    {
        //Fetch the target gems:
        $this->db->select('*');
        $this->db->from('tb_actionplans w');

        if (in_array('in', $join_objects)) {
            $this->db->join('tb_intents', 'w_c_id = c_id');
        }

        if (in_array('en', $join_objects)) {
            $this->db->join('tb_entities u', 'w_child_u_id = u_id');
            if (in_array('u_x', $join_objects)) {
                $this->db->join('tb_entity_urls x', 'x.x_id = u_cover_x_id', 'left'); //Fetch the cover photo if >0
            }
        }
        foreach ($match_columns as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
        }
        if (count($order_columns) > 0) {
            foreach ($order_columns as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        $q = $this->db->get();
        $results = $q->result_array();

        //Return everything that was collected:
        return $results;
    }




    function k_fetch($match_columns, $join_objects = array(), $order_columns = array(), $limit = 0, $select = '*', $group_by = null)
    {
        //Fetch the target gems:
        $this->db->select($select);
        $this->db->from('tb_actionplan_links k');


        if(in_array('cr',$join_objects)){
            $this->db->join('tb_intent_links cr', 'k.k_cr_id = cr.cr_id');
            if(in_array('cr_c_child',$join_objects)){
                //Also join with subscription row:
                $this->db->join('tb_intents c', 'c.c_id = cr.cr_child_c_id');
            } elseif(in_array('cr_c_parent',$join_objects)){
                //Also join with subscription row:
                $this->db->join('tb_intents c', 'c.c_id = cr.cr_parent_c_id');
            }
        }

        if (in_array('w', $join_objects)) {

            //Also join with subscription row:
            $this->db->join('tb_actionplans w', 'w_id = k_w_id');

            if (in_array('w_c', $join_objects)) {
                //Also join with subscription row:
                $this->db->join('tb_intents c', 'c_id = w_c_id');
            }
            if (in_array('w_u', $join_objects)) {
                //Also add subscriber and their profile picture:
                $this->db->join('tb_entities u', 'u_id = w_child_u_id');
                $this->db->join('tb_entity_urls x', 'x.x_id = u.u_cover_x_id', 'left'); //Fetch the cover photo if >0
            }

        }

        foreach ($match_columns as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
        }

        if ($group_by) {
            $this->db->group_by($group_by);
        }

        if (count($order_columns) > 0) {
            foreach ($order_columns as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }

        if ($limit > 0) {
            $this->db->limit($limit);
        }

        $q = $this->db->get();
        $results = $q->result_array();

        //Return everything that was collected:
        return $results;
    }



}