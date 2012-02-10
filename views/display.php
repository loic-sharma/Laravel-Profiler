<!-- JavaScript -->
<script type="text/javascript">
	var PQP_DETAILS = true;
	var PQP_HEIGHT = "short";
	
	addEvent(window, 'load', loadCSS);

	function changeTab(tab) {
		var pQp = document.getElementById('pQp');
		hideAllTabs();
		addClassName(pQp, tab, true);
	}
	
	function hideAllTabs() {
		var pQp = document.getElementById('pQp');
		removeClassName(pQp, 'console');
		removeClassName(pQp, 'speed');
		removeClassName(pQp, 'queries');
		removeClassName(pQp, 'memory');
		removeClassName(pQp, 'files');
	}
	
	function toggleDetails(){
		var container = document.getElementById('pqp-container');
		
		if(PQP_DETAILS){
			addClassName(container, 'hideDetails', true);
			PQP_DETAILS = false;
		}
		else{
			removeClassName(container, 'hideDetails');
			PQP_DETAILS = true;
		}
	}
	function toggleHeight(){
		var container = document.getElementById('pqp-container');
		
		if(PQP_HEIGHT == "short"){
			addClassName(container, 'tallDetails', true);
			PQP_HEIGHT = "tall";
		}
		else{
			removeClassName(container, 'tallDetails');
			PQP_HEIGHT = "short";
		}
	}
	
	function loadCSS() {
		var sheet = document.createElement("link");
		sheet.setAttribute("rel", "stylesheet");
		sheet.setAttribute("type", "text/css");
		sheet.setAttribute("href", "http://127.0.0.1:8080/laravel/public/bundles/profiler/css/pQp.css");
		document.getElementsByTagName("head")[0].appendChild(sheet);
		setTimeout(function(){document.getElementById("pqp-container").style.display = "block"}, 10);
	}
	
	
	//http://www.bigbold.com/snippets/posts/show/2630
	function addClassName(objElement, strClass, blnMayAlreadyExist){
	   if ( objElement.className ){
		  var arrList = objElement.className.split(' ');
		  if ( blnMayAlreadyExist ){
			 var strClassUpper = strClass.toUpperCase();
			 for ( var i = 0; i < arrList.length; i++ ){
				if ( arrList[i].toUpperCase() == strClassUpper ){
				   arrList.splice(i, 1);
				   i--;
				 }
			   }
		  }
		  arrList[arrList.length] = strClass;
		  objElement.className = arrList.join(' ');
	   }
	   else{  
		  objElement.className = strClass;
		  }
	}

	//http://www.bigbold.com/snippets/posts/show/2630
	function removeClassName(objElement, strClass){
	   if ( objElement.className ){
		  var arrList = objElement.className.split(' ');
		  var strClassUpper = strClass.toUpperCase();
		  for ( var i = 0; i < arrList.length; i++ ){
			 if ( arrList[i].toUpperCase() == strClassUpper ){
				arrList.splice(i, 1);
				i--;
			 }
		  }
		  objElement.className = arrList.join(' ');
	   }
	}

	//http://ejohn.org/projects/flexible-javascript-events/
	function addEvent( obj, type, fn ) {
	  if ( obj.attachEvent ) {
		obj["e"+type+fn] = fn;
		obj[type+fn] = function() { obj["e"+type+fn]( window.event ) };
		obj.attachEvent( "on"+type, obj[type+fn] );
	  } 
	  else{
		obj.addEventListener( type, fn, false );	
	  }
	}
</script>
<div id="pqp-container" class="pQp" style="display:none">
	<div id="pQp" class="console">
		<table id="pqp-metrics" cellspacing="0">
			<tr>
				<td class="green" onclick="changeTab('console');">
					<var><?php echo count(Profiler::$logs); ?></var>
					<h4>Console</h4>
				</td>
				<td class="blue" onclick="changeTab('speed');">
					<var><?php echo Profiler::load_time() * 1000; ?> ms</var>
					<h4>Load Time</h4>
				</td>
				<td class="purple" onclick="changeTab('queries');">
					<var><?php echo count(Profiler::$queries); ?> Queries</var>
					<h4>Database</h4>
				</td>
				<td class="orange" onclick="changeTab('memory');">
					<var><?php echo Profiler::memory(); ?></var>
					<h4>Memory Used</h4>
				</td>
				<td class="red" onclick="changeTab('files');">
					<var><?php echo count(Profiler::$files); ?> Files</var>
					<h4>Included</h4>
				</td>
			</tr>
		</table>

	<div id="pqp-console" class="pqp-box">
		<?php if(count(Profiler::$logs) == 0): ?>
			<h3>This panel has no log items.</h3>
		<?php else: ?>
			<table class="side" cellspacing="0">
				<tr>
					<td class="alt1"><var><?php echo Profiler::$logs_count; ?></var><h4>Logs</h4></td>
					<td class="alt2"><var><?php echo Profiler::$speed_logs; ?></var> <h4>Errors</h4></td>
				</tr>
				<tr>
					<td class="alt3"><var><?php echo Profiler::$memory_logs; ?></var> <h4>Memory</h4></td>
					<td class="alt4"><var><?php echo Profiler::$speed_logs; ?></var> <h4>Speed</h4></td>
				</tr>
			</table>
			<table class="main" cellspacing="0">
				<?php foreach(Profiler::$logs as $log): ?>
					<tr class="log-<?php echo $log['type']; ?>">
						<td class="type"><?php echo $log['type']; ?></td>
						<td class="alt">
							<div>
								<?php if(isset($log['data'])): ?>
									<pre><?php echo $log['data']; ?></pre> 
								<?php endif; ?>
								<?php echo $log['message']; ?>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		<?php endif; ?>
	</div>

	<div id="pqp-speed" class="pqp-box">
		<?php if(Profiler::$speed_logs == 0): ?>
			<h3>This panel has no log items.</h3>
		<?php else: ?>
			<table class="side" cellspacing="0">
				<tr><td><var><?php echo Profiler::load_time() * 1000; ?> ms</var><h4>Load Time</h4></td></tr>
		  		<tr><td class="alt"><var><?php echo ini_get('max_execution_time'); ?></var> <h4>Max Execution Time</h4></td></tr>
		 	</table>
			<table class="main" cellspacing="0">
				<?php foreach(Profiler::$logs as $log): ?>
					<?php if($log['type'] == 'speed'): ?>		
						<tr class="log-speed">
							<td class="alt">
								<div><pre><?php echo $log['data']; ?></pre> <em><?php echo $log['message']; ?></em></div>
							</td>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>
			</table>
		<?php endif; ?>
	</div>

	<div id="pqp-queries" class="pqp-box">
		<?php if(count(Profiler::$queries) > 0): ?>
			<table class="side" cellspacing="0">
				<tr><td><var><?php echo count(Profiler::$queries); ?></var><h4>Total Queries</h4></td></tr>
				<tr><td class="alt"><var><?php echo Profiler::$query_total_time; ?> ms</var> <h4>Total Time</h4></td></tr>
				<tr><td><var><?php echo Profiler::$query_duplicates; ?></var> <h4>Duplicates</h4></td></tr>
			</table>
			<table class="main" cellspacing="0">
				<?php foreach(Profiler::$queries as $query): ?>
					<tr><td class="alt"><?php echo $query; ?></td></tr>
				<?php endforeach; ?>
			</table>
		<?php else: ?>
			<h3>No queries were executed</h3>
		<?php endif; ?>
	</div>
		
	<div id="pqp-memory" class="pqp-box">
		<?php if(Profiler::$memory_logs == 0): ?>
			<h3>This panel has no log items.</h3>
		<?php else: ?>
			<table class="side" cellspacing="0">
				<tr>
					<td><var><?php echo Profiler::memory(); ?></var><h4>Used Memory</h4></td>
				</tr>
		  		<tr>
					<td class="alt"><var><?php echo ini_get('memory_limit'); ?></var> <h4>Total Available</h4></td>
				</tr>
			</table>

			<table class="main" cellspacing="0">
				<?php foreach(Profiler::$logs as $log): ?>
					<?php if($log['type'] == 'memory'): ?>
						<tr class="log-memory"><td class="alt"><b><?php echo $log['data']; ?></b> <?php echo $log['message']; ?></td></tr>
					<?php endif; ?>
				<?php endforeach; ?>
			</table>
		<?php endif; ?>
	</div>

	<div id="pqp-files" class="pqp-box">
		<table class="side" cellspacing="0">
			<tr><td><var><?php echo count(Profiler::$files); ?></var><h4>Total Files</h4></td></tr>
			<tr><td class="alt"><var><?php echo Profiler::$files_total_size; ?></var> <h4>Total Size</h4></td></tr>
			<tr><td><var><?php echo Profiler::$files_largest; ?></var> <h4>Largest</h4></td></tr>
		</table>
		<table class="main" cellspacing="0">
			<?php foreach(Profiler::$files as $file): ?>
				<tr><td class=""><b><?php echo $file['size']; ?></b> <?php echo $file['path']; ?></td></tr>
			<?php endforeach; ?>
		</table>
	</div>

	<table id="pqp-footer" cellspacing="0">
			<tr>
				<td class="credit">
					<a href="http://particletree.com" target="_blank">
					<strong>PHP</strong> 
					<b class="green">Q</b><b class="blue">u</b><b class="purple">i</b><b class="orange">c</b><b class="red">k</b>
					Profiler</a>
				</td>
				<td class="actions">
					<a href="#" onclick="toggleDetails();return false">Details</a>
					<a class="heightToggle" href="#" onclick="toggleHeight();return false">Height</a>
				</td>
			</tr>
		</table>
	</div>
</div>