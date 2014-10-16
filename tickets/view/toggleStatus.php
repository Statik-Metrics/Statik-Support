<?php
include ("../../assets/functions.php");

if($_POST["token"] === $_SESSION["token"] && isset($_POST["ticketID"])) {
    $ticket_id = $_POST["ticketID"];

    if ($_POST['changeTo'] === "Open") {
        $stmtOpen = $pdo->prepare("UPDATE statik_supportTickets SET status='Open' WHERE id = :id");
        if ($stmtOpen->execute(array("id" => $ticket_id))) {
            $message = '
            This is a notification to let you know that the status of your ticket #' . $ticket_id . ' has been changed to Open.
            <br><br>
            You can view the ticket here: http://support.statik.io/tickets/view/' . $ticket_id . '
            ';
            sendMail($USER["username"], $USER["email"], "[Ticket ID: " . $ticket_id . "] Ticket Re-Opened", $message);
            echo "true";
        } else {
            echo "false";
        }
    } elseif ($_POST['changeTo'] === "Closed") {
        $stmtClosed = $pdo->prepare("UPDATE statik_supportTickets SET status='Closed' WHERE id = :id");
        if ($stmtClosed->execute(array("id" => $ticket_id))) {
            $message = '
            This is a notification to let you know that the status of your ticket #' . $ticket_id . ' has been changed to Closed.
            <br><br>
            If you have any further questions then please re-open the ticket and reply.
            <br><br>
            You can view the ticket here: http://support.statik.io/tickets/view/' . $ticket_id . '
            ';
            sendMail($USER["username"], $USER["email"], "[Ticket ID: " . $ticket_id . "] Ticket Closed", $message);
            echo "true";
        } else {
            echo "false";
        }
    }
}else{
    echo "false";
}