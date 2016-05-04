
      <table class="table table-striped table-borderd table-hover">
        <thead>
          <tr><th>日時</th><th>場所</th><th>備考</th></tr>
        </thead>
        <tbody>
<?php
// Define variable
$NumItems_Page = 10;
$MaxNumPageButtons = 10;

// Get page no.
//$PageNo = isset($_POST['page']) ? $_POST['page'] : 1;
$PageNo = intval($Option);
//echo $PageNo;

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

    // Rewrite PageNo
    if ($PageNo == 0) {
        $PageNo = 1;
    }
    else if ($PageNo > $NumPages) {
        $PageNo = 1;
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
