<?php
require_once 'config.php';
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

$conn = new mysqli(servername, username, password, db);

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
    $result=$conn->query("SELECT WebTitle, WebContent FROM web_message");
    $messages_count=$result->num_rows;
    $data += ['web_messages_count' => $messages_count];
    $messages=[];
    while ($row=$result->fetch_assoc()){
        array_push($messages,$row);
    }
    $data += ['web_messages' => $messages];
    Response::json(200, "API Successfully Called", $data);
    exit(0);
}

if ($_POST['function'] == 'login_check') {
    if (check_login($conn)) {
        $arr = ["isSucceed" => "true"];
        Response::json(200, "API successfully called", $arr);
        exit(0);
    } else {
        $arr = ["isSucceed" => "false"];
        Response::json(401, "User Not Login", $arr);
        exit(0);
    }
}

if ($_POST['function'] == "login") {
    $stmt = $conn->prepare("SELECT Password FROM user WHERE UserName=?");
    $stmt->bind_param("s", $_POST['username']);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($password);
    if ($stmt->num_rows() != 1) {
        $arr = ["isSucceed" => false];
        Response::json(401, "Login Message Wrong", $arr);
        exit(0);
    }
    $stmt->fetch();
    if (md5("#*#*4636" . md5($_POST['password']) . "114514*#*#") != $password) {
        $arr = ["isSucceed" => false];
        Response::json(401, "Login Message Wrong", $arr);
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

if ($_POST['function'] == "admin_pic") {
    $result = $conn->query("SELECT PID, PicTitle, PicLink FROM pictures ORDER BY PID");
    $count = $result->num_rows;
    $pics = [];
    while ($row = $result->fetch_assoc()) {
        array_push($pics, $row);
    }
    $data = ['pic' => $pics,'count'=>$count];
    Response::json(200, "API Successfully Called", $data);
    exit(0);
}

if ($_POST['function'] == "add_link") {
    if (!check_login($conn)) {
        $arr = ["isSucceed" => "false", "error_message" => "您未登录"];
        Response::json(401, "User Not Login", $arr);
        exit(0);
    }
    $result = $conn->query("SELECT ID FROM links WHERE LinkTitle='" . $_POST['link_title'] . "'");
    if ($result->num_rows != 0) {
        $arr = ["isSucceed" => "false", "error_message" => "当前标题链接已存在"];
        Response::json(503, "Asset Is Existed", $arr);
        exit(0);
    }
    $stmt=$conn->prepare("INSERT INTO links (LinkTitle,Link)VALUES(?,?)");
    $stmt->bind_param("ss", $_POST['link_title'],$_POST['link']);
    $stmt->execute();
    $arr = ["isSucceed" => "true"];
    Response::json(200, "API successfully called", $arr);
    exit(0);
}

if ($_POST['function'] == "update_link") {
    if (!check_login($conn)) {
        $arr = ["isSucceed" => 'false'];
        Response::json(401, "User Not Login", $arr);
        exit(0);
    }
    $stmt=$conn->prepare("UPDATE links SET LinkTitle=? , Link=? WHERE LinkTitle=?");
    $stmt->bind_param("sss", $_POST['link_title'],$_POST['link'],$_POST['link_title_old']);
    $stmt->execute();
    $arr = ["isSucceed" => 'true'];
    Response::json(200, "API successfully called", $arr);
    exit(0);
}


if ($_POST['function'] == "delete_link") {
    if (!check_login($conn)) {
        $arr = ["isSucceed" => 'false'];
        Response::json(401, "User Not Login", $arr);
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
        Response::json(401, "User Not Login", $arr);
        exit(0);
    }
    $result = $conn->query("SELECT iid FROM indexes WHERE index_name='" . $_POST['index_name'] . "'");
    if ($result->num_rows != 0) {
        $arr = ["isSucceed" => "false", "error_message" => "当前标题链接已存在"];
        Response::json(503, "Assets Is Existed", $arr);
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
        Response::json(401, "User Not Login", $arr);
        exit(0);
    }
    $conn->query("UPDATE indexes SET index_name='" . $_POST['index_title'] . "' WHERE index_name='" . $_POST['index_title_old'] . "'");
    $arr = ["isSucceed" => 'true'];
    Response::json(200, "API successfully called", $arr);
    exit(0);
}

if ($_POST['function'] == "delete_index") {
    if (!check_login($conn)) {
        $arr = ["isSucceed" => 'false','error'=>"用户未登录"];
        Response::json(401, "User Not Login", $arr);
        exit(0);
    }
    $result=$conn->query("SELECT PID FROM pages WHERE index_name='" . $_POST['index_name'] . "'");
    if ($result->num_rows!=0){
        $arr = ["isSucceed" => 'false','error'=>"当前目录不为空，请先删除目录下文章"];
        Response::json(502, "Illegal operation", $arr);
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
        Response::json(401, "User Not Login", $arr);
        exit(0);
    }

    $path_name="content/".md5($_POST['title'].microtime()).".html";
    $file = fopen($path_name, "w+");
    fwrite($file, $_POST['content']);
    fclose($file);

    $conn->query("INSERT INTO pages (title, index_name, description, content)
        VALUES ('" . $_POST['title'] . "','" . $_POST['index_name'] . "','" . $_POST['description'] . "','" . $path_name . "')");
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

if ($_POST['function']=="draw_main_page"){
    $result=$conn->query("SELECT pid, title, index_name, description, latestsubmit FROM pages ORDER BY PID DESC LIMIT 10");
    $count=$result->num_rows;
    $pages=[];
    while ($row=$result->fetch_assoc()){
        $pics=$conn->query("SELECT PicLink FROM pictures ORDER BY RAND() LIMIT 1");
        $pic=$pics->fetch_assoc();
        $row+=['pic'=>$pic['PicLink']];
        array_push($pages,$row);
    }
    $data=['count'=>$count,'pages'=>$pages];
    Response::json(200, "API successfully called", $data);
    exit(0);
}

if ($_POST['function']=="draw_page"){
    $result=$conn->query("SELECT pid, title, content ,index_name, description, latestsubmit FROM pages WHERE PID=".$_POST['PID']);
    if ($result->num_rows!=1){
        $data=['isSucceed'=>'false'];
        Response::json(404, "Page Not Found", $data);
        exit(0);
    }
    $row=$result->fetch_assoc();

    $file = fopen( $row['content'], "r");
    $row['content']=fread($file,filesize($row['content']));
    fclose($file);

    $result=$conn->query("SELECT Tag FROM tags WHERE PID=".$_POST['PID']);
    $tags_count=$result->num_rows;
    $tags=[];
    while ($tag=$result->fetch_assoc()){
        array_push($tags,$tag);
    }
    $row+=['tags'=>$tags,'tags_count'=>$tags_count];
    Response::json(200, "API successfully called", $row);
    exit(0);
}

if ($_POST['function']=="delete_page"){
    if (!check_login($conn)) {
        $arr = ["isSucceed" => 'false'];
        Response::json(401, "User Not Login", $arr);
        exit(0);
    }
    $conn->query("DELETE FROM pages WHERE PID='" . $_POST['pid'] . "'");
    $conn->query("DELETE FROM tags WHERE PID='" . $_POST['pid'] . "'");
    $arr = ["isSucceed" => 'true'];
    Response::json(200, "API successfully called", $arr);
    exit(0);
}

if ($_POST['function'] == "add_pic") {
    if (!check_login($conn)) {
        $arr = ["isSucceed" => "false", "error_message" => "您未登录"];
        Response::json(401, "User Not Login", $arr);
        exit(0);
    }
    $conn->query("INSERT INTO pictures (PicTitle, PicLink)VALUES('" . $_POST['pic_title'] . "','" . $_POST['pic'] . "')");
    $arr = ["isSucceed" => "true"];
    Response::json(200, "API successfully called", $arr);
    exit(0);

}

if ($_POST['function'] == "delete_pic") {
    if (!check_login($conn)) {
        $arr = ["isSucceed" => 'false'];
        Response::json(401, "User Not Login", $arr);
        exit(0);
    }
    $conn->query("DELETE FROM pictures WHERE PID='" . $_POST['pid'] . "'");
    $arr = ["isSucceed" => 'true'];
    Response::json(200, "API successfully called", $arr);
    exit(0);
}

if ($_POST['function'] == "update_page") {
    if (!check_login($conn)) {
        $arr = ["isSucceed" => "false", "error_message" => "您未登录"];
        Response::json(401, "User Not Login", $arr);
        exit(0);
    }

    $result=$conn->query("SELECT Content FROM pages WHERE PID=".$_POST['pid']);
    $row=$result->fetch_assoc();

    $path_name=$row['Content'];
    $file = fopen($path_name, "w+");
    fwrite($file, $_POST['content']);
    fclose($file);

    $conn->query("UPDATE pages SET Title='" . $_POST['title'] .
        "',index_name='" . $_POST['index_name'] .
        "',Description='" . $_POST['description'] .
        "' WHERE PID=".$_POST['pid']);
    $conn->query("DELETE FROM tags WHERE PID=".$_POST['pid']);
    $ID=$_POST['pid'];
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

if ($_POST['function']=="draw_index_page"){
    $result=$conn->query("SELECT pid, title, index_name, description, latestsubmit FROM pages WHERE index_name='".$_POST['index']."' ORDER BY PID DESC LIMIT 10");
    $count=$result->num_rows;
    $pages=[];
    while ($row=$result->fetch_assoc()){
        $pics=$conn->query("SELECT PicLink FROM pictures ORDER BY RAND() LIMIT 1");
        $pic=$pics->fetch_assoc();
        $row+=['pic'=>$pic['PicLink']];
        array_push($pages,$row);
    }
    $data=['count'=>$count,'pages'=>$pages];
    Response::json(200, "API successfully called", $data);
    exit(0);
}

if ($_POST['function']=='search'){
    $result=$conn->query("SELECT pid, title, index_name, description, latestsubmit FROM pages WHERE PID LIKE '".$_POST['key']."' OR Content LIKE '".$_POST['key']."' OR title  LIKE '".$_POST['key']."' OR description  LIKE '".$_POST['key']."' ORDER BY PID DESC LIMIT 10");
    $count=$result->num_rows;
    $pages=[];
    while ($row=$result->fetch_assoc()){
        $pics=$conn->query("SELECT PicLink FROM pictures ORDER BY RAND() LIMIT 1");
        $pic=$pics->fetch_assoc();
        $row+=['pic'=>$pic['PicLink']];
        array_push($pages,$row);
    }
    $data=['count'=>$count,'pages'=>$pages];
    Response::json(200, "API successfully called", $data);
    exit(0);
}

if ($_POST['function']=='update_password'){
    if (!check_login($conn)) {
        $arr = ["isSucceed" => "false", "error_message" => "您未登录"];
        Response::json(401, "User Not Login", $arr);
        exit(0);
    }
    $result=$conn->query("SELECT Password FROM user WHERE Token='" . $_COOKIE['Token'] . "' AND TokenID='" . $_COOKIE['TokenID'] . "'");
    if ($result->num_rows!=1){
        $arr = ["isSucceed" => "false", "error_message" => "您未登录"];
        Response::json(401, "User Not Login", $arr);
        exit(0);
    }
    $row=$result->fetch_assoc();
    if ($row['Password']!=md5("#*#*4636" . md5($_POST['password_old']) . "114514*#*#")){
        $arr = ["isSucceed" => "false", "error_message" => "旧密码错误"];
        Response::json(403, "Wrong Password", $arr);
        exit(0);
    }
    $conn->query("UPDATE user SET Password='".md5("#*#*4636" . md5($_POST['password']) . "114514*#*#")."' WHERE Token='" . $_COOKIE['Token'] . "' AND TokenID='" . $_COOKIE['TokenID'] . "'");
    $arr = ["isSucceed" => "true"];
    Response::json(200, "API successfully called", $arr);
    exit(0);
}

if ($_POST['function']=='update_username'){
    if (!check_login($conn)) {
        $arr = ["isSucceed" => "false", "error_message" => "您未登录"];
        Response::json(401, "User Not Login", $arr);
        exit(0);
    }
    $conn->query("UPDATE user SET UserName='".$_POST['username']."' WHERE Token='" . $_COOKIE['Token'] . "' AND TokenID='" . $_COOKIE['TokenID'] . "'");
    $arr = ["isSucceed" => "true"];
    Response::json(200, "API successfully called", $arr);
    exit(0);
}

if ($_POST['function']==='update_title'){
    $stmt=$conn->prepare("UPDATE web_message SET WebContent=? WHERE WebTitle='title'");
    $stmt->bind_param("s", $_POST['title']);
    $stmt->execute();
    $arr = ["isSucceed" => "true"];
    Response::json(200, "API successfully called", $arr);
    exit(0);
}

if ($_POST['function']==='update_footer'){
    $stmt=$conn->prepare("UPDATE web_message SET WebContent=? WHERE WebTitle='footer'");
    $stmt->bind_param("s", $_POST['title']);
    $stmt->execute();
    $arr = ["isSucceed" => "true"];
    Response::json(200, "API successfully called", $arr);
    exit(0);
}

if ($_POST['function']==='tags_cloud'){
    $result=$conn->query("SELECT Tag FROM tags GROUP BY Tag");
    $count=$result->num_rows;
    $tags=[];
    while($row=$result->fetch_assoc()){
        array_push($tags,$row);
    }
    $arr = ['count'=>$count,'tags'=>$tags];
    Response::json(200, "API successfully called", $arr);
    exit(0);
}

if ($_POST['function']=="draw_tag_page"){
    $result=$conn->query("SELECT pages.pid, pages.title, pages.index_name, pages.description, pages.latestsubmit FROM pages,tags WHERE tags.Tag='".$_POST['tag']."' AND pages.PID=tags.PID ORDER BY PID DESC LIMIT 10");
    $count=$result->num_rows;
    $pages=[];
    while ($row=$result->fetch_assoc()){
        $pics=$conn->query("SELECT PicLink FROM pictures ORDER BY RAND() LIMIT 1");
        $pic=$pics->fetch_assoc();
        $row+=['pic'=>$pic['PicLink']];
        array_push($pages,$row);
    }
    $data=['count'=>$count,'pages'=>$pages];
    Response::json(200, "API successfully called", $data);
    exit(0);
}

if ($_POST['function']=="draw_archive_page"){
    $result=$conn->query("SELECT pid, title, latestsubmit FROM pages ORDER BY LatestSubmit DESC");
    $count=$result->num_rows;
    $pages=[];
    while ($row=$result->fetch_assoc()){
        array_push($pages,$row);
    }
    $data=['count'=>$count,'pages'=>$pages];
    Response::json(200, "API successfully called", $data);
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