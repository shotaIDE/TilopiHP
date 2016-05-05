<?php

// Define variable
$NumItems_Page = 10;
$MaxNumPageButtons = 10;
$EditModeID = array('add'=>1, 'change'=>2, 'delete'=>3);
$EditModeExplain = array('add'=>"追加", 'change'=>"変更", 'delete'=>"削除");

require_once('schedule-view.php');

$MySchedule = new ScheduleView();

// Get page no.
//$PageNo = isset($_POST['page']) ? $_POST['page'] : 1;
if ((count($URIParams) >= 3) && ($URIParams[2] != '')) {
    // 'schedule/dd'
    $PageNo = intval($URIParams[2]);
}
else {
    // 'schedule/' or 'schedule'
    $PageNo = 1;
}

// Sign-in -> display edit buttons
$EditDisplay = '';
if ($IsSignin) {
    $EditDisplay = "";
}

// Is Edit/Delete Mode ?
$EditMode = 0;    // 0: NotEdit
if (count($URIParams) == 4) {
    // Add
    if ($URIParams[3] == 'add') {
        // 'shcedule/dd/add'
        $EditMode = $EditModeID['add'];
    }
    // Change or Delete
    else if (isset($_GET['target_id'])) {
        if ($URIParams[3] == 'change') {
            // 'schedule/dd/change'
            $EditMode = $EditModeID['change'];
            $TargetID = intval($_GET['target_id']);
        }
        else if ($URIParams[3] == 'delete') {
            // 'schedule/dd/delete'
            $EditMode = $EditModeID['delete'];
            $TargetID = intval($_GET['target_id']);
        }
    }
}

// Is ConformMode?
if ($EditMode == $EditModeID['change']) {
    $ChangeConfirmError = false;
    if (isset($_POST['change_confirm'])) {
        // error
        if ($_POST['change_date'] == '') {
            $ChangeConfirmError = true;
        }
        // NO error
        else {
            $ChangeData_Date = $MyDB->Escape($_POST['change_date']);
            $ChangeData_Place = $MyDB->Escape($_POST['change_place']);
            $ChangeData_Remarks = $MyDB->Escape($_POST['change_remarks']);
            $query = "UPDATE schedule SET date='$ChangeData_Date', place='$ChangeData_Place', remarks='$ChangeData_Remarks' WHERE id=$TargetID";
            //echo $query;
            $result = $MyDB->Query($query);
            // Succeed
            if ($result) {
                header('Location: /schedule/'.$PageNo.'/');
                exit();
            }
            else {
                echo "失敗しました．";
                exit();
            }
        }
    }
}
else if (($EditMode == $EditModeID['delete']) && (isset($_POST['delete_confirm']))) {
    $query = "DELETE FROM schedule WHERE id = " . $TargetID;
    $result = $MyDB->Query($query);
    // Succeed
    if ($result) {
        header('Location: /schedule/'.$PageNo.'/');
        exit();
    }
    else {
        echo "失敗しました．";
        exit();
    }
}

// Load target data
if (($EditMode == $EditModeID['change']) || ($EditMode == $EditModeID['delete'])) {
    $query = "SELECT * FROM schedule WHERE id = " . $TargetID;
    $result = $MyDB->Query($query);
    $row = $result->fetch_assoc();

    // Delete Mode
    if ($EditMode == $EditModeID['delete']) {
?>
    （※まだ処理は完了していません）<br />
    以下のデータを<?php print $EditExplain[$EditMode]; ?>してもよろしいですか？
<?php
    }
?>
    <table class="table table-striped table-borderd table-condensed">
      <thead>
        <tr><th>日時</th><th>場所</th><th>備考</th><th></th></tr>
      </thead>
      <tbody>
        <tr>
          <td><?php print htmlspecialchars($row['date']); ?></td>
          <td><?php print htmlspecialchars($row['place']); ?></td>
          <td><?php print htmlspecialchars($row['remarks']); ?></td>
          <td><?php
    // Display ChangeButton on DeleteMode
    if ($EditMode == $EditModeID['delete']) {
        print '<a href="schedule/'.$PageNo.'/change?target_id='.$TargetID.'"><i class="glyphicon glyphicon-pencil"></i>'.$EditExplain[$EditModeID['change']].'する</a>';
    }
        ?></td>
        </tr>
      </tbody>
    </table>
<?php
    // Display Change form on ChangeMode
    if ($EditMode == $EditModeID['change']) {
?>
    <form class="form-horizontal" style="margin-bottom:15px;" method="post" action="/schedule/<?php print $PageNo; ?>/change?target_id=<?php print $TargetID; ?>">
      <div class="form-group">
        <input type="hidden" name="change_confirm" value="change">
        <label class="control-label col-sm-2" for="change_date">Date</label>
		<div class="col-sm-6">
		  <input type="text" id="change_date" name="change_date" class="form-control" placeholder="Date" value="<?php print $row['date']; ?>">
          </div>
        </div>
	    <div class="form-group">
		  <label class="control-label col-sm-2" for="change_place">Place</label>
		  <div class="col-sm-6">
		    <input type="text" id="change_place" name="change_place" class="form-control" placeholder="Place" value="<?php print $row['place']; ?>">
		  </div>
	    </div>
	    <div class="form-group">
		  <label class="control-label col-sm-2" for="change_remarks">Remarks</label>
		  <div class="col-sm-6">
		    <input type="text" id="change_remarks" name="change_remarks" class="form-control" placeholder="Remarks" value="<?php print $row['remarks']; ?>">
		  </div>
	    </div>
<?php
        if ($ChangeConfirmError) {
?>
        <div class="form-group has-error">
          <div class="col-sm-offset-2 col-sm-6">
            <span class="help-block">日時は空欄にはできません．</span>
          </div>
        </div>
<?php
        }
?>
	    <div class="form-group">
		  <div class="col-sm-offset-2 col-sm-6">
		    <input type="submit" value="Submit" class="btn btn-primary">
		  </div>
	    </div>
	  </form>
<?php
    }
    // Display Delete button on DeleteMode
    else if ($EditMode == $EditModeID['delete']) {
?>
      <div class="row">
        <div class="col-xs-6 col-sm-offset-3 col-sm-3 text-center">
          <form class="form-horizontal" style="margin-bottom:15px;" method="post" action="/schedule/<?php print $PageNo; ?>/delete?target_id=<?php print $TargetID; ?>">
            <input type="hidden" name="delete_confirm" value="delete">
            <div class="form-group">
              <input type="submit" value="Delete" class="btn btn-lg btn-danger">
            </div>
          </form>
        </div>
        <div class="col-xs-6 col-sm-3 text-center">
          <input type="button" class="btn btn-lg btn-default" onClick="location.href='/schedule/<?php print $PageNo; ?>/'" value="Cancel">
        </div>
      </div>
    
<?php
    }
}

// Load schedule from database
$query = "SELECT COUNT(*) AS cnt FROM schedule";
$result = $MyDB->Query($query);
//$result = $mysqli->query($query);
$row = $result->fetch_assoc();

if (!$result) {
    array_push($error, "データベースに接続できませんでした．");
}
else {
    // Get #rows
    $NumItems = $row['cnt'];
    $NumPages = ceil($NumItems / $NumItems_Page);

    //echo $NumItems;

    // Invalid PageNo -> redirect to page 1
    if (($PageNo <= 0) || ($PageNo > $NumPages)) {
        header('Location: /schedule/');
        exit();
    }


    // Compute page buttons' layout
    $PageStart = 1;
    if ($NumPages > $MaxNumPageButtons) {
        // Middle
        if (($PageNo >= ((int)(($MaxNumPageButtons - 1) / 2) + 2))
            && ($PageNo <= ($NumPages - ((int)(($MaxNumPageButtons - 1) / 2) + 2)))) {
            $PageStart = $PageNo - ((int)(($MaxNumPageButtons - 1) / 2) + 2);
        }
        // End
        else if ($PageNo > ($NumPages - ((int)(($MaxNumPageButtons - 1) / 2) + 2))) {
            $PageStart = $NumPages - ($PageNo <= ($NumPages - ((int)(($MaxNumPageButtons - 1) / 2) + 2)));
        }
    }

    $PageEnd = $PageStart + $NumPages - 1;

    $query = sprintf("SELECT * FROM schedule ORDER BY id DESC LIMIT %d, %d", ($PageNo - 1) * $NumItems_Page, $NumItems_Page);
    //echo $query;
    $result = $MyDB->Query($query);

?>
      <table class="table table-striped table-borderd table-hover">
        <thead>
          <tr><th>日時</th><th>場所</th><th>備考</th><th><?php print $EditDisplay; ?></th></tr>
        </thead>
        <tbody>
<?php

    if (!$result) {
        array_push($error, "データを取得できませんでした．");
    }
    else {
        while ($row = $result->fetch_assoc()) {
?>
          <tr>
            <td><?php print htmlspecialchars($row['date']); ?></td>
            <td><?php print htmlspecialchars($row['place']); ?></td>
            <td><?php print htmlspecialchars($row['remarks']); ?></td>
            <td><?php
            $EditLink = '';
            if ($IsSignin) {
                $EditLink = sprintf(
                    '<a href="schedule/%d/change?target_id=%d"><i class="glyphicon glyphicon-pencil"></i></a>&nbsp;&nbsp;&nbsp;' .
                        '<a href="schedule/%d/delete?target_id=%d"><i class="glyphicon glyphicon-trash"></i></a>', $PageNo, $row['id'], $PageNo, $row['id']);
            }
            print $EditLink;
          ?></td>
          </tr>
<?php
        }
    }
}
?>
	    </tbody>
	  </table>

<?php
// Compute paging buttons' layout
$ToTopStatus = '';
$ToTopLink = ' href="shcedule/1"';
$ToPreviousStatus = '';
$ToPreviousLink = ' href="schedule/' . ($PageNo - 1) . '"';
if ($PageNo <= 1) {
    $ToTopStatus = ' class="disabled"';
    $ToTopLink = '';
    $ToPreviousStatus = ' class="disabled"';
    $ToPreviousLink = '';
}

$ToEndStatus = '';
$ToEndLink = ' href="schedule/' . $NumPages . '"';
$ToNextStatus = '';
$ToNextLink = ' href="schedule/' . ($PageNo + 1) . '"';
if ($PageNo >= $NumPages) {
    $ToEndStatus = ' class="disabled"';
    $ToEndLink = '';
    $ToNextStatus = ' class="disabled"';
    $ToNextLink = '';
}

?>

	  <ul class="pagination">
        <li<?php print $ToTopStatus; ?>><a<?php print $ToTopLink; ?>>&laquo;&laquo;</a></li>
        <li<?php print $ToPreviousStatus; ?>><a<?php print $ToPreviousLink; ?>>&laquo;</a></li>
<?php
for ($page = $PageStart; $page <= $PageEnd; ++$page) {
    $ToPageStatus = '';
    $ToPageLink = ' href="schedule/' . $page . '"';
    if ($page == $PageNo) {
        $ToPageStatus = ' class="active"';
        $ToPageLink = '';
    }
?>
        <li<?php print $ToPageStatus; ?>><a<?php print $ToPageLink; ?>><?php print $page; ?></a></li>
<?php
}
?>
        <li<?php print $ToNextStatus; ?>><a<?php print $ToNextLink; ?>>&raquo;</a></li>
        <li<?php print $ToEndStatus; ?>><a<?php print $ToEndLink; ?>>&raquo;&raquo</a></li>
	  </ul>
