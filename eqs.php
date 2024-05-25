<?php require_once "connection.php"; ?>
<b>add new questions</b>
<form action="/eqs_add_questions.php" method="post">
    enter new questions/values, separated with ", "<br> <textarea name="questions"></textarea> <br>
    <input type="submit">
</form>

<br>

<b>fill out survey</b>
<form action="/eqs_add_answer.php" method="post" id="add_answer_form">
    <!-- will be filled by php -->
</form>

<br>

<b>export as csv</b> <span>compatiable with excel/sheets</span> <br>
<form action="/export_as_csv.php" method="post">
    <input type="submit" value="export values to csv file">
</form>

<?php
if (isset($_POST["username"]) && isset($_POST["password"]))
{
    $username = $_POST["username"];
    $password = hash("sha256", $_POST["password"]);
    $rawPassword = $_POST["password"];
    //todo: escape
    $user = $conn->query("SELECT * FROM users WHERE username = '$username' AND password = '$password'");
    $userData = NULL;
    $userEQSData = NULL;
    $eqsId = NULL;

    if ($user->num_rows > 0) {
        //TODO: move to 10 years beyond login or smth
        setcookie("username", "$username", time() + (86400 * 30), "/");
        setcookie("password", "$rawPassword", time() + (86400 * 30), "/");
        $userData = $user->fetch_assoc();
        $eqsId = $userData["eqsId"];
        $questionsJson = NULL;
        $answersJson = NULL;
        
        //TODO: escape

        if ($eqsId == NULL)
        {
            $rand = rand(0, 200000000);
            $eqsId = $rand;
            if ($conn->query("INSERT INTO eqs (eqsId, questions, answers) VALUES ($rand, '[]', '[]')") == TRUE) {
                if ($conn->query("UPDATE users SET eqsId = $rand") == FALSE) {
                    die("update user with eqs id fail");
                } else {
                    $questionsJson = json_decode("{}");
                    $answersJson = json_decode("{}");
                };
            }
            
            else {
                die("insert eqs fail");
            };
        }

        else 
        {
            $userEQS = $conn->query("SELECT * FROM eqs WHERE eqsId = $eqsId");
            $userEQSData = $userEQS->fetch_assoc();
            $questionsJson = json_decode($userEQSData["questions"]);
            $answersJson = json_decode($userEQSData["answers"]);
        }
    }

    else
    {
        die("bad login");
    };

    //TODO: fill out questions, answers, username/password inputs, eqs id inputs
    $count = 0;
    $answersScript = "<script>document.getElementById('add_answer_form').innerHTML += `";
    foreach ($questionsJson as $question)
    {
        $answersScript = $answersScript . "$question <input type='text' name='question$count'><br>";
        $count = $count + 1;
    }
    $answersScript = $answersScript . "<input type='submit'>`</script>";
    echo $answersScript;
}

else {
    die ("bad login");
}
?>

