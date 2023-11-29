<?php 
    session_start();

 include("./common/header.php"); 
?>
<div class="container">
<h1>Welcome to Online Registration </h1>
<br/>
<ul>
    <li>If you have never used this before, you have to <a href="NewUser.php">sign up</a> first.</li>
    <li>If you have already signed up, you can <a href="Login.php">log in</a> now.</li>
</ul>
</div>
<?php include('./common/footer.php'); ?>