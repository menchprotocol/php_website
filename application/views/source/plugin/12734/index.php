<?php

$active_ideas = $this->MAP_model->fetch(array(
    'i__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
), ( isset($_GET['limit']) ? $_GET['limit'] : 0 ));
$found = 0;
foreach($active_ideas as $count=>$in){

    $recursive_children = $this->MAP_model->recursive_child_ids($in['i__id'], false);
    if(count($recursive_children) > 0){
        $recursive_parents = $this->MAP_model->recursive_parents($in['i__id']);
        foreach($recursive_parents as $grand_parent_ids) {
            $crossovers = array_intersect($recursive_children, $grand_parent_ids);
            if(count($crossovers) > 0){
                //Ooooopsi, this should not happen:
                echo $in['idea_titile'].' Has Parent/Child crossover for #'.join(' & #', $crossovers).'<hr />';
                $found++;
                break; //Otherwise too show...
            }
        }
    }
}

echo 'Found '.$found.' Crossovers.';
