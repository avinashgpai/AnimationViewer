var pause_entries = ["<center style='display:inline-flex;flex-flow:row;'><div style='padding:0 20px;width:20px;'><label>1</label></div><input style='width:100px' id='pause1' type='number' min='0' step='0.01' name='pause1'/></center>"];
var instruction_entries = ["<center style='display:inline-flex;flex-flow:row;'><div style='padding:0 20px;width:20px;'><label>1</label></div><input style='width:100px' id='instruction1' type='text' name='instruction1'/></center>"];
var image_entries = ["<center style='display:inline-flex;flex-flow:row;'><div style='padding:0 20px;width:20px;'><label>1</label></div><select style='width:100px' id='image1'  name='image1'></select></center>"];

var options = "";

function gltf_selected_name(gltf){
	options = "";
	document.querySelectorAll("[id='gltf_name']")[0].innerHTML=gltf;
	document.querySelectorAll("[id='gltf_name']")[1].innerHTML=gltf;
	document.querySelectorAll("[id='hidden_gltf_file']")[0].value=gltf;
	document.querySelectorAll("[id='hidden_gltf_file']")[1].value=gltf;
	document.getElementById("set_pause_entry").disabled=false;
	document.getElementById("set_image_entry").disabled=false;
	document.getElementById("pausetime_instruction_submit").disabled = false;
	document.getElementById("image_title_submit").disabled = false;
	document.getElementById("set_pause_entry").value = 1;
	document.getElementById("set_image_entry").value = 1;
	document.getElementById("pause_time").innerHTML = "<center style='display:inline-flex;flex-flow:row;'><div style='padding:0 20px;width:20px;'><label>1</label></div><input style='width:100px' id='pause1' type='number' min='0' step='0.01' name='pause1'/></center>";
	document.getElementById("instructions_text").innerHTML = "<center style='display:inline-flex;flex-flow:row;'><div style='padding:0 20px;width:20px;'><label>1</label></div><input style='width:100px' id='instruction11' type='text' name='instruction1'/></center>";
	document.getElementById("clip_image").innerHTML = "<center style='display:inline-flex;flex-flow:row;'><div style='padding:0 20px;width:20px;'><label>1</label></div><select style='width:100px' id='image1' name='image1'></select></center>";
	document.getElementById("hidden_pause_entries").value = pause_entries.length;
	document.getElementById("hidden_image_entries").value = image_entries.length;
	$.ajax({
		url: "files.php",
		type: "POST",
		data: {
			gltfname:gltf				
		},
		cache:false,
		success: function(dataResult){
			var response = dataResult.split("=")[1].split("[")[1].split("]")[0].split(",");
			for(let i = 0; i<response.length; i++){
				options = options + "<option value='"+response[i].split('"')[1]+".jpg'>"+response[i].split('"')[1]+".jpg</option>";
			}
			document.getElementsByTagName("select")[0].innerHTML = options;
			if(dataResult.statusCode==200){
				console.log('added');		
			}
			else if(dataResult.statusCode==201){
			   alert("Error occured !");
			}		
		}
	});
}

function delete_gltf(gltf){
	$.ajax({
		url: "upload_manager.php",
		type: "POST",
		data: {
			deletegltf:gltf				
		},
		cache:false,
		success: function(dataResult){
			if(dataResult=="success"){
				alert("Deleted successfully");
				location.reload();	
			}
			else if(dataResult=="nothing"){
			   alert("There is nothing to delete");
			}
			else if(dataResult=="failed"){
			   alert("Cannot operate delete opertation. Check entries in pause_times.txt and clip_images.txt.");
			}	
			else{
				alert("looser");
			}
		}
	});
}

function set_pause_instruction_entries(event){
	pause_entries = ["<center style='display:inline-flex;flex-flow:row;'><div style='padding:0 20px;width:20px;'><label>1</label></div><input style='width:100px' id='1' type='number' min ='0' step='0.01' name='pause1'/></center>"];
	instruction_entries = ["<center style='display:inline-flex;flex-flow:row;'><div style='padding:0 20px;width:20px;'><label>1</label></div><input style='width:100px' id='instruction1' type='text' name='instruction1'/></center>"];
	
	for (let i = 1; i<event.target.value; i++){
		pause_entries[i] = "<center style='display:inline-flex;flex-flow:row;'><div style='padding:0 20px;width:20px;'><label>"+(i+1)+"</label></div><input style='width:100px' id='pause"+(i+1)+"' type='number' min='0' step='0.01' name='pause"+(i+1)+"'/></center>";
		instruction_entries[i] = "<center style='display:inline-flex;flex-flow:row;'><div style='padding:0 20px;width:20px;'><label>"+(i+1)+"</label></div><input style='width:100px' id='instruction"+(i+1)+"' type='text' name='instruction"+(i+1)+"'/></center>";
	}
	document.getElementById("pause_time").innerHTML = pause_entries.join('');
	document.getElementById("instructions_text").innerHTML = instruction_entries.join('');
	document.getElementById("hidden_pause_entries").value = pause_entries.length;
}

function set_image_title_entries(event){
	image_entries = ["<center style='display:inline-flex;flex-flow:row;'><div style='padding:0 20px;width:20px;'><label>1</label></div><select style='width:100px' id='1'  name='image1'></select></center>"];
	document.getElementsByTagName("select")[0].innerHTML = options;
	for (let i = 1; i<event.target.value; i++){
		image_entries[i] = "<center style='display:inline-flex;flex-flow:row;'><div style='padding:0 20px;width:20px;'><label>"+(i+1)+"</label></div><select style='width:100px' id='image"+(i+1)+"'  name='image"+(i+1)+"'></select></center>";
	}
	document.getElementById("clip_image").innerHTML = image_entries.join('');
	for(let i =0; i<event.target.value;i++){
		document.getElementsByTagName("select")[i].innerHTML = options;
	}
	document.getElementById("hidden_image_entries").value = image_entries.length;
}

function img_update1(id){
	document.getElementById("delete"+id).src = './assets/images/delete_hover.jpg';
}

function img_update2(id){
	document.getElementById("delete"+id).src = './assets/images/delete.jpg';
}

