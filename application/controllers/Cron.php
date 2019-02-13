<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
    }


    //Cache of cron jobs as of now [keep in sync when updating cron file]
    //* * * * * /usr/bin/php /home/ubuntu/mench-web-app/index.php cron fn___facebook_attachment_sync
    //*/5 * * * * /usr/bin/php /home/ubuntu/mench-web-app/index.php cron message_drip
    //*/6 * * * * /usr/bin/php /home/ubuntu/mench-web-app/index.php cron fn___save_media_to_cdn
    //31 * * * * /usr/bin/php /home/ubuntu/mench-web-app/index.php cron fn___in_metadata_update
    //30 2 * * * /usr/bin/php /home/ubuntu/mench-web-app/index.php cron fn___update_algolia b 0
    //30 4 * * * /usr/bin/php /home/ubuntu/mench-web-app/index.php cron fn___update_algolia u 0
    //30 3 * * * /usr/bin/php /home/ubuntu/mench-web-app/index.php cron e_score_recursive


    function go(){
        $string = '<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ac">AC</a></td>
<td>C</td>
<td>com.ac edu.ac gov.ac net.ac mil.ac org.ac
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ad">AD</a></td>
<td>C</td>
<td>ad nom.ad</td>
<td> </td>
<td><a rel="nofollow" class="external autonumber" href="http://www.nic.ad/index_eng.htm">[1]</a>
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ae">AE</a></td>
<td>C</td>
<td>ae net.ae gov.ae org.ae mil.ae sch.ae ac.ae pro.ae name.ae</td>
<td> </td>
<td><a rel="nofollow" class="external autonumber" href="http://www.nic.ae/english/index.jsp">[2]</a>
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.aero">AERO</a></td>
<td>A</td>
<td>aero</td>
<td> </td>
<td><a rel="nofollow" class="external autonumber" href="http://www.nic.aero/">[3]</a>
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.af">AF</a></td>
<td>C</td>
<td>af gov.af edu.af net.af com.af</td>
<td> </td>
<td><a rel="nofollow" class="external autonumber" href="http://www.nic.af/">[4]</a>
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ag">AG</a></td>
<td>C</td>
<td>ag com.ag org.ag net.ag co.ag nom.ag</td>
<td> </td>
<td><a rel="nofollow" class="external autonumber" href="http://www.nic.ag/">[5]</a>
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ai">AI</a></td>
<td>C</td>
<td>ai off.ai com.ai net.ai org.ai</td>
<td> </td>
<td><a rel="nofollow" class="external autonumber" href="http://nic.com.ai/">[6]</a>
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.al">AL</a></td>
<td>C</td>
<td>gov.al edu.al org.al com.al net.al (uniti.al tirana.al soros.al upt.al inima.al)
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.am">AM</a></td>
<td>A</td>
<td>am</td>
<td> </td>
<td><a rel="nofollow" class="external autonumber" href="https://www.amnic.net/">[7]</a>
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.an">AN</a></td>
<td>C</td>
<td>an com.an net.an org.an edu.an</td>
<td> </td>
<td><a rel="nofollow" class="external autonumber" href="http://www.una.an/an_domreg/">[8]</a> (in Dutch)
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ao">AO</a></td>
<td>B</td>
<td>co.ao ed.ao gv.ao it.ao og.ao pb.ao</td>
<td> </td>
<td><a rel="nofollow" class="external autonumber" href="http://www.dns.ao/">[9]</a>
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.aq">AQ</a></td>
<td> </td>
<td> </td>
<td>This TLD is unused.
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ar">AR</a></td>
<td>B</td>
<td>com.ar gov.ar int.ar mil.ar net.ar org.ar</td>
<td> </td>
<td><a rel="nofollow" class="external autonumber" href="http://www.nic.ar/">[10]</a> (in Spanish)
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.arpa">ARPA</a></td>
<td>B</td>
<td>e164.arpa in-addr.arpa iris.arpa ip6.arpa uri.arpa urn.arpa</td>
<td>Domains cannot be registered under the .arpa TLD.</td>
<td>n/a
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.as">AS</a></td>
<td>A</td>
<td>as</td>
<td> </td>
<td><a rel="nofollow" class="external autonumber" href="http://www.nic.as/">[11]</a>
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.at">AT</a></td>
<td>C</td>
<td>at gv.at ac.at co.at or.at priv.at</td>
<td> </td>
<td><a rel="nofollow" class="external autonumber" href="http://www.nic.at/en/index/">[12]</a>
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.au">AU</a></td>
<td>D</td>
<td>asn.au com.au net.au id.au org.au csiro.au oz.au info.au conf.au act.au nsw.au nt.au qld.au sa.au tas.au vic.au wa.au<br>For gov.au and edu.au: act nsw nt qld sa tas vic wa</td>
<td>Mostly B, but gov.au and edu.au are split geographically. Tertiary institutions are typically exempt from requiring state-based distinctions; these need to be set as exemptions.</td>
<td><a rel="nofollow" class="external autonumber" href="http://www.auda.org.au/">[13]</a>
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.aw">AW</a></td>
<td>C</td>
<td>aw com.aw</td>
<td> </td>
<td><a rel="nofollow" class="external autonumber" href="http://www.setarnet.aw/domreg.html">[14]</a>
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ax">AX</a></td>
<td>A</td>
<td>ax</td>
<td> </td>
<td><a rel="nofollow" class="external autonumber" href="http://www.regeringen.ax/axreg/">[15]</a> (in Swedish)
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.az">AZ</a></td>
<td>C</td>
<td>az com.az net.az int.az gov.az biz.az org.az edu.az mil.az pp.az name.az info.az</td>
<td> </td>
<td><a rel="nofollow" class="external autonumber" href="http://www.nic.az/EnIndex.htm">[16]</a>
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ba">BA</a></td>
<td>C</td>
<td>???
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.bb">BB</a></td>
<td>C</td>
<td>com.bb edu.bb gov.bb net.bb org.bb
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.bd">BD</a></td>
<td>B</td>
<td>com.bd edu.bd net.bd gov.bd org.bd mil.bd
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.be">BE</a></td>
<td>C</td>
<td>ac.be</td>
<td>There are commercial 3rd level domains (to.be, com.be, co.be, xa.be, ap.be) but not official<br>fgov.be is the government, but I\'m not sure that counts
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.bf">BF</a></td>
<td>C</td>
<td>gov.bf
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.bg">BG</a></td>
<td>A
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.bh">BH</a></td>
<td>C</td>
<td>???
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.bi">BI</a></td>
<td>C</td>
<td>???
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.biz">BIZ</a></td>
<td>A
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.bj">BJ</a></td>
<td>C</td>
<td>???
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.bm">BM</a></td>
<td>C</td>
<td>possibly com.bm edu.bm org.bm gov.bm net.bm
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.bn">BN</a></td>
<td>B</td>
<td>com.bn edu.bn org.bn net.bn
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.bo">BO</a></td>
<td>C</td>
<td>bo com.bo org.bo net.bo gov.bo gob.bo edu.bo tv.bo mil.bo int.bo
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.br">BR</a></td>
<td>B</td>
<td>agr.br am.br art.br edu.br com.br coop.br esp.br far.br fm.br g12.br gov.br imb.br ind.br inf.br mil.br net.br org.br psi.br rec.br srv.br tmp.br tur.br tv.br etc.br adm.br adv.br arq.br ato.br bio.br bmd.br cim.br cng.br cnt.br ecn.br eng.br eti.br fnd.br fot.br fst.br ggf.br jor.br lel.br mat.br med.br mus.br not.br ntr.br odo.br ppg.br pro.br psc.br qsl.br slg.br trd.br vet.br zlg.br dpn.br nom.br
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.bs">BS</a></td>
<td>C</td>
<td>bs com.bs net.bs org.bs (others&nbsp;?)
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.bt">BT</a></td>
<td>C</td>
<td>bt com.bt edu.bt gov.bt net.bt org.bt
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.bv">BV</a></td>
<td> </td>
<td> </td>
<td>This TLD is unused.
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.bw">BW</a></td>
<td>C</td>
<td>bw co.bw org.bw (others&nbsp;?)
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.by">BY</a></td>
<td>C</td>
<td>gov.by mil.by
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.bz">BZ</a></td>
<td>C</td>
<td>???
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ca">CA</a></td>
<td>C</td>
<td>.ca .ab.ca .bc.ca .mb.ca .nb.ca .nf.ca .nl.ca .ns.ca .nt.ca .nu.ca .on.ca .pe.ca .qc.ca .sk.ca .yk.ca
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.cat">CAT</a></td>
<td>A
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.cc">CC</a></td>
<td>C</td>
<td>.cc .co.cc</td>
<td>.co.cc sells as a tld</td>
<td><a rel="nofollow" class="external autonumber" href="http://www.enic.cc">[17]</a>, <a rel="nofollow" class="external autonumber" href="https://www.co.cc">[18]</a>
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.cd">CD</a></td>
<td>C</td>
<td>.cd .com.cd .net.cd .org.cd (others&nbsp;?)
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.cf">CF</a></td>
<td>A
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.cg">CG</a>
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ch">CH</a></td>
<td>C?</td>
<td>.ch .com.ch .net.ch .org.ch .gov.ch
</td></tr>
<tr>
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ci">CI</a>
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ck">CK</a></td>
<td>C</td>
<td>.co.ck and others
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.cl">CL</a></td>
<td>A
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.cm">CM</a>
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.cn">CN</a></td>
<td>C</td>
<td>.cn .ac.cn .com.cn .edu.cn .gov.cn .net.cn .org.cn .ah.cn .bj.cn .cq.cn .fj.cn .gd.cn .gs.cn .gz.cn .gx.cn .ha.cn .hb.cn .he.cn .hi.cn .hl.cn .hn.cn .jl.cn .js.cn .jx.cn .ln.cn .nm.cn .nx.cn .qh.cn .sc.cn .sd.cn .sh.cn .sn.cn .sx.cn .tj.cn .xj.cn .xz.cn .yn.cn .zj.cn
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.co">CO</a></td>
<td>B</td>
<td>.com.co .edu.co .org.co .gov.co .mil.co .net.co .nom.co
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.com">COM</a></td>
<td>C</td>
<td>.us.com
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.coop">COOP</a></td>
<td>C
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.cr">CR</a></td>
<td>B</td>
<td>ac.cr co.cr ed.cr fi.cr go.cr or.cr sa.cr
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.cu">CU</a></td>
<td>C</td>
<td>.cu .com.cu .edu.cu .org.cu .net.cu .gov.cu .inf.cu
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.cv">CV</a>
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.cx">CX</a></td>
<td>C</td>
<td>.cx .gov.cx
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.cy">CY</a></td>
<td>B</td>
<td>com.cy biz.cy info.cy ltd.cy pro.cy net.cy org.cy name.cy tm.cy ac.cy ekloges.cy press.cy parliament.cy
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.cz">CZ</a></td>
<td>A
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.de">DE</a></td>
<td>A? </td>
<td> </td>
<td> <a rel="nofollow" class="external text" href="http://de.geek-tools.org/en/">More info</a> about .de domains
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.dj">DJ</a></td>
<td>A?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.dk">DK</a></td>
<td>A
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.dm">DM</a></td>
<td>C</td>
<td>.dm com.dm net.dm org.dm edu.dm gov.dm
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.do">DO</a></td>
<td>B</td>
<td>.edu.do .gov.do .gob.do .com.do .org.do .sld.do .web.do .net.do .mil.do .art.do
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.dz">DZ</a></td>
<td>C</td>
<td>.dz .com.dz .org.dz .net.dz .gov.dz .edu.dz .asso.dz .pol.dz .art.dz
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ec">EC</a></td>
<td>C</td>
<td>.ec .com.ec .info.ec .net.ec .fin.ec .med.ec .pro.ec .org.ec .edu.ec .gov.ec .mil.ec (others&nbsp;?)
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.edu">EDU</a></td>
<td>A
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ee">EE</a></td>
<td>C</td>
<td>.ee .com.ee .org.ee .fie.ee .pri.ee (others&nbsp;?)
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.eg">EG</a></td>
<td>B</td>
<td>.eun.eg .edu.eg .sci.eg .gov.eg .com.eg .org.eg .net.eg .mil.eg
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.er">ER</a>
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.es">ES</a></td>
<td>C</td>
<td>.es .com.es .nom.es .org.es .gob.es .edu.es (others&nbsp;?)
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.et">ET</a></td>
<td>B</td>
<td>.com.et .gov.et .org.et .edu.et .net.et .biz.et .name.et .info.et
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.eu">EU</a></td>
<td>A
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.fi">FI</a></td>
<td>C</td>
<td>.fi .aland.fi
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.fj">FJ</a></td>
<td>B</td>
<td>biz.fj com.fj info.fj name.fj net.fj org.fj pro.fj ac.fj gov.fj mil.fj school.fj
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.fk">FK</a></td>
<td>B</td>
<td>.co.fk .org.fk .gov.fk .ac.fk .nom.fk .net.fk
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.fm">FM</a></td>
<td>A?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.fo">FO</a></td>
<td>A?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.fr">FR</a></td>
<td>C</td>
<td>.fr .tm.fr .asso.fr .nom.fr .prd.fr .presse.fr .com.fr .gouv.fr
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ga">GA</a></td>
<td>?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.gb">GB</a></td>
<td> </td>
<td> </td>
<td> This TLD is unused.
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.gd">GD</a></td>
<td>?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ge">GE</a></td>
<td>C</td>
<td>.ge .com.ge .edu.ge .gov.ge .org.ge .mil.ge .net.ge .pvt.ge
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.gf">GF</a></td>
<td>?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.gg">GG</a></td>
<td>C</td>
<td>.gg .co.gg .net.gg .org.gg (others&nbsp;?)
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.gh">GH</a></td>
<td>B</td>
<td>com.gh edu.gh gov.gh org.gh mil.gh
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.gi">GI</a></td>
<td>C</td>
<td>.gi .com.gi .ltd.gi .gov.gi .mod.gi .edu.gi .org.gi
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.gl">GL</a></td>
<td>A</td>
<td>
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.gm">GM</a></td>
<td>A
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.gn">GN</a></td>
<td>B</td>
<td>.com.gn .ac.gn .gov.gn .org.gn .net.gn
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.gov">GOV</a></td>
<td>A
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.gp">GP</a></td>
<td>C</td>
<td>.gp .com.gp, .net.gp, .edu.gp, .asso.gp, or .org.gp
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.gq">GQ</a></td>
<td>?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.gr">GR</a></td>
<td>C</td>
<td>.gr .com.gr .edu.gr .net.gr .org.gr .gov.gr
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.gs">GS</a></td>
<td>?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.gt">GT</a></td>
<td>?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.gu">GU</a></td>
<td>B</td>
<td>???
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.gw">GW</a></td>
<td>?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.gy">GY</a></td>
<td>?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.hk">HK</a></td>
<td>C</td>
<td>.hk .com.hk .edu.hk .gov.hk .idv.hk .net.hk .org.hk
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.hm">HM</a></td>
<td>A?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.hn">HN</a></td>
<td>C</td>
<td>.hn .com.hn .edu.hn .org.hn .net.hn .mil.hn .gob.hn
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.hm">HR</a></td>
<td>C</td>
<td>.hr .iz.hr .from.hr .name.hr .com.hr
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ht">HT</a></td>
<td>C</td>
<td>.ht .com.ht .net.ht .firm.ht .shop.ht .info.ht .pro.ht .adult.ht .org.ht .art.ht .pol.ht .rel.ht .asso.ht .perso.ht .coop.ht .med.ht .edu.ht .gouv.ht
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.hu">HU</a></td>
<td>C</td>
<td>.hu co.hu info.hu org.hu priv.hu sport.hu tm.hu 2000.hu agrar.hu bolt.hu casino.hu city.hu erotica.hu erotika.hu film.hu forum.hu games.hu hotel.hu ingatlan.hu jogasz.hu konyvelo.hu lakas.hu media.hu news.hu reklam.hu sex.hu shop.hu suli.hu szex.hu tozsde.hu utazas.hu video.hu
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.id">ID</a></td>
<td>B</td>
<td>ac.id co.id or.id go.id
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ie">IE</a></td>
<td>C</td>
<td>.ie .gov.ie
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.il">IL</a></td>
<td>B</td>
<td>ac.il co.il org.il net.il k12.il gov.il muni.il idf.il
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.im">IM</a></td>
<td>D</td>
<td>co.im ltd.co.im plc.co.im net.im gov.im org.im nic.im ac.im</td>
<td>4th level domains are possible within co.im
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.in">IN</a></td>
<td>C</td>
<td>.in .co.in .firm.in .net.in .org.in .gen.in .ind.in .nic.in .ac.in .edu.in .res.in .gov.in .mil.in</td>
<td>many governement  domains in nic.in instead of gov.in
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.info">INFO</a></td>
<td>A
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.int">INT</a></td>
<td>C?</td>
<td> </td>
<td>should be A, but there exists a europa.eu.int too
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.io">IO</a></td>
<td>C</td>
<td>???
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.iq">IQ</a></td>
<td> </td>
<td> </td>
<td>This TLD is unused.
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ir">IR</a></td>
<td>C</td>
<td>.ir .ac.ir .co.ir .gov.ir .net.ir .org.ir .sch.ir
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.is">IS</a></td>
<td>C</td>
<td>???</td>
<td>ac.is, org.is, etc ... exists, but didn\'t found a list
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.it">IT</a></td>
<td>D</td>
<td>.it .gov.it others ...</td>
<td>most are 2nd level, but 3rd and 4th level geographical names exists like pisa.it and pontedera.pisa.it
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.je">JE</a></td>
<td>C</td>
<td>.je .co.je .net.je .org.je
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.jm">JM</a></td>
<td>B</td>
<td>.edu.jm .gov.jm .com.jm .net.jm org.jm
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.jo">JO</a></td>
<td>C</td>
<td>.jo .com.jo .org.jo .net.jo .edu.jo .gov.jo .mil.jo
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.jobs">JOBS</a></td>
<td>A?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.jp">JP</a></td>
<td>D</td>
<td>.jp ac.jp ad.jp co.jp ed.jp go.jp gr.jp lg.jp ne.jp or.jp<br>Geo-names: hokkaido.jp  aomori.jp  iwate.jp  miyagi.jp  akita.jp  yamagata.jp fukushima.jp  ibaraki.jp  tochigi.jp  gunma.jp  saitama.jp  chiba.jp tokyo.jp  kanagawa.jp  niigata.jp  toyama.jp  ishikawa.jp  fukui.jp yamanashi.jp  nagano.jp  gifu.jp  shizuoka.jp  aichi.jp  mie.jp shiga.jp  kyoto.jp  osaka.jp  hyogo.jp  nara.jp  wakayama.jp tottori.jp  shimane.jp  okayama.jp  hiroshima.jp  yamaguchi.jp tokushima.jp  kagawa.jp  ehime.jp  kochi.jp  fukuoka.jp  saga.jp nagasaki.jp  kumamoto.jp  oita.jp  miyazaki.jp  kagoshima.jp okinawa.jp  sapporo.jp  sendai.jp  yokohama.jp  kawasaki.jp nagoya.jp  kobe.jp kitakyushu.jp</td>
<td>complicated rules for geo-names: bug <a rel="nofollow" class="external text" href="https://bugzilla.mozilla.org/show_bug.cgi?id=252342#c31">252342</a>
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ke">KE</a></td>
<td>B</td>
<td>???
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.kg">KG</a></td>
<td>C</td>
<td>???
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.kh">KH</a></td>
<td>B</td>
<td>per.kh com.kh edu.kh gov.kh mil.kh net.kh org.kh
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ki">KI</a></td>
<td>B?</td>
<td>???
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.km">KM</a></td>
<td>A?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.kn">KN</a></td>
<td>?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.kr">KR</a></td>
<td>C</td>
<td>.kr .co.kr .or.kr others???
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.kw">KW</a></td>
<td>B</td>
<td>.com.kw .edu.kw .gov.kw .net.kw .org.kw .mil.kw
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ky">KY</a></td>
<td>C</td>
<td>.ky .edu.ky .gov.ky .com.ky .org.ky .net.ky
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.kz">KZ</a></td>
<td>B</td>
<td>.org.kz .edu.kz .net.kz .gov.kz .mil.kz .com.kz
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.la">LA</a></td>
<td>A
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.lb">LB</a></td>
<td>B</td>
<td>net.lb org.lb gov.lb edu.lb com.lb
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.lc">LC</a></td>
<td>B</td>
<td>com.lc org.lc edu.lc gov.lc
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.li">LI</a></td>
<td>C?</td>
<td>.li .com.li .net.li .org.li .gov.li (others?)
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.lk">LK</a></td>
<td>C</td>
<td>.lk .gov.lk .sch.lk .net.lk .int.lk .com.lk .org.lk .edu.lk .ngo.lk .soc.lk .web.lk .ltd.lk .assn.lk .grp.lk .hotel.lk
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.lr">LR</a></td>
<td>B</td>
<td>.com.lr .edu.lr .gov.lr .org.lr .net.lr
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ls">LS</a></td>
<td>B</td>
<td>.org.ls .co.ls
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.lt">LT</a></td>
<td>C</td>
<td>.lt .gov.lt .mil.lt others?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.lu">LU</a></td>
<td>C</td>
<td>.lu .gov.lu .mil.lu .org.lu .net.lu others?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.lv">LV</a></td>
<td>C</td>
<td>.lv .com.lv .edu.lv .gov.lv .org.lv .mil.lv .id.lv .net.lv .asn.lv .conf.lv
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ly">LY</a></td>
<td>C</td>
<td>.ly .com.ly .net.ly .gov.ly .plc.ly .edu.ly .sch.ly .med.ly .org.ly .id.ly
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ma">MA</a></td>
<td>C</td>
<td>.ma .co.ma .net.ma .gov.ma .org.ma others?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.mc">MC</a></td>
<td>C</td>
<td>.mc .tm.mc .asso.mc
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.md">MD</a></td>
<td>A?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.mg">MG</a></td>
<td>C</td>
<td>.mg .org.mg .nom.mg .gov.mg .prd.mg .tm.mg .com.mg .edu.mg .mil.mg others!!!</td>
<td>I didn\'t find a full list, but there could be many as suggested on their NIC
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.mh">MH</a></td>
<td>?</td>
<td> </td>
<td>.net.mh
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.mil">MIL</a></td>
<td>C?</td>
<td>army.mil navy.mil ...
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.mk">MK</a></td>
<td>C</td>
<td>.mk .com.mk .org.mk others?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ml">ML</a></td>
<td>B</td>
<td>???
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.mm">MM</a></td>
<td>B?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.mn">MN</a></td>
<td>A
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.mo">MO</a></td>
<td>C</td>
<td>.mo .com.mo .net.mo .org.mo .edu.mo .gov.mo
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.mobi">MOBI</a></td>
<td>C</td>
<td>weather.mobi music.mobi ...
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.mp">MP</a></td>
<td>A
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.mq">MQ</a></td>
<td>A
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.mr">MR</a></td>
<td>A?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ms">MS</a></td>
<td>?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.mt">MT</a></td>
<td>C</td>
<td>.mt .org.mt .com.mt .gov.mt .edu.mt .net.mt
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.mu">MU</a></td>
<td>C</td>
<td>.mu .com.mu .co.mu others?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.museum">MUSEUM</a></td>
<td>C
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.mv">MV</a></td>
<td>B</td>
<td>.aero.mv .biz.mv .com.mv .coop.mv .edu.mv .gov.mv .info.mv .int.mv .mil.mv .museum.mv .name.mv .net.mv .org.mv .pro.mv
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.mw">MW</a></td>
<td>B</td>
<td>ac.mw co.mw com.mw coop.mw edu.mw gov.mw int.mw museum.mw net.mw org.mw
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.mx">MX</a></td>
<td>B</td>
<td>.com.mx .net.mx .org.mx .edu.mx .gob.mx
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.my">MY</a></td>
<td>B</td>
<td>.com.my .net.my .org.my .gov.my .edu.my .mil.my .name.my
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.mz">MZ</a></td>
<td>?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.na">NA</a></td>
<td>?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.name">NAME</a></td>
<td>C</td>
<td> </td>
<td>Used to be B, but now has second level too. Complicated to work out which second level domains are allowed to set cookies and which are not.
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.nc">NC</a></td>
<td>A?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ne">NE</a></td>
<td>?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.net">NET</a></td>
<td>A
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.nf">NF</a></td>
<td>?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ng">NG</a></td>
<td>B</td>
<td>.edu.ng .com.ng .gov.ng .org.ng .net.ng
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ni">NI</a></td>
<td>B?</td>
<td>.gob.ni .com.ni .edu.ni .org.ni .nom.ni .net.ni
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.nl">NL</a></td>
<td>C</td>
<td>.nl .000.nl/.999.nl </td>
<td>personal domainames are 3th level behind a 3 digit numeric code like johnsmith.752.nl
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.no">NO</a></td>
<td>C</td>
<td>.no mil.no stat.no kommune.no herad.no priv.no vgs.no fhs.no museum.no fylkesbibl.no folkebibl.no idrett.no [geo].no gs.[county].no
</td>
<td> geographic names listed <a rel="nofollow" class="external text" href="http://www.norid.no/regelverk/vedlegg-b.en.html">here</a> (includes normal and IDN versions)
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.np">NP</a></td>
<td>B</td>
<td>com.np org.np edu.np net.np gov.np mil.np
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.nr">NR</a></td>
<td>C</td>
<td>.nr .gov.nr .edu.nr .biz.nr .info.nr .nr org.nr .com.nr .net.nr</td>
<td>co.nr is not a official TLD, but acts like one
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.nu">NU</a></td>
<td>A
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.nz">NZ</a></td>
<td>B</td>
<td>.ac.nz .co.nz .cri.nz .gen.nz .geek.nz .govt.nz .iwi.nz .maori.nz .mil.nz .net.nz .org.nz .school.nz
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.om">OM</a></td>
<td>B</td>
<td>com.om co.om edu.om ac.com sch.om gov.om net.om org.om mil.om museum.om biz.om pro.om med.om
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.org">ORG</a></td>
<td>A
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.pa">PA</a></td>
<td>B</td>
<td>com.pa ac.pa sld.pa gob.pa edu.pa org.pa net.pa abo.pa ing.pa med.pa nom.pa
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.pe">PE</a></td>
<td>B</td>
<td>com.pe org.pe net.pe edu.pe mil.pe gob.pe nom.pe
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.pf">PF</a></td>
<td>C</td>
<td>.pf .com.pf .org.pf .edu.pf
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.pg">PG</a></td>
<td>B</td>
<td>.com.pg .net.pg
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ph">PH</a></td>
<td>C</td>
<td>.ph .com.ph .gov.ph others?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.pk">PK</a></td>
<td>C</td>
<td>.pk .com.pk .net.pk .edu.pk .org.pk .fam.pk .biz.pk .web.pk .gov.pk .gob.pk .gok.pk .gon.pk .gop.pk .gos.pk
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.pl">PL</a></td>
<td>C</td>
<td>.pl .com.pl .biz.pl .net.pl .art.pl .edu.pl .org.pl .ngo.pl .gov.pl .info.pl .mil.pl <br>Geographic: .waw.pl .warszawa.pl .wroc.pl .wroclaw.pl .krakow.pl .poznan.pl .lodz.pl .gda.pl .gdansk.pl .slupsk.pl .szczecin.pl .lublin.pl .bialystok.pl .olsztyn.pl.torun.pl <a href="/TLD_List:.pl" title="TLD List:.pl">and more...</a></td>
<td><a rel="nofollow" class="external text" href="http://www.dns.pl/english/zonestats.html">here</a> are more domains visible
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.pm">PM</a></td>
<td> </td>
<td> </td>
<td>This TLD is unused.
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.pn">PN</a></td>
<td>?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.pr">PR</a></td>
<td>C</td>
<td>.pr .biz.pr .com.pr .edu.pr .gov.pr .info.pr .isla.pr .name.pr .net.pr .org.pr .pro.pr
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.pro">PRO</a></td>
<td>C</td>
<td>law.pro med.pro cpa.pro
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ps">PS</a></td>
<td>C</td>
<td>.ps .edu.ps .gov.ps .sec.ps .plo.ps .com.ps .org.ps .net.ps
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.pt">PT</a></td>
<td>C</td>
<td>.pt .com.pt .edu.pt .gov.pt .int.pt .net.pt .nome.pt .org.pt .publ.pt
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.pw">PW</a></td>
<td>B?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.py">PY</a></td>
<td>B</td>
<td>.net.py .org.py .gov.py .edu.py .com.py
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.qa">QA</a></td>
<td>B</td>
<td>???
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.re">RE</a></td>
<td>?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ro">RO</a></td>
<td>C</td>
<td>.ro .com.ro .org.ro .tm.ro .nt.ro .nom.ro .info.ro .rec.ro .arts.ro .firm.ro .store.ro .www.ro </td>
<td><a rel="nofollow" class="external text" href="http://www.siteuri.org">here</a> are more details about Romanian domain names
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ru">RU</a></td>
<td>C</td>
<td>.ru .com.ru .net.ru .org.ru .pp.ru .msk.ru .int.ru .ac.ru others!!!
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.rw">RW</a></td>
<td>C</td>
<td>.rw .gov.rw .net.rw .edu.rw .ac.rw .com.rw .co.rw .int.rw .mil.rw .gouv.rw
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.sa">SA</a></td>
<td>B</td>
<td>.com.sa .edu.sa .sch.sa .med.sa .gov.sa .net.sa .org.sa .pub.sa
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.sb">SB</a></td>
<td>B</td>
<td>.com.sb .gov.sb .net.sb .edu.sb others?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.sc">SC</a></td>
<td>C</td>
<td>.sc .com.sc .gov.sc .net.sc .org.sc  .edu.sc
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.sd">SD</a></td>
<td>C</td>
<td>.sd .com.sd .net.sd .org.sd .edu.sd .med.sd .tv.sd .gov.sd .info.sd
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.se">SE</a></td>
<td>C</td>
<td>.se .org.se .pp.se .tm.se .brand.se .parti.se .press.se .komforb.se .kommunalforbund.se .komvux.se .lanarb.se .lanbib.se .naturbruksgymn.se .sshn.se .fhv.se .fhsk.se .fh.se .mil.se<br>Geographical: .ab.se .c.se .d.se .e.se .f.se .g.se .h.se .i.se .k.se .m.se .n.se .o.se .s.se .t.se .u.se .w.se .x.se .y.se .z.se .ac.se .bd.se
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.sg">SG</a></td>
<td>C</td>
<td>.sg .com.sg .net.sg .org.sg .gov.sg .edu.sg .per.sg .idn.sg
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.sh">SH</a></td>
<td>C</td>
<td>???
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.si">SI</a></td>
<td>A?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.sj">SJ</a></td>
<td> </td>
<td> </td>
<td> This TLD is unused.
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.sk">SK</a></td>
<td>?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.sl">SL</a></td>
<td>?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.sm">SM</a></td>
<td>A
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.sn">SN</a></td>
<td>A
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.so">SO</a></td>
<td> </td>
<td> </td>
<td> This TLD is unused.
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.sr">SR</a></td>
<td>A?</td>
<td> </td>
<td>.rs.sr is Republika Srpska
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.st">ST</a></td>
<td>A?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.su">SU</a></td>
<td>A</td>
<td> </td>
<td>The future of this TLD is unclear.
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.sv">SV</a></td>
<td>B</td>
<td>.edu.sv .com.sv .gob.sv .org.sv .red.sv
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.sy">SY</a></td>
<td>B?</td>
<td>.gov.sy .com.sy .net.sy others?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.sz">SZ</a></td>
<td>A?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.tc">TC</a></td>
<td>?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.td">TD</a></td>
<td>A?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.tf">TF</a></td>
<td>?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.tg">TG</a></td>
<td>C?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.th">TH</a></td>
<td>B</td>
<td>.ac.th .co.th .in.th .go.th .mi.th .or.th .net.th
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.tj">TJ</a></td>
<td>C</td>
<td>.tj .ac.tj .biz.tj .com.tj .co.tj .edu.tj .int.tj .name.tj .net.tj .org.tj .web.tj .gov.tj .go.tj .mil.tj
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.tk">TK</a></td>
<td>A
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.tl">TL</a></td>
<td> </td>
<td> </td>
<td> This is the new TLD for Timor-Leste; see .tp for info
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.tm">TM</a></td>
<td>C?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.tn">TN</a></td>
<td>B</td>
<td>.com.tn .intl.tn .gov.tn .org.tn .ind.tn .nat.tn .tourism.tn .info.tn .ens.tn .fin.tn .net.tn
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.to">TO</a></td>
<td>C</td>
<td>.to .gov.to others?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.tp">TP</a></td>
<td>C</td>
<td>.tp .gov.tp others?</td>
<td> Should become unused, replaced with .tl
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.tr">TR</a></td>
<td>D</td>
<td>.com.tr .info.tr .biz.tr .net.tr .org.tr .web.tr .gen.tr .av.tr .dr.tr .bbs.tr .name.tr .tel.tr .gov.tr .bel.tr .pol.tr .mil.tr .k12.tr .edu.tr</td>
<td>.bel.tr is used for geographical names
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.travel">TRAVEL</a></td>
<td>A</td>
<td> </td>
<td>third levels seem to be explicitly forbidden
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.tt">TT</a></td>
<td>C</td>
<td>.tt .co.tt .com.tt .org.tt .net.tt .biz.tt .info.tt .pro.tt .name.tt .edu.tt .gov.tt</td>
<td>us.tt is not an official 2nd-level domain, but acts as one
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.tv">TV</a></td>
<td>C</td>
<td>.tv .gov.tv others?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.tw">TW</a></td>
<td>C</td>
<td>.tw .edu.tw .gov.tw .mil.tw .com.tw .net.tw .org.tw .idv.tw .game.tw .ebiz.tw .club.tw 網路.tw 組織.tw 商業.tw</td>
<td>there are 3 IDN domains in this list&nbsp;!
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.tz">TZ</a></td>
<td>B</td>
<td>.co.tz .ac.tz .go.tz .or.tz .ne.tz
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ua">UA</a></td>
<td>C</td>
<td>.ua .com.ua .gov.ua .net.ua .edu.ua .org.ua<br>Geographical names: .cherkassy.ua .ck.ua .chernigov.ua .cn.ua .chernovtsy.ua .cv.ua .crimea.ua .dnepropetrovsk.ua .dp.ua .donetsk.ua .dn.ua .ivano-frankivsk.ua .if.ua .kharkov.ua .kh.ua .kherson.ua .ks.ua .khmelnitskiy.ua .km.ua .kiev.ua .kv.ua .kirovograd.ua .kr.ua .lugansk.ua .lg.ua .lutsk.ua .lviv.ua .nikolaev.ua .mk.ua .odessa.ua .od.ua .poltava.ua .pl.ua .rovno.ua .rv.ua .sebastopol.ua .sumy.ua .ternopil.ua .te.ua .uzhgorod.ua .vinnica.ua .vn.ua .zaporizhzhe.ua .zp.ua .zhitomir.ua .zt.ua
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ug">UG</a></td>
<td>C</td>
<td>.ug .co.ug .ac.ug .sc.ug .go.ug .ne.ug .or.ug
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.uk">UK</a></td>
<td>B</td>
<td>
<p>.ac.uk .co.uk .gov.uk .ltd.uk .me.uk .mil.uk .mod.uk .net.uk .nic.uk .nhs.uk .org.uk .plc.uk .police.uk .sch.uk||exceptions:
.bl.uk .british-library.uk .icnet.uk .jet.uk .nel.uk .nls.uk .national-library-scotland.uk .parliament.uk<br>.sch.uk uses 4th level domains
</p>
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.um">UM</a></td>
<td>C?</td>
<td> </td>
<td>unused
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.us">US</a></td>
<td>C</td>
<td>.us .ak.us .al.us .ar.us .az.us .ca.us .co.us .ct.us .dc.us .de.us .dni.us .fed.us .fl.us .ga.us .hi.us .ia.us .id.us .il.us .in.us .isa.us .kids.us .ks.us .ky.us .la.us .ma.us .md.us .me.us .mi.us .mn.us .mo.us .ms.us .mt.us .nc.us .nd.us .ne.us .nh.us .nj.us .nm.us .nsn.us .nv.us .ny.us .oh.us .ok.us .or.us .pa.us .ri.us .sc.us .sd.us .tn.us .tx.us .ut.us .vt.us .va.us .wa.us .wi.us .wv.us .wy.us</td>
<td> there are still some 4th level domain within the geographical ones (localities, k12, pvt.k12, cc, tec, lib, state, gen)
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.uy">UY</a></td>
<td>B</td>
<td>.edu.uy .gub.uy .org.uy .com.uy .net.uy .mil.uy
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.uz">UZ</a></td>
<td>A?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.va">VA</a></td>
<td>B</td>
<td>vatican.va
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.vc">VC</a></td>
<td>A?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ve">VE</a></td>
<td>B</td>
<td>.com.ve .net.ve .org.ve .info.ve .co.ve .web.ve
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.vg">VG</a></td>
<td>?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.vi">VI</a></td>
<td>C</td>
<td>.vi .com.vi .org.vi .edu.vi .gov.vi
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.vn">VN</a></td>
<td>C</td>
<td>.vn .com.vn .net.vn .org.vn .edu.vn .gov.vn .int.vn .ac.vn .biz.vn .info.vn .name.vn .pro.vn .health.vn
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.vu">VU</a></td>
<td>A?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.wf">WF</a></td>
<td> </td>
<td> </td>
<td>This TLD is unused.
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ws">WS</a></td>
<td>A
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.ye">YE</a></td>
<td>B?</td>
<td>.com.ye .net.ye others?
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.yt">YT</a></td>
<td> </td>
<td> </td>
<td>This TLD is unused.
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.yu">YU</a></td>
<td>B</td>
<td>.ac.yu .co.yu .org.yu .edu.yu</td>
<td>The future of this TLD is unclear.
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.za">ZA</a></td>
<td>B</td>
<td>.ac.za .city.za .co.za .edu.za .gov.za .law.za .mil.za .nom.za .org.za .school.za .alt.za .net.za .ngo.za .tm.za .web.za</td>
<td> <a rel="nofollow" class="external text" href="http://www.zadna.org.za/slds.html">list of sld\'s</a>
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.zm">ZM</a></td>
<td>B</td>
<td>.co.zm .org.zm .gov.zm .sch.zm .ac.zm
</td></tr>
<tr valign="top">
<td> <a rel="nofollow" class="external text" href="http://en.wikipedia.org/wiki/.zw">ZW</a></td>
<td>B</td>
<td>.co.zw .org.zw .gov.zw .ac.zw
</td></tr>';

        $rows = explode('<tr', $string);
        $all_tlds = array();
        foreach($rows as $row){

            $columns = explode('<td>', $row);
            if(!isset($columns[3])){
                continue;
            }

            $columns[3]= str_replace('||exceptions:',' ', $columns[3]);
            $tlds = explode(' ', $columns[3]);

            $valid_tlds = array();
            foreach($tlds as $tld){
                if(substr_count($tld, '.') == 0){
                    continue;
                }

                $count = 0;
                $tld_string = '';
                $tld_parts = explode('.', $tld);
                foreach($tld_parts as $tld_part){
                    if(strlen(trim($tld_part)) > 0 && preg_match('/[^a-z_\-0-9]/i', $tld_part)==$tld_part){
                        $count++;
                        $tld_string .= '.'.trim($tld_part);
                    }
                }

                if($count >= 2){
                    array_push($valid_tlds, $tld_string);
                    array_push($all_tlds, "'".$tld_string."',");
                }
            }

            //echo '<b>'.$columns[3].'</b> => '.join('__',$valid_tlds ).'<hr />';

        }

        echo join(' ', $all_tlds);
    }

    function urls(){

        //Migrate from URL to People/ORG
        $current_urls = $this->Database_model->fn___tr_fetch(array(
            'tr_status >=' => 0,
            'tr_type_en_id IN (' . join(',', $this->config->item('en_ids_4537')) . ')' => null, //Entity URL Links
        ), array('en_child'), 99999, 0, array('tr_content' => 'ASC'));

        //Echo table:
        echo '<table class="table table-condensed table-striped stats-table sources-mined hidden" style="max-width:100%;">';

        //Object Header:
        echo '<tr style="font-weight: bold;">';
        echo '<td style="text-align: left;">Domain</td>';
        echo '<td style="text-align: left;"></td>';
        echo '<td style="text-align: left;">Current URL</td>';
        echo '<td style="text-align: left;">Entity</td>';
        echo '</tr>';


        foreach ($current_urls as $i=>$tr){

            //Detect domain parent:
            $parseURL = base_domain($tr['tr_content']);

            //Object Header:
            echo '<tr>';
            echo '<td style="text-align: left;">'.$parseURL['basedomain'].'</td>';
            echo '<td style="text-align: left;">'.( $parseURL['isroot'] ? ' 11' : '' ).'</td>';
            echo '<td style="text-align: left;"><a href="'.$tr['tr_content'].'" target="_blank">'.$tr['tr_content'].'</a></td>';
            echo '<td style="text-align: left;"><a href="/entities/'.$tr['en_id'].'" target="_blank">'.$tr['en_name'].'</a></td>';
            echo '</tr>';

        }

        echo '</table>';

    }

    function test($fb_messenger_format = 0){

        $quick_replies = array();

        if(isset($_POST['inputt'])){

            $p = $this->Chat_model->fn___dispatch_message($_POST['inputt'], ( intval($_POST['recipient_en']) ? array('en_id' => $_POST['recipient_en']) : array() ), $fb_messenger_format, $quick_replies);

            if($fb_messenger_format || !$p['status']){
                fn___echo_json(array(
                    'analyze' => fn___extract_message_references($_POST['inputt']),
                    'results' => $p,
                ));
            } else {
                //HTML:
                echo $p['output_messages'][0]['message_body'];
            }

        } else {
            echo '<form method="POST" action="">';
            echo '<textarea name="inputt" style="width:400px; height: 200px;"></textarea><br />';
            echo '<input type="number" name="recipient_en" value="1"><br />';
            echo '<input type="submit" value="GO">';
            echo '</form>';
        }

    }


    function pay(){

        exit; //Maybe use to update all rates if needed?

        //Issue coins for each transaction type:
        $all_engs = $this->Database_model->fn___tr_fetch(array(), array('en_type'), 0, 0, array('trs_count' => 'DESC'), 'COUNT(tr_type_en_id) as trs_count, en_name, tr_type_en_id', 'tr_type_en_id, en_name');

        //return fn___echo_json($all_engs);

        //Give option to select:
        foreach ($all_engs as $tr) {

            //DOes it have a rate?
            $rate_trs = $this->Database_model->fn___tr_fetch(array(
                'tr_status >=' => 2, //Must be published+
                'en_status >=' => 2, //Must be published+
                'tr_type_en_id' => 4319, //Number
                'tr_en_parent_id' => 4374, //Mench Coins
                'tr_en_child_id' => $tr['tr_type_en_id'],
            ), array('en_child'), 1);

            if(count($rate_trs) > 0){
                //Issue coins at this rate:
                $this->db->query("UPDATE table_ledger SET tr_coins = '".$rate_trs[0]['tr_content']."' WHERE tr_type_en_id = " . $tr['tr_type_en_id']);
            }

        }

        echo 'done';

    }

    function fn___matrix_cache(){

        /*
         *
         * This function prepares a PHP-friendly text to be copies to matrix_cache.php
         * (which is auto loaded) to provide a cache image of some entities in
         * the tree for faster application processing.
         *
         * */

        //First first all entities that have Cache in PHP Config @4527 as their parent:
        $config_ens = $this->Database_model->fn___tr_fetch(array(
            'tr_status >=' => 0,
            'tr_type_en_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'tr_en_parent_id' => 4527,
        ), array('en_child'), 0);

        foreach($config_ens as $en){

            //Now fetch all its children:
            $children = $this->Database_model->fn___tr_fetch(array(
                'tr_status >=' => 2,
                'en_status >=' => 2,
                'tr_en_parent_id' => $en['tr_en_child_id'],
                'tr_type_en_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            ), array('en_child'), 0, 0, array('tr_order' => 'ASC', 'en_id' => 'ASC'));


            $child_ids = array();
            foreach($children as $child){
                array_push($child_ids , $child['en_id']);
            }

            echo '<br />//'.$en['en_name'].':<br />';
            echo '$config[\'en_ids_'.$en['tr_en_child_id'].'\'] = array('.join(', ',$child_ids).');<br />';
            echo '$config[\'en_all_'.$en['tr_en_child_id'].'\'] = array(<br />';
            foreach($children as $child){

                //Do we have an omit command?
                if(substr_count($en['tr_content'], '&var_trimcache=') == 1){
                    $child['en_name'] = trim(str_replace( str_replace('&var_trimcache=','',$en['tr_content']) , '', $child['en_name']));
                }

                //Fetch all parents for this child:
                $child_parent_ids = array(); //To be populated soon
                $child_parents = $this->Database_model->fn___tr_fetch(array(
                    'tr_status >=' => 2,
                    'en_status >=' => 2,
                    'tr_en_child_id' => $child['en_id'],
                    'tr_type_en_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                ), array('en_parent'), 0);
                foreach($child_parents as $cp_en){
                    array_push($child_parent_ids, $cp_en['en_id']);
                }

                echo '&nbsp;&nbsp;&nbsp;&nbsp; '.$child['en_id'].' => array(<br />';

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_icon\' => \''.htmlentities($child['en_icon']).'\',<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_name\' => \''.$child['en_name'].'\',<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_desc\' => \''.str_replace('\'','\\\'',$child['tr_content']).'\',<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_parents\' => array('.join(', ',$child_parent_ids).'),<br />';

                echo '&nbsp;&nbsp;&nbsp;&nbsp; ),<br />';

            }
            echo ');<br />';
        }
    }

    function fn___in_metadata_update($in_id = 0, $update_c_table = 1)
    {

        /*
         *
         * Updates the metadata cache data for intents starting at $in_id.
         *
         * If $in_id is not provided, it defaults to in_mission_id which
         * is the highest level of intent in the Mench tree.
         *
         * */

        if(!$in_id){
            $in_id = $this->config->item('in_mission_id');
        }
        //Cron Settings: 31 * * * *
        //Syncs intents with latest caching data:

        $sync = $this->Matrix_model->fn___in_recursive_fetch($in_id, true, $update_c_table);
        if (isset($_GET['redirect']) && strlen($_GET['redirect']) > 0) {
            //Now redirect;
            header('Location: ' . $_GET['redirect']);
        } else {
            //Remove the long "in_tree" variable which makes the page load slow:
            unset($sync['in_tree']);

            //Show json:
            fn___echo_json($sync);
        }
    }


    //I cannot update algolia from my local server so if fn___is_dev() is true I will call mench.com/cron/fn___update_algolia to sync my local change using a live end-point:
    function fn___update_algolia($obj, $obj_id = 0)
    {
        fn___echo_json($this->Database_model->fn___update_algolia($obj, $obj_id));
    }


    function fn___list_duplicate_ins()
    {

        //Do a query to detect intents with the exact same title:
        $q = $this->db->query('select in1.* from table_intents in1 where (select count(*) from table_intents in2 where in2.in_outcome = in1.in_outcome) > 1 ORDER BY in1.in_outcome ASC');
        $duplicates = $q->result_array();

        $prev_title = null;
        foreach ($duplicates as $in) {
            if ($prev_title != $in['in_outcome']) {
                echo '<hr />';
                $prev_title = $in['in_outcome'];
            }

            echo '<a href="/intents/' . $in['in_id'] . '">#' . $in['in_id'] . '</a> ' . $in['in_outcome'] . '<br />';
        }
    }

    function fn___list_duplicate_ens()
    {

        $q = $this->db->query('select en1.* from table_entities en1 where (select count(*) from table_entities en2 where en2.en_name = en1.en_name) > 1 ORDER BY en1.en_name ASC');
        $duplicates = $q->result_array();

        $prev_title = null;
        foreach ($duplicates as $u) {
            if ($prev_title != $u['en_name']) {
                echo '<hr />';
                $prev_title = $u['en_name'];
            }

            echo '<a href="/entities/' . $u['en_id'] . '">#' . $u['en_id'] . '</a> ' . $u['en_name'] . '<br />';
        }
    }


    function e_score_recursive($u = array())
    {

        //Updates en_trust_score based on number/value of connections to other intents/entities
        //Cron Settings: 2 * * * 30

        //Define weights:
        $score_weights = array(
            'u__childrens' => 0, //Child entities are just containers, no score on the link

            'tr_en_child_id' => 1, //Transaction initiator
            'tr_miner_en_id' => 1, //Transaction recipient

            'tr_en_parent_id' => 13, //Action Plan Items
        );

        //Fetch child entities:
        $ens = array();

        //Recursively loops through child entities:
        $score = 0;
        foreach ($ens as $$en) {
            //Addup all child sores:
            $score += $this->e_score_recursive($$en);
        }

        //Anything to update?
        if (count($u) > 0) {

            //Update this row:
            $score += count($ens) * $score_weights['u__childrens'];

            $score += count($this->Database_model->fn___tr_fetch(array(
                    'tr_en_child_id' => $u['en_id'],
                ), array(), 5000)) * $score_weights['tr_en_child_id'];
            $score += count($this->Database_model->fn___tr_fetch(array(
                    'tr_miner_en_id' => $u['en_id'],
                ), array(), 5000)) * $score_weights['tr_miner_en_id'];
            $score += count($this->Database_model->w_fetch(array(
                    'tr_en_parent_id' => $u['en_id'],
                ))) * $score_weights['tr_en_parent_id'];

            //Update the score:
            $this->Database_model->fn___en_update($u['en_id'], array(
                'en_trust_score' => $score,
            ));

            //return the score:
            return $score;

        }
    }


    function fn___save_media_to_cdn()
    {

        /*
         *
         * Every time we receive a media file from Facebook
         * we need to upload it to our own CDNs using the
         * short-lived URL provided by Facebook so we can
         * access it indefinitely without restriction.
         * This process is managed by creating a @4299
         * Transaction Type which this cron job grabs and
         * uploads to Mench CDN
         *
         * */

        $max_per_batch = 20; //Max number of scans per run

        $tr_pending = $this->Database_model->fn___tr_fetch(array(
            'tr_status' => 0, //Pending
            'tr_type_en_id' => 4299, //Save media file to Mench cloud
        ), array(), $max_per_batch);


        //Lock item so other Cron jobs don't pick this up:
        foreach ($tr_pending as $tr) {
            if ($tr['tr_id'] > 0 && $tr['tr_status'] == 0) {
                $this->Database_model->fn___tr_update($tr['tr_id'], array(
                    'tr_status' => 1, //Working on... (So other cron jobs do not pickup this item again)
                ));
            }
        }

        //Go through and upload to CDN:
        foreach ($tr_pending as $u) {

            $tr_type_en_id = fn___detect_tr_type_en_id($new_file_url);
            if(!$tr_type_en_id['status']){
                //Opppsi, there was some error:
                //TODO Log error
                continue;
            }

            //Update transaction data:
            $this->Database_model->fn___tr_update($trp['tr_id'], array(
                'tr_content' => $new_file_url,
                'tr_type_en_id' => $tr_type_en_id['tr_type_en_id'],
                'tr_status' => 2, //Publish
            ));


            //Save the file to S3
            $new_file_url = fn___upload_to_cdn($u['tr_content'], $u);

            if ($new_file_url) {

                //Success! Is this an image to be added as the entity icon?
                if (strlen($u['en_icon'])<1) {
                    //Update Cover ID:
                    $this->Database_model->fn___en_update($u['en_id'], array(
                        'en_icon' => '<img class="profile-icon" src="' . $new_file_url . '" />',
                    ), true);
                }

                //Update transaction:
                $this->Database_model->fn___tr_update($u['tr_id'], array(
                    'tr_status' => 2, //Publish
                ));

            } else {

                //Error has already been logged in the CDN function, so just update transaction:
                $this->Database_model->fn___tr_update($u['tr_id'], array(
                    'tr_status' => -1, //Removed
                ));

            }
        }

        fn___echo_json($tr_pending);
    }

    function fn___facebook_attachment_sync()
    {

        /*
         * This cron job looks for all requests to sync
         * Media files with Facebook so we can instantly
         * deliver them over Messenger.
         *
         * Cron Settings: * * * * *
         *
         */

        $max_per_batch = 20; //Max number of syncs per cron run
        $success_count = 0; //Track success
        $en_convert_4537 = $this->config->item('en_convert_4537'); //Supported Media Types
        $tr_metadata = array();


        //Let's fetch all Media files without a Facebook attachment ID:
        $pending_urls = $this->Database_model->fn___tr_fetch(array(
            'tr_type_en_id IN (' . join(',',array_keys($en_convert_4537)) . ')' => null,
            'tr_metadata' => null, //Missing Facebook Attachment ID
        ), array(), $max_per_batch, 0 , array('tr_id' => 'ASC')); //Sort by oldest added first

        foreach ($pending_urls as $tr) {

            $payload = array(
                'message' => array(
                    'attachment' => array(
                        'type' => $en_convert_4537[$tr['tr_type_en_id']],
                        'payload' => array(
                            'is_reusable' => true,
                            'url' => $tr['tr_content'], //The URL to the media file
                        ),
                    ),
                )
            );

            //Attempt to sync Media to Facebook:
            $result = $this->Chat_model->fn___facebook_graph('POST', '/me/message_attachments', $payload);
            $db_result = false;

            if ($result['status'] && isset($result['tr_metadata']['result']['attachment_id'])) {

                //Save Facebook Attachment ID to DB:
                $db_result = $this->Matrix_model->fn___metadata_update('tr', $tr, array(
                    'fb_att_id' => intval($result['tr_metadata']['result']['attachment_id']),
                ));

            }

            //Did it go well?
            if ($db_result) {

                $success_count++;

            } else {

                //Log error:
                $this->Database_model->fn___tr_create(array(
                    'tr_type_en_id' => 4246, //Platform Error
                    'tr_content' => 'fn___facebook_attachment_sync() Failed to sync attachment using Facebook API',
                    'tr_metadata' => array(
                        'payload' => $payload,
                        'result' => $result,
                    ),
                ));

                //Also disable future attempts for this transaction:
                $db_result = $this->Matrix_model->fn___metadata_update('tr', $tr, array(
                    'fb_att_id_failed' => true,
                ));

            }

            //Save stats:
            array_push($tr_metadata, array(
                'payload' => $payload,
                'fb_result' => $result,
            ));

        }

        //Echo message:
        fn___echo_json(array(
            'status' => ($success_count == count($pending_urls) && $success_count > 0 ? 1 : 0),
            'message' => $success_count . '/' . count($pending_urls) . ' synced using Facebook Attachment API',
            'tr_metadata' => $tr_metadata,
        ));

    }


}