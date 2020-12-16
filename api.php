<?php

class Response
{
    public static function json($code, $message = '', $data = [])
    {
        $result = [
            "code" => $code,
            "message" => $message,
            "data" => $data
        ];
        echo json_encode($result);
        exit;
    }
}

$servername = 'localhost';
$username = 'root';
$password = '123456';
$db = 'blog_wu';

$conn = new mysqli($servername, $username, $password, $db);

header('Content-Type:text/json;charset=utf-8');

if ($_POST['function'] == "draw_slider") {
    $result = $conn->query("SELECT Link,LinkTitle FROM links ORDER BY ID");
    $link_counts = $result->num_rows;
    $data = ['count_links' => $link_counts];
    $links = [];
    while ($row = $result->fetch_assoc()) {
        array_push($links, ['LinkTitle' => $row['LinkTitle'], 'Link' => $row['Link']]);
    }
    $data += ['links' => $links];
    $result = $conn->query("SELECT index_name FROM indexes ORDER BY IID");
    $index_counts = $result->num_rows;
    $data += ['count_indexes' => $index_counts];
    $indexes = [];
    while ($row = $result->fetch_assoc()) {
        array_push($indexes, ['IndexName' => $row['index_name']]);
    }
    $data += ['indexes' => $indexes];
    Response::json(200, "API Successfully Called", $data);
    exit(0);
}

if ($_POST['function'] == "login") {
    $stmt = $conn->prepare("SELECT Password FROM user WHERE UserName=?");
    $stmt->bind_param("s", $_POST['username']);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($password);
    if ($stmt->num_rows() != 1) {
        $arr = ["isSucceed" => false];
        Response::json(200, "API successfully called", $arr);
        exit(0);
    }
    $stmt->fetch();
    if (md5("#*#*4636" . md5($_POST['password']) . "114514*#*#") != $password) {
        $arr = ["isSucceed" => false];
        Response::json(200, "API successfully called", $arr);
        exit(0);
    }
    $TokenID = md5(md5($_POST['username']) . microtime(true));
    $Token = md5(md5($_POST['password']) . microtime(true));
    setcookie("TokenID", $TokenID, time() + 10 * 365 * 24 * 60 * 60, "/");
    setcookie("Token", $Token, time() + 10 * 365 * 24 * 60 * 60, "/");
    $conn->query("UPDATE user SET TokenID='$TokenID',Token='$Token' WHERE UserName='" . $_POST['username'] . "'");
    $arr = ["isSucceed" => true];
    Response::json(200, "API successfully called", $arr);
    exit(0);
}

if (@$_POST['function'] == "logout") {
    //echo "UPDATE user SET TokenID=null,Token=null WHERE TokenID='".$_COOKIE['TokenID']."'";
    $conn->query("UPDATE user SET TokenID=null,Token=null WHERE TokenID='" . $_COOKIE['TokenID'] . "'");
    $arr = ["isSucceed" => "成功"];
    Response::json(200, "API successfully called", $arr);
    exit(0);
}

if ($_POST['function'] == 'login_check') {
    if (check_login($conn)) {
        $arr = ["isSucceed" => "true"];
        Response::json(200, "API successfully called", $arr);
        exit(0);
    } else {
        $arr = ["isSucceed" => "false"];
        Response::json(200, "API successfully called", $arr);
        exit(0);
    }
}

if ($_POST['function'] == "add_link") {
    if (!check_login($conn)) {
        $arr = ["isSucceed" => "false", "error_message" => "您未登录"];
        Response::json(200, "API successfully called", $arr);
        exit(0);
    }
    $result = $conn->query("SELECT ID FROM links WHERE LinkTitle='" . $_POST['link_title'] . "'");
    if ($result->num_rows != 0) {
        $arr = ["isSucceed" => "false", "error_message" => "当前标题链接已存在"];
        Response::json(200, "API successfully called", $arr);
        exit(0);
    }
    $conn->query("INSERT INTO links (LinkTitle,Link)VALUES('" . $_POST['link_title'] . "','" . $_POST['link'] . "')");
    $arr = ["isSucceed" => "true"];
    Response::json(200, "API successfully called", $arr);
    exit(0);

}

if ($_POST['function'] == "update_link") {
    if (!check_login($conn)) {
        $arr = ["isSucceed" => 'false'];
        Response::json(200, "API successfully called", $arr);
        exit(0);
    }
    $conn->query("UPDATE links SET LinkTitle='" . $_POST['link_title'] . "' , Link='" . $_POST['link'] . "' WHERE LinkTitle='" . $_POST['link_title_old'] . "'");
    $arr = ["isSucceed" => 'true'];
    Response::json(200, "API successfully called", $arr);
    exit(0);
}

if ($_POST['function'] == "delete_link") {
    if (!check_login($conn)) {
        $arr = ["isSucceed" => 'false'];
        Response::json(200, "API successfully called", $arr);
        exit(0);
    }
    $conn->query("DELETE FROM links WHERE LinkTitle='" . $_POST['link_title'] . "'");
    $arr = ["isSucceed" => 'true'];
    Response::json(200, "API successfully called", $arr);
    exit(0);
}

if ($_POST['function'] == "add_index") {
    if (!check_login($conn)) {
        $arr = ["isSucceed" => "false", "error_message" => "您未登录"];
        Response::json(200, "API successfully called", $arr);
        exit(0);
    }
    $result = $conn->query("SELECT iid FROM indexes WHERE index_name='" . $_POST['index_name'] . "'");
    if ($result->num_rows != 0) {
        $arr = ["isSucceed" => "false", "error_message" => "当前标题链接已存在"];
        Response::json(200, "API successfully called", $arr);
        exit(0);
    }
    $conn->query("INSERT INTO indexes (index_name)VALUES('" . $_POST['index_name'] . "')");
    $arr = ["isSucceed" => "true"];
    Response::json(200, "API successfully called", $arr);
    exit(0);

}

if ($_POST['function'] == "update_index") {
    if (!check_login($conn)) {
        $arr = ["isSucceed" => 'false'];
        Response::json(200, "API successfully called", $arr);
        exit(0);
    }
    $conn->query("UPDATE indexes SET index_name='" . $_POST['index_title'] . "' WHERE index_name='" . $_POST['index_title_old'] . "'");
    $arr = ["isSucceed" => 'true'];
    Response::json(200, "API successfully called", $arr);
    exit(0);
}

if ($_POST['function'] == "delete_index") {
    if (!check_login($conn)) {
        $arr = ["isSucceed" => 'false'];
        Response::json(200, "API successfully called", $arr);
        exit(0);
    }
    $conn->query("DELETE FROM indexes WHERE index_name='" . $_POST['index_name'] . "'");
    $arr = ["isSucceed" => 'true'];
    Response::json(200, "API successfully called", $arr);
    exit(0);
}

if ($_POST['function'] == "add_page") {
    if (!check_login($conn)) {
        $arr = ["isSucceed" => "false", "error_message" => "您未登录"];
        Response::json(200, "API successfully called", $arr);
        exit(0);
    }
    $conn->query("INSERT INTO pages (title, index_name, description, content)
        VALUES ('" . $_POST['title'] . "','" . $_POST['index_name'] . "','" . $_POST['description'] . "','" . $_POST['content'] . "')");
    $result = $conn->query("SELECT LAST_INSERT_ID()");
    $ID = $result->fetch_assoc()['LAST_INSERT_ID()'];
    if (@$_POST['tags']) {
        if ($_POST['tags'])
            $tags = @explode(',', $_POST['tags']);
        if (@!count($tags)> 1)
            $conn->query("INSERT INTO tags (Tag, PID)VALUES('$tags',$ID) ");
        else
            foreach ($tags as $value) {
                $conn->query("INSERT INTO tags (Tag, PID)VALUES('$value',$ID) ");
            }
    }
    $arr = ["isSucceed" => "true", "PID" => $ID];
    Response::json(200, "API successfully called", $arr);
    exit(0);
}

function check_login($conn): bool
{
    if (@!$_COOKIE['TokenID'] && !@$_COOKIE['Token']) {
        return false;
    }
    $result = $conn->query("SELECT UID FROM user WHERE Token='" . $_COOKIE['Token'] . "' AND TokenID='" . $_COOKIE['TokenID'] . "'");
    if ($result->num_rows == 0) {
        return false;
    }
    return true;
}