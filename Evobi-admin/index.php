<!DOCTYPE html>
<html>
	<head>
		<title>Model Viewer</title>
		<link rel="stylesheet" href="./index.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
		<script src="./admin.js"></script>
	</head>
	<body>
	  <div class="upload">
	    <div style="margin-left:15px;">
		  <form action="upload_manager.php" method="POST" enctype="multipart/form-data">
			<center style="padding-top:15px;display:inline-flex;flex-flow:column;align-items:center;border:1px solid black;border-radius:5px;">
				 <h4>Column 1</h4>
				 <div class="wrapper">
					 <center>Upload .gltf/.glb /.jpg files</center>	
					 <input id="fileInput" type="file" name="file[]" multiple="multiple"/>	 
				 </div>
				 <div class="wrapper">
					 <center>Upload a .jpg file indicating animation in slider</center>
					 <input id="fileInput" type="file" name="title"/> 
				 </div>	 
				 <input style="margin:18px 0;" id="submit" type="submit" value="Upload"/>
			</center>	 
		  </form>
		</div>  
		<div class="upload_clip_images">
			<center><h4>Column 2</h4></center>
			<form action="upload_manager.php" method="POST">
			  <label>No.of clip images : </label><input id="set_image_entry" onchange="set_image_title_entries(event);" style="width:50px;" type="number" min="1" name="clip_image_entries" disabled />
			  <center id="gltf_name">No gltf/glb file chosen</center>
			  <div id="clip_image">
			  </div>
			  <center id="title_image">
			    <div style="margin-bottom:15px;">Set title image</div>
				<select name="title_image">
					<?php 
					  $dir = 'assets/images/title/';
					  $files = scandir($dir);
					  for($index = 2;$index<count($files);$index++){
						echo "<option value='".$files[$index]."'>".$files[$index]."</option>";  
					  }
					?>
				</select>
			  </center>
			  <input id="hidden_gltf_file" type="hidden" name="hidden_gltf_file"/>
			  <input id="hidden_image_entries" type="hidden" name="hidden_image_entries"/>
			  <center><input style="margin-top:25px;" id="image_title_submit" type="submit" value="Upload" disabled /></center>
			</form>  
			<script src='files.php'></script>
		</div>		
		<div class="upload_pause_time">
			<center><h4>Column 3</h4></center>
			<form action="upload_manager.php" method="POST">
			  <label>No.of pause & instr: </label><input id="set_pause_entry" onchange="set_pause_instruction_entries(event);" style="width:40px;" type="number" min="1" name="pause_time_entries" disabled />
			  <center id="gltf_name">No gltf/glb file chosen</center>
			  <div id="pause_time">
			  </div>
			  <div id="instructions_text">
			  </div>
			  <input id="hidden_gltf_file" type="hidden" name="hidden_gltf_file"/>
			  <input id="hidden_pause_entries" type="hidden" name="hidden_pause_entries"/>
			  <center><input style="margin-top:25px;" id="pausetime_instruction_submit" type="submit" value="Upload" disabled /></center>
			</form>  
		</div>		
	  </div>
	
	  <div class="files1">
		<form action="upload_manager.php" method="POST">
			<center>Uploaded gltf/glb files</center>
			<center class="file_display">
			
				<?php 
				  $dir = 'assets/gltf/';
				  $files = scandir($dir);
				  $gltf_files = array();
				  $max_gltf_already_saved = 0;
				  for($index = 2;$index<count($files);$index++){
					$string = file_get_contents("clip_images.txt");
					$lines = explode("\n", $string);
					$found = 0;
					for($line_no = 0; $line_no < count($lines); $line_no++){
						$line = explode(",",$lines[$line_no]);
						if(in_array($files[$index], $line)){
							$max_gltf_already_saved = $max_gltf_already_saved + 1;
							$files[$index] = $line_no.'-'.$files[$index];
							$found = 1;
						}	
					}
					if($found==0){
						$files[$index] = 'null-'.$files[$index];
					}
					array_push($gltf_files,$files[$index]);   
				  }
				  
				  for ($index = 0;$index<count($gltf_files);$index++){
					  $destructure_file = explode('-',$gltf_files[$index]);
					  if($destructure_file[0]=='null'){
						 echo "<center class='row'><input class='index' type='text' value='null' name='position".$index."' disabled/><input type='hidden' value='$destructure_file[1]' name='rearrange_".$index."'/><div class='file' onclick='gltf_selected_name(`$destructure_file[1]`);'>".$destructure_file[1]."</div><img src='./assets/images/delete.jpg' class='delete' id='delete".$index."' onmouseenter='img_update1(".$index.")'  onmouseleave='img_update2(".$index.")' onclick='delete_gltf(`$destructure_file[1]`);'/></center>";   
					  }
					  else{
						 echo "<center class='row'>".($destructure_file[0]+1)."<input class='index' type='number' value='".($destructure_file[0]+1)."' min='1' max='".($max_gltf_already_saved)."' name='position".$index."'/><input type='hidden' value='$destructure_file[1]' name='rearrange_".$index."'/><div class='file' onclick='gltf_selected_name(`$destructure_file[1]`);'>".$destructure_file[1]."</div><img src='./assets/images/delete.jpg' class='delete' id='delete".$index."' onmouseenter='img_update1(".$index.")'  onmouseleave='img_update2(".$index.")' onclick='delete_gltf(`$destructure_file[1]`);'/></center>";   
					  } 
				  }
				?>
			
			</center>	
			<center><input id="rearrange" type="submit" value="Rearrange"/></center>
		</form>	
	  </div>
	  
	  <div class="instructions">
		<h3>Instructions</h3>
		<ul>
			<li>In column 1 
			     <div>1st row, multiple file, only one gltf/glb, any no.of jpg and sorted such that gltf/glb must be on top. 2nd row, only one jpg file can be chosen. Both 1st, 2nd row must be chose before upload.</div>		
			</li>
			<li>In column 2 
			     <div>Both 1st, 2nd row must be chose before upload.</div>		
			</li>
			<li>In column 3 
			     <div>All slots must be filled before upload.</div>		
			</li>
			<li>While rearranging no two inputs must have same values.</li>
		</ul>
	  </div>
	 
	</body>
</html>