<?php
include ("../../../assets/functions.php");

date_default_timezone_set('UTC');

if($_POST["token"] === $_SESSION["token"] && isset($_POST["ticketID"]) && isset($_POST["reply"]) && isset($_POST["username"]) && isset($_POST["submitter"])) {
    $ticket_id = $_POST["ticketID"];
    $subject = $_POST["subject"];
    $username = $_POST['username'];
    $submitter = $_POST['submitter'];
    $email = getEmail($submitter);
    $reply = nl2br(htmlspecialchars($_POST["reply"]));
    $timestamp = date('Y-m-d G:i:s');

    $stmtReply = $pdo->prepare("INSERT INTO statik_supportReplies (ticket_id, datetime,username,reply) VALUES (:ticket, :times, :username, :reply)");
        if ($stmtReply->execute(array("ticket" => $ticket_id, "times" => $timestamp, "username" => $username, "reply" => $reply))) {
            echo "true";

            $message = '
            This is a notification to let you know that an administrator has replied to your support ticket #' . $ticket_id . '.
            <br><br>
            If you have any further questions regarding this please notify our administrators via the website.
            <br><br>
            You can view the ticket here: http://support.statik.io/tickets/view/' . $ticket_id . '
            <br><br>
            The ticket will have its status automatically changed to closed after 72 hours should you not reply.
            ';

            sendMail($username, $email, "[Ticket ID: " . $ticket_id . "] " . $subject, $message);
        } else {
            echo "false";
        }
}else{
    echo "false";
}