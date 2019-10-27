<?php
$en_all_2738 = $this->config->item('en_all_2738'); //MENCH
?>
<div class="container">

    <div class="row" style="padding-top: 30px;">

        <div class="col-lg-3">&nbsp;</div>

        <div class="col-lg-2" style="text-align:left;">
            <img src="/img/mench-v2-128.png" class="mench-spin" />
        </div>

        <div class="col-lg-5">


            <h1>BLOGGING. REINVENTED.</h1>


            <h2>MENCH IS...</h2>
            <ul class="intructions-list double-line-list">
                <li>An interactive publishing platform for sharing stories & ideas that matter</li>
                <li>A conversational reading experience offered over the web or Messenger</li>
                <li>A learning game that players <b class="montserrat play">PLAY</b> every time they <b class="montserrat read"><?= $en_all_2738[6205]['m_name'] ?></b> or <b class="montserrat blog"><?= $en_all_2738[4535]['m_name'] ?></b></li>
                <li>An open-source protocol for building & sharing consensus</li>
                <li>A non-profit organization on a mission to expand human potential</li>
            </ul>

            <div class="learn_more hidden">
                <h2>HOW TO <b class="play">PLAY</b></h2>
                <ul class="intructions-list">
                    <li>Earn a <?= $en_all_2738[6205]['m_icon'] ?> coin for every word you <b class="montserrat read"><?= $en_all_2738[6205]['m_name'] ?></b></li>
                    <li>Earn a <?= $en_all_2738[4535]['m_icon'] ?> coin for every word you <b class="montserrat blog"><?= $en_all_2738[4535]['m_name'] ?></b></li>
                    <li><b class="montserrat read"><?= $en_all_2738[6205]['m_name'] ?></b> up to <?= config_var(11061) ?> words/month <b class="montserrat">FREE</b></li>
                    <li><b class="montserrat read"><?= $en_all_2738[6205]['m_name'] ?></b> unlimited words for $<?= config_var(11162) ?>/month</li>
                    <li>Earn monthly cash with your <?= $en_all_2738[4535]['m_icon'] ?> coins</li>
                </ul>

                <p>Release Date is ~ <b class="montserrat">Early 2020</b></p>

            </div>

            <p>
                <a href="javascript:void(0);" onclick="$('.learn_more').toggleClass('hidden');" class="btn btn-read montserrat learn_more">READ MORE <i class="fas fa-search-plus"></i></a>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSca_W0_pP1anDmJ9_iJx82icCXTjKMjblXCx9hIHrlScwUoGg/viewform" class="btn btn-play montserrat">JOIN WAIT LIST <i class="fas fa-arrow-right"></i></a>
            </p>


            <p>Or <a href="/play/signin" style="font-weight: bold; text-decoration: underline;">login</a> if you already have an account.</p>

        </div>

        <div class="col-lg-2">&nbsp;</div>
    </div>

</div>

