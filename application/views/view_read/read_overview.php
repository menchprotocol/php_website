<?php
$session_en = $this->session->userdata('user');
?>
<div class="container">

    <h1>ASSESS YOUR HTML SKILLS</h1>



    <?php
    echo '<div class="row">';
    $col_num = 0;
    foreach ($this->config->item('en_all_11021') as $en_id => $m){
        $col_num++;
        echo '<div class="'.( $col_num==1 ? 'col-lg-8 col-md-7' : 'col-lg-4 col-md-5' ).'">';
        echo '<h4><b>'.$m['m_name'].' <a href="/play/'.$en_id.'"><i class="far fa-info-circle" data-toggle="tooltip" data-placement="top" title="'.$m['m_desc'].' [CLICK to learn more]"></i></a></b><span>&nbsp;</span></h4>';
        echo '<ul class="nav nav-tabs">';
        foreach ($this->config->item('en_all_'.$en_id) as $en_id2 => $m2){

            $show_tab_names = in_array($en_id, $this->config->item('en_ids_11031')) || in_array($en_id2, $this->config->item('en_ids_11031'));

            if($en_id2!=10991){
                $counter = ' '.rand(1,9);
            } else {
                $counter = null;
            }

            echo '<li class="nav-item"><a class="nav-link '.( in_array(5007 , $m2['m_parents']) ? ' ' . advance_mode() . '' : '' ).'" href="#notes-'.$en_id2.'" data-toggle="tooltip" data-placement="top" title="'.( $show_tab_names ? '' : $m2['m_name'].': ' ).$m2['m_desc'].'">'.$m2['m_icon'].$counter.( $show_tab_names ? ' '.$m2['m_name'] : '' ).'</a></li>';
        }

        echo '</ul>';
        echo '</div>';
    }
    echo '</div>';
    ?>




    <br /><br /><br />
    <h1><i class="far fa-monkey ismatt"></i> SHERVIN ENAYATI</h1>

    <ul class="nav nav-tabs">
        <?php
        foreach ($this->config->item('en_all_10998') as $en_id => $m){
            if($en_id!=10999){
                $counter = ' '.rand(1,9);
            } else {
                $counter = null;
            }
            echo '<li class="nav-item"><a class="nav-link '.( in_array(5007 , $m['m_parents']) ? ' ' . advance_mode() . '' : '' ).'" href="#notes-'.$en_id.'" data-toggle="tooltip" data-placement="top" title="'.$m['m_name'].'">'.$m['m_icon'].$counter.'</a></li>';
        }
        ?>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"></a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <a class="dropdown-item" href="#">Something else here</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Separated link</a>
            </div>
        </li>
    </ul>



    <br /><br /><br />
    <h1>ACCOUNT</h1>
    <ul class="nav nav-tabs">
        <?php
        foreach ($this->config->item('en_all_6225') as $en_id => $m){
            echo '<li class="nav-item"><a class="nav-link '.( in_array(5007 , $m['m_parents']) ? ' ' . advance_mode() . '' : '' ).'" href="#notes-'.$en_id.'" data-toggle="tooltip" data-placement="top" title="'.$m['m_name'].'">'.$m['m_icon'].'</a></li>';
        }
        ?>
    </ul>

</div>