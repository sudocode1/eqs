<?php
if (isset($_COOKIE["username"]) && isset($_COOKIE["password"]))
{
    echo "<script>window.location = '/eqs.php'</script>";
}
?>
<form action="/eqs.php" method="post">
    username <input type="text" name="username"> <br>
    password <input type="password" name="password">
    <input type="submit">
</form>