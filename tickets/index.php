<?php
$page_title = "View Tickets";
include("../assets/page_head.php");

$stmt = $pdo->prepare("SELECT * FROM statik_supportTickets WHERE submitter= :username ORDER BY status DESC,  CASE priority WHEN 'High' THEN 3 WHEN 'Medium' THEN 2 WHEN 'Low' THEN 1 END DESC, id DESC");
$stmt->execute(array("username" => $USER["username"]));
?>

<div class="ui column">
    <div class="ui container">
        <div class="ui piled orange segment" id="ticketsData" style="display: none">

            <table class="ui table segment">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Priority</th>
                    <th>Category</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($ticket = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                        <td>#" . $ticket['id'] . "</td>
                        <td>" . date("d-m-Y H:i", strtotime($ticket['datetime'])) . "</td>
                        <td class='" . (($ticket['priority'] === 'Low') ? 'positive' : '') . (($ticket['priority'] === 'Medium') ? 'warning' : '') . (($ticket['priority'] === 'High') ? 'error' : '') . "'>" . $ticket['priority'] . "</td>
                        <td>" . $ticket['category'] . "</td>
                        <td>" . $ticket['subject'] . "</td>
                        <td>" . $ticket['status'] . "</td>
                        <td><div class='ui blue button tiny' onclick=\"window.location.replace('view/" . $ticket['id'] . "') \">View Ticket</div></td>
                    </tr>";
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
                    <p>You don't have any tickets to view. You can open a ticket by pressing "Open A Ticket" at the
                        top of the page</p>
                </div>
            </div>
        </div>

        <div class="ui piled orange segment" id="noPerms" style="display: none">
            <div class="ui icon message red">
                <i class="thumbs down icon"></i>

                <div class="content">
                    <div class="header">
                        You do not have permission to this page.
                    </div>
                    <p>You must log in to continue.</p>
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
    if(<?php echo $USER["loggedIn"] ?>){
        if (<?php echo $stmt->rowCount() ?> === 0){
            document.getElementById("noTickets").style.display = "block";
        } else {
            document.getElementById("ticketsData").style.display = "block";
        }
    }else{
        document.getElementById("noPerms").style.display = "block";
    }
</script>
<?php include("../assets/page_foot.php"); ?>