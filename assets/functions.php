<?php
session_start();
function sendMail($username, $email, $subject, $content){

    $headers = 'From: Statik <noreply@statik.io>' . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    $message = '
    <html>
        <body>
        <center>
            <div style="padding-right: 15px; padding-left: 15px; text-align: left;">
                <center><img src="http://support.statik.io/assets/images/email.png" style="width:40%;"></center>
                <hr style="height: 0; -webkit-box-sizing: content-box; -moz-box-sizing: content-box; margin-top: 20px; margin-bottom: 20px; border: 0; border-top: 1px solid #eee;">
                <br>
                Hello ' . $username . ',
                <br><br>
                ' . $content . '
                <br><br>
                Best Regards
                <br>
                Statik Staff
                <br><br>
                **Please do not reply to this email. The inbox is not monitored**
            </div>
        </center>
        </body>
    </html>
 ';

    mail($email, $subject, $message, $headers);

}

function isAdmin($username)
{
    $monogoConnection = new Mongo(MongoDbUrl);
    $users = $monogoConnection->selectDB(MongoDbName)->selectCollection("users")->findOne(array('username' => $username));
    return $users["group"] == "admin";
}

function getEmail($username)
{
    $monogoConnection = new Mongo(MongoDbUrl);
    $users = $monogoConnection->selectDB(MongoDbName)->selectCollection("users")->findOne(array('username' => $username));

    if (isset($users["selectedEmail"])) {
        return $users["selectedEmail"];
    } elseif (isset($users["local"]["email"])) {
        return $users["local"]["email"];
    } elseif (isset($users["bitbucket"]["email"])) {
        return $users["bitbucket"]["email"];
    } elseif (isset($users["github"]["email"])) {
        return $users["github"]["email"];
    } elseif (isset($users["google"]["email"])) {
        return $users["google"]["email"];
    }
}

try {
    $pdo = new PDO("mysql:host=MysqlUrl;dbname=MysqlDatabase", MysqlUsername, MysqlPassword);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    echo $e->getMessage();
}

$session_id = explode('.', str_replace("s:", "", $_COOKIE["connect_sid"]));

$redisObj = new Redis();
$redisObj->connect(RedisUrl, RedisPort);
$redisObj->auth(RedisAuth);
$redis = json_decode($redisObj->get("sess:" . $session_id[0]), true);

if (isset($redis["passport"]["user"])) {
    $monogoConnection = new Mongo(MongoDbUrl);
    $users = $monogoConnection->selectDB(MongoDbName)->selectCollection("users")->findOne(array('username' => $username));
    $USER["loggedIn"] = 1;
    $USER["username"] = $users["username"];
    $USER["rank"] = $users["group"];
    if (isset($users["selectedEmail"])) {
        $USER["email"] = $users["selectedEmail"];
    } elseif (isset($users["local"]["email"])) {
        $USER["email"] = $users["local"]["email"];
    } elseif (isset($users["bitbucket"]["email"])) {
        $USER["email"] = $users["bitbucket"]["email"];
    } elseif (isset($users["github"]["email"])) {
        $USER["email"] = $users["github"]["email"];
    } elseif (isset($users["google"]["email"])) {
        $USER["email"] = $users["google"]["email"];
    }
    if(!isset($_SESSION["token"]))
        $_SESSION["token"] = md5(uniqid(mt_rand(), true));
} else {
    session_unset();
    $USER["loggedIn"] = 0;
}