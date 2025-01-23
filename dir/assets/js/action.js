var xmlHttp = createXmlHttpRequestObject();

function createXmlHttpRequestObject(){
	var xmlHttp;
	if(window.XMLHttpRequest){
		xmlHttp = new XMLHttpRequest();
		}
		else{
			xmlHttp = new ActiveObject("Microsoft.XMLHTTP");
			}
			return xmlHttp;
}

function addward(){
	$('#overlaybxz').fadeIn(200);

}

function addsubjectlevel(){
	$('#addsubjectlevelover').fadeIn(200);
}

function addsubject(){
	$('#addsubjectover').fadeIn(200);

}

function addlesson(){
	$('#addlessonover').fadeIn(200);

}

function addlessonmat(slcid){
	$('#addlessonmatover').fadeIn(200);
}


function hideform(){
	$('#overlaybxz').fadeOut(200);
	$('#grayoverlay').fadeOut(200);
	$('#progressover').fadeOut(200);
	$('#addsubjectover').fadeOut(200);
	$('#addsubjectlevelover').fadeOut(200);
	$('#addlessonover').fadeOut(200);
	$('#addlessonmatover').fadeOut(200);
	$('#addquestionover').fadeOut(200);
}

function enroll(stid){
	$('#grayoverlay').fadeIn(200);
}


function removeel(elementid){
	$('#elementbx'+elementid).fadeOut(200,function(){
		$('#elementbx'+elementid).remove();
	});
}


function addquest(){
	var mattype = document.getElementById('qmattype').value;
	if(mattype == "q"){
		document.getElementById('questionbx').innerHTML = '<div class="elementbx"><div class="elementbody"><div class="form-group"><textarea name="qdescrip" class="summernote" placeholder="Type here..." required></textarea></div><div id="optioncnt"><div id="qptionx"><div class="form-group" style="margin-bottom: 10px;"><input type="text" class="form-control" name="qoption[]" placeholder="Option" required></div><div class="form-group"><input type="text" class="form-control" name="qoption[]" placeholder="Option" required></div></div><button onclick="addoptions()" style="float: right;" type="button" class="btn btn-primary"><i class="material-icons">add_box</i><span class="icon-text">Add more options</span></button></div><div class="answerbx"><div class="form-group"><input type="text" class="form-control" name="qanswer" placeholder="Answer" required></div></div></div></div>';
		(function ($) {
			/**
			 * jQuery plugin wrapper for compatibility
			 */
			$.fn.APSummernote = function () {
				if (! this.length) return;
				if (typeof $.fn.summernote != 'undefined') {
					this.summernote({
						popover: {
							image: [],
							link: [],
							air: []
						},
						toolbar: [
					        ['style', ['bold', 'italic', 'underline', 'clear']],
						    ['font', ['strikethrough', 'superscript', 'subscript']],
						    ['fontsize', ['fontsize']],
						    ['color', ['color']],
						    ['para', ['ul', 'ol', 'paragraph']],
						    ['height', ['height']]
					      ],

					    placeholder: 'Description goes here...'
					});
				}
			};
			
			$('.summernote').APSummernote();

		}(jQuery));
	}
	else if(mattype == "qi"){
		document.getElementById('questionbx').innerHTML = '<div class="elementbx"><div class="elementbody"><div class="form-group"><textarea class="summernote" name="qdescrip" placeholder="Description goes here..." required></textarea><br><p style="margin-bottom: 0;color: #FF9800;font-size: 13px;">*Allowed format (jpg,jpeg,png,svg)</p><input type="file" class="form-control" name="qimage" style="font-size:12px;margin-top:5px;" required></div><div id="optioncnt"><div id="qptionx"><div class="form-group" style="margin-bottom: 10px;"><input type="text" class="form-control" name="qoption[]" placeholder="Option" required></div><div class="form-group"><input type="text" class="form-control" name="qoption[]" placeholder="Option" required></div></div><button onclick="addoptions()" style="float: right;" type="button" class="btn btn-primary"><i class="material-icons">add_box</i><span class="icon-text">Add more options</span></button></div><div class="answerbx"><div class="form-group"><input type="text" class="form-control" name="qanswer" placeholder="Answer" required></div></div></div></div>';
		(function ($) {
			/**
			 * jQuery plugin wrapper for compatibility
			 */
			$.fn.APSummernote = function () {
				if (! this.length) return;
				if (typeof $.fn.summernote != 'undefined') {
					this.summernote({
						popover: {
							image: [],
							link: [],
							air: []
						},
						toolbar: [
					        ['style', ['bold', 'italic', 'underline', 'clear']],
						    ['font', ['strikethrough', 'superscript', 'subscript']],
						    ['fontsize', ['fontsize']],
						    ['color', ['color']],
						    ['para', ['ul', 'ol', 'paragraph']],
						    ['height', ['height']]
					      ],

					    placeholder: 'Description goes here...'
					});
				}
			};
			
			$('.summernote').APSummernote();

		}(jQuery));
	}
	else if(mattype == "qa"){
		document.getElementById('questionbx').innerHTML = '<div class="elementbx"><div class="elementbody"><div class="form-group"><textarea class="summernote" name="qdescrip" placeholder="Description goes here..." required></textarea><br><p style="margin-bottom: 0;color: #FF9800;font-size: 13px;">*Allowed format (mp3,wav)</p><input type="file" class="form-control" name="qaudio" style="font-size:12px;margin-top:5px;" required></div><div id="optioncnt"><div id="qptionx"><div class="form-group" style="margin-bottom: 10px;"><input type="text" class="form-control" name="qoption[]" placeholder="Option" required></div><div class="form-group"><input type="text" class="form-control" name="qoption[]" placeholder="Option" required></div></div><button onclick="addoptions()" style="float: right;" type="button" class="btn btn-primary"><i class="material-icons">add_box</i><span class="icon-text">Add more options</span></button></div><div class="answerbx"><div class="form-group"><input type="text" class="form-control" name="qanswer" placeholder="Answer" required></div></div></div></div>';
		(function ($) {
			/**
			 * jQuery plugin wrapper for compatibility
			 */
			$.fn.APSummernote = function () {
				if (! this.length) return;
				if (typeof $.fn.summernote != 'undefined') {
					this.summernote({
						popover: {
							image: [],
							link: [],
							air: []
						},
						toolbar: [
					        ['style', ['bold', 'italic', 'underline', 'clear']],
						    ['font', ['strikethrough', 'superscript', 'subscript']],
						    ['fontsize', ['fontsize']],
						    ['color', ['color']],
						    ['para', ['ul', 'ol', 'paragraph']],
						    ['height', ['height']]
					      ],

					    placeholder: 'Description goes here...'
					});
				}
			};
			
			$('.summernote').APSummernote();

		}(jQuery));
	}
}


function addmatcont(){
	var mattype = document.getElementById('mattype').value;
	if(mattype == "a"){
		audiox();
	}
	else if(mattype == "d"){
		documentx();
	}
	else if(mattype == "g"){
		gamex();
	}
	else if(mattype == "i"){
		imagex();
	}
	else if(mattype == "v"){
		videox();
	}
	else if(mattype == "q"){
		questionx();
	}
}



function addoptions(){
	var content = document.getElementById('qptionx').innerHTML;
	document.getElementById('qptionx').innerHTML = content+'<div class="form-group"><input type="text" class="form-control" name="qoption[]" placeholder="Option"></div>';
}



function deletelesson(lessonid){
	swal({   
		title: "Are you sure...?",   
		text: "This will delete ths lesson and all the materials associated to it.",   
		type: "warning",   
		showCancelButton: true,   
		confirmButtonColor: "#DD6B55",   
		confirmButtonText: "Yes!",   
		closeOnConfirm: false 
		},function (isConfirm) {
        if (isConfirm) {
            dellesson(lessonid);
        } 
	});
}


function addquestion(question){
	$('#addquestionover').fadeIn(200);
	document.getElementById('xa').innerHTML = '<input type="hidden" name="qid" value="'+question+'"><select class="form-control" name="qtype" id="qmattype" onchange="addquest()" required><option value="">*-- Question type --*</option><option value="q">Normal Question</option><option value="qi">Question with Image</option><option value="qa">Question with Audio</option></select>';

}


function enrollx(subject){
	swal({   
		title: "",   
		text: "Are you sure you want to enroll for this subject?",   
		type: "warning",   
		showCancelButton: true,   
		confirmButtonColor: "#DD6B55",   
		confirmButtonText: "Yes!",   
		closeOnConfirm: false 
		},function (isConfirm) {
        if (isConfirm) {
            enrollstudent(subject);
        } 
	});
}


function unenroll(subject){
	swal({   
		title: "",   
		text: "Are you sure you want to unenroll for this subject?",   
		type: "warning",   
		showCancelButton: true,   
		confirmButtonColor: "#DD6B55",   
		confirmButtonText: "Yes!",   
		closeOnConfirm: false 
		},function (isConfirm) {
        if (isConfirm) {
            unenrollstudent(subject);
        } 
	});
}


function enrollstudent(subject){
	$('#progressover').fadeIn(200);
	if(xmlHttp.readyState == 0 || xmlHttp.readyState == 4){
			xmlHttp.open("POST", "inc/admin_action.php", true);
			xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlHttp.onreadystatechange = function(){
				if (xmlHttp.readyState==4 && xmlHttp.status==200){
					$('#progressover').fadeOut(200);
					if(xmlHttp.responseText == 'done'){
						enrolled();
					}
					else{
						swal("Error!", xmlHttp.responseText, "error");
					}
				}
			}
			xmlHttp.send("action=enroll&subject="+subject);
	}
	else{
		setTimeout(function(){enrollstudent(subject)},1000);
		}
}


function unenrollstudent(subject){
	$('#progressover').fadeIn(200);
	if(xmlHttp.readyState == 0 || xmlHttp.readyState == 4){
			xmlHttp.open("POST", "inc/admin_action.php", true);
			xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlHttp.onreadystatechange = function(){
				if (xmlHttp.readyState==4 && xmlHttp.status==200){
					$('#progressover').fadeOut(200);
					if(xmlHttp.responseText == 'done'){
						unenrolled();
					}
					else{
						swal("Error!", xmlHttp.responseText, "error");
					}
				}
			}
			xmlHttp.send("action=unenroll&subject="+subject);
	}
	else{
		setTimeout(function(){unenrollstudent(subject)},1000);
		}
}


function dellesson(lessonid){
	$('#progressover').fadeIn(200);
	if(xmlHttp.readyState == 0 || xmlHttp.readyState == 4){
			xmlHttp.open("POST", "inc/admin_action.php", true);
			xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlHttp.onreadystatechange = function(){
				if (xmlHttp.readyState==4 && xmlHttp.status==200){
					$('#progressover').fadeOut(200);
					if(xmlHttp.responseText == 'done'){
						lessondeleted();
					}
					else{
						swal("Error!", xmlHttp.responseText, "error");
					}
				}
			}
			xmlHttp.send("action=dellesson&lessonid="+lessonid);
	}
	else{
		setTimeout(function(){dellesson(lessonid)},1000);
		}
}


function unenrolled(){
	swal({
        title: "Unenrolled successfully",
        text: "You have unenrolled successfully",
        type: "success",
        showCancelButton: false,
        confirmButtonText: "Done!",
        closeOnConfirm: false
    }, function (isConfirm) {
        if (isConfirm) {
            location.reload();
        }
    });
}


function enrolled(){
	swal({
        title: "Enrolled successfully",
        text: "You have enrolled successfully",
        type: "success",
        showCancelButton: false,
        confirmButtonText: "Done!",
        closeOnConfirm: false
    }, function (isConfirm) {
        if (isConfirm) {
            location.reload();
        }
    });
}


function lessondeleted(){
	swal({
        title: "Lesson Deleted",
        text: "Lesson has be deleted successfully",
        type: "success",
        showCancelButton: false,
        confirmButtonText: "Done!",
        closeOnConfirm: false
    }, function (isConfirm) {
        if (isConfirm) {
            location.reload();
        }
    });
}


function documentx(){
	
	document.getElementById('lessonstbx').innerHTML = '<div class="elementbx"><div class="elementbody"><div class="form-group"><textarea name="descrip" class="summernote" placeholder="Type here..." required></textarea></div></div></div>';
	(function ($) {
	/**
	 * jQuery plugin wrapper for compatibility
	 */
	$.fn.APSummernote = function () {
		if (! this.length) return;
		if (typeof $.fn.summernote != 'undefined') {
			this.summernote({
				popover: {
					image: [],
					link: [],
					air: []
				},
				toolbar: [
			        ['style', ['bold', 'italic', 'underline', 'clear']],
				    ['font', ['strikethrough', 'superscript', 'subscript']],
				    ['fontsize', ['fontsize']],
				    ['color', ['color']],
				    ['para', ['ul', 'ol', 'paragraph']],
				    ['height', ['height']]
			      ],

			    placeholder: 'Description goes here...'
			});
		}
	};
	
	$('.summernote').APSummernote();

}(jQuery));
}


function questionx(){
	document.getElementById('lessonstbx').innerHTML = '<div class="form-group"><input type="number" class="form-control" data-parsley-min="1" name="quiznumber" placeholder="Number of quiz questions" style="width:50%;" required></div>';
	
}



function gamex(){
	document.getElementById('lessonstbx').innerHTML = '<div class="elementbx"><div class="elementbody"><div class="form-group"><textarea class="summernote" name="descrip" placeholder="Description goes here..."></textarea><br><p style="margin-bottom: 0;color: #FF9800;font-size: 13px;">*Allowed format (fla,swf)</p><input type="file" class="form-control" name="game" style="font-size:12px;margin-top:5px;" required></div></div></div>';
	(function ($) {
	/**
	 * jQuery plugin wrapper for compatibility
	 */
	$.fn.APSummernote = function () {
		if (! this.length) return;
		if (typeof $.fn.summernote != 'undefined') {
			this.summernote({
				popover: {
					image: [],
					link: [],
					air: []
				},
				toolbar: [
			        // [groupName, [list of button]]
			        ['style', ['bold', 'italic', 'underline', 'clear']],
			        ['fontsize', ['fontsize']]
			      ],

			    placeholder: 'Description goes here...'
			});
		}
	};
	
	$('.summernote').APSummernote();

}(jQuery));
}


function videox(){
	document.getElementById('lessonstbx').innerHTML = '<div class="elementbx"><div class="elementbody"><div class="form-group"><textarea class="summernote" name="descrip" placeholder="Description goes here..."></textarea><br><p style="margin-bottom: 0;color: #FF9800;font-size: 13px;">*Allowed format (mp4,wmv)</p><input type="file" class="form-control" name="video" style="font-size:12px;margin-top:5px;" required></div></div></div>';
	(function ($) {
	/**
	 * jQuery plugin wrapper for compatibility
	 */
	$.fn.APSummernote = function () {
		if (! this.length) return;
		if (typeof $.fn.summernote != 'undefined') {
			this.summernote({
				popover: {
					image: [],
					link: [],
					air: []
				},
				toolbar: [
			        // [groupName, [list of button]]
			        ['style', ['bold', 'italic', 'underline', 'clear']],
			        ['fontsize', ['fontsize']]
			      ],

			    placeholder: 'Description goes here...'
			});
		}
	};
	
	$('.summernote').APSummernote();

}(jQuery));
}



function imagex(){
	document.getElementById('lessonstbx').innerHTML = '<div class="elementbx"><div class="elementbody"><div class="form-group"><textarea class="summernote" name="descrip" placeholder="Description goes here..."></textarea><br><p style="margin-bottom: 0;color: #FF9800;font-size: 13px;">*Allowed format (jpg,jpeg,png,svg)</p><input type="file" class="form-control" name="image" style="font-size:12px;margin-top:5px;" required></div></div></div>';
	(function ($) {
	/**
	 * jQuery plugin wrapper for compatibility
	 */
	$.fn.APSummernote = function () {
		if (! this.length) return;
		if (typeof $.fn.summernote != 'undefined') {
			this.summernote({
				popover: {
					image: [],
					link: [],
					air: []
				},
				toolbar: [
			        // [groupName, [list of button]]
			        ['style', ['bold', 'italic', 'underline', 'clear']],
			        ['fontsize', ['fontsize']]
			      ],

			    placeholder: 'Description goes here...'
			});
		}
	};
	
	$('.summernote').APSummernote();

}(jQuery));
}



function audiox(){
	document.getElementById('lessonstbx').innerHTML = '<div class="elementbx"><div class="elementbody"><div class="form-group"><textarea class="summernote" name="descrip" placeholder="Description goes here..."></textarea><br><p style="margin-bottom: 0;color: #FF9800;font-size: 13px;">*Allowed format (mp3,wav)</p><input type="file" class="form-control" name="audio" style="font-size:12px;margin-top:5px;" required></div></div></div>';
	(function ($) {
	/**
	 * jQuery plugin wrapper for compatibility
	 */
	$.fn.APSummernote = function () {
		if (! this.length) return;
		if (typeof $.fn.summernote != 'undefined') {
			this.summernote({
				popover: {
					image: [],
					link: [],
					air: []
				},
				toolbar: [
			        // [groupName, [list of button]]
			        ['style', ['bold', 'italic', 'underline', 'clear']],
			        ['fontsize', ['fontsize']]
			      ],

			    placeholder: 'Description goes here...'
			});
		}
	};
	
	$('.summernote').APSummernote();

}(jQuery));
}


function progressview(student,subject){
	$('#progressover').fadeIn(200);
	if(xmlHttp.readyState == 0 || xmlHttp.readyState == 4){
		xmlHttp.open("GET", "inc/admin_action.php?progerss="+7+"&stid="+student+"&sid="+subject, true);
		xmlHttp.onreadystatechange = function(){
			if (xmlHttp.readyState==4 && xmlHttp.status==200){
				var respondx = xmlHttp.responseText.split("|");
				if(respondx[0] == 'done'){
					document.getElementById('progressover').innerHTML = xmlHttp.responseText;
				}
				else{
					$('#progressover').fadeOut(200);
					swal("Error!", xmlHttp.responseText, "error");
				}
			}
		}
		xmlHttp.send();
	}
	else{
		setTimeout(function(){filterads()},1000);
		}

}


function searchnusers(){
	var name = document.getElementById('studentinput').value;
	if( name.length < 3){
		$('#dropstulistbx').fadeOut(500);
	}
	else{
		$('#dropstulistbx').fadeIn(500);

		if(xmlHttp.readyState == 0 || xmlHttp.readyState == 4){
			xmlHttp.open("POST", "inc/admin_action.php", true);
			xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlHttp.onreadystatechange = function(){
				if (xmlHttp.readyState==4 && xmlHttp.status==200){
					var respondx = xmlHttp.responseText.split("|");
					if(respondx[0] == 'done'){
						document.getElementById('dropstulistbx').innerHTML = respondx[1];
					}
					else if(respondx[0] == 'none'){
						document.getElementById('dropstulistbx').innerHTML = '<p style="margin-top:20px;text-align: center;font-size: 14px;color: #999;">No result found...</p>';
					}
					else{
						swal("Error!", xmlHttp.responseText, "error");
					}
				}
			}
			xmlHttp.send("action=searchnusers&input="+name);
		}
		else{
			setTimeout(function(){searchnusers()},1000);
			}	
	}
}