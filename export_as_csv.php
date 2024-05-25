<?php
// actual genius
// https://stackoverflow.com/questions/55025324/on-xml-file-download-a-new-line-is-added-to-start-of-the-file
header('Content-Type: text/csv; charset=utf-8');
require_once "connection.php";
if (
    isset($_COOKIE["username"])
    && isset($_COOKIE["password"])
    ) {
        $username = $_COOKIE["username"];
        $password = hash('sha256', $_COOKIE["password"]);
        $user = $conn->query("SELECT eqsId FROM users WHERE username = '$username' AND password = '$password'");
        $userData = NULL;
        $userEQSRawData = NULL;
        $eqsId = NULL;
        // $time = date("d-m-y-H-i-s");
        $csv = "";

        if ($user->num_rows > 0) {
            $userData = $user->fetch_assoc();   
            if ($userData["eqsId"] == NULL) {
                die("no eqs id");
            } else { $eqsId = $userData["eqsId"]; }

            $userEQSRawData = $conn->query("SELECT * FROM eqs WHERE eqsId = $eqsId");

            if ($userEQSRawData->num_rows == 0) { die("no eqs"); };
            $userEQSData = $userEQSRawData->fetch_assoc();
            $userEQSQuestions = json_decode($userEQSData["questions"]);
            $userEQSAnswers = json_decode($userEQSData["answers"]);

            $csv = implode(",", $userEQSQuestions) . "\n";
            foreach ($userEQSAnswers as $answerArray) {
                $csv = $csv . implode(",", $answerArray) . "\n";
            };

            echo $csv;
        }

        else {
            die("bad login");
        }
    }
?>