<?php
if (isset($_POST['reservation'])) {
    
    
    $res_id = $_POST['res_id'];
    $reservation_name = $_POST['reservation_name'];
    $reservation_phone = $_POST['reservation_phone'];
    $reservation_date = $_POST['reservation_date'];
    $reservation_time = $_POST['reservation_time'];

}else{
    echo "No reservation data received.";
}
?>