<table class="table table-condensed table-striped">
<tr style="font-weight: bold;">
	<td>#</td>
	<td style="width:300px; text-align:left;">Instructor</td>
	<td>Courses</td>
	<td>Students</td>
	<td>Engagement</td>
	<td>Email</td>
	<td>Is Company</td>
	<td style="width:140px; text-align:right;">Instructor Profiles</td>
</tr>
<?php
$totals = array(0,0,0);
$links = array(
    'il_youtube' => '<i class="fa fa-youtube-play" aria-hidden="true"></i>',
    'il_linkedin' => '<i class="fa fa-linkedin-square" aria-hidden="true"></i>',
    'il_twitter' => '<i class="fa fa-twitter" aria-hidden="true"></i>',
    'il_facebook' => '<i class="fa fa-facebook-official" aria-hidden="true"></i>',
    'il_website' => '<i class="fa fa-chrome" aria-hidden="true"></i>',
    'il_url' => '<img src="https://www.udemy.com/staticx/udemy/images/v6/apple-touch-icon-precomposed.png" width="16" style="margin-top:-2px;" />', //The Udemy URL
);

foreach($il_category as $i=>$ilo){
    echo '<tr>';
    echo '<td>'.number_format(($i+1),0).'</td>';
    echo '<td style="width:300px; text-align:left; ">'.( strlen($ilo['il_overview'])>0 ? '<i class="fa fa-info-circle" data-toggle="tooltip" title="'.strip_tags(substr($ilo['il_overview'],0,600)).'..." data-placement="right" aria-hidden="true"></i> ' : '' ).$ilo['il_first_name'].' '.$ilo['il_last_name'].'</td>';
    echo '<td style="">'.number_format($ilo['il_course_count'],0).'</td>';
    echo '<td style="">'.number_format($ilo['il_student_count'],0).'</td>';
        echo '<td style="">'.number_format(( $ilo['il_student_count']>0 ? ( $ilo['il_review_count']/$ilo['il_student_count']*100 ) : 0 ),1).'%</td>';
        echo '<td style="">---</td>';
        echo '<td style="">---</td>';
        echo '<td style=" width:120px; text-align:right;">';
            foreach($links as $link_id=>$link_icon){
                if(strlen($ilo[$link_id])>0){
                    echo '<a href="'.$ilo[$link_id].'" target="_blank">'.$link_icon.'</a> ';
                }
            }
            echo '<a href="https://www.google.ca/search?q='.urlencode($ilo['il_first_name'].' '.$ilo['il_last_name']).'" target="_blank"><i class="fa fa-google" aria-hidden="true"></i></a>';
        echo '</td>';
    echo '</tr>';
    
    $totals[0] += $ilo['il_course_count'];
    $totals[1] += $ilo['il_student_count'];
    $totals[2] += $ilo['il_review_count'];
}

echo '<tr style="font-weight: bold;">';
echo '<td>'.number_format(count($il_category),0).'</td>';
echo '<td style="width:300px; text-align:left;">Total Instructors</td>';
echo '<td>'.number_format($totals[0],0).'</td>';
echo '<td>'.number_format($totals[1],0).'</td>';
    echo '<td>'.number_format(( $totals[1]>0 ? ( $totals[2]/$totals[1]*100 ) : 0 ),1).'%</td>';
    echo '<td>&nbsp;</td>';
    echo '<td>&nbsp;</td>';
    echo '<td style="width:120px; text-align:right;">&nbsp;</td>';
echo '</tr>';
?>
</table>