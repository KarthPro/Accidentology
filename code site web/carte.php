<!DOCTYPE HTML>
<!--
	Linear by TEMPLATED
    templated.co @templatedco
    Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html>
	<?php
 	include('integre.php')
 	 ?>
	<body>

	<!-- Header -->
		<div id="header">
			<div id="nav-wrapper">

			<!-- Navigation -->
			<?php
			include('navigation.php')
			?>
			<!-- /Navigation -->

			</div>
			<div class="container"> 
				
			<!-- Logo -->
				<?php
					include('logo.php');
				?>
			<!-- /Logo -->

			</div>
		</div>
	<!-- /Header --> 

	<!-- Main -->
		<div id="main">
			


				<!--display map and filters-->
				<center>
				<iframe 
					scrolling="no"                                              
		            width= "100%"
		            height= "800"
		            frameborder="0" style="border:0"
		            src="googlemap.php" allowfullscreen>
		    	</iframe> 
		    	</center>                                            

			
		</div>
	<!-- /Main -->

	<!-- Footer -->
		<?php 
			include('footer.php');
			?>
	<!-- /Footer -->

	</body>
</html>