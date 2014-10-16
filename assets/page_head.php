<?php
include("functions.php");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Statik Support | <? echo $page_title ?></title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="Statik Support - User Support for Statik Metrics">
    <meta name="keywords" content="minecraft metrics statik support public java plugin server system stats graphs">
    <meta name="revisit-after" content="7 days">
    <meta name="robots" content="index,follow">

    <link rel="shortcut icon" href="/assets/images/icon.png">
    <link rel="apple-touch-icon-precomposed" href="/assets/images/icon.png">

    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="//cdnjs.cloudflare.com/ajax/libs/normalize/3.0.1/normalize.css" rel="stylesheet">
    <link href="//cdn.jsdelivr.net/semantic-ui/0.19.0/css/semantic.min.css" rel="stylesheet">

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/semantic-ui/0.19.0/javascript/semantic.min.js"></script>

    <link href="/assets/css/style.css" rel="stylesheet">
    <script src="/assets/js/scripts.js" rel="stylesheet"></script>
</head>

<body>

<div class="ui one column page grid">

    <div class="ui tiered menu">
        <div id="menu" class="ui orange inverted menu">
            <span class="header item padded-title"><strong>&nbsp;Statik&nbsp;</strong></span>
            <a class="item" href="http://statik.io"><i class="home icon"></i> Home</a>
            <a class="item" href="http://api.statik.io/"><i class="setting icon"></i> API</a>
            <a class="item" href="http://wiki.statik.io/"><i class="info letter icon"></i> Wiki</a>
            <a class="active item" href="/"><i class="chat icon"></i> Support</a>

            <div class="ui dropdown item right">
                <i class="user icon"></i> Account <i class="icon dropdown"></i>
                <div class="menu">
                    <? if ($USER["loggedIn"]): ?>
                        <a class="item" href="http://dev.statik.io/ucp">My Plugin List</a>
                        <a class="item" href="http://dev.statik.io/ucp/settings">Settings</a>
                        <a class="item" href="http://dev.statik.io/users/logout">Logout</a>
                    <?php else: ?>
                        <a class="item" href="http://dev.statik.io/users/login">Login</a>
                        <a class="item" href="http://dev.statik.io/users/signup">Sign-up</a>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <div class="ui sub menu">
            <a class="item" href="/">Home</a>
            <a class="item" href="/tickets/new">Open A Ticket</a>
            <a class="item" href="/tickets">Previous Tickets</a>
            <a class="item" href="mailto:support@statik.io">Send us an Email</a>

            <?php if ($USER["loggedIn"] && $USER["rank"] === "admin"): ?>
                <a class="right menu item" href="/acp">Admin Control Panel</a>
            <?php endif ?>
        </div>
    </div>
    <br>