<?php
$page_title = "ACP - Unanswered Tickets";
include("../../../assets/page_head.php");

if (!($USER["loggedIn"] && $USER["rank"] === "admin")) {
    die('<script>window.location.replace("/")</script>');
}

$stmt = $pdo->prepare("SELECT * FROM statik_supportTickets WHERE status='Open' ORDER BY status DESC, CASE priority WHEN 'High' THEN 3 WHEN 'Medium' THEN 2 WHEN 'Low' THEN 1 END DESC, id DESC");
$stmt->execute();
$stmtAnswered = $pdo->prepare("SELECT * FROM statik_supportReplies WHERE ticket_id=:id ORDER BY id DESC LIMIT 1");
?>

    <div class="ui column">
        <div class="ui container">

            <h2 class="ui dividing header">Admin Control Panel - Unanswered Tickets</h2>

            <div class="ui animated fade button small" onclick="window.location.replace('/acp')">
                <div class="visible content">Back</div>
                <div class="hidden content">
                    <i class="left icon"></i>
                </div>
            </div>

            <div class="ui piled orange segment" id="ticketsData" style="display: none">

                <table class="ui table segment">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Priority</th>
                        <th>Category</th>
                        <th>Subject</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($ticket = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $stmtAnswered->execute(array("id" => $ticket["id"]));
                        $reply = $stmtAnswered->fetch(PDO::FETCH_ASSOC);
                        if (!isAdmin($reply["username"])) {
                            echo "<tr>
                                      <td>#" . $ticket['id'] . "</td>
                                      <td>" . date("d-m-Y H:i", strtotime($ticket['datetime'])) . "</td>
                                      <td class='" . (($ticket['priority'] === 'Low') ? 'positive' : '') . (($ticket['priority'] === 'Medium') ? 'warning' : '') . (($ticket['priority'] === 'High') ? 'error' : '') . "'>" . $ticket['priority'] . "</td>
                                      <td>" . $ticket['category'] . "</td>
                                      <td>" . $ticket['subject'] . "</td>
                                    <td><div class='ui blue button tiny' onclick=\"window.location.replace('../view/" . $ticket['id'] . "') \">View Ticket</div></td>
                                  </tr>";
                        }
                    }
                    ?>
                    </tbody>
                </table>

            </div>

            <div class="ui piled orange segment" id="noTickets" style="display: none">
                <div class="ui icon message yellow">
                    <i class="ticket icon"></i>

                    <div class="content">
                        <div class="header">
                            No Tickets
                        </div>
                        <p>There are no tickets available to view in this category.</p>
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
        if (<?php echo $stmt->rowCount() ?> ===
        0
        )
        {
            document.getElementById("noTickets").style.display = "block";
        }
        else
        {
            document.getElementById("ticketsData").style.display = "block";
        }
    </script>
<?php include("../../../assets/page_foot.php"); ?>