<?php 
session_start();
require 'over.php';
$apid = $_SESSION['apid'];
$rlid = get_user_role($link, $apid);
$uidx = get_userid($link, $apid);

if(isset($_POST['action']) && $_POST['action'] == 'dellesson'){
	if($rlid != 3){
		echo "You do not have clearance to perform this action";
		exit();	
	}
	$slcid = clean($link, $_POST['lessonid']);
	$sdq = mysqli_query($link, "SELECT mid,mtype FROM lesson_structure WHERE slcid='$slcid' ");
	while ($sqr = mysqli_fetch_array($sdq, MYSQLI_ASSOC)) {
		$midx = $sqr['mid'];
		$mtypex = $sqr['mtype'];
		if($mtypex == 'd'){
			$delmq = mysqli_query($link, "DELETE FROM document WHERE dcid='$midx' ");
			if($delmq){
				$delq = mysqli_query($link, "DELETE FROM lesson_structure WHERE slcid='$slcid' AND mid='$midx' AND mtype='$mtypex' ");
			}
		}
		else if($mtypex == 'a'){
			$arq = mysqli_query($link, "SELECT `path` FROM videonaudio WHERE vaid='$midx' ");
			$ar = mysqli_fetch_assoc($arq);
			$filez = $ar['path'];

			if(unlink('../assets/materials/'.$filez)){
				$delmq = mysqli_query($link, "DELETE FROM videonaudio WHERE vaid='$midx' ");
				if($delmq){
					$delq = mysqli_query($link, "DELETE FROM lesson_structure WHERE slcid='$slcid' AND mid='$midx' AND mtype='$mtypex' ");
				}
			}
			
		}
		else if($mtypex == 'g'){
			$grq = mysqli_query($link, "SELECT `path` FROM games WHERE gid='$midx' ");
			$gr = mysqli_fetch_assoc($grq);
			$filez = $gr['path'];
			if(unlink('../assets/materials/'.$filez)){
				$delmq = mysqli_query($link, "DELETE FROM games WHERE gid='$midx' ");
				if($delmq){
					$delq = mysqli_query($link, "DELETE FROM lesson_structure WHERE slcid='$slcid' AND mid='$midx' AND mtype='$mtypex' ");
				}
			}
		}
		else if($mtypex == 'i'){
			$irq = mysqli_query($link, "SELECT `path` FROM image WHERE imid='$midx' ");
			$ir = mysqli_fetch_assoc($irq);
			$filez = $ir['path'];
			if(unlink('../assets/materials/'.$filez)){
				$delmq = mysqli_query($link, "DELETE FROM image WHERE imid='$midx' ");
				if($delmq){
					$delq = mysqli_query($link, "DELETE FROM lesson_structure WHERE slcid='$slcid' AND mid='$midx' AND mtype='$mtypex' ");
				}
			}
		}
		else if($mtypex == 'v'){
			$vrq = mysqli_query($link, "SELECT `path` FROM videonaudio WHERE vaid='$midx' ");
			$vr = mysqli_fetch_assoc($vrq);
			$filez = $vr['path'];
			if(unlink('../assets/materials/'.$filez)){
				$delmq = mysqli_query($link, "DELETE FROM videonaudio WHERE vaid='$midx' ");
				if($delmq){
					$delq = mysqli_query($link, "DELETE FROM lesson_structure WHERE slcid='$slcid' AND mid='$midx' AND mtype='$mtypex' ");
				}
			}
		}
	}

	$dell = mysqli_query($link, "DELETE FROM subject_level_curriculum WHERE slcid='$slcid' ");
	if(!$dell){
		echo "Error deleting the lesson...";
	}
	else{
		echo "done";
	}
	exit();
	
}

if(isset($_POST['action']) && $_POST['action'] == 'unenroll'){
	if($rlid != 2){
		echo "You do not have clearance to perform this action";
		exit();	
	}
	$slid = clean($link, $_POST['subject']);
	$chq = mysqli_query($link, "SELECT slid FROM student_subject_level WHERE slid='$slid' AND stid='$uidx' ");
    $chnum = mysqli_num_rows($chq);
    if($chnum != 0){
    	$inwq = mysqli_query($link, "SELECT inst_id FROM student WHERE stid='$uidx' ");
    	$str = mysqli_fetch_assoc($inwq);
    	if(empty($str['inst_id']) == true){
    		$sqq = mysqli_query($link, "SELECT slcid FROM subject_level_curriculum WHERE slid='$slid' ");
    		while ($srz = mysqli_fetch_array($sqq, MYSQLI_ASSOC)) {
    			$slcidv = $srz['slcid'];

    			$delqz = mysqli_query($link, "DELETE FROM student_queries WHERE stid='$uidx' AND slcid='$slcidv' ");
    			$delpz = mysqli_query($link, "DELETE FROM student_progress WHERE stid='$uidx' AND slcid='$slcidv' ");
    		}
    		$upq = mysqli_query($link, "DELETE FROM student_subject_level WHERE slid='$slid' AND stid='$uidx' ");
    		if($upq){
    			echo "done";	
    		}
    		else{
    			echo "Error unenrolling you...Try again";
    		}
    	}
    	else{
    		echo "Sorry you can not unenroll yourself, Have your instructor uneroll you for this course...";	
    	}
    }
    else{
    	echo "You are not enrolled for this course";	
    }
	exit();
	
}

if(isset($_POST['action']) && $_POST['action'] == 'enroll'){
	if($rlid != 2){
		echo "You do not have clearance to perform this action";
		exit();	
	}
	$slid = clean($link, $_POST['subject']);
	$chq = mysqli_query($link, "SELECT slid FROM student_subject_level WHERE slid='$slid' AND stid='$uidx' ");
    $chnum = mysqli_num_rows($chq);
    if($chnum == 0){
    	$inwq = mysqli_query($link, "SELECT inst_id FROM student WHERE stid='$uidx' ");
    	$str = mysqli_fetch_assoc($inwq);
    	if(empty($str['inst_id']) == true){
    		$upq = mysqli_query($link, "INSERT INTO student_subject_level(`slid`,`stid`,`added`) VALUES('$slid','$uidx',now()) ");
    		if($upq){
    			echo "done";	
    		}
    		else{
    			echo "Error enrolling you...Try again";
    		}
    	}
    	else{
    		echo "Sorry you can not enroll yourself, Have your instructor eroll you for this course...";	
    	}
    }
    else{
    	echo "You are already enrolled for this course";	
    }
	exit();
	
}

if(isset($_GET['progerss'])){
	if($rlid != 1){
		echo "You do not have clearance to perform this action";
		exit();
	}

	$stid = clean($link, $_GET['stid']);
	$slid = clean($link, $_GET['sid']);

	$sq = mysqli_query($link, "SELECT lname,fname FROM student WHERE stid='$stid' ");
	$sr = mysqli_fetch_assoc($sq);
	$studentnm = $sr['lname'].' '.$sr['fname'];

	$qsl = mysqli_query($link, "SELECT * FROM subject_level WHERE slid='$slid' ");
	$srx = mysqli_fetch_assoc($qsl);
	$level = get_level($link, $srx['lid']);
	$subjx = get_subject($link, $srx['sid'] );
	$subjx = explode('|', $subjx);
	$subjectnm = $subjx[0];

	$result = '';

	$q = mysqli_query($link, "SELECT * FROM subject_level_curriculum WHERE slid='$slid' ORDER BY lesson_number ASC");
	$qnum = mysqli_num_rows($q);
	if($qnum == 0){
		$result = 'No lessons found';
	}
	else{
		while ($qr = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
			$slcid = $qr['slcid'];
			$lessonnm = $qr['lesson_name'];
			$lessonnum = $qr['lesson_number'];

			$sg = mysqli_query($link, "SELECT score FROM student_lessons WHERE stid='$stid' AND slcid='$slcid' ");
			$sgnum = mysqli_num_rows($sg);
			if($sgnum == 0){
				$grade=$score='';
			}
			else{
				$sgr = mysqli_fetch_assoc($sg);
				$grade = grade($link, $sgr['score']);
				$score = $sgr['score'];

			}

			$qq = mysqli_query($link, "SELECT * FROM lesson_structure WHERE mtype='q' AND slcid='$slcid' ");
			$qqnum = mysqli_num_rows($qq);
			if($qqnum == 0){
				$ques = '';
			}
			else{
				$qqr = mysqli_fetch_assoc($qq);
				$qid = $qqr['mid'];
		        $ques = '';
			}

			$result .= '<li class="nestable-item" data-id="1">
		                  <div class="nestable-handle">Lesson '.$lessonnum.' - '.$lessonnm.' 
		                  	<span class="label label-success" style="float:right;font-size:16px;">'.$score.' - '.$grade.'</span>
		                  </div>
		                  '.$ques.'
		                </li>';

		}
		$result = 'done|<div class="row">
				      <div class="col-sm-8 col-sm-push-1 col-md-8 col-md-push-2 col-lg-8 col-lg-push-2">
				        <div class="center m-a-2">
				          <div class="icon-block img-circle" onclick="hideform()" style="background-color: #fff;cursor:pointer;">
				            <i class="material-icons md-36 text-muted" style="color: #ff6666;font-weight: 800;">close</i>
				          </div>
				        </div>
				        <div class="card">
				          <div class="card-header bg-white center">
				            <h4 class="card-title">'.$studentnm.'</h4>
				            <p class="card-subtitle">'.$subjectnm.' <span class="label label-primary">'.$level.'</span> - Lessons</p>
				          </div>
				          <div class="p-a-2">
				            <div class="nestable" id="nestable">
				              <ul class="nestable-list">
				                '.$result.'
				              </ul>
				            </div>
				          </div>
				        </div>
				      </div>
				    </div>';
	}

	echo $result;
	exit();
}
?>