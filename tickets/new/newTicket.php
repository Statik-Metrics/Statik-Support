<?php
include("../../assets/functions.php");
date_default_timezone_set('UTC');
if ($USER["loggedIn"] && isset($_POST["subject"]) && isset($_POST["category"]) && isset($_POST["priority"]) && isset($_POST["description"])) {
    if (!($_SESSION["token"] === $_POST["token"])) {
        echo "false";
    } else {
        $subject = htmlspecialchars($_POST['subject']);
        $category = htmlspecialchars($_POST['category']);
        $priority = htmlspecialchars($_POST['priority']);
        $description = nl2br(htmlspecialchars($_POST['description']));
        $submitter = $USER['username'];
        $timestamp = date('Y-m-d G:i:s');

        $stmt = $pdo->prepare("INSERT INTO statik_supportTickets (subject, category, priority, description, submitter, datetime) VALUES (:subject,:category,:priority,:description,:submitter, :times)");
        $stmtId = $pdo->prepare("SELECT id FROM statik_supportTickets WHERE id = :id LIMIT 1");

        if ($stmt->execute(array("subject" => $subject, "category" => $category, "priority" => $priority, "description" => $description, "submitter" => $submitter, "times" => $timestamp))) {
            $stmtId->execute(array("id" => $pdo->lastInsertId()));
            while ($reply = $stmtId->fetch(PDO::FETCH_ASSOC)){
                echo $reply["id"];
                $id = $reply["id"];
            }

            $message = '
            Thank you or contacting our support team. A support ticket has now been opened for your request. You will be notified by email when a staff member responds. The details of your ticket are shown below.
            <br><br>
            Ticket ID: #' . $id . '
            <br>
            Category: ' . $category . '
            <br>
            Subject: ' . $subject . '
            <br>
            Priority: ' . $priority . '
            <br>
            Status: <span style="color:green">Open</span>
            <br><br>
            You can view the ticket at any time at http://support.statik.io/tickets/view/' . $id . '
            ';

            sendMail($USER["username"], $USER["email"], "[Ticket ID: " . $id . "] " . $subject, $message);
        } else {
            echo "false";
        }
    }
} else {
    echo "false";
}