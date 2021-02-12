var files = <?php $out = array();
	if(isset($_POST['gltfname'])){
		foreach (glob('assets/images/'.$_POST['gltfname'].'/*') as $filename) {
			$p = pathinfo($filename);
			$out[] = $p['filename'];
		}
	}	
	echo json_encode($out); ?>;