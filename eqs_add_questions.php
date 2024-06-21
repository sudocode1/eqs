<?php
require_once "connection.php";
if (
    isset($_COOKIE["username"])
    && isset($_COOKIE["password"])
    && isset($_POST["questions"])
    ) {
        $username = $_COOKIE["username"];
        $password = hash('sha256', $_COOKIE["password"]);
        $questions = explode(", ", $_POST["questions"]);

        $user = $conn->query("SELECT * FROM users WHERE username = '$username' AND password = '$password'");
        $userData = NULL;
        $userEQSRawData = NULL;
        $eqsId = NULL;

        if ($user->num_rows > 0) {
            $userData = $user->fetch_assoc();
            
            if ($userData["eqsId"] == NULL) {
                die("no eqs id");
            } else { $eqsId = $userData["eqsId"]; }

            $userEQSRawData = $conn->query("SELECT * FROM eqs WHERE eqsId = $eqsId");
            
            if ($userEQSRawData->num_rows == 0) { die("no eqs"); };

            $userEQSData = $userEQSRawData->fetch_assoc();
            $userEQSQuestions = json_decode($userEQSData["questions"]);
            foreach ($questions as $question) {
                array_push($userEQSQuestions, $question);
            }

            $userEQSQuestionsJSON = json_encode($userEQSQuestions);
            
            $updateSQL = "UPDATE eqs SET questions = '$userEQSQuestionsJSON' WHERE eqsId = $eqsId";
            if ($conn->query($updateSQL) == TRUE)
            {
                echo "ok";
                echo "<script>window.location = '/eqs.php'</script>";
            }
            else
            {
                echo "failed at update";
            }
        }
        else {
            die("bad login");
        }
    }
?>