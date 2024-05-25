<?php
require_once "connection.php";
if (
    isset($_COOKIE["username"])
    && isset($_COOKIE["password"])
    && isset($_POST["question0"])
    ) {
        $username = $_COOKIE["username"];
        $password = hash('sha256', $_COOKIE["password"]);
    
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
            $userEQSQuestionAmount = count(json_decode($userEQSData["questions"]));
            $userEQSAnswers = json_decode($userEQSData["answers"]);

            $response = array();
            foreach ($_POST as $key => $answer)
            {
                if (str_starts_with($key, "question"))
                {
                    array_push($response, $answer);
                }
            }

            if (count($response) != $userEQSQuestionAmount)
            {
                die("amount of responses does not match up");
            }

            array_push($userEQSAnswers, $response);
            $userEQSAnswersJSON = json_encode($userEQSAnswers);

            $updateSQL = "UPDATE eqs SET answers = '$userEQSAnswersJSON' WHERE eqsId = $eqsId";
            if ($conn->query($updateSQL) == TRUE)
            {
                echo "ok";
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