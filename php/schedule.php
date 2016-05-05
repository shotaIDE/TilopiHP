<?php

// Define variable
$NumItems_Page = 10;
$MaxNumPageButtons = 10;
$EditModeID = array('add'=>1, 'change'=>2, 'delete'=>3);
$EditModeExplain = array('add'=>"追加", 'change'=>"変更", 'delete'=>"削除");

require_once('schedule-view.php');

$MySchedule = new ScheduleView();

// Get page No.
if ((count($URIParams) >= 3) && ($URIParams[2] != '')) {
    // 'schedule/dd'
    $PageNo = intval($URIParams[2]);
}
else {
    // 'schedule/' or 'schedule'
    $PageNo = 1;
}
$MySchedule->SetPageNo($PageNo);

// Sign-in -> display edit buttons
$EditDisplay = '';
if ($IsSignin) {
    $EditDisplay = "";

    // Is Edit Mode ?
    $EditMode = 0;    // 0: NOT Edit mode
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
                $EditTargetID = intval($_GET['target_id']);
            }
            else if ($URIParams[3] == 'delete') {
                // 'schedule/dd/delete'
                $EditMode = $EditModeID['delete'];
                $EditTargetID = intval($_GET['target_id']);
            }
            $MySchedule->SetEditTargetID($EditTargetID);
        }
    }

    // Is Confirm Mode ?
    // Add Mode
    if (($EditMode == $EditModeID['add']) && (isset($_POST['add_confirm']))) {
        $AddData_Date = $MyDB->Escape($_POST['add_date']);
        $AddData_Place = $MyDB->Escape($_POST['add_place']);
        $AddData_Remarks = $MyDB->Escape($_POST['add_remarks']);
        $query = "INSERT INTO schedule (date, place, remarks) VALUES ('$AddData_Date', '$AddData_Place', '$AddData_Remarks')";
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
    // Change Mode
    else if ($EditMode == $EditModeID['change']) {
        $ChangeInputError = false;
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
                $query = "UPDATE schedule SET date='$ChangeData_Date', place='$ChangeData_Place', remarks='$ChangeData_Remarks' WHERE id=$EditTargetID";
                $result = $MyDB->Query($query);
                echo mb_detect_encoding($query);
                //var_dump($result);
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
    // Delete Mode
    else if (($EditMode == $EditModeID['delete']) && (isset($_POST['delete_confirm']))) {
        $query = "DELETE FROM schedule WHERE id=$EditTargetID";
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

// Count items on schedule
$query = "SELECT COUNT(*) AS cnt FROM schedule";
$result = $MyDB->Query($query);
$row = $result->fetch_assoc();

if (!$result) {
    array_push($error, "データベースに接続できませんでした．");
}
else {
    $NumItems = $row['cnt'];
    if ($NumItems == 0) {
        $NumPages = 1;
    }
    else {
        $NumPages = ceil($NumItems / $NumItems_Page);
    }

    // Invalid PageNo -> redirect to page 1
    if (($PageNo <= 0) || ($PageNo > $NumPages)) {
        header('Location: /schedule/');
        exit();
    }
}

// Add Mode
if ($IsSignin) {
    if ($EditMode == $EditModeID['add']) {
        // Add input
        if (!isset($_POST['add_submit'])) {
            $MySchedule->MakeEventForm();
        }
        // Add check
        else {
            $ChangeData = array('date'=>$_POST['add_date'],
                                'place'=>$_POST['add_place'],
                                'remarks'=>$_POST['add_remarks']);
            // Blank check
            if ($ChangeData['date'] == '') {
                $MySchedule->MakeEventForm('add', $ChangeData, true);
            }
            else {
                $MySchedule->StartEventTable(1);
                $MySchedule->WriteEvent($ChangeData);
                $MySchedule->EndEventTable();
                $MySchedule->MakeConfirmForm('add', $ChangeData);
            }
        }
    }
    // Change / Delete Mode
    else if (($EditMode == $EditModeID['change']) || ($EditMode == $EditModeID['delete'])) {
        // Load target data
        $query = "SELECT * FROM schedule WHERE id=$EditTargetID";
        $result = $MyDB->Query($query);
        $row = $result->fetch_assoc();

        // Change Mode
        if ($EditMode == $EditModeID['change']) {
            // Change input
            if (!isset($_POST['change_submit'])) {
                $MySchedule->StartEventTable(1);
                $MySchedule->WriteEvent($row, false, false, true);
                $MySchedule->EndEventTable();
                $MySchedule->MakeEventForm('change', $row);
            }
            // Change check
            else {
                $ChangeData = array('date'=>$_POST['change_date'],
                                    'place'=>$_POST['change_place'],
                                    'remarks'=>$_POST['change_remarks']);
                // Blank check
                if ($ChangeData['date'] == '') {
                    $MySchedule->StartEventTable(1);
                    $MySchedule->WriteEvent($row, false, false, true);
                    $MySchedule->EndEventTable();
                    $MySchedule->MakeEventForm('change', $ChangeData, true);
                }
                else {
                    $MySchedule->StartEventTable(1);
                    $MySchedule->WriteEvent($row, false, false, true);
                    $MySchedule->EndEventTable();
                    $MySchedule->StartEventTable(1);
                    $MySchedule->WriteEvent($ChangeData, false, false, true);
                    $MySchedule->EndEventTable();
                    $MySchedule->MakeConfirmForm('change', $ChangeData);
                }
            }
        }
        // Delete Mode
        else if ($EditMode == $EditModeID['delete']) {
            ?>
            （※まだ処理は完了していません）<br />
                以下のデータを<?php print $EditModeExplain['delete']; ?>してもよろしいですか？
<?php
                                                                            $MySchedule->StartEventTable(1);
            $MySchedule->WriteEvent($row, true, false, true);
            $MySchedule->EndEventTable();
            $MySchedule->MakeConfirmForm('delete');
        }
    }
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
$result = $MyDB->Query($query);

$MySchedule->StartEventTable(0, $IsSignin);

if (!$result) {
    array_push($error, "データを取得できませんでした．");
}
else {
    while ($row = $result->fetch_assoc()) {
        $_row = array('date'=>$row['date'],
                      'place'=>$row['place'],
                      'remarks'=>$row['remarks']);
        if ($IsSignin) {
            $MySchedule->WriteEvent($_row, true, true, false, $row['id']);
        }
        else {
            $MySchedule->WriteEvent($_row);
        }
    }
}
$MySchedule->EndEventTable();

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
