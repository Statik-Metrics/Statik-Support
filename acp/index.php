<?php
$page_title = "ACP - Index";
include("../assets/page_head.php");


if (!($USER["loggedIn"] && $USER["rank"] === "admin")) {
    die('<script>window.location.replace("/")</script>');
}

$stmtOpen = $pdo->prepare("SELECT * FROM statik_supportTickets WHERE status='Open' ORDER BY id ASC");
$stmtOpen->execute();
$stmtClosed = $pdo->prepare("SELECT * FROM statik_supportTickets WHERE status='Closed'");
$stmtClosed->execute();

$stmtAnswered = $pdo->prepare("SELECT username FROM statik_supportReplies WHERE ticket_id=:id ORDER BY id DESC LIMIT 1");

$openCount = $stmtOpen->rowCount();
$closedCount = $stmtClosed->rowCount();

$answeredCount = 0;
$unansweredCount = 0;
$openWebsiteIssueCount = 0;
$openIntegrationIssueCount = 0;
$openApiIssueCount = 0;
$openOtherQuestionCount = 0;

$answeredOtherQuestionCount = 0;
$answeredWebsiteIssueCount = 0;
$answeredIntegrationIssueCount = 0;
$answeredApiIssueCount = 0;

$unansweredOtherQuestionCount = 0;
$unansweredWebsiteIssueCount = 0;
$unansweredIntegrationIssueCount = 0;
$unansweredApiIssueCount = 0;

while ($ticket = $stmtOpen->fetch(PDO::FETCH_ASSOC)) {
    switch($ticket["category"]) {
        case "Website Issue": $openWebsiteIssueCount = $openWebsiteIssueCount + 1; break;
        case "Integration Issue": $openIntegrationIssueCount = $openIntegrationIssueCount + 1; break;
        case "API Issue": $openApiIssueCount = $openApiIssueCount + 1; break;
        case "Other Questions": $openOtherQuestionCount = $openOtherQuestionCount + 1; break;
    }

    $stmtAnswered->execute(array("id" => $ticket["id"]));
    while ($reply = $stmtAnswered->fetch(PDO::FETCH_ASSOC)) {
        if (isAdmin($reply["username"])) {
            $answeredCount = $answeredCount + 1;
            switch($ticket["category"]) {
                case "Website Issue": $answeredWebsiteIssueCount = $answeredWebsiteIssueCount + 1; break;
                case "Integration Issue": $answeredIntegrationIssueCount = $answeredIntegrationIssueCount + 1; break;
                case "API Issue": $answeredApiIssueCount = $answeredApiIssueCount + 1; break;
                case "Other Questions": $answeredOtherQuestionCount = $answeredOtherQuestionCount + 1; break;
            }
            
        }
    }
}
$unansweredOtherQuestionCount = $openOtherQuestionCount - $answeredOtherQuestionCount;
$unansweredWebsiteIssueCount = $openWebsiteIssueCount - $answeredWebsiteIssueCount;
$unansweredIntegrationIssueCount = $openIntegrationIssueCount - $answeredIntegrationIssueCount;
$unansweredApiIssueCount = $openApiIssueCount - $answeredApiIssueCount;
$unansweredCount = $openCount - $answeredCount;

$closedWebsiteIssueCount = 0;
$closedIntegrationIssueCount = 0;
$closedApiIssueCount = 0;
$closedOtherQuestionCount = 0;

$stmtClosed->execute();
while ($ticket = $stmtClosed->fetch(PDO::FETCH_ASSOC)) {
    switch ($ticket["category"]) {
        case "Website Issue": $closedWebsiteIssueCount = $closedWebsiteIssueCount + 1; break;
        case "Integration Issue": $closedIntegrationIssueCount = $closedIntegrationIssueCount + 1; break;
        case "API Issue": $closedApiIssueCount = $closedApiIssueCount + 1; break;
        case "Other Questions": $closedOtherQuestionCount = $closedOtherQuestionCount + 1; break;
    }
}
?>

<div class="ui column">
    <div class="ui container">
        <h2 class="ui dividing header">Admin Control Panel</h2>

        <div class="ui <?php echo(($unansweredCount === 0) ? 'green' : 'red') ?> message center">
            <b><?php echo $openCount ?></b> Open Ticket(s) - <b><?php echo $unansweredCount ?></b> Unanswered Ticket(s) - <b><?php echo $answeredCount ?></b> Answered Ticket(s) - <b><?php echo $closedCount ?></b> Closed Ticket(s)
        </div>

        <div class="ui piled red segment center">
            <div class='ui blue button small' onclick="window.location.replace('tickets/unanswered')">View All Unanswered Tickets</div>&nbsp;&nbsp;
            <div class='ui purple button small' onclick="window.location.replace('tickets/answered')">View All Answered Tickets</div>&nbsp;&nbsp;
            <div class='ui purple button small' onclick="window.location.replace('tickets/open')">View All Open Tickets</div>&nbsp;&nbsp;
            <div class='ui red button small' onclick="window.location.replace('tickets/closed')">View All Closed Tickets</div>
        </div>

        <div class="ui piled red segment center">
            <div class="ui two column grid">
                <div class="column">
                    <div id="openStatusChart"></div>
                </div>
                <div class="column">
                    <div id="openCategoryChart"></div>
                </div>
                <div class="column">
                    <div id="unansweredCategoryChart"></div>
                </div>
                <div class="column">
                    <div id="answeredCategoryChart"></div>
                </div>
                <div class="column">
                    <div id="closedCategoryChart"></div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
<script>
    $('.ui.dropdown').dropdown();
</script>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
    google.load("visualization", "1", {packages: ["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var openStatus_Answered = <?php echo $answeredCount ?>;
        var openStatus_Unanswered = <?php echo $unansweredCount ?>;
        var openStatus_data = google.visualization.arrayToDataTable([
            ['Status', 'Amount'],
            ['Answered', openStatus_Answered],
            ['Unanswered', openStatus_Unanswered]
        ]);
        var openStatus_options = {
            title: 'Open Tickets by Status',
            colors: ['#16a085', '#c0392b'],
            backgroundColor: 'transparent'
        };
        var openStatus_chart = new google.visualization.PieChart(document.getElementById('openStatusChart'));
        openStatus_chart.draw(openStatus_data, openStatus_options);
        
        var openCategory_website = <?php echo $openWebsiteIssueCount ?>;
        var openCategory_api = <?php echo $openApiIssueCount ?>;
        var openCategory_integration = <?php echo $openIntegrationIssueCount ?>;
        var openCategory_other = <?php echo $openOtherQuestionCount ?>;
        var openCategory_data = google.visualization.arrayToDataTable([
            ['Category', 'Amount'],
            ['Website Issue', openCategory_website],
            ['API Issue', openCategory_api],
            ['Integration Issue', openCategory_integration],
            ['Other Question', openCategory_other]
        ]);
        var openCategory_options = {
            title: 'Open Tickets by Category',
            colors: ['#3498db', '#8e44ad', '#31cd73', '#f39c12'],
            backgroundColor: 'transparent'
        };
        var openCategory_chart = new google.visualization.PieChart(document.getElementById('openCategoryChart'));
        openCategory_chart.draw(openCategory_data, openCategory_options);

        var unansweredCategory_website = <?php echo $unansweredWebsiteIssueCount ?>;
        var unansweredCategory_api = <?php echo $unansweredApiIssueCount ?>;
        var unansweredCategory_integration = <?php echo $unansweredIntegrationIssueCount ?>;
        var unansweredCategory_other = <?php echo $unansweredOtherQuestionCount ?>;
        var unansweredCategory_data = google.visualization.arrayToDataTable([
            ['Category', 'Amount'],
            ['Website Issue', unansweredCategory_website],
            ['API Issue', unansweredCategory_api],
            ['Integration Issue', unansweredCategory_integration],
            ['Other Question', unansweredCategory_other]
        ]);
        var unansweredCategory_options = {
            title: 'Unanswered Tickets by Category',
            colors: ['#3498db', '#8e44ad', '#31cd73', '#f39c12'],
            backgroundColor: 'transparent'
        };
        var unansweredCategory_chart = new google.visualization.PieChart(document.getElementById('unansweredCategoryChart'));
        unansweredCategory_chart.draw(unansweredCategory_data, unansweredCategory_options);

        var answeredCategory_website = <?php echo $answeredWebsiteIssueCount ?>;
        var answeredCategory_api = <?php echo $answeredApiIssueCount ?>;
        var answeredCategory_integration = <?php echo $answeredIntegrationIssueCount ?>;
        var answeredCategory_other = <?php echo $answeredOtherQuestionCount ?>;
        var answeredCategory_data = google.visualization.arrayToDataTable([
            ['Category', 'Amount'],
            ['Website Issue', answeredCategory_website],
            ['API Issue', answeredCategory_api],
            ['Integration Issue', answeredCategory_integration],
            ['Other Question', answeredCategory_other]
        ]);
        var answeredCategory_options = {
            title: 'Answered Tickets by Category',
            colors: ['#3498db', '#8e44ad', '#31cd73', '#f39c12'],
            backgroundColor: 'transparent'
        };
        var answeredCategory_chart = new google.visualization.PieChart(document.getElementById('answeredCategoryChart'));
        answeredCategory_chart.draw(answeredCategory_data, answeredCategory_options);

        var closedCategory_website = <?php echo $closedWebsiteIssueCount ?>;
        var closedCategory_api = <?php echo $closedApiIssueCount ?>;
        var closedCategory_integration = <?php echo $closedIntegrationIssueCount ?>;
        var closedCategory_other = <?php echo $closedOtherQuestionCount ?>;
        var closedCategory_data = google.visualization.arrayToDataTable([
            ['Category', 'Amount'],
            ['Website Issue', closedCategory_website],
            ['API Issue', closedCategory_api],
            ['Integration Issue', closedCategory_integration],
            ['Other Question', closedCategory_other]
        ]);
        var closedCategory_options = {
            title: 'Closed Tickets by Category',
            colors: ['#3498db', '#8e44ad', '#31cd73', '#f39c12'],
            backgroundColor: 'transparent'
        };
        var closedCategory_chart = new google.visualization.PieChart(document.getElementById('closedCategoryChart'));
        closedCategory_chart.draw(closedCategory_data, closedCategory_options);
    }
</script>