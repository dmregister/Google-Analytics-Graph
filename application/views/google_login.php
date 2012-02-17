<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta charset="utf-8">
<meta name="description" content="" />  
<meta name="keywords" content="" />

	<title>Google Analytics</title>
<!-- CSS Files -->
<link type="text/css" href="<?php echo base_url();?>bar_graph/base.css" rel="stylesheet" />
<link type="text/css" href="<?php echo base_url();?>bar_graph/BarChart.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo base_url()?>bar_graph/json_parse.js"></script>
<script type="text/javascript">
function login(){	
	var xmlHttpReq = false;
    var self = this;
    var params = "username="+document.getElementById("username").value+"&password="+document.getElementById("password").value;
    // Mozilla/Safari
    if (window.XMLHttpRequest) {
        self.xmlHttpReq = new XMLHttpRequest();
    }
    // IE
    else if (window.ActiveXObject) {
        self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
    }
    self.xmlHttpReq.open('POST', 'http://davidmregister.com/google/login_submit', true);
    self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    self.xmlHttpReq.onreadystatechange = function() {
    	if (self.xmlHttpReq.readyState == 4) {
    		var obj = JSON.parse(self.xmlHttpReq.response);
    		
    		if(obj.error == true){
	    		var errors = self.xmlHttpReq.response.split(".");
	    		// This is the <ul id="myList"> element that will contains the new elements
				var container = document.getElementById('form_errors');
				for(var i=0;i<errors.length-1;i++){
					// Create a new <li> element for to insert inside <ul>
					var new_element = document.createElement('li');
					new_element.innerHTML = obj[i];
					container.insertBefore(new_element, container.firstChild); 		
	    		}
        	}else{
        		
        		var select = document.getElementById('user_id_list');
        		
        		for(index in  obj){
        			if(obj[index] != false){
	        			var option = obj[index].split("/");
						select.options[select.options.length] = new Option(option[1], option[0]);  
					} 			
	    		}
        	}	
        }
    }
    self.xmlHttpReq.send(params);
}
</script>
</head>
	
	<body>	
		
<div id="container">
		<ul id="form_errors"></ul>
		<?php echo validation_errors(); ?>
		<form action="/google/login_submit" method="POST">
			<p><label for="username">Username</label><br />
			<input type="text" name="username" id="username"/></p>
			<p><label for="password">Password</label><br />
			<input type="password" name="password" id="password"/></p>
			<p><input type="submit" value="Get Analytics" onclick="login();return false;"/></p>
		</form>
		<div id="user_id">
			<form action="http://davidmregister.com/google/logged_in" method="post">
				<select id="user_id_list" name="user_id_list"></select>
				<input type="submit" value="Submit"/>
			</form>
			
			<p>If you do not have a Google Analytics account, view mine: <a href="<?php echo site_url();?>/google/no_account">My Account</a></p>
		</div>
		
</div>	
	</body>

</html>
