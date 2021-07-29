<!DOCTYPE html>
<html>
<head>
	<title>Port&aacute;l &uacute;dr&#382;by</title>
    <link rel="stylesheet" href="css/bootstrap/bootstrap.css"/>
	<style type="text/css">
		@import url('https://fonts.googleapis.com/css?family=Press+Start+2P');

		html,body{
			width: 100%;
			height: 100%;
			margin: 0;
		}

		*{
			font-family: 'Press Start 2P', cursive;
			box-sizing: border-box;
		}
		#app{
			padding: 1rem;
			background: black;
			display: flex;
			height: 100%;
			justify-content: center; 
			align-items: center;
			color: #54FE55;
			text-shadow: 0px 0px 10px ;
			font-size: 4rem;
			flex-direction: column;
			.txt {
				font-size: 1.8rem;
			}
		}
		@keyframes blink {
		    0%   {opacity: 0}
		    49%  {opacity: 0}
		    50%  {opacity: 1}
		    100% {opacity: 1}
		}

		.blink {
			animation-name: blink;
			animation-duration: 1s;
			animation-iteration-count: infinite;
		}
	</style>
	<script type="text/javascript">
		var timeleft = 4;
		var downloadTimer = setInterval(function(){

		  	document.getElementById("timer").innerHTML = timeleft.toString();
		  	timeleft -= 1;
		  	if(timeleft < 0)
		    	window.location.replace("<?php echo $_GET['target'] ?>");
		}, 1000);
	</script>
</head>
<body>
	<div id="app">
   	<div>404</div>
   	<div class="txt text-center">
      	Str&aacute;nka nenalezena
      	<hr />
      	<hr />
      	<?php
      	if(isset($_GET['target'])){
      		echo '
	      	<div class="row">
	      		P&#345;esm&#283;rov&aacute;n&iacute; za&nbsp;<div id="timer">5</div>s
	      		<span class="blink">_</span>
	  		</div>';
  		}
  		else{
      		echo '
	      	<div class="">
	      		P&#345;ejd&#283;te jinam<span class="blink">_</span>
	  		</div>';
  		}
  		?>
   	</div>
</div>
</body>
</html>