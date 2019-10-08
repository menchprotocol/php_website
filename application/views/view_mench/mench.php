<div class="container-body">

    <table class="container navbar-top fixed-top">
        <tr>
            <?php
            foreach($this->config->item('en_all_2738') as $en_id => $m){
                $handle = strtolower($m['m_name']);
                echo '<td><a class="'.$handle.' border-'.$handle.( $this->uri->segment(1)==$handle || $handle=='read' ? ' background-'.$handle: null ).'" href="/'.$handle.'">' . $m['m_icon'] . '<span class="mn_name">' . $m['m_name'] . '</span> <span class="current_count"><i class="fas fa-yin-yang fa-spin"></i></span></a></td>';
            }
            ?>
        </tr>
    </table>


    <div id="searchresults"></div>

</div>