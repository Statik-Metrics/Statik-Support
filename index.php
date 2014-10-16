<?
$page_title = "Index";
include("assets/page_head.php");

if ($USER["loggedIn"]) {
    $stmtOpen = $pdo->prepare("SELECT * FROM statik_supportTickets WHERE submitter= :username AND status='Open'");
    $stmtClosed = $pdo->prepare("SELECT * FROM statik_supportTickets WHERE submitter= :username AND status='Closed'");
    $stmtOpen->execute(array("username" => $USER["username"]));
    $stmtClosed->execute(array("username" => $USER["username"]));
    $openTickets = $stmtOpen->rowCount();
    $closedTickets = $stmtClosed->rowCount();
}
?>

    <div class="ui column">
        <div class="ui container">
            <div class="ui segment piled orange">
                <h2 class="ui orange dividing header">Statik Support</h2>

                <p>Welcome to the Statik support desk.</p>

                <p>If you can't find the info you need on our <a href="http://wiki.statik.io/">wiki</a> or are having
                    issues setting up Statik for your plugin you should contact by opening a ticket and an administrator
                    will happily help you out.</p>
            </div>
        </div>
    </div>

<?php if ($USER["loggedIn"]): ?>
    <div class="ui column">
    <div class="ui container">
    <div class="ui grid">

        <div class="eight wide column">
            <div class="ui attached segment piled">
                <h6 class="ui orange dividing header">Open a Ticket</h6>

                <p>Before submitting a ticket check out our <a href="http://wiki.statik.io">wiki</a>, 99% of the
                    time you will find the answer there. </p>
            </div>
            <a class="bottom attached ui orange button" href="tickets/new">Open a Ticket</a>
        </div>

        <div class="eight wide column">
            <div class="ui attached segment piled">
                <h6 class="ui orange dividing header">My Tickets</h6>

                <p>By clicking the below button you can view all previous tickets you have created. All open
                    and closed tickets can be viewed.</p>

                <div class="ui green label circular">&nbsp;Open
                    Tickets&nbsp;&nbsp;<strong><?php echo $openTickets ?></strong></div>
                <div class="ui black label circular">&nbsp;Closed
                    Tickets&nbsp;&nbsp;<strong><?php echo $closedTickets ?></strong></div>
            </div>
            <a class="bottom attached ui orange button" href="/tickets">View Previous Tickets</a>
        </div>
    </div>
<?php endif ?>
    </div>
</div>
    </div>

    <script>
        $('.ui.dropdown').dropdown();
    </script>

<?php include("assets/page_foot.php"); ?>