<?php
if($_FILES['profile_pic']['size']>0){
	
	if($_FILES['profile_pic']['size']<=153600){
		if(move_uploaded_file($_FILES['profile_pic']['tmp_name'], "images/".$_FILES['profile_pic']["name"])){
			?>
			<script>
			parent.document.getElementById("upload_message").innerHTML="";
			parent.document.getElementById("profile_pic").value="";
			window.parent.updatepicture("<?php echo 'images/'.$_FILES['profile_pic']["name"];?>");
			
			</script>
			<?php
			
		}else{
			//the upload failed
			?>
			<script>
			parent.document.getElementById("upload_message").innerHTML = "<font color='white'> Your upload failed.";
			</script>
			<?php
		}
	}else{
		//the file is too big
		?>
		<script>
		parent.document.getElementById("upload_message").innerHTML = "<font color='white'> Your file is larger than 150KB. Please pick another picture";
		</script>
		<?php
	}
}


?>