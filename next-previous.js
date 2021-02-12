var k=0;
var arr_pause_time = localStorage.getItem("pauseTimes").split(",");
var arr_curr_obj = localStorage.getItem("clipImages").split(",");
var arr_curr_instruction = localStorage.getItem("instructionsText").split(",");
var src = localStorage.getItem("modelSrc");
console.log(arr_pause_time,arr_curr_obj);

function load(){
	const modelViewer = document.querySelector('#paused-change-demo');
	modelViewer.src = "./Evobi-admin/assets/gltf/"+src;
	if(localStorage.getItem("start")===true){
		k=0;
		modelViewer.currentTime=0;
	}
	document.getElementById("object").src = "./Evobi-admin/assets/images/"+src+'/'+arr_curr_obj[k];
	document.getElementById("instructions").innerHTML= arr_curr_instruction[k];
	document.getElementById("name").innerHTML= arr_curr_obj[k].split(".")[0].split("_").join(" ");
	document.getElementById('steps').innerHTML=(k+1)+"/"+arr_pause_time.length;
	document.getElementById('prevCBtn').disabled=true;
	document.getElementById('nextCBtn').disabled=true;
	if(modelViewer.currentTime>=arr_pause_time[k]){
		modelViewer.pause();
		
		document.getElementById('prevCBtn').disabled=false;
		document.getElementById('nextCBtn').disabled=false;
		
		
		if(k==arr_pause_time.length-1){
			document.getElementById('nextCBtn').disabled=true;
		}
		if(k==0){
			document.getElementById('prevCBtn').disabled=true;
		}
	}
	localStorage.setItem("start",false);	
}

if(window.location.href=="http://localhost/animation/model.html"){
	var myVar = setInterval(load,10);
}

function play_prev(){
	const modelViewer = document.querySelector('#paused-change-demo');
	if(k-2<0){
		modelViewer.currentTime=0;
		k=k-1;
	}
	else{
		modelViewer.currentTime=arr_pause_time[k-2];
		k=k-1;
	}
	document.getElementById('prevCBtn').disabled=true;
	document.getElementById('nextCBtn').disabled=true;
    modelViewer.play();
	clearInterval(myVar);
	myVar = setInterval(load,10);
}

function play_next(){
	const modelViewer = document.querySelector('#paused-change-demo');
	k=k+1;
	document.getElementById('prevCBtn').disabled=true;
	document.getElementById('nextCBtn').disabled=true;
    modelViewer.play();
	clearInterval(myVar);
	myVar = setInterval(load,10);
	
}

function background_toggle(){
	if(document.getElementById("toggle").checked == true){
		document.getElementById("animation").style.backgroundColor = "#555151";
	}
	else{
		document.getElementById("animation").style.backgroundColor = "white";
	}
}