<?php
if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']){
    echo 'zalogowano';
}else{
    echo 'wylogowano';
}
?>
