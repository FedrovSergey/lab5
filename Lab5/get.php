<?php
    include_once('db.php');
    if (isset($_POST['id'])) {
        echo(mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM arr WHERE id='$_POST[id]'"))['val']);        
    }
?>