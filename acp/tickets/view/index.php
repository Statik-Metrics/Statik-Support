<?php
$id = $_GET["id"];

$page_title = "ACP - View Ticket #" . $id;
include("../../../assets/page_head.php");

if ($USER["loggedIn"]) {
    $stmtTicket = $pdo->prepare("SELECT * FROM statik_supportTickets WHERE id = :id LIMIT 1");
    $stmtTicket->execute(array("id" => $id));
    $ticket = $stmtTicket->fetch(PDO::FETCH_ASSOC);

    if ($stmtTicket->rowCount() > 0) {
        $stmtReply = $pdo->prepare("SELECT * FROM statik_supportReplies WHERE ticket_id = :ticketId ORDER BY id DESC");
    }
}
?>

<div class="ui column">
    <div class="ui container">

        <div class="ui piled orange segment" id="ticketData" style="display: none">
            <h2 class="ui dividing header">View Ticket #<?php echo $ticket["id"] ?></h2>
            <h4 class="ui header"><?php echo $ticket["subject"] ?></h4>

            <div class="ui orange inverted segment fluid">
                <div class="ui grid">

                    <div class="ui column wide four">
                        <p class="center"><strong>Submitted</strong></p>

                        <div class="ui label fluid center">
                            <strong><?php echo date("d-m-Y H:i", strtotime($ticket["datetime"])) ?></strong>
                        </div>
                    </div>

                    <div class="ui column wide four">
                        <p class="center"><strong>Category</strong></p>

                        <div class="ui label fluid center">
                            <strong><?php echo $ticket["category"] ?></strong>
                        </div>
                    </div>

                    <div class="ui column wide four">
                        <p class="center"><strong>Priority</strong></p>

                        <div class="ui label fluid center">
                            <strong><?php echo $ticket["priority"] ?></strong>
                        </div>
                    </div>

                    <div class="ui column wide four">
                        <p class="center"><strong>Status</strong></p>

                        <div class="ui label fluid center">
                            <strong><?php echo $ticket["status"] ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ui animated fade button small" onclick="window.location.replace('/acp')">
                <div class="visible content">Back</div>
                <div class="hidden content">
                    <i class="left icon"></i>
                </div>
            </div>

            <?php if ($ticket['status'] === "Open"): ?>
                <div class="ui animated fade button orange small" onclick="toggleReply()">
                    <div class="visible content">Add Reply</div>
                    <div class="hidden content">
                        <i class="icon plus"></i>
                    </div>
                </div>
            <?php endif ?>

            <br><br>

            <div id="replyBlock" style="display: none">
                <div class="ui segment attached slide tertiary orange">
                    <div class="ui red message" id="replyError" style="display: none"><h4 class="ui header">You must
                            enter a reply
                            to your ticket before you can do that.</h4>
                    </div>
                    <h2 class="ui header">Reply to ticket:</h2>

                    <div class="ui form">
                        <div class="field">
                            <textarea placeholder="Enter a reply to your ticket here..." id="replyArea"></textarea>
                        </div>
                    </div>
                </div>
                <div class="bottom attached ui orange button" onclick="submitReply()">Add Reply</div>
            </div>

            <br><br>

            <?php
            if (isset($stmtReply)) {
                $stmtReply->execute(array("ticketId" => $id));
                if ($stmtReply->rowCount() > 0) {
                    while($reply = $stmtReply->fetch(PDO::FETCH_ASSOC)) {
                        echo '
                            <div class="ui segment attached">
                                <span class="ui top left attached label ' . ((isAdmin($reply["username"])) ? 'black' : '') . '"><strong>' . $reply["username"] . ' - ' . ((isAdmin($reply["username"])) ? 'Admin' : 'User') . '</strong></span>
                                <span class="ui top right attached label ' . ((isAdmin($reply["username"])) ? 'black' : '') . '" style="margin-top: 0px"><strong>' . date("d-m-Y H:i", strtotime($reply['datetime'])) . '</strong></span>
                                <div class="ui divider"></div>
                                <p> ' . $reply["reply"] . ' <br></p>
                            </div>
                            ';
                    }
                    echo "<br>";
                }

                echo '
                    <div class="ui segment attached">
                        <span class="ui top left attached label ' . ((isAdmin($ticket["submitter"])) ? 'black' : '') . '"><strong>' . $ticket["submitter"] . ' - ' . ((isAdmin($reply["username"])) ? 'Admin' : 'User') . '</strong></span>
                        <span class="ui top right attached label ' . ((isAdmin($ticket["submitter"])) ? 'black' : '') . '" style="margin-top: 0px"><strong>' . date("d-m-Y H:i", strtotime($ticket['datetime'])) . '</strong></span>
                        <div class="ui divider"></div>
                        <p> ' . $ticket["description"] . ' <br></p>
                    </div>
                    ';
            }
            ?>
        </div>

        <div class="ui piled orange segment" id="ticketNotFound" style="display: none">
            <div class="ui icon message red">
                <i class="thumbs down icon"></i>

                <div class="content">
                    <div class="header">
                        Ticket #<?php echo $id ?> does not exist in our database.
                    </div>
                    <p>We apologise for this inconvenience, press "Previous Tickets" to view a list of your previous
                        tickets.</p>
                </div>
            </div>
        </div>

        <div class="ui piled orange segment" id="ticketNoPerms" style="display: none">
            <div class="ui icon message red">
                <i class="thumbs down icon"></i>

                <div class="content">
                    <div class="header">
                        You do not have permission to view ticket #<?php echo $id ?>.
                    </div>
                    <p>We apologise for this inconvenience, press "Previous Tickets" to view a list of your previous
                        tickets.</p>
                </div>
            </div>
        </div>


    </div>
</div>
</div>
<script>
    $('.ui.dropdown').dropdown();
</script>

<script>
    function toggleReply() {
        $("#replyBlock").slideToggle();
    }

    function submitReply() {
        var ticketID = "<?php echo $id ?>";
        var user = "<?php echo $USER["username"] ?>";
        var reply = document.getElementById("replyArea").value;
        var token = "<?php echo $_SESSION["token"] ?>";
        var submitter = "<?php echo $ticket["submitter"] ?>";

        if (reply === "") {
            $("#replyError").slideDown("fast");
        } else {
            $.post("addReply.php", {ticketID: ticketID, username: user, reply: reply, token: token, submitter: submitter},
                function (data) {
                    if (data === "true") {
                        location.reload();
                    }
                });
        }
    }
</script>

<script>
    if (<?php echo $USER["loggedIn"] ?>) {
        if ("<?php echo $ticket['submitter'] ?>" === "<?php echo $USER["username"] ?>" || "<?php echo $USER["rank"] ?>" === "admin") {
            document.getElementById("ticketData").style.display = "block";
        } else {
            document.getElementById("ticketData").innerHTML = "";
            document.getElementById("ticketNoPerms").style.display = "block";
        }
    } else {
        document.getElementById("ticketData").innerHTML = "";
        document.getElementById("ticketNoPerms").style.display = "block";
    }
</script>
<?php include("../../../assets/page_foot.php"); ?>