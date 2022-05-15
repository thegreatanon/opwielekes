Dropzone.autoDiscover = false;

$(document).ready(function () {

});

function loadImageDropzone(){
	var imageDropzoneOptions = {
		paramName: "file", // The name that will be used to transfer the file
		maxFilesize: 10, // MB
		url: 'uploadimage.php',
		maxFiles: 1,
		//autoProcessQueue: false, // for adding thumbnail to upload
		createImageThumbnails: true,
		thumbnailHeight: 75,
		acceptedFiles: 'image/jpeg,image/jpg,image/png',
		dictDefaultMessage: "Sleep je afbeelding hier om op te laden.",
		renameFile: function (file) {
			ext = file.name.split('.').pop();
			filebase = 'bike_' + Date.now() + '.' + file.name.split('.').pop();
			return file.name = filebase;
		},
		init: function() {
			this.on("success", function(file, serverResponse) {
				var bikeid = $('#bike_id').val();
				imageopenbikeid = bikeid;
				$.ajax({
					type: 'POST',
					url: 'api/bikes/image',
					data: JSON.stringify({
						'BikeID': bikeid,
						'ImageFile' : file.upload.filename,
						'UploadDatetime': moment().format('YYYY-MM-DD HH:mm:ss'),
						'Active' : 1
					}),
					contentType: "application/json",
					success: function () {
						//loadBikes(bikeid);
					},
					error: function (data) {
						console.error(data);
					}
				});
			});
			// clear interface after upload
			this.on("complete", function(file) {
				//let self = this;
				imageDropzone.removeFile(file);
				loadBikes(imageopenbikeid);
				//document.getElementById('my-great-dropzone').style.display = "none";
			});
		}
	};
	if (document.getElementById('my-great-dropzone')) {
			imageDropzone = new Dropzone("div#my-great-dropzone",imageDropzoneOptions);
	}
}

function setBikeBasicsDiv() {
	$('#bike_basics_div').empty();
	var myhtml = '';
	if (getProperty('bike_show_image').value == false) {
		myhtml += '<div class="form-group">';
		myhtml += '<label class="col-sm-2 control-label lb-sm">Nummer</label>';
		myhtml += '<div class="col-sm-2">';
		myhtml += '<input type="number" class="form-control input-sm" id="bike_nr" name="bike_nr" value=1>';
		myhtml += '</div>';
		myhtml += '<label class="col-sm-1 control-label lb-sm">Naam</label>';
		myhtml += '<div class="col-sm-3">';
		myhtml += '<input type="text" class="form-control input-sm" id="bike_name" name="bike_name" placeholder="name">';
		myhtml += '</div>';
		myhtml += '<label class="col-sm-2 control-label">Status</label>';
		myhtml += '<p class="col-sm-2 form-control-static preducedvertspace" id="bike_status_text"> </p>';
		myhtml += '</div>';
	} else {
		// display the image
		myhtml += '<div class="form-group" style="margin-bottom:0px;">';
		myhtml += '<div class="col-sm-6">';
		myhtml += '<input type="hidden" id="bike_imageid" name="bike_imageid" value="0">';
		myhtml += '<label class="col-sm-4 control-label lb-sm">Foto</label>';
		myhtml += '<div class="col-sm-7">';
		// TODO: image fullscreen on click
		myhtml += '<img src="images/transparent.png" style="height:125px;" id="bikeimagesource"></img>';
		myhtml += '</div>';
		myhtml +=	'<div class="col-sm-1">';
		myhtml += '<a class="btn btn-default btn-sm" onclick="removeBikeImage()" id="bikeimagedelete" name="bikeimagedelete">';
		myhtml += '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>';
		myhtml += '</a>';
		myhtml += '<a class="btn btn-default btn-sm" onclick="uploadBikeImage()" style="margin-top:3px;" id="bikeimageupload" name="bikeimageupload">';
		myhtml += '<span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>';
		myhtml += '</a>';
		myhtml += '</div>';
		myhtml += '</div>';
		myhtml += '<div class="col-sm-6">';
		myhtml += '<div class="form-group">'
		myhtml += '<label class="col-sm-4 control-label lb-sm">Nummer</label>';
		myhtml += '<div class="col-sm-8">';
		myhtml += '<input type="number" class="form-control input-sm" id="bike_nr" name="bike_nr" value=1>';
		myhtml += '</div>';
		myhtml += '</div>';
		myhtml += '<div class="form-group">'
		myhtml += '<label class="col-sm-4 control-label lb-sm">Naam</label>';
		myhtml += '<div class="col-sm-8">';
		myhtml += '<input type="text" class="form-control input-sm" id="bike_name" name="bike_name" placeholder="name">';
		myhtml += '</div>';
		myhtml += '</div>';
		myhtml += '<div class="form-group">'
		myhtml += '<label class="col-sm-4 control-label">Status</label>';
		myhtml += '<p class="col-sm-8 form-control-static preducedvertspace" id="bike_status_text"> </p>';
		myhtml += '</div>';
		myhtml += '</div>';
		myhtml += '</div>';
		// add dropzone element
		myhtml += '<div class="form-group" style="margin-bottom:0px;">';
		myhtml += '<div class="col-sm-2" >';
		myhtml += '</div>';
		myhtml += '<div class="clsbox-1 col-sm-4" runat="server" style="max-width: 500px;" hidden>';
		myhtml += '<div class="dropzone clsbox" id="my-great-dropzone">';
		myhtml += '</div>';
		myhtml += '</div>';
		myhtml += '<div class="col-sm-6" >';
		myhtml += '</div>';
		myhtml += '</div>';
	}
	$('#bike_basics_div').append(myhtml);
	if (getProperty('bike_show_image').value == true) {
		loadImageDropzone();
	}
}

function removeBikeImage(){
		var bikeid = $('#bike_id').val();
		var imageid = $('#bike_imageid').val();
		$.ajax({
			type: 'POST',
			url: 'api/bikes/deleteimage',
			data: JSON.stringify({
				'ID': imageid,
				'Active' : false
			}),
			contentType: "application/json",
			success: function () {
				loadBikes(bikeid);
			},
			error: function (data) {
				console.error(data);
			}
		});
}

function uploadBikeImage(){
	imageDropzone.hiddenFileInput.click();
}

// image logic (only if images on to reduce bandwith usage)
function setBikeImage(bike) {
	if (getProperty('bike_show_image').value == true) {
		if (bike.ImageFile !== null) {
			$('#bikeimagedelete').attr("disabled", false);
			$('#bikeimageupload').attr("disabled", false);
			document.getElementById('bikeimagesource').src = "uploads/" + $('#act_bi').val() + "/" + bike.ImageFile;
		} else {
			$('#bikeimagedelete').attr("disabled", true);
			$('#bikeimageupload').attr("disabled", false);
			document.getElementById('bikeimagesource').src = "images/transparent.png";
		}
		if (bike.ImageID !== null) {
			$('#bike_imageid').val(bike.ImageID);
		} else {
			$('#bike_imageid').val(0);
		}
		//document.getElementById('my-great-dropzone').style.display = "none";
	}
}

function resetBikeImage() {
	if (getProperty('bike_show_image').value == true) {
		$('#bikeimagedelete').attr("disabled", true);
		$('#bikeimageupload').attr("disabled", true);
		document.getElementById('bikeimagesource').src = "images/transparent.png";
		$('#bike_imageid').val(0);
		//document.getElementById('my-great-dropzone').style.display = "none";
	}
}
