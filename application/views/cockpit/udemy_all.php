<p>Note: <b>Course & Admissions Have Overlaps</b> as they do not reflect unique numbers because co-instructors share their course admissions. <a href="https://about.udemy.com/udemy-for-business/a-year-of-udemy-2016-in-review/" target="_blank">Udemy Reported</a> 10M students by the end of 2016 and 27M admissions during the entire 2016.</p>
<table class="table table-condensed table-striped">
<tr style="font-weight: bold;">
	<td>Udemy Category</td>
	<td>Instructors</td>
	<td>Courses</td>
	<td>Admissions</td>
	<td>Engagement</td>
	<td>Download</td>
</tr>
<?php
$totals = array(0,0,0,0);
foreach($il_overview as $ilo){
    echo '<tr>';
        echo '<td><a href="/cockpit/udemy?cat='.urlencode($ilo['il_udemy_category']).'">'.$ilo['il_udemy_category'].'</td>';
        echo '<td>'.number_format($ilo['total_instructors'],0).'</td>';
        echo '<td>'.number_format($ilo['total_courses'],0).'</td>';
        echo '<td>'.number_format($ilo['total_students'],0).'</td>';
        echo '<td>'.number_format(( $ilo['total_students']>0 ? ( $ilo['total_reviews']/$ilo['total_students']*100 ) : 0 ),1).'%</td>';
        echo '<td><a href="/scraper/udemy_csv?cat='.urlencode($ilo['il_udemy_category']).'"><i class="fa fa-cloud-download" aria-hidden="true"></i>CSV</a></td>';
        
        $totals[0] += $ilo['total_instructors'];
        $totals[1] += $ilo['total_courses'];
        $totals[2] += $ilo['total_students'];
        $totals[3] += $ilo['total_reviews'];
        echo '</tr>';
}

echo '<tr style="font-weight: bold;">';
    echo '<td>Totals</td>';
    echo '<td>'.number_format($totals[0],0).'</td>';
    echo '<td>'.number_format($totals[1],0).'</td>';
    echo '<td>'.number_format($totals[2],0).'</td>';
    echo '<td>'.number_format(( $totals[2]>0 ? ( $totals[3]/$totals[2]*100 ) : 0 ),1).'%</td>';
    echo '<td>&nbsp;</td>';
echo '</tr>';
?>
</table>