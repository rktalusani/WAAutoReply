<?php
?>

<html>
<head>
<link rel="stylesheet" href="dist/css/volta.css"> 
<link rel="stylesheet" href="dist/css/addons/volta-prism.css"> 
<style>
#topbanner{
width:100%;
height:80px;
background: #211f1f;
}


#extmgr{
margin-top:10px;
margin-left:5px;
width: 1200px;
background:#ece8e8;
}
#uploadbox{
 position: absolute;
    background: #b2beca;
    width: 400px;
    height: 200px;
    z-index: 200;
    margin-left: 400px;
    margin-top: 200px;
    border-width: 1px;
    border-style: solid;
    visibility: hidden;
}
#newnumberbox{
 position: absolute;
    background: #b2beca;
    width: 400px;
    height: 200px;
    z-index: 200;
    margin-left: 400px;
    margin-top: 200px;
    border-width: 1px;
    border-style: solid;
    visibility: hidden;
}

#bottom{
	position:absolute;
	bottom:5px;
	right:10px;
}
</style>
<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>
  <script src="dist/js/volta.js"></script>

</head>
<body>
<div id="bottom">
	<a href="help.html" target="_new">Help!</a>
</div>
<div id="topbanner">
	<div style="position:absolute">
		<!--img src="" style="height:80px"/-->
	</div>
	<div style="font-size:30px;margin-right: 50px; margin-top:30px;color:white;float:right">
		AutoReply configuration
	</div>
</div>
<div id="uploadbox">
	<form id="uploadform" enctype="multipart/form-data" >
		<br/><input type="file" name="keyfile" id="keyfile"/> 
		<br/><input type="submit" name="startupload" value="Upload"/>		
	</form>
</div>
<div id="newnumberbox">
	<form id="uploadform" enctype="multipart/form-data" >
		<br/>Enter a number: <input type="textbox" name="newnumber" id="newnumber"/> 
		<br/><input type="button" name="addnumber" value="Add number" onclick="addNewNumber()"/>		
	</form>
</div>

<div id="extmgr" class="Vlt-card">
	<div class="Vlt-card__header">
		<!-- Header content / -->
		<b>Auto reply management</b><br/><br/>
	</div>
	<div class="Vlt-card__content">
		<!-- Content / -->
		<div>Select a WhatsApp Number:
			<div class="Vlt-native-dropdown">
				<select id="waba" onChange="onWabaSelected()">
					<option value="None">Select a Number</option>
<?php
					foreach (glob("config/*.json") as $filename) {
						$number = str_replace("config/","",$filename);
						$number = str_replace(".json","",$number);
						echo '<option value="'.$number.'">'.$number.'</option>';
					}
?>
				</select>
			</div>
			&nbsp;&nbsp;<a href="javascript:showAddNumber()"> Add a Number </a>	
			<div class="Vlt-input">
				<label>AutoReply message </label><br/>
				<textarea  style="width:50%" name="message" placeholder="comma separated list of whilisted numbers"  rows="6" id="message"></textarea>
			</div>
			<div class="Vlt-form__element" style="width:50%">
				<label class="Vlt-label" for="appid">Application-ID</label>
				<div class="Vlt-input">
					<input type="text" id="appid" />
				</div>
			</div>
			<div>
				Private Key: <label id="pvtlabel">None</label> [<a href="javascript:showUploader()"> Change</a>]
			</div>

		</div>
		<div>
			<button class="Vlt-btn Vlt-btn--primary" onclick="saveConfig()">
				Save Configuration	
			</button>
		</div>
	</div>
</div>


</body>

<script>
function showAddNumber(){
	document.getElementById("newnumber").value="";
	document.getElementById("newnumberbox").style.visibility="visible";
}
function addNewNumber(){
	var number = document.getElementById("newnumber").value;
	document.getElementById("newnumberbox").style.visibility="hidden";
	if(number == ""){
		alert("enter valid number");
		return;
	}
	document.getElementById("waba").innerHTML += "<option value='"+number+"'>"+number+"</option>";

}
function onWabaSelected(){

	document.getElementById("message").value = "";
	document.getElementById("appid").value = "";
	document.getElementById("pvtlabel").innerHTML = "None";
	var waba = document.getElementById("waba").value;
	var url = "get_config.php?filename=config/"+waba+".json";
        $.get(url, function(data, status){
                try{
                        console.log(data);
                        var json = data;//JSON.parse(data);
			document.getElementById("message").value = json.message;
			document.getElementById("appid").value = json.appid;
			checkPvtKeyExists(waba);
                }
                catch (err){
                        console.log(err);
                }
        });
}

function checkPvtKeyExists(waba){
	var url = "check_pvt_key.php?waba="+waba;
        $.get(url, function(data, status){
                try{
                        console.log(data);
			if(data == "None"){
				document.getElementById("pvtlabel").innerHTML = "None";
			}
			else{
				document.getElementById("pvtlabel").innerHTML = "Exists";
			}
                }
                catch (err){
                        console.log(err);
                }
        });

}

function showUploader(){
	document.getElementById("uploadbox").style.visibility="visible";
}
function hideUploader(){
	document.getElementById("uploadbox").style.visibility="hidden";
}

function makeid() {
  var text = "";
  var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

  for (var i = 0; i < 5; i++)
    text += possible.charAt(Math.floor(Math.random() * possible.length));

  return text;
}


$( document ).ready(function() {
	var form = document.forms.namedItem("uploadform");
	form.addEventListener('submit', function(ev) {
	  
	  var oData = new FormData(form);
	  oData.append("waba", document.getElementById("waba").value);

	  var oReq = new XMLHttpRequest();
	  oReq.open("POST", "upload.php", true);
	  oReq.onload = function(oEvent) {
	    if (oReq.status == 200) {
	    } else {
	      console.log("Error " + oReq.status + " occurred when trying to upload your file");
	    }
	  };
	  oReq.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) {
			//alert("success");
			hideUploader();
			document.getElementById("pvtlabel").innerHTML = "Exists";
    	  	}
	  };
	  oReq.send(oData);
	  ev.preventDefault();
	}, false);

});

function saveConfig(){
	var message = document.getElementById("message").value;
	var appid = document.getElementById("appid").value;
	var waba = document.getElementById("waba").value;	
	var postdata = "message="+message+"&appid="+appid+"&waba="+waba;
	$.ajax({
		url : "saveconfig.php",
		type: "POST",
		data: postdata,
		success    : function(){
			alert("posted");
		}
    	});
}

</script>
</html>
