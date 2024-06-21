<?php
setcookie("username", "", time() - 3600);
setcookie("password", "", time() - 3600);
?>
<script>window.location = '/index.php';</script>