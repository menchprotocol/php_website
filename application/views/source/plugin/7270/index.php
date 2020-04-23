<?php

if(isset($_POST['test_message'])){

    if(intval($_POST['push_message']) && intval($_POST['recipient_en'])){

        //Send to Facebook Messenger:
        $msg_validation = $this->COMMUNICATION_model->comm_message_send(
            $_POST['test_message'],
            array('en_id' => intval($_POST['recipient_en'])),
            true
        );

    } elseif(intval($_POST['recipient_en']) > 0) {

        $msg_validation = $this->COMMUNICATION_model->comm_message_construct($_POST['test_message'], array('en_id' => $_POST['recipient_en']), $_POST['push_message']);

    } else {

        echo 'Missing recipient';

    }

    //Show results:
    print_r($msg_validation);

} else {

    //UI to compose a test message:
    echo '<form method="POST" action="" class="maxout">';

    echo '<div class="mini-header">Message:</div>';
    echo '<textarea name="test_message" class="form-control border" style="width:400px; height: 200px;"></textarea><br />';

    echo '<div class="mini-header">Player Source ID:</div>';
    echo '<input type="number" class="form-control border" name="recipient_en" value="1"><br />';

    echo '<div class="mini-header">Format Is Messenger:</div>';
    echo '<input type="number" class="form-control border" name="push_message" value="1"><br /><br />';


    echo '<input type="submit" class="btn btn-idea" value="Compose Test Message">';
    echo '</form>';

}