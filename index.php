<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
	<link rel="stylesheet" href="./index.css"/>
	<script src="next-previous.js"></script>
	<title>Model Viewer</title>
	<script>
		function model(src,pause_array,image_array,instruction_array){
			console.log(src,pause_array,image_array,instruction_array);
			var modelSrc = src;
			var pauseTimes = pause_array;
			var clipImages = image_array;
			var instructionsText = instruction_array;
			localStorage.setItem("modelSrc",modelSrc);
			localStorage.setItem("pauseTimes",pauseTimes);
			localStorage.setItem("clipImages",clipImages);
			localStorage.setItem("instructionsText",instructionsText);
			localStorage.setItem("start",true);
			return false;
		}
	</script>
  </head>
  <body>
		<div class="swiper-container">
			<div class="swiper-wrapper">
				<?php
					$pause_time = file_get_contents('./Evobi-admin/pause_times.txt');
					$image_clip = file_get_contents('./Evobi-admin/clip_images.txt');
					$title_image = file_get_contents('./Evobi-admin/title.txt');
					$instruction_text = file_get_contents('./Evobi-admin/instructions_text.txt');
					$lines1 = explode("\n", $pause_time);
					$lines2 = explode("\n", $image_clip);
					$lines3 = explode("\n", $title_image);
					$lines4 = explode("\n", $instruction_text);
					for($index = 0; $index < count($lines1); $index++){
						$line1 = explode(",",$lines1[$index]);
						$line2 = explode(",",$lines2[$index]);
						$line3 = explode(",",$lines3[$index]);
						$line4 = explode(",",$lines4[$index]);
						$pause = "";
						$image = "";
						$instructions="";
						for($i = 2; $i < count($line1)-1; $i++){
							$pause = $pause.$line1[$i].",";
							$image = $image."'".$line2[$i]."',";
							$instructions = $instructions."'".$line4[$i]."',";
						}
						$pause = $pause.$line1[$i];
						$image = $image."'".$line2[$i]."'";
						$instructions = $instructions."'".$line4[$i]."'";
				?>
					<div class="swiper-slide" style="background-image:url(./Evobi-admin/assets/images/title/<?php echo $line3[1];?>)" onclick="model(<?php echo "'".$line1[0]."'"?>,<?php echo "[".$pause."]" ?>,<?php echo "[".$image."]" ?>,<?php echo "[".$instructions."]" ?>);window.location.href='model.html';"></div>
				<?php
					}
				?>	
			</div>
    <!-- Add Pagination -->
			<div class="swiper-pagination"></div>
		</div>	
		<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
		<script>
			var swiper = new Swiper('.swiper-container', {
			  effect: 'coverflow',
			  grabCursor: true,
			  centeredSlides: true,
			  slidesPerView: 'auto',
			  coverflowEffect: {
				rotate: 50,
				stretch: 0,
				depth: 100,
				modifier: 1,
				slideShadows: true,
			  },
			  pagination: {
				el: '.swiper-pagination',
			  },
			});
		</script>
  </body>
</html>	