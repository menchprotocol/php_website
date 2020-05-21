<?php

$active_ideas = $this->IDEA_model->fetch(array(
    'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
), ( isset($_GET['limit']) ? $_GET['limit'] : 0 ));
$found = 0;
foreach($active_ideas as $count=>$in){

    $recursive_children = $this->IDEA_model->recursive_child_ids($in['idea__id'], false);
    if(count($recursive_children) > 0){
        $recursive_parents = $this->IDEA_model->recursive_parents($in['idea__id']);
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
