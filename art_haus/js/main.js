function _(x){
		return document.getElementById(x);
	}
$(document).ready(function(){
	
	// var uid_div = _("user_id");
// 	var uid = uid_div.innerHTML;
// 	$("#submit_status").click(function(){
// 		var my_status = _("stat_box").value;
// 		$('#stat_box').val(' ');
//
// 		$.ajax({
// 			url:"update_status.php",
// 			async:true,
// 			type: "POST",
// 			data:{ uid: uid,
// 				stat: my_status
// 			},
// 			success: function(data){
// 				_("status_status").innerHTML = "your status has been updated!";
// 				_("get_stats").innerHTML = data;
//
// 			}
// 		});
// 	});
	
	
});



function emptyElement(x){
	_(x).innerHTML = "";
}

function new_skill(id,link){
	_(id).style.display = "block";
	emptyElement(link);
	
}
	function ajaxObj( meth, url ) {
		var x = new XMLHttpRequest();
		x.open( meth, url, true );
		x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		return x;
	}
	
	function ajaxReturn(x){
		if(x.readyState == 4 && x.status == 200){
		    return true;	
		}
	}
	
	function create_status(){
		_("status_space").style.display = "block";
		
	}
	
	function post_status(){
		var u = _("status_box").value;
		var ajax = ajaxObj("POST", "profile.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            // _("get_stats").appendChild(u);
				var get_stats = _("get_stats");
				var new_stats = document.createElement('div');
				var br = document.createElement('br');
				new_stats.innerHTML=u;
				while(new_stats.firstChild){
					new_stats.insertBefore(get_stats.firstChild, new_stats.firstChild);
					// get_stats.appendChild(new_stats.firstChild);
					get_stats.appendChild(br);
					get_stats.appendChild(br);
					get_stats.appendChild(br);
				}
				
	        }
        }
        ajax.send("x_status="+u);
		
	}