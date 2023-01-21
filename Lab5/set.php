<?php
    include_once('db.php');
    if (isset($_POST['id']) && isset($_POST['val'])) {
        if ($_POST['id'] > -1 && $_POST['id'] < 10)
            mysqli_query($link, "UPDATE arr SET val='$_POST[val]' WHERE id='$_POST[id]'");        
    }
?>