<?php
include ("../../assets/functions.php");

date_default_timezone_set('UTC');

if($_POST["token"] === $_SESSION["token"] && isset($_POST["ticketID"]) && isset($_POST["reply"])) {
    $ticket_id = $_POST["ticketID"];
    $username = $USER['username'];
    $reply = nl2br(htmlspecialchars($_POST["reply"]));
    $timestamp = date('Y-m-d G:i:s');

    $stmtReply = $pdo->prepare("INSERT INTO statik_supportReplies (ticket_id, datetime,username,reply) VALUES (:ticket, :times, :username, :reply)");
        if ($stmtReply->execute(array("ticket" => $ticket_id, "times" => $timestamp, "username" => $username, "reply" => $reply))) {
            echo "true";
        } else {
            echo "false";
        }
}else{
    echo "false";
}