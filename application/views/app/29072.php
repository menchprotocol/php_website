<style>
    body {
        background: #000 url("/img/valentinu/bg-roses@2x.jpg") center;
        background-repeat: repeat;
        background-attachment: fixed;
    }
    .container{
        max-width: 800px;
    }
    .text-logo{
        color:#e50201 !important;
    }
    div, p {
        color: #FFFFFF;
        text-align: center;
    }
    .redbold {
        color: #e50201 !important;
    }
    .user_nav_box, .block-menu{
        display: none;
    }
    h1 {
        font-size: 4.5em;
    }

    .htb-link-video{
        position: relative;
        z-index: 2;
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        width: 650px;
        height: 350px;
        margin-top: 2.5em;
        -webkit-box-pack: center;
        -webkit-justify-content: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -webkit-align-items: center;
        -ms-flex-align: center;
        align-items: center;
        border: 10px solid #fff;
        border-radius: 24px;
        background-image: url("https://www.babycake.app/images/video_image.png");
        background-position: 50% 50%;
        background-size: cover;
        background-repeat: no-repeat;
        -webkit-transition: all 150ms ease;
        transition: all 150ms ease;
        margin: 34px auto;
    }


    @media (max-width:1500px) {
        .htb-link-video {
            width: 560px;
            height: 290px;
        }
    }

    @media (max-width:767px) {
        .htb-link-video {
            width: 280px;
            height: 150px;
        }
    }

    .social-footer li {
        margin: 0 5px;
    }
    .social-footer {
        padding: 13px 0;
    }


</style>

<script>
    $("#favicon").attr("href","https://s3foundation.s3-us-west-2.amazonaws.com/fed7ebd414ee52728a6d76c09293a7d0.png");
</script>

<?php


$is = $this->I_model->fetch(array(
    'i__id' => get_domain_setting(14002),
));

//echo '<h1>' .view_cover(12273,$is[0]['i__cover']) . '</h1>';
echo '<div style="text-align: center; padding: 55px;"><img src="/img/valentinu/Valentinu Doge@2x.png" style="width:100%; text-align: center;" class="round"></div>';

//IDEA TITLE
echo '<h1>' . $is[0]['i__title'] . '</h1>';


//MESSAGES
echo '<div class="center-frame larger-font">';
foreach($this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PRIVATE
    'x__type' => 4231, //IDEA NOTES Messages
    'x__right' => $is[0]['i__id'],
), array(), 0, 0, array('x__spectrum' => 'ASC')) as $count => $x) {
    echo $this->X_model->message_view( $x['x__message'], true);
}
echo '</div>';


//FEATURED IDEAS
$counter = 0;
$visible_ui = '';
$topic_id = intval(get_domain_setting(14877));
if($topic_id){
    //Go through Featured Categories:
    foreach($this->config->item('e___'.$topic_id) as $e__id => $m) {

        $query_filters = array(
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PRIVATE
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PRIVATE
            'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
            'x__up' => $e__id,
        );
        $query = $this->X_model->fetch($query_filters, array('x__right'), view_memory(6404,13206), 0, array('i__spectrum' => 'DESC'));
        if(!count($query)){
            continue;
        }

        $ui = '<div class="row justify-content margin-top-down-half">';
        foreach($query as $i){
            $ui .= view_i(14877, 0, null, $i);
        }
        $query2 = $this->X_model->fetch($query_filters, array('x__right'), 1, 0, array(), 'COUNT(x__id) as totals');
        $ui .= '</div>';


        $visible_ui .= view_headline($e__id, null, $m, $ui, !$counter);
        $counter++;
    }
}
echo $visible_ui;


//Info Boxes:
//echo view_info_box();


//SOCIAL FOOTER
$social_nav = '<ul class="social-footer">
    <li><a href="https://t.me/+rkr1EDILgxMyYWNh"><i class="fab fa-telegram"></i></a></li>
    <li><a href="https://discord.gg/hWSnhkYbDA"><i class="fab fa-discord"></i></a></li>
    <li><a href="https://twitter.com/valentinudoge"><i class="fab fa-twitter"></i></a></li>
    <li><a href="mailto:support@valentinudoge.com"><i class="fal fa-envelope-open"></i></a></li>
</ul>


<div class="center-icons">
    <a href="javascript:alert(\'Coming Soon...\')"><img src="/img/mcbroke/light-bscscan.svg" class="Footer_link__DBs2K" style="background-color:#FFF; border-radius: 50%;"></a>
    <a href="javascript:alert(\'Coin Gecko Listing Coming Soon...\')"><img src="/img/mcbroke/coingecko.svg"></a>
    <a href="javascript:alert(\'DexTools Link Coming Soon...\')"><img src="/img/mcbroke/dextools.svg"></a>
    <a href="javascript:alert(\'PooCoin Coming Soon...\')"><img src="/img/mcbroke/poocoin.svg"></a>
</div>

'; //    <a href="javascript:alert(\'Coming Soon...\')"><img src="/img/mcbroke/light-cmc.svg" class="Footer_link__DBs2K" style="background-color:#FFF; border-radius: 50%;"></a>



$call_to_action = '<a class="btn btn-default" href="javascript:alert(\'Coming Soon...\')"><i class="fas fa-usd-circle"></i> Buy Now</a>';

?>


<p style="text-align: center">
    <a class="btn btn-default" href="#tokenomics"><i class="fas fa-coins"></i> Tokenomics</a>
    <a class="btn btn-default" href="#roadmap"><i class="fas fa-clipboard-list-check"></i> Roadmap</a>
    <?php echo $call_to_action; ?>
</p>

<?php //echo view_social() ?>
<?php echo $social_nav ?>

<br />
<br />


<br />
<br />
<br />
<div style="text-align: center;">🌹🌹🌹🌹🌹🌹🌹🌹🌹🌹🌹🌹🌹🌹</div>
<br />
<br />
<a name="tokenomics">&nbsp;</a>
<h2 class="text-center main_title">Tokenomics</h2>


<br />
<div class="row justify-content-center" style="text-align: center; color: #000;">
    <div class="col-12 col-md-4">
        <div class="info_box_cover">🌹️ 8B</div>
        <div class="info_box_title redbold">Flowers</div>
        <div class="info_box_message">Our mission is to deliver personalized flowers for every person on the planet.</div>
    </div>
    <div class="col-12 col-md-4">
        <div class="info_box_cover">❤️‍🔥 1%+</div>
        <div class="info_box_title redbold">Holders</div>
        <div class="info_box_message">Investors holding 1%+ on Feb 14th 6p PST can schedule their flower bouquet for any day up to a year ahead.</div>
    </div>
    <div class="col-12 col-md-4">
        <div class="info_box_cover">🥰 $70B</div>
        <div class="info_box_title redbold">Market</div>
        <div class="info_box_message">Global floral market is estimated to grow from $49B in 2020 to $70B in 2026<br /><a href="https://www.globenewswire.com/news-release/2021/12/14/2351482/0/en/Global-Floriculture-Market-Size-Expected-to-Acquire-USD-70-Billion-By-2026-Facts-Factors.html#:~:text=In%20this%20report%2C%20the%20global,USD%2070%20Billion%20by%202026." target="_blank"><u>*Source: Facts & Factors</u></a></div>
    </div>
</div>

<br />
<br />
<br />
<p>We collect a 15% tax to support the growth our ecommerce platform:</p>

<div class="row justify-content-center" style="text-align: center; color: #000;">
    <div class="col-12 col-md-4">
        <div class="info_box_cover">🌹 9%</div>
        <div class="info_box_title redbold">Flower Gifts</div>
        <div class="info_box_message">Towards our eCommerce platform to buy & deliver flowers in 96 countries across 4 continents</div>
    </div>
    <div class="col-12 col-md-4">
        <div class="info_box_cover">💰 5%</div>
        <div class="info_box_title redbold">Liquidity</div>
        <div class="info_box_message">To keep growing our liquidity pool & build a strong ecommerce business around gift deliveries</div>
    </div>
    <div class="col-12 col-md-4">
        <div class="info_box_cover">📣 1%</div>
        <div class="info_box_title redbold">Marketing</div>
        <div class="info_box_message">So the world hears about our community of lovers who share a passion for making their partners feel special</div>
    </div>
</div>
<br />
<br />


<br />
<br />
<p style="text-align: center">
    <a class="btn btn-default" href="javascript:alert('Coming Soon...')"><i class="fas fa-file-certificate"></i> Contract</a>
    <a class="btn btn-default" href="javascript:alert('Coming Soon...')"><i class="fas fa-lock"></i> IP Lock</a>
</p>


<br />
<br />
<br />
<br />
<br />
<div style="text-align: center;">🌹🌹🌹🌹🌹🌹🌹🌹🌹🌹🌹🌹🌹🌹</div>
<br />
<br />
<br />
<br />
<a name="howtobuy">&nbsp;</a>
<h2 class="text-center main_title">How To Buy?</h2>
<br />
<p style="color: #e50201 !important; font-weight: bold; font-size: 1.5em;" >in 3 easy steps...</p>

<div style="text-align: center; position: relative;">
    <a href="https://www.youtube.com/watch?v=KpF41eS3YZQ" target="_blank" class="htb-link-video"><img src="https://www.babycake.app/images/play.svg" loading="lazy" style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;" alt="" class="play">
        <div style="opacity: 0;" class="video-overlay"></div>
    </a>
</div>


<div class="row justify-content-center">
    <div class="col-12 col-md-4">
        <div class="info_box_cover"><img src="/img/mcbroke/metamask-2728406-2261817.png" style="width:110px;"></div>
        <div class="info_box_title">Setup MetaMask</div>
        <div class="info_box_message" style="text-align: left;">
            <ul>
                <li>
                    Download MetaMask or TrustWallet.
                </li>
                <li>
                    Add the Binance Smart Chain to your network-list.
                </li>
            </ul>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="info_box_cover"><img src="/img/mcbroke/coinbase.png" style="width:110px;" class="round"></div>
        <div class="info_box_title">Buy & Send BNB</div>
        <div class="info_box_message" style="text-align: left;">
            <ul>
                <li>
                    Buy BNB on an exchange. (i.e. Binance, Kraken, Coinbase etc.).
                </li>
                <li>
                    Transfer the tokens to your MetaMask wallet address. BEP-20 addresses start with a "0x".
                </li>
            </ul>

        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="info_box_cover"><img src="/img/mcbroke/pancakeswap-cake-logo.png" style="width:110px;"></div>
        <div class="info_box_title">Swap on Pancake</div>
        <div class="info_box_message" style="text-align: left;">

            <ul>
                <li>
                    You can <a class="btn btn-default" href="#howtobuy">BUY NOW</a> on PancakeSwap.
                </li>
                <li>
                    Select FLOWERS or copy/paste contract address.
                </li>
                <li>
                    Set slippage tolerance to 16-21%
                </li>

            </ul>

        </div>
    </div>
</div>


<br />
<br />
<br />
<br />
<a name="roadmap">&nbsp;</a>
<div style="text-align: center; color: #000; background-color: #000; padding: 21px 0 55px; border-radius: 10px;">

    <h2 class="text-center main_title">Rosy Roadmap</h2>
    <br />
    <br />

    <div class="row justify-content-center">
        <div class="col-12 col-md-4">
            <div class="info_box_cover"><i class="fal fa-flower-daffodil"></i></div>
            <div class="info_box_title">Phase 1</div>
            <div class="info_box_message" style="text-align: left; margin-left: 13px">
                <i class="fas fa-check-circle"></i> Website Launch
                <br /><i class="fas fa-check-circle"></i> Lock & Burn Tokens
                <br /><i class="fas fa-check-circle"></i> Flower Order Form
                <br /><i class="fas fa-check-circle"></i> 96 Countries
                <br /><i class="fal fa-circle"></i> List on CMC & CG
                <br /><i class="fal fa-circle"></i> Contract Audits
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="info_box_cover"><i class="far fa-flower-daffodil"></i></div>
            <div class="info_box_title">Phase 2</div>
            <div class="info_box_message" style="text-align: left; margin-left: 13px">
                <i class="fal fa-circle"></i> Gift Ordering Engine V2
                <br /><i class="fal fa-circle"></i> Social Campaigns
                <br /><i class="fal fa-circle"></i> Promotional Contests
                <br /><i class="fal fa-circle"></i> 120+ Countries
                <br /><i class="fal fa-circle"></i> Florist Partnerships
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="info_box_cover"><i class="fas fa-flower-daffodil"></i></div>
            <div class="info_box_title">Phase 3</div>
            <div class="info_box_message" style="text-align: left; margin-left: 13px">
                <i class="fal fa-circle"></i> Expand Gift Selection
                <br /><i class="fal fa-circle"></i> Lovers NFT Collection
                <br /><i class="fal fa-circle"></i> More Partnerships
                <br /><i class="fal fa-circle"></i> 150+ Countries
                <br /><i class="fal fa-circle"></i> Livestream Giveaways</div>
        </div>
    </div>
</div>


<br />
<br />
<br />
<br />
<br />


<p style="padding-bottom: 21px; text-align: center">
    <?php echo $call_to_action; ?>
</p>


<?php echo $social_nav ?>

<p style="font-size: 0.8em;">Legal Disclaimer: The information provided on this website does not constitute investment advice, financial advice, trading advice, or any other sort of advice and you should not treat any of the website's content as such. The Valentinu Doge team does not recommend that any cryptocurrency should be bought, sold, or held by you. Do conduct your own due diligence and consult your financial advisor before making any investment decisions. By purchasing Valentinu Doge, you agree that you are not purchasing a security or investment, and you agree to hold the team harmless and not liable for any losses or taxes you may incur. You also agree that the team is presenting the token "as is" and is not required to provide any support or services. You should have no expectation of any form from Valentinu Doge and its team. Valentinu Doge is an experimental token for social experiment and not a digital currency. Always make sure that you are in compliance with your local laws and regulations before you make any purchase.</p>
