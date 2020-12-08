
<?php
$e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION
$e___14393 = $this->config->item('e___14393'); //SUGGEST
$user_e = superpower_unlocked();



if($user_e){
    ?>

    <!-- ACCOUNT Modal -->
    <div class="modal fade" id="modal6225" tabindex="-1" role="dialog" aria-labelledby="modal6225Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title montserrat <?= extract_icon_color($e___11035[6225]['m__icon']) ?>" id="modal14393Label"><?= $e___11035[6225]['m__icon'].' '.$e___11035[6225]['m__title'] ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    $this->load->view('e/account', array(
                        'user_e' => $user_e,
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- SUGGEST Modal -->
    <div class="modal fade" id="modal14393" tabindex="-1" role="dialog" aria-labelledby="modal14393Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title montserrat <?= extract_icon_color($e___11035[14393]['m__icon']) ?>" id="modal14393Label"><?= $e___11035[14393]['m__icon'].' '.$e___11035[14393]['m__title'] ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    <div class="headline no-left-padding"><?= '<span class="icon-block">'.$e___14393[14394]['m__icon'].'</span>'.$e___14393[14394]['m__title'] ?></div>
                    <select class="form-control border" id="sugg_type">
                        <?php
                        echo '<option value="0">SELECT...</option>';
                        foreach($this->config->item('e___14394') /* SUGGESTION TYPE */ as $x__type => $m){
                            echo '<option value="' . $x__type . '" title="' . $m['m__message'] . '">' . $m['m__title'] . '</option>';
                        }
                        ?>
                    </select>


                    <div class="headline no-left-padding"><?= '<span class="icon-block">'.$e___14393[14395]['m__icon'].'</span>'.$e___14393[14395]['m__title'] ?></div>
                    <textarea class="form-control text-edit border white-border" id="sugg_note" data-lpignore="true" placeholder="More details here..."></textarea>


                </div>
                <div class="modal-footer">
                    <button type="button" onclick="x_suggestion()" class="btn btn-source">SUBMIT</button>
                </div>
            </div>
        </div>
    </div>

    <?php
    if($user_e){
        ?>
        <!-- APP Modal -->
        <div class="modal fade" id="modal6287" tabindex="-1" role="dialog" aria-labelledby="modal6287Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title montserrat <?= extract_icon_color($e___11035[6287]['m__icon']) ?>" id="modal14393Label"><?= $e___11035[6287]['m__icon'].' '.$e___11035[6287]['m__title'] ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php
                        $this->load->view('e/app_home');
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

}

?>


</body>