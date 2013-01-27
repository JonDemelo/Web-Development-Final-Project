<script type="text/javascript">
	$(document).ready(function() {
		
		var $datepicker = $('#datepicker');
		$datepicker.datepicker({
			    yearRange: "{$HTML['birth']}}:+0", 
				dateFormat: 'mm-dd-yy',
				showOn: "both",
				buttonImage: "images/calendar.gif",
				buttonImageOnly: false,
				changeMonth: true,
				changeYear: true,
				onSelect: function(dateText, inst) {createGraph();}
		}).datepicker("setDate",new Date());
		
	    $('.rollover').hover(function() {
	        var currentImg = $(this).attr('src');
	        $(this).attr('src', $(this).attr('hover'));
	        $(this).attr('hover', currentImg);
	    }, function() {
	        var currentImg = $(this).attr('src');
	        $(this).attr('src', $(this).attr('hover'));
	        $(this).attr('hover', currentImg);
		});
		
		createGraph();
		
		$('#leftArrowDiv img').click(function() {
			var date = new Date(Date.parse($("#datepicker").val()));
			date.setDate(date.getDate() - 10);
			$('#datepicker').datepicker("setDate", date);
			createGraph();
		});
		
		$('#rightArrowDiv img').click(function() {
			var date = new Date(Date.parse($("#datepicker").val()));
			date.setDate(date.getDate() + 10);
			$('#datepicker').datepicker("setDate", date)
			createGraph();
		});
		
		function createGraph() {
			$(".graph").attr("width", 100).attr("height", 100).attr("src", "./images/ajax-loader.gif").addClass("loading");
			$.post("process.php", {birth:"<?php echo $HTML['birth'];?>", selection: $("#datepicker").val()}
				, function (result) {
					if (result != "Error generating graph") {
						$(".graph").attr("src", result).attr("width", 600).attr("height", 450).removeClass("loading");
					}
					else {
						$(".graph").attr("src", "").attr("width", "0").attr("height", "0").removeClass("loading");
						$(".error").text(result);
					}
						
				}
			);
		}
	});
</script>


<div id="container">
	<?php if (!defined('TMPL_DIR')) return; ?>

	<div id="accountBody">
		<div id="topBar">
		    <a href="index.php?action=logout" target="_self">Logout</a>
		</div>
		<div id="mainContent">
		    <div id="leftSide"> 
			  	<p class="labels">Date of Birth: 
			  		<b><?php echo get_SESSION("birth");?></b>
			  	</p>
			    <p class="labels">Select a Date:
			        <br /><input type="text" name="birth" value="" id="datepicker" />
			    </p>
			    <p class="error"></p>
		    </div>
		    <div id="rightSide"> 
			  	<div id="leftArrowDiv">
			    	<img src="./images/leftArrow.png" hover="./images/leftArrowHighlight.png" width="50" height="152" alt="" class="rollover" />
			    </div>
			    
			    <div id="graphDiv">
			    	<img src="" width="" height="" alt="" class="graph" />
			    </div>
			    
			    <div id="rightArrowDiv">
			    	<img src="./images/rightArrow.png" hover ="./images/rightArrowHighlight.png" width="50" height="152" alt="" class="rollover" />
			    </div>
		    </div>
	    </div>
	</div>
</div>