<?php

class ScheduleView
{
    // Variables
    private $PageNo = 1;
    private $EditTargetID = 0;
    private $ChangeBtnExplain = "&nbsp;変更する";
    private $DeleteBtnExplain = "&nbsp;削除する";
    private $EditBtnValues = array('add'=>"Add", 'change'=>"Change", 'delete'=>"Delete");
    private $EditModeExplain = array('add'=>"追加", 'change'=>"変更", 'delete'=>"削除");
    private $EditBtnClasses = array('add'=>"success", 'change'=>"warning", 'delete'=>"danger");

    // Methods
    public function SetPageNo($page_no) {
        $this->PageNo = $page_no;
    }

    public function SetEditTargetID($target_id) {
        $this->EditTargetID = $target_id;
    }
    
    public function StartEventTable($height_mode=0, $add_btn=false) {
        $HeightModeStatus = '';
        if ($height_mode == 1) {
            $HeightModeStatus = ' table-condensed';
        }
?>
    <table class="table table-striped table-borderd table-hover <?php print $HeightModeStatus; ?>">
      <thead>
        <tr><th>日時</th><th>場所</th><th>備考</th><th><?php if ($add_btn) print '<a href="/schedule/'.$this->PageNo.'/add"><i class="glyphicon glyphicon-file"></i></a>' ?></th></tr>
      </thead>
      <tbody>
<?php
    }

    public function WriteEvent($one_row, $change_btn=false, $delete_btn=false, $explain_on=false, $target_id=-null) {
        if ($target_id == null) {
            $target_id = $this->EditTargetID;
        }
?>
        <tr>
          <td><?php print htmlspecialchars($one_row['date']); ?></td>
          <td><?php print htmlspecialchars($one_row['place']); ?></td>
          <td><?php print htmlspecialchars($one_row['remarks']); ?></td>
          <td><?php
        // Display ChangeButton
        if ($change_btn) {
            print '<a href="schedule/'.$this->PageNo.'/change?target_id='.$target_id.'"><i class="glyphicon glyphicon-pencil"></i>'.(($explain_on)?$this->ChangeBtnExplain:'').'</a>';
            // Display space between 2 buttons
            if ($delete_btn) {
                print '&nbsp;&nbsp;&nbsp;';
            }
        }
        // Display DeleteButton
        if ($delete_btn) {
            print '<a href="schedule/'.$this->PageNo.'/delete?target_id='.$target_id.'"><i class="glyphicon glyphicon-trash"></i>'.(($explain_on)?$this->DeleteBtnExplain:'').'</a>';
        }
        ?></td>
        </tr>
<?php
    }

    public function EndEventTable() {
?>
      </tbody>
    </table>
<?php
    }

    public function MakeEventForm($mode='add',
        $one_row=array('date'=>'', 'place'=>'', 'remarks'=>''), $input_error=false) {
?>
    <form class="form-horizontal" style="margin-bottom:15px;" method="post" action="/schedule/<?php print $this->PageNo; ?>/<?php print $mode; if ($mode == 'change') print '?target_id='.$this->EditTargetID; ?>">
      <div class="form-group">
        <input type="hidden" name="<?php print $mode; ?>_submit" value="あ">
        <label class="control-label col-sm-2" for="<?php print $mode; ?>_date">Date</label>
		<div class="col-sm-6">
		  <input type="text" id="<?php print $mode; ?>_date" name="<?php print $mode; ?>_date" class="form-control" placeholder="Date" value="<?php print $one_row['date']; ?>">
          </div>
        </div>
	    <div class="form-group">
		  <label class="control-label col-sm-2" for="<?php print $mode; ?>_place">Place</label>
		  <div class="col-sm-6">
		    <input type="text" id="<?php print $mode; ?>_place" name="<?php print $mode; ?>_place" class="form-control" placeholder="Place" value="<?php print $one_row['place']; ?>">
		  </div>
	    </div>
	    <div class="form-group">
		  <label class="control-label col-sm-2" for="<?php print $mode; ?>_remarks">Remarks</label>
		  <div class="col-sm-6">
		    <input type="text" id="<?php print $mode; ?>_remarks" name="<?php print $mode; ?>_remarks" class="form-control" placeholder="Remarks" value="<?php print $one_row['remarks']; ?>">
		  </div>
	    </div>
<?php
        if ($input_error) {
?>
        <div class="form-group has-error">
          <div class="col-sm-offset-2 col-sm-6">
            <span class="help-block">日時は空欄にできません．</span>
          </div>
        </div>
<?php
        }
?>
	    <div class="form-group">
		  <div class="col-sm-offset-2 col-sm-6">
		    <input type="submit" value="Submit" class="btn btn-info btn-lg">
		  </div>
	    </div>
	  </form>

<?php
    }

    public function MakeConfirmForm($mode='delete',
        $one_row=array('date'=>'', 'place'=>'', 'remarks'=>'')) {
?>
      <form class="form-horizontal" style="margin-bottom:15px;" method="post" action="/schedule/<?php print $this->PageNo; ?>/<?php print $mode; ?>?target_id=<?php print $this->EditTargetID; ?>">
        <div class="form-group">
          <div class="col-xs-6 col-sm-offset-3 col-sm-3 text-center">
            <input type="hidden" name="<?php print $mode; ?>_confirm" value="あ">
            <input type="hidden" name="<?php print $mode; ?>_date" value="<?php print $one_row['date']; ?>">
            <input type="hidden" name="<?php print $mode; ?>_place" value="<?php print $one_row['place']; ?>">
            <input type="hidden" name="<?php print $mode; ?>_remarks" value="<?php print $one_row['remarks']; ?>">
            <input type="submit" value="<?php print $this->EditBtnValues[$mode]; ?>" class="btn btn-lg btn-<?php print $this->EditBtnClasses[$mode]; ?>">
          </div>
          <div class="col-xs-6 col-sm-3 text-center">
            <input type="button" class="btn btn-lg btn-default" onClick="location.href='/schedule/<?php print $this->PageNo; ?>/'" value="Cancel">
          </div>
        </div>
      </form>
<?php
    }

}
?>
