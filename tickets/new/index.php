<?php
$page_title = "Create New Ticket";
include("../../assets/page_head.php");
?>
<div class="ui five small steps">
    <div class="ui active step" id="stageOneStep">Category</div>
    <div class="ui disabled step" id="stageTwoStep">Subject</div>
    <div class="ui disabled step" id="stageThreeStep">Description</div>
    <div class="ui disabled step" id="stageFourStep">Review</div>
    <div class="ui disabled step" id="stageFiveStep">Complete</div>
</div>

<div class="ui column" id="addTicket" style="display: none">
    <!-- \*/ Category and Priority Stage \*/ -->
    <div class="ui container" id="categoryStage">
        <br>

        <div class="ui piled attached orange segment" id=categoryStage">
            <div class="ui grid">

                <div class="ui eight wide column">
                    <h2 class="header">Category:</h2>

                    <div class="ui red message" id="catError" style="display: none"><h4 class="ui header">You must
                            select a
                            category before
                            you can continue</h4>
                    </div>
                    <div class="ui selection dropdown fluid labeled" id="catDropdown">
                        <input type="hidden" name="category" id="category">

                        <div class="default text">Please Select One</div>
                        <i class="dropdown icon"></i>

                        <div class="menu">
                            <div class="item" data-value="Website Issue">Website Issue</div>
                            <div class="item" data-value="Integration Issue">Integration Issue</div>
                            <div class="item" data-value="API Issue">API Issue</div>
                            <div class="item" data-value="Other Questions">Other Question</div>
                        </div>
                    </div>
                </div>
                <div class="ui eight wide column">
                    <h2 class="header">Priority:</h2>

                    <div class="ui red message" id="priorityError" style="display: none"><h4 class="ui header">You must
                            select a
                            priority before
                            you can continue</h4>
                    </div>
                    <div class="ui selection dropdown fluid labeled" id="priorityDropdown">
                        <input type="hidden" name="priority" id="priority" value="Medium">

                        <div class="default text">Please Select One</div>
                        <i class="dropdown icon"></i>

                        <div class="menu">
                            <div class="item" data-value="Low">Low</div>
                            <div class="item" data-value="Medium">Medium</div>
                            <div class="item" data-value="High">High</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom attached ui orange button" onclick="toStageTwo()">Continue</div>
    </div>


    <!-- \*/ Subject Stage \*/ -->
    <div class="ui container" id="subjectStage" style="display: none">
        <br>

        <div class="ui piled attached orange segment">
            <h2>Subject:</h2>

            <div class="ui red message" id="subError" style="display: none"><h4 class="ui header">You must enter a
                    subject before
                    you can continue</h4>
            </div>
            <div class="ui form">
                <div class="ui input fluid" id="subjectBox">
                    <input type="text" placeholder="Please Enter a Subject" id="subject">
                </div>
            </div>

        </div>
        <div class="bottom attached ui orange button" onclick="toStageThree()">Continue</div>
    </div>

    <!-- \*/ Description Stage \*/ -->
    <div class="ui container" id="descriptionStage" style="display: none">
        <br>

        <div class="ui piled attached orange segment">
            <h2>Description:</h2>

            <div class="ui red message" id="descError" style="display: none"><h4 class="ui header">You must enter a
                    description of
                    your issue</h4>
            </div>

            <div class="ui form">
                <div class="field">
                    <textarea placeholder="Enter a description of your issue here..." id="description"></textarea>
                </div>
            </div>
        </div>
        <div class="bottom attached ui orange button" onclick="toStageFour()">Continue</div>
    </div>

    <!-- \*/ Review Stage \*/ -->
    <div class="ui container" id="reviewStage" style="display: none">
        <br>

        <div class="ui piled attached orange segment">
            <h2>Review:</h2>

            <p id="revCategory"></p>

            <div class="ui divider"></div>
            <p id="revSubject"></p>

            <div class="ui divider"></div>

            <p id="revDescription"></p>
        </div>
        <div class="bottom attached ui orange button" onclick="toStageFive()">Submit</div>
    </div>

    <!-- \*/ Complete Stage \*/ -->
    <div class="ui container" id="completeStage" style="display: none">
        <br>

        <div class="ui piled orange segment">
            <div class="ui active dimmer" id="completeStageLoading">
                <div class="ui text loader">Processing...</div>
            </div>
            <h2>Complete:</h2>

            <div id="completeStageSuccess" style="display: none">

                <div class="ui icon message green">
                    <i class="thumbs up icon"></i>

                    <div class="content">
                        <div class="header">Success!</div>
                        <p>Your ticket has been successfully created. The ticket ID is <a id="idLink"></a>.</p>
                    </div>
                </div>

                <p class="center">
                    <small>An email has been sent to your address with the ticket information.<br>
                        You will be emailed when a support staff member responds to your ticket.<br>
                        If you would like to view the ticket progress you can do so now.
                    </small>
                </p>

            </div>

            <div id="completeStageFailure" style="display: none">
                <div class="ui icon message red">
                    <i class="thumbs down icon"></i>

                    <div class="content">
                        <div class="header">Failure</div>
                        <p>Failed to create your ticket, we apologise for this inconvenience, Please try again
                            later.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

    <div class=" column ui piled orange segment" id="notLoggedIn" style="display: none">
        <div class="ui icon message red">
            <i class="thumbs down icon"></i>

            <div class="content">
                <div class="header">
                    Not Logged In.
                </div>
                <p>You must log in to continue.</p>
            </div>
        </div>
    </div>

</div>
<script>
    $('.ui.dropdown').dropdown();
</script>
<script>
    function toStageTwo() {
        if (document.getElementById("category").value === "") {
            $("#catError").slideDown("fast");
            document.getElementById("catDropdown").className = "ui selection dropdown fluid error";
        } else {
            document.getElementById("catError").className = "ui red message hidden";
            document.getElementById("catDropdown").className = "ui selection dropdown fluid";

            if (document.getElementById("priority").value == "") {
                $("#priorityError").slideDown("fast");
                document.getElementById("priorityDropdown").className = "ui selection dropdown fluid error";
            } else {
                document.getElementById("categoryStage").style.display = "none";
                document.getElementById("stageOneStep").className = "ui step";
                document.getElementById("stageTwoStep").className = "ui active step";
                document.getElementById("subjectStage").style.display = "block";
            }
        }
    }
    ;

    function toStageThree() {
        if (document.getElementById("subject").value === "") {
            $("#subError").slideDown("fast");
            document.getElementById("subjectBox").className = "ui labeled input fluid error";
        } else {
            document.getElementById("subjectStage").style.display = "none";
            document.getElementById("stageTwoStep").className = "ui step";
            document.getElementById("stageThreeStep").className = "ui active step";
            document.getElementById("descriptionStage").style.display = "block";
        }
    }
    ;

    function toStageFour() {
        if (document.getElementById("description").value === "") {
            $("#descError").slideDown("fast");
        } else {
            document.getElementById("descriptionStage").style.display = "none";
            document.getElementById("stageThreeStep").className = "ui step";
            document.getElementById("stageFourStep").className = "ui active step";

            document.getElementById("revCategory").innerHTML = "<strong>Category: </strong>" + document.getElementById("category").value;
            document.getElementById("revSubject").innerHTML = "<strong>Subject: </strong>" + document.getElementById("subject").value;
            document.getElementById("revDescription").innerHTML = "<strong>Description: </strong><br>" + htmlspecialchars(document.getElementById("description").value);

            document.getElementById("reviewStage").style.display = "block";
        }
    }
    ;

    function toStageFive() {
        document.getElementById("reviewStage").style.display = "none";
        document.getElementById("stageFourStep").className = "ui step";
        document.getElementById("stageFiveStep").className = "ui active step";
        document.getElementById("completeStage").style.display = "block";
        document.getElementById("completeStageLoading").style.display = "block";

        var category = document.getElementById("category").value;
        var subject = document.getElementById("subject").value;
        var description = document.getElementById("description").value;
        var priority = document.getElementById("priority").value;
        var token = "<?php echo $_SESSION["token"] ?>";

        $.post("newTicket.php", {category: category, subject: subject, description: description, priority: priority, token: token},
            function (data) {
                if (data != "false") {
                    document.getElementById("idLink").setAttribute("href", "/tickets/view/" + data);
                    document.getElementById("idLink").innerHTML = "#" + data;
                    document.getElementById("completeStageLoading").style.display = "none";
                    document.getElementById("completeStageSuccess").style.display = "block";
                } else {
                    document.getElementById("completeStageLoading").style.display = "none";
                    document.getElementById("completeStageFailure").style.display = "block";
                }
            });
    }
    ;

</script>
<script>
        if (<?php echo $USER["loggedIn"] ?> === 0){
            document.getElementById("notLoggedIn").style.display = "block";
            document.getElementById("addTicket").innerHTML = "";
        }else{
            document.getElementById("addTicket").style.display = "block";
        }
</script>
<?php include("../../assets/page_foot.php"); ?>