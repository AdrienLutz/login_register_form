<?php
session_start();
if(array_key_exists("email", $_SESSION)){
    header('Location: restricted.php');
} else {
    header('Location: login.php');
}