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


// 接收傳入變數
$uid = $_GET['uid'] ?? 0;
$page = $_GET['page'] ??  1;   // 目前的頁碼
$nump = $_GET['nump'] ?? 10;   // 每頁的筆數


// 網頁內容預設
$ihc_content = '';
$ihc_error = '';

// 連接資料庫
$pdo = db_open();

// SQL 語法
$sqlstr = "SELECT * FROM work WHERE uid=?";

$sth = $pdo->prepare($sqlstr);
$sth->bindValue(1, $uid, PDO::PARAM_INT);

// 執行 SQL
try {
    $sth->execute();
    
    if($row = $sth->fetch(PDO::FETCH_ASSOC)) {
        $uid = $row['uid'];
        $workcode = html_encode($row['workcode']);
        $workname = html_encode($row['workname']);
        $intro    = html_encode($row['intro']);
        $descr    = html_encode($row['descr']);
        $pub_date = html_encode($row['pub_date']);
        $picture  = html_encode($row['picture']);
        $tags     = html_encode($row['tags']);
        $category = html_encode($row['category']);
        $score    = html_encode($row['score']);
        $is_open  = html_encode($row['is_open']);
        $remark   = html_encode($row['remark']);

        $data = <<< HEREDOC
        <table class="table">
            <tr><th>代碼</th><td>{$workcode}</td></tr>
            <tr><th>名稱</th><td>{$workname}</td></tr>
            <tr><th>簡介</th><td>{$intro}</td></tr>
            <tr><th>說明</th><td>{$descr}</td></tr>
            <tr><th>日期</th><td>{$pub_date}</td></tr>
            <tr><th>圖片</th><td>{$picture}</td></tr>
            <tr><th>標籤</th><td>{$tags}</td></tr>
            <tr><th>分類</th><td>{$category}</td></tr>
            <tr><th>評分</th><td>{$score}</td></tr>
            <tr><th>發佈</th><td>{$is_open}</td></tr>
            <tr><th>備註</th><td>{$remark}</td></tr>
        </table>
HEREDOC;

        // 網頁連結
        $lnk_prev = 'list_page.php?uid=' . $uid . '&page=' . $page . '&nump=' . $nump;
        $lnk_edit =  'edit.php?uid=' . $uid . '&page=' . $page . '&nump=' . $nump;
        $lnk_delete = 'delete.php?uid=' . $uid . '&page=' . $page . '&nump=' . $nump;;

        // 網頁內容
        $ihc_content = <<< HEREDOC
        <p>
            <button onclick="location.href='{$lnk_prev}';" class="btn btn-info">返回列表</button>
            <button onclick="location.href='{$lnk_edit}';" class="btn btn-warning">修改</button>
            <button onclick="if(confirm('確定要刪除嗎？')) {location.href='{$lnk_delete}';}" class="btn btn-danger">刪除</button>
        </p>
        {$data}
HEREDOC;
    }
    else {
        $ihc_data = '<p class="center">查不到相關記錄！</p>';
    }
}
catch(PDOException $e) {
    $ihc_error = error_message('ERROR_QUERY', $e->getMessage());
}

db_close();


//網頁顯示
$html = <<< HEREDOC
<h2>詳細資料</h2>
{$ihc_content}
{$ihc_error}
HEREDOC;

include 'pagemake.php';
pagemake($html);
?>