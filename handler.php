<?php
session_start();
include 'configdb.php';
include 'Mobile_Detect.php';//library for detecting mobile device
//code to detect Mobile Device
$detect = new Mobile_Detect();

if ($detect->isMobile()) {
    header('Location: disclaimer.php');
    exit(0);
}//end of mobile detect code
if(!isset($_SESSION['uname']))
{    
	echo "<script type='text/javascript'>
			alert('You are not logged in.Please login first.');
			location.href = 'login.php';
		  </script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Busted Elf</title>
	<!-- favicons -->
	<link rel="shortcut icon" href="assets/img/inc/favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" href="assets/img/inc/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="57x57" href="assets/img/inc/apple-touch-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="72x72" href="assets/img/inc/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/inc/apple-touch-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="assets/img/inc/apple-touch-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="assets/img/inc/apple-touch-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="assets/img/inc/apple-touch-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="assets/img/inc/apple-touch-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="assets/img/inc/apple-touch-icon-180x180.png">
	<!-- viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- page description -->
	<meta name="description" content="description">
	<!-- styles -->
	<link rel="stylesheet" href="assets/css/styles.min.css">
	<!-- fonts -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=PT+Sans">
	<!-- icons -->
	<link rel="stylesheet" href="assets/icons/styles.css">
	
	<link rel="stylesheet" href="assets/css/theme-colors/theme-tomato.css">
	<style>
	video, canvas
      {
          border:1px solid #000;
      }
	  #imageBackgrounddata,#instruction{
	      border:1px solid #000;
	  }
	  .col-md-offset-3
	  {
		margin-left: 33%;
		padding:5%;
	  }
      #PlayPause{
      padding-top:5%;
      margin-bottom:3%;
      padding-bottom:5%;
      background-color:#0099CC;
      border-radius:6px;
      }
	 </style>
</head>

<body  class="homepage" onload="StartBackground();">

	<!-- preloader -->
	<div class="preloader"></div>
	<!-- page preloader -->
	<div class="p-preloader">
		<div class="p-preloader__top"></div>
		<div class="p-preloader__bottom"></div>
		<div class="p-preloader__progressbar"></div>
		<div class="p-preloader__percentage">0</div>
	</div>
	<!-- global wrapper -->
	<div class="wrapper">
		<!-- start header(menu) multipage section -->
		<?php
		include "header.php";
		?>
		<!-- end header(menu) multipage section -->
		<div class="wrapper-content">

				<!-- start get it section -->
			<div class="s-get-it">
				<div class="s-get-it__bg parallax" data-background-image="assets/img/342.jpg" data-parallax-min-fading="60"></div>
				<div class="container">
				<h4 class="s-get-it__title">DOWNLOAD YOUR VIDEO</h4>
				
				</div>
			</div>
			<!-- end get it section -->
			<!-- start about 1 section -->
			<section class="section-2 section-white s-about" id="about">
				<div class="container">
					<div class="row">
						<div class="col-xs-12 col-sm-12">
							
							<h3 class="section__title">Download Your <span class="theme-color">Video </span></h3>
						</div>
					</div>
					<div class="row about">
						
<div class="col-md-6">
		      <div id="source">
              <p>Selected Video</p>
<?php
//code for loading image of that user
$User_Id=base64_decode($_GET['User_Id']);
$Unique_Id=base64_decode($_GET['Unique_Id']);

$sqlimg = "SELECT * FROM image_info WHERE User_id ='$User_Id' AND Unique_Id='$Unique_Id'";
$resultimg = mysqli_query($conn, $sqlimg);
if (mysqli_num_rows($resultimg) > 0)
{
    // output data of each row
    while($all_img = mysqli_fetch_assoc($resultimg))
   {
		$Img= $all_img['Image_Path'];
   }
}

//code for loading selected video
$VidId =base64_decode($_GET['VideoId']);
$sql = "SELECT * FROM video_info WHERE Video_Id = '$VidId'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0)
{
    // output data of each row
    while($all_video = mysqli_fetch_assoc($result))
   {
?> 
    <video id="videodata" width="554" height="280" controls >
	<source src="Admin/test_upload/<?php echo $all_video['Video_Path']; ?>" type="video/mp4">
	</video>
<?php
   }
}
?>
         </div><!-- end of div video source--> 
</div>	
<!--image loading div-->
<div class="col-md-6">
			<div id="backgroundvideo">
			 <p>Background Video</p>
			 <video style="" id="videoBackgrounddata" loop="loop" preload="auto" width="554" height="280">
			 </video>
			 </div>
			<div id="backgroundimage">
            <p>Background Image</p>
            <img src="<?php echo $Img ?>" width="554" height="280"  id="imageBackgrounddata" / >
            </div>
</div>
<!--start of button div-->	
<hr>
<div class="col-md-6">
<!-- hidden field for saving downloader record in db-->
<input type="hidden" value="<?php echo $User_Id ?>" id="User_Id">
<input type="hidden" value="<?php echo $VidId ?>" id="Video_Id">
<input type="hidden" value="<?php echo $Img ?>" id="Image_Path">

		   <strong><b>Instructions for You</b></strong>
<div id="instruction">
           <p>&nbsp;1.  The top row has your selected video and background image</p>
           <p>&nbsp;2.  Please Click on Process Button and video processing will start</p>
           <p>&nbsp;3.  After Process Completion just click on Download button </p>
           <p>&nbsp;4.  You have done your video downloading </p>
		   <div id="backgroundvideoselection">
			<ul>
				<li style="display:none"><input type="radio" id="newrow" name="background" value="<?php echo $Img ?>" onclick="loadBackgroundVideo();" checked=checked />
				 <label for="newrow">Your Image Is Set For Processing</label>
				</li>
			</ul>
            </div>
			 
			<div id="videocontrols" class="col-md-offset-3 col-md-6">
				<input class="btn btn-info col-md-7" id="PlayPause" type="button" onclick="Play();savedownload();" value=" Process"/>
            </div>
			<div>
                <progress id="progress" value="0" max="100" min="0" style="width: 100%;margin:0%;"></progress>             
                <!--Status:--> <span id="status">Idle</span>
                <a style="display:none" id="download" download="<?php $date=date("m-d-Y H:i"); echo $date.'.mp4'?>">Download</a>
            </div><br><br><br>
</div>
</div>	
<!-- end of preview buttons-->
<div class="col-md-6">
<p><b>Your Output video will be shown below .</b></p>
<div id="output">
 <canvas id="videoscreen" width="554" height="305" >
   <p>Sorry your browser does not support HTML5</p>
 </canvas>
</div>
</div>
<!--end of output pane col-md-6-->
		 
					</div>
				</div>
			</section>
			<!-- end about 1 section -->
			
			<!-- start footer 2 section -->
			<?php
			include "footer.php";
			?>
			<!-- end footer 2 section -->

		</div>
		<!-- /.wrapper-content -->
		<!-- start modal window -->
		<div class="modal">
			<div class="ico-108 modal__close"></div>
			<div class="ico-157 modal__info"></div>
			<div class="modal__content"></div>
		</div>
		<div class="overlay"></div>
		<!-- end modal window -->
		

	</div>
	<!-- /.wrapper -->
	<script>
		function setBgImageFromData() {
			var el = document.querySelectorAll('[data-background-image]');
			for (var i = 0; i < el.length; i++) {
				if (getComputedStyle(el[i]).backgroundImage === 'none') el[i].style.backgroundImage = 'url(' + el[i].getAttribute('data-background-image') + ')';
			}
		}
		setBgImageFromData();

		//async loading scripts with callback
		function loadjs(src, callback) {
			var script = document.createElement('script');
			script.src = src;
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(script, s);
			var loaded = false;

			function onload() {
				if (loaded) return;
				loaded = true;
				if (callback) callback();
			}
			script.onload = onload;
			script.onreadystatechange = function() {
				if (this.readyState === 'loaded' || this.readyState === 'complete') {
					setTimeout(onload, 0);
				}
			}
		}
		loadjs('assets/js/jquery.min.js', function() {
			loadjs('assets/js/libs.min.js', function() {
				loadjs('assets/js/common.min.js');
			});
		});
	</script>
<!--video processiong script-->
	<script>
        var oggSupported = false;
        var webmSupported = false;
        var mp4Supported = false;
        var isPlaying = false;
        var videoExt = "";
        var isBackgroundVideo = true;

        function draw() {
            if (window.requestAnimationFrame) window.requestAnimationFrame(draw);
            // IE implementation
            else if (window.msRequestAnimationFrame) window.msRequestAnimationFrame(draw);
            // Firefox implementation
            else if (window.mozRequestAnimationFrame) window.mozRequestAnimationFrame(draw);
            // Chrome implementation
            else if (window.webkitRequestAnimationFrame) window.webkitRequestAnimationFrame(draw);
            // Other browsers that do not yet support feature
            else setTimeout(draw, 16.7);
            DrawVideoOnCanvas();
        }


        function Play() {
            if (!isPlaying) {
                document.getElementById("videodata").play();
                document.getElementById("videoBackgrounddata").play();
                document.getElementById("PlayPause").value = "Pause";
                isPlaying = true;               
            }
            else {
                document.getElementById("videodata").pause();
                document.getElementById("videoBackgrounddata").pause();
                document.getElementById("PlayPause").value = "Play";
                isPlaying = false;                
            }
            draw();
        }

        function DrawVideoOnCanvas() {
            var object = document.getElementById("videodata")

            var backgroundObject;
            if (isBackgroundVideo) {
                backgroundObject = document.getElementById("videoBackgrounddata");
            }
            else {
                backgroundObject = document.getElementById("imageBackgrounddata");
            }
            var width = object.width;
            var height = object.height;
            var canvas = document.getElementById("videoscreen");
            canvas.setAttribute('width', width);
            canvas.setAttribute('height', height);
            if (canvas.getContext) {
                var context = canvas.getContext('2d');
                context.drawImage(backgroundObject, 0, 0, width, height);
                var imgBackgroundData = context.getImageData(0, 0, width, height);
                context.drawImage(object, 0, 0, width, height);
                imgDataNormal = context.getImageData(0, 0, width, height);
                var imgData = context.createImageData(width, height);

                for (i = 0; i < imgData.width * imgData.height * 4; i += 4) {
                    var r = imgDataNormal.data[i + 0];
                    var g = imgDataNormal.data[i + 1];
                    var b = imgDataNormal.data[i + 2];
                    var a = imgDataNormal.data[i + 3];
                    // compare rgb levels for green and set alphachannel to 0;
                    selectedR = 25;//25
                    selectedG = 90;//90
                    selectedB = 60;//60
                    if (r <= selectedR && b <= selectedB && g >= selectedG) {
                        a = 0;
                    }
                    if (a != 0) {
                        imgData.data[i + 0] = r;
                        imgData.data[i + 1] = g;
                        imgData.data[i + 2] = b;
                        imgData.data[i + 3] = a;
                    }
                }

                for (var y = 0; y < imgData.height; y++) {
                    for (var x = 0; x < imgData.width; x++) {
                        var r = imgData.data[((imgData.width * y) + x) * 4];
                        var g = imgData.data[((imgData.width * y) + x) * 4 + 1];
                        var b = imgData.data[((imgData.width * y) + x) * 4 + 2];
                        var a = imgData.data[((imgData.width * y) + x) * 4 + 3];
                        if (imgData.data[((imgData.width * y) + x) * 4 + 3] != 0) {
                            offsetYup = y - 1;
                            offsetYdown = y + 1;
                            offsetXleft = x - 1;
                            offsetxRight = x + 1;
                            var change=false;
                            if(offsetYup>0)
                            {
                                if(imgData.data[((imgData.width * (y-1) ) + (x)) * 4 + 3]==0)
                                {
                                    change=true;
                                }
                            }
                            if (offsetYdown < imgData.height)
                            {
                                if (imgData.data[((imgData.width * (y + 1)) + (x)) * 4 + 3] == 0) {
                                    change = true;
                                }
                            }
                            if (offsetXleft > -1) {
                                if (imgData.data[((imgData.width * y) + (x -1)) * 4 + 3] == 0) {
                                    change = true;
                                }
                            }
                            if (offsetxRight < imgData.width) {
                                if (imgData.data[((imgData.width * y) + (x + 1)) * 4 + 3] == 0) {
                                    change = true;
                                }
                            }
                            if (change) {
                                var gray = (imgData.data[((imgData.width * y) + x) * 4 + 0] * .393) + (imgData.data[((imgData.width * y) + x) * 4 + 1] * .769) + (imgData.data[((imgData.width * y) + x) * 4 + 2] * .189);                                
                                imgData.data[((imgData.width * y) + x) * 4] = (gray * 0.2) + (imgBackgroundData.data[((imgData.width * y) + x) * 4] *0.9);
                                imgData.data[((imgData.width * y) + x) * 4 + 1] = (gray * 0.2) + (imgBackgroundData.data[((imgData.width * y) + x) * 4 + 1]*0.9);
                                imgData.data[((imgData.width * y) + x) * 4 + 2] = (gray * 0.2) + (imgBackgroundData.data[((imgData.width * y) + x) * 4 + 2] * 0.9);
                                imgData.data[((imgData.width * y) + x) * 4 + 3] = 255;
                            }
                        }
                        
                    }
                }

                for (i = 0; i < imgData.width * imgData.height * 4; i += 4) {
                    var r = imgData.data[i + 0];
                    var g = imgData.data[i + 1];
                    var b = imgData.data[i + 2];
                    var a = imgData.data[i + 3];                
                    if (a == 0) {
                            imgData.data[i + 0] = imgBackgroundData.data[i + 0];
                            imgData.data[i + 1] = imgBackgroundData.data[i + 1];
                            imgData.data[i + 2] = imgBackgroundData.data[i + 2];
                            imgData.data[i + 3] = imgBackgroundData.data[i + 3];
                    }                   
                }
                context.putImageData(imgData, 0, 0);
              
            }
        }      

        function SupportedVideoFormat() {
            var video = document.createElement("video"); 
            if (video.canPlayType('video/ogg; codecs="theora, vorbis"')) {
                // it can play (maybe)!
                oggSupported = true;
            }
            if (video.canPlayType('video/mp4; codecs="avc1.42E01E, mp4a.40.2"')) {
                // it can play (maybe)!
                mp4Supported = true;
            }
            if (video.canPlayType('video/webm; codecs="vp8, vorbis"')) {
                // it can play (maybe)!
                webmSupported = true;
            }
        }

        function StartBackground() {
            SupportedVideoFormat();
            if (oggSupported) {
                videoExt = ".ogv";
            }
            if (webmSupported) {
                videoExt = ".webm"
            }
            if (mp4Supported) {
                videoExt = ".mp4";
            }
            loadBackgroundVideo();
        }

        function loadBackgroundVideo() {
            var value = "";
            var radioObj = document.getElementsByName("background");
            if (!radioObj)
                return "";
            var radioLength = radioObj.length;
            if (radioLength == undefined)
                if (radioObj.checked)
                    value= radioObj.value;
                else
                    value= "";
            for (var i = 0; i < radioLength; i++) {
                if (radioObj[i].checked) {
                    value= radioObj[i].value;
                }
            }

            //
            var backgroundType= value.split("/");
            if (backgroundType[0] == "videos") {
                isBackgroundVideo = true;
                var backgroundFileName = value + videoExt;
                document.getElementById("backgroundvideo").style.display = "inline";
                document.getElementById("backgroundimage").style.display = "none";
                document.getElementById("videoBackgrounddata").src = backgroundFileName;
                document.getElementById("videoBackgrounddata").loop = true
                if (isPlaying)
                    document.getElementById("videoBackgrounddata").play();
            }
            else {
                isBackgroundVideo = false;
                document.getElementById("backgroundvideo").style.display = "none";
                document.getElementById("backgroundimage").style.display = "inline";
                document.getElementById("imageBackgrounddata").src = value;
            }
        }

</script>
<script type="text/javascript" src="assets/js/sandip.js"></script>
<script type="text/javascript">
    
    document.addEventListener('DOMContentLoaded', function(){

            param1_input_video_id='videodata';
            param2_masked_canvas="videoscreen";
            param3_progressbar='progress';
            param4_output_vid='output_video';
            param5_download_link='download';
            param6_status_div='status';
    
            RedGenerateVideo(param1_input_video_id,param2_masked_canvas,param3_progressbar,param4_output_vid,param5_download_link,param6_status_div
            );
},false);
</script>
<script>
 function savedownload()
 {			   
			   var User_Id=document.getElementById('User_Id').value;	
			   var Video_Id=document.getElementById('Video_Id').value;
			   var Image_Path=document.getElementById('Image_Path').value;
			   $.ajax({
                   url:'downloads.php',
                   type:"post",    
                   data:{User_Id:User_Id,Video_Id:Video_Id,Image_Path:Image_Path},

                   success:function(data){
						 
                     }
                });
 }       
</script>
</body>
</html>
 
  
	