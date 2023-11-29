<?php
session_start();
session_destroy();
header("Location: Index.php");
include("./common/header.php");
include('./common/footer.php'); ?>

