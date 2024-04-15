<?php
session_start();

include '../common/config.php';
include '../common/define.php';
include '../common/utility.php';

$ss_usertype = $_SESSION[DEF_SESSION_USERTYPE] ?? '';
$ss_workcode = $_SESSION[DEF_SESSION_workcode] ?? '';

if($ss_usertype!=DEF_LOGIN_ADMIN) {
    header('Location: login_error.php');
    exit;
}

//======= 以上為權限控管檢查 ==========================


$page = $_GET['page'] ??  1;   // 目前的頁碼
$nump = $_GET['nump'] ?? 10;   // 每頁的筆數

// 網頁連結
$lnk_list = 'list_page.php?page=' . $page . '&nump=' . $nump;

$html = <<< HEREDOC
<h2>新增資料區</h2>
<button onclick="location.href='{$lnk_list}';" class="btn btn-primary">返回列表</button>
<form action="add_save.php" method="post">
    <p>代碼：<input type="text" name="workcode"></p>
    <p>名稱：<input type="text" name="workname"></p>
    <p>簡介：<input type="text" name="intro"></p>
    <p>說明：<input type="text" name="descr"></p>
    <p>日期：<input type="text" name="pub_date"></p>
    <p>照片：<input type="text" name="picture"></p>
    <p>標籤：<input type="text" name="tags"></p>
    <p>分類：<input type="text" name="category"></p>
    <p>評分：<input type="text" name="score"></p>
    <p>發佈：<input type="text" name="is_open"></p>
    <p>備註：<input type="text" name="remark"></p>
    <input type="hidden" name="page" value="{$page}">
    <input type="hidden" name="nump" value="{$nump}">
    <input type="submit" value="新增">
</form>
HEREDOC;

include 'pagemake.php';
pagemake($html);
?>