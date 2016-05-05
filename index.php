<?php

// Session Start
session_start();

// Set Variables
$SiteTitle = "MandolinEnsembleTILOPI";

// Read PHP files
require_once('php/db.php');
require_once('php/html-page.php');

// Get Mode or other parameters
$URIParams = explode('/', $_SERVER['REQUEST_URI']);

// Remove get method message, 'edit?id=29' -> 'edit'
$NumURIParams = count($URIParams);
if (strpos($URIParams[$NumURIParams - 1], '?')) {
    $URIParams[$NumURIParams - 1] = strstr($URIParams[$NumURIParams - 1], '?', true);
}

// Get ViewMode
$ViewMode = $URIParams[1];

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
    $OnlyUsersPagesList = array();
    foreach ($OnlyUsersPagesList as $only_page) {
        if ($ViewMode == $only_page) {
            $ViewMode = 'top';
        }
    }
}

// Switch by Mode
switch ($ViewMode) {
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
        $PageTitle = "練習日程一覧";
        $Module = 'php/schedule.php';
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
