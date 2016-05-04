<?php

// Session Start
session_start();

//$HTTP_HOST = $_SERVER['HTTP_HOST'];

// Set Variables
$SiteTitle = "MandolinEnsembleTILOPI";

// Read PHP files
require_once('php/db.php');
require_once('php/html-page.php');

// Get Mode
$Mode = isset($_GET['mode']) ? $_GET['mode'] : '';
$Option = isset($_GET['option']) ? $_GET['option'] : '';

// Signin-User or Guest
$IsSignin = false; $SigninUserName = '';
if (isset($_SESSION['signin_userid'])) {
    $IsSignin = true;
    $SigninUserID = $_SESSION['signin_userid'];
    $SigninName = $_SESSION['signin_name'];
}

// Error message
$error = array();

// When access to users pages, jump to signin page
if (!$IsSignin) {
    $OnlyUsersPagesList = array('schedule');
    foreach ($OnlyUsersPagesList as $only_page) {
        if ($Mode == $only_page) {
            $Mode = 'top';
        }
    }
}

// Switch by Mode
switch ($Mode) {
    // Sign-in
    case 'signin':
        $PageTitle = "サインイン画面";
        $Module = 'php/signin.php';
        break;

    // Sign-out
    case 'signout':
        $PageTitle = "サインアウト中";
        $Module = 'php/signout.php';
        break;

    // Schedule
    case 'schedule':
        $PageTitle = "練習日程一覧";
        $Module = 'php/schedule.php';
        break;

    // Top
    case '':
        $PageTitle = "サインイン画面";
        $Module = 'php/signin.php';
        break;
  
    // NotFound -> redirect to top
    default:
        header('Location: /');
        exit();
        break;
}

// Connect to database
$MyDB = new MySQLDatabase();

// HTML document
$MyPage = new HTMLPage();

$MyPage->StartHTMLDoc();

if ($PageTitle == '') {
    $MyPage->SetTitle($SiteTitle);
}
else {
    $MyPage->SetTitle($PageTitle . ' - ' . $SiteTitle);
}

$MyPage->WriteHeader($_SERVER['HTTP_HOST']);

$MyPage->StartHTMLBody();

// Signin user or Guest
if ($IsSignin) {
    $MyPage->Signin($SigninName);
}

$MyPage->WriteTopMenu();
$MyPage->StartHTMLMain();

require_once($Module);

$MyPage->EndHTMLMain();

$MyPage->WriteHTMLSub();
$MyPage->WriteFooter();

$MyPage->EndHTMLBody();
$MyPage->EndHTMLDoc();

?>
