<?php

include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()

include_once '../models/user.class.php';
$user = new USER($db);


$user->login($_POST['username'],$_POST['password'],$_POST['remember_me']);
