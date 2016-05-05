<?php

class HTMLPage
{
    // Variables
    private $PageTitle = '';
    private $IsSignin = false;
    private $SigninName = '';

    // Methods
    public function SetTitle($title) {
        $this->PageTitle = htmlspecialchars($title);
    }

    public function Signin($name) {
        $this->IsSignin = true;
        $this->SigninName = $name;
    }

    public function StartHTMLDoc() {
?>
<!DOCTYPE html>
<html lang="ja">
<?php
    }
    
    public function WriteHeader($host) {
?>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php print $this->PageTitle; ?></title>
    <base href="http://<?php print $host; ?>/">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap.offcanvas.min.css" rel="stylesheet">
  </head>
<?php
    }

    public function StartHTMLBody() {
?>
  <body class="body-offcanvas">
<?php
    }
    
    public function WriteTopMenu() {
?>
    <nav class="navbar navbar-default" role="navigation">
      <div class="container">
	    <div class="navbar-header">
          <p class="pull-right">
	        <button type="button" class="navbar-toggle offcanvas-toggle" data-toggle="offcanvas" data-target="#sidebar">
	          <span class="icon-bar"></span>
	          <span class="icon-bar"></span>
	          <span class="icon-bar"></span>
	        </button>
          </p>
	      <a class="navbar-brand">TILOPI</a>
	    </div>

        <div class="navbar-offcanvas navbar-offcanvas-touch" id="sidebar">
          <ul class="nav navbar-nav">
            <li><a>About</a></li>
            <li><a>Concerts</a></li>
            <li><a href="schedule/">Schedule</a></li>
            <li><a>E-Mail</a></li>
          </ul>
<?php
        if ($this->IsSignin) {
?>
          <ul class="nav navbar-nav navbar-right">
            <li><a><i class="glyphicon glyphicon-user"></i>&nbsp;<strong><?php print $this->SigninName; ?></strong></a></li>
            <li>&nbsp;&nbsp;&nbsp;<button type="button" onclick="location.href='signout/'" class="btn btn-default navbar-btn">Sign-out</button></li>
          </ul>
<?php
        }
        else {
?>
          <div class="nav navbar-nav navbar-right">
            &nbsp;&nbsp;&nbsp;<button type="button" onclick="location.href='signin/'" class="btn btn-default navbar-btn"><i class="glyphicon glyphicon-user"></i>&nbsp;Sign-in</button>
          </div>
	      
<?php
        }
?>
        </div>
      </div>
    </nav>
<?php
    }

    public function StartHTMLMain() {
?>
    <div class="container">
      <div class="row"><!--sub-row-->
	    <div class="col-sm-8">
<?php
    }

    public function EndHTMLMain(){
?>
        </div>
<?php
    }

    public function WriteHTMLSub() {
?>
        <div class="col-sm-4 hidden-xs">

          <div class="panel panel-default">
	        <div class="panel-heading">Mandolin Ensemble TILOPI</div>
	        <div class="panel-body">
              九州大学マンドリンクラブのx期生で構成されたマンドリン演奏団体です．(<a href="">詳細へ</a>)
	        </div>
	        <div class="panel-heading">最近の更新</div>
            <div class="panel-body" style="margin:-10px 0">
	          <ul class="nav nav-pills nav-stacked">
                <li><a>5.3(火) 練習日程更新</a></li>
                <li><a>5.1(日) 練習風景アップ</a></li>
                <li><a>4.20(水) 選曲会議議事録アップ</a></li>
                <li><a>4.7(木) サイトオープン</a></li>
              </ul>
            </div>
	      </div>
        </div>
      </div>
    </div>
      </div>
              </div>
      <!--VisibleContents-->
<?php
    }

    public function WriteFooter() {
?>
    <div id="footer" style="background:#999999;">
      <div class="container text-center">
        <small style="color:#fff;"><em>Copyright (C) 2016-2017 Mandolin Ensemble TILOPI. All Rights Reserved.</em></small>
      </div>
    </div>
<?php
    }

    public function EndHTMLBody() {
?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.offcanvas.js"></script>
  </body>
<?php
    }

    public function EndHTMLDoc() {
?>
</html>
<?php
    }

}
?>
