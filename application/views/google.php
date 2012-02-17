<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta charset="utf-8">
<meta name="description" content="" />  
<meta name="keywords" content="" />

	<title>Google Analytics</title>
<!-- CSS Files -->
<link type="text/css" href="bar_graph/base.css" rel="stylesheet" />
<link type="text/css" href="bar_graph/BarChart.css" rel="stylesheet" />

<!--[if IE]><script language="javascript" type="text/javascript" src="../../Extras/excanvas.js"></script><![endif]-->

<!-- JIT Library File -->
<script language="javascript" type="text/javascript" src="bar_graph/jit.js"></script>

<!-- Example File -->
<script language="javascript" type="text/javascript" src="bar_graph/bar_graph.js"></script>
<script src="bar_graph/domready.js" type="application/javascript"></script>
<script type="application/javascript">
    DomReady.ready(function() {
    	var json = <?php echo $json;?>;
    	init(json);
    	document.getElementById('loader').style.display = 'none';
    	document.getElementById('updated_time').innerHTML = json.updated;
    });
</script>
</head>
	
	<body>
<!--
	<table style="margin-top:20px;">
		<tr>
		  <th>Browser &amp; Browser Version</th>
		  <th>Pageviews</th>
		  <th>Visits</th>
		</tr>
		<?php
		foreach($results as $result):
		?>
		<tr>
		  <td><?php echo $result->getBrowser() ?></td>
		  <td><?php echo $result->getPageviews() ?></td>
		  <td><?php echo $result->getVisits() ?></td>
		</tr>
		<?php
		endforeach
		?>
		</table>
		
		<table style="margin-top:20px;">
		<tr>
		  <th>Total Results</th>
		  <td><?php echo $total_views; ?></td>
		</tr>
		<tr>
		  <th>Total Pageviews</th>
		  <td><?php echo $total_pageviews; ?>
		</tr>
		<tr>
		  <th>Total Visits</th>
		  <td><?php echo $total_visits; ?></td>
		</tr>
		<tr>
		  <th>Results Updated</th>
		  <td><?php echo $updated; ?></td>
		</tr>
		</table>
-->
		
		
		<div id="container">

<div id="left-container">



        <div class="text">
        <h4>
Google Analytics Bar Chart   
        </h4> 

            A static vertical Bar Chart example with gradients. The Bar Chart displays tooltips when hovering the stacks. <br /><br />
            Click the Update button to update the JSON data.
            
        </div>
        <ul id="id-list"></ul>
        <a id="update" href="#" class="theme button white">Update Data</a>
		<div id="loader" style="margin-left: 70px;"><img src="/resources/img/ajax_loader.gif"/></div>
		<div class="text"><p>Lasted Updated: </p><span id="updated_time"></span></div>
           
</div>

<div id="center-container">
    <div id="infovis"></div>    
</div>

<div id="right-container">

<div id="inner-details"></div>

</div>

<div id="log"></div>
</div>	
	</body>

</html>
