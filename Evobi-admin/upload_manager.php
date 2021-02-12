<?php
   if(isset($_POST['rearrange_0'])){
	   $rearrange = 0;
	   if(filesize('pause_times.txt')>0 && filesize('clip_images.txt')>0 && filesize('title.txt')>0){
			$string1 = file_get_contents('pause_times.txt');
			$lines1 = explode("\n", $string1);
			$string2 = file_get_contents('clip_images.txt');
			$lines2 = explode("\n", $string2);
			$string3 = file_get_contents('title.txt');
			$lines3 = explode("\n", $string3);
			$string4 = file_get_contents('instructions_text.txt');
			$lines4 = explode("\n", $string4);
			$content1 = array_fill(0,count($lines1),"");
			$content2 = array_fill(0,count($lines1),"");
			$content3 = array_fill(0,count($lines1),"");
			$content4 = array_fill(0,count($lines1),"");
			if(count($lines1)==count($lines2) && count($lines2)==count($lines3) && count($lines3)==count($lines4) ){
				$no_of_files = count(scandir('assets/gltf/'))-2;
				for($i = 0; $i < $no_of_files; $i++){
					for($index = 0; $index < count($lines1); $index++){
						$line1 = explode(",",$lines1[$index]);
						$line2 = explode(",",$lines2[$index]);
						$line3 = explode(",",$lines3[$index]);
						$line4 = explode(",",$lines4[$index]);
						if(isset($_POST['position'.$i])){
							if(empty($_POST['position'.$i])){
								echo '<script>alert("Please fill all the slots. Dont leave it blank"); window.location = "index.php";</script>';
								break;
							}
							else if(in_array($_POST['rearrange_'.$i], $line1)){
								if($_POST['position'.$i]-1!=$index){
									$rearrange = 1;
								}
								$line1 = implode(",",explode(",",$lines1[$index]));
								$line2 = implode(",",explode(",",$lines2[$index]));
								$line3 = implode(",",explode(",",$lines3[$index]));
								$line4 = implode(",",explode(",",$lines4[$index]));
								$content1[$_POST['position'.$i]-1] = $line1;
								$content2[$_POST['position'.$i]-1] = $line2;
								$content3[$_POST['position'.$i]-1] = $line3;
								$content4[$_POST['position'.$i]-1] = $line4;
							}
						}
					}
				}
				
			}else{
				echo '<script>alert("Check in all txt files. entries for particular gltf/glb file is missing"); window.location = "index.php";</script>';
			}
			if($rearrange == 1){
				$rearranged_content1 = implode("\n",$content1);
				$rearranged_content2 = implode("\n",$content2);
				$rearranged_content3 = implode("\n",$content3);
				$rearranged_content4 = implode("\n",$content4);
				$myfile1 = fopen('pause_times.txt', "w") or die("Unable to open file!");
				$myfile2 = fopen('clip_images.txt', "w") or die("Unable to open file!");
				$myfile3 = fopen('title.txt', "w") or die("Unable to open file!");	
				$myfile4 = fopen('instructions_text.txt', "w") or die("Unable to open file!");	
				fwrite($myfile1, $rearranged_content1);
				fwrite($myfile2, $rearranged_content2);
				fwrite($myfile3, $rearranged_content3);
				fwrite($myfile4, $rearranged_content4);
				fclose($myfile1);
				fclose($myfile2);
				fclose($myfile3);
				fclose($myfile4);
				
				echo '<script>alert("Rearranged!!"); window.location = "index.php";</script>'; 
			}
			else{
				echo '<script>alert("There is nothing to rearrange"); window.location = "index.php";</script>'; 
			}	
	   }
   }
   else if(isset($_POST['deletegltf'])){
	    $deleted=false;
		if(filesize('pause_times.txt')>0 && filesize('clip_images.txt')>0 && filesize('instructions_text.txt')>0 && filesize('title.txt')>0){
			$string1 = file_get_contents('pause_times.txt');
			$lines1 = explode("\n", $string1);
			$string2 = file_get_contents('clip_images.txt');
			$lines2 = explode("\n", $string2);
			$string4 = file_get_contents('instructions_text.txt');
			$lines4 = explode("\n", $string4);
			if(count($lines1)==count($lines2) && count($lines2)==count($lines4)){
				for($index = 0; $index < count($lines1); $index++){
					$line1 = explode(",",$lines1[$index]);
					$line2 = explode(",",$lines2[$index]);
					$line4 = explode(",",$lines4[$index]);
					if(in_array($_POST['deletegltf'], $line1)){
						$deleted=true;
						array_splice($lines1,$index,1);
						array_splice($lines2,$index,1);
						array_splice($lines4,$index,1);
						$myfile1 = fopen('pause_times.txt', "w") or die("Unable to open file!");
						$myfile2 = fopen('clip_images.txt', "w") or die("Unable to open file!");
						$myfile4 = fopen('instructions_text.txt', "w") or die("Unable to open file!");
						$content1 = implode("\n",$lines1);
						$content2 = implode("\n",$lines2);
						$content4 = implode("\n",$lines4);
						fwrite($myfile1, $content1);
						fwrite($myfile2, $content2);
						fwrite($myfile4, $content4);
						fclose($myfile1);
						fclose($myfile2);
						fclose($myfile4);
						echo 'success'; 
						break;
					}	
				}	
				if(!$deleted){
					  delete_file_and_folder($_POST['deletegltf']);
				}	
			}
			else{
				echo 'failed'; 
			}	
	   }
	   else if(filesize('title.txt')>0){
		   delete_file_and_folder($_POST['deletegltf']);
	   }
       else{
		   echo 'nothing'; 
	   } 	    
   }
   else if(isset($_FILES['file'])){
	  $str = ''; 
	  $files = array_filter($_FILES['file']['name']);
	  $total_count = count($files);
	  $title_tmp = explode('.',$_FILES['title']['name']);
	  $title_file_ext=strtolower(end($title_tmp));
	  $gltfExtensions= array("gltf","glb");
	  $imageExtensions= array("jpg");
	  $target_dir = 'assets/';
	  $uploaded_gltf = null;
	  $uploaded_gltf_index = null;
	  $imgCount = 0;
	  for($i=0 ; $i < $total_count ; $i++){
		if ((strpos($files[$i],".jpg")!== false)){
			$imgCount = $imgCount + 1;
		}
		else if (((strpos($files[$i],".glb")!== false)||(strpos($files[$i],".gltf")!== false)) && $_FILES['file']['size'][$i]<103809024){
		  $uploaded_gltf = $files[$i];
		  $uploaded_gltf_index = $i;
		  $str = $str.$files[$i].',';
		  break;
		}
	  }
	  if($uploaded_gltf== null){
		  echo '<script>alert("couldn\'t find gltf/glb file"); window.location = "index.php";</script>';
	  }
	  else{
		  if(!file_exists('assets/images/'.$uploaded_gltf)){
				if($imgCount==0){
					echo '<script>alert("No image file corresponding to gltf/glb file"); window.location = "index.php";</script>';
				}
				else if(!file_exists('assets/images/title/'.$_FILES['title']['name']) && in_array($title_file_ext,$imageExtensions)=== true){
					move_uploaded_file($_FILES['title']['tmp_name'],'assets/images/title/'.$_FILES['title']['name']);
					$str = $str.$_FILES['title']['name'];
					move_uploaded_file($_FILES['file']['tmp_name'][$uploaded_gltf_index],$target_dir.'/gltf/'.$uploaded_gltf);
					mkdir('assets/images/'.$uploaded_gltf, 0777, true);	
				}
				else if($_FILES["title"]["error"] == 4 ){
					echo '<script>alert("Please upload a jpg file for title image"); window.location = "index.php";</script>'; 
				}
				else{
					echo '<script>alert("Sorry, either jpg file in title folder already exist or extension isn\'t jpg."); window.location = "index.php";</script>';
				}
		  }
		  for( $i=0 ; $i < $total_count ; $i++ ) {
			  $file_name = $files[$i];
			  $file_size = $_FILES['file']['size'][$i];
			  $file_tmp = $_FILES['file']['tmp_name'][$i];
			  $file_type = $_FILES['file']['type'][$i];
			  $tmp = explode('.',$files[$i]);
			  $file_ext=strtolower(end($tmp));
			  
			  if($_FILES["file"]["error"][0] == 4 ){
				 echo '<script>alert("Please upload a file"); window.location = "index.php";</script>'; 
			  }
			  else if((in_array($file_ext,$gltfExtensions)=== true)){
				  continue;
			  }
			  else if(in_array($file_ext,$imageExtensions)=== true && $file_size<103809024){
				 if(file_exists($target_dir.'images/'.$uploaded_gltf.'/'.basename($file_name))) {
					echo '<script>alert("Sorry, jpg file already exist."); window.location = "index.php";</script>';
				 }
				 else{
					$status = move_uploaded_file($file_tmp,$target_dir.'images/'.$uploaded_gltf.'/'.$file_name);
					if($status){
						upload_to_txt_file('title_image',$str,'title.txt');
						echo '<script>alert("Uploaded succesfully.");  window.location = "index.php";</script>';
					}	
				 }
			  }
			  else if(in_array($file_ext,$imageExtensions)=== false){
				 echo '<script>alert("Failed to upload. image extension not matching"); window.location = "index.php";</script>';
			  }
			  else if($file_size>=103809024){
				 echo '<script>alert("Failed to upload. Exceeded the limit !"); window.location = "index.php";</script>';
			  }
			  else{
				  echo '<script>alert("No image file corresponding to gltf/glb file"); window.location = "index.php";</script>';
			  }
		  }
		  
	  }
   }
   else if(isset($_POST['hidden_pause_entries'])){
	  upload_to_txt_file('hidden_pause_entries','pause','pause_times.txt');
	  upload_to_txt_file('hidden_pause_entries','instruction','instructions_text.txt');
   }
   else if(isset($_POST['hidden_image_entries'])){
	  upload_to_txt_file('hidden_image_entries','image','clip_images.txt');
	  upload_to_txt_file('title_image',null,'title.txt');
   }
   function upload_to_txt_file($entry,$type,$txtFile){
	  $str = $type;
	  $flag = 0;
	  if($type==null){
		$str = $_POST['hidden_gltf_file'].','.$_POST[$entry];  
	  }
      else if($entry!='title_image'){	
		$str = $_POST['hidden_gltf_file'].','.$_POST[$entry];
		for($index = 0;$index<$_POST[$entry];$index++){
			if(!empty($_POST[$type.($index+1).''])){
				$str = $str.",".$_POST[$type.($index+1).''];
				$flag = 0;
			}  
			else{
				$flag = 1;
				echo '<script>alert("Please fill all the slots. Dont leave it blank"); window.location = "index.php";</script>';
				break;
			}
		}
	  }	  
	  if($flag==0){
		$content = "";  
		$replace = false;
		if(filesize($txtFile)>0){
			$string = file_get_contents($txtFile);
			$lines = explode("\n", $string);
			for($index = 0; $index < count($lines); $index++){
				$line = explode(",",$lines[$index]);
				if($entry!='title_image'){
					if(in_array($_POST['hidden_gltf_file'], $line)){
						$replace = true;
						unset($line);
						$line = array();
						array_push($line,$str); 
						$lines[$index] = implode(",",$line);
						$myfile = fopen($txtFile, "w") or die("Unable to open file!");
						$content = implode("\n",$lines);
						fwrite($myfile, $content);
						fclose($myfile);
						break;
					}	
				}
				else{
					$val = explode(",",$str);
					if(in_array($val[0], $line)){
						$replace = true;
						unset($line);
						$line = array();
						array_push($line,$str); 
						$lines[$index] = implode(",",$line);
						$myfile = fopen($txtFile, "w") or die("Unable to open file!");
						$content = implode("\n",$lines);
						fwrite($myfile, $content);
						fclose($myfile);
						break;
					}	
				}
			}
			if($replace==false){
				$myfile = fopen($txtFile, "r") or die("Unable to open file!");
				$content = fread($myfile,filesize($txtFile));
				fclose($myfile);
				$myfile = fopen($txtFile, "w") or die("Unable to open file!");
				$str = $content."\n".$str;
				fwrite($myfile, $str);
				fclose($myfile);
				echo '<script>alert("Uploaded successfully"); window.location = "index.php";</script>';
			}
			else{
				echo '<script>alert("Uploaded successfully"); window.location = "index.php";</script>';
				$replace = false;
			}
		}
		else{
			$myfile = fopen($txtFile, "w") or die("Unable to open file!");
			$str = $content.$str;
			fwrite($myfile, $str);
			fclose($myfile);
			echo '<script>alert("Uploaded successfully");  window.location = "index.php"; </script>';
		}
	  }
   }
   function delete_file_and_folder($delete){
		$string3 = file_get_contents('title.txt');
		$lines3 = explode("\n", $string3);
		unlink('assets/gltf/'.$delete);
		$files = glob('assets/images/'.$delete.'/*'); // get all file names
		foreach($files as $file){ // iterate files
			if(is_file($file)) {
				unlink($file); // delete file
			}
		}
		for($index = 0; $index < count($lines3); $index++){
			$line3 = explode(",",$lines3[$index]);
			if(in_array($delete, $line3)){
				$deleted=true;
				unlink('assets/images/title/'.$line3[1]);
				array_splice($lines3,$index,1);
				$myfile3 = fopen('title.txt', "w") or die("Unable to open file!");
				$content3 = implode("\n",$lines3);
				fwrite($myfile3, $content3);
				fclose($myfile3);
				break;
			}	
		}	
		if(!$deleted){
			echo 'nothing';
		}	
		rmdir('assets/images/'.$delete);
		echo 'success';
   }
?>