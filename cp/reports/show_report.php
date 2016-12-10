<?php
    $username = $_SESSION['current_user']['username'];
    $password = $_SESSION['current_user']['password'];
    
    $query_text = "
      SELECT reportServiceTitle,
              reportClientsQuantity,
              reportSummaryCost
       FROM report
       WHERE reportMonth = $r_month AND
             reportYear = $r_year";
    
?>