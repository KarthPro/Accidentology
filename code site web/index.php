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
	<body class="homepage">

	<!-- Header  -->
		<div id="header">
			<div id="nav-wrapper"> 
			</div>
				<div class="container"> 
				
					<!-- Logo -->
						<div id="image_logo">
						<img class = "logo_accueil" src = "images/miniature.png" alt = "logo_accueil" height="302" width="302">
						</div>
						<?php
							include('logo.php');
						?>
					<!-- Logo -->

				</div>
		</div>
	<!-- /Header  -->

	<!-- Homepage choice -->
		<div id="featured">
			<div class="container">
				<header>
					<h2>Bienvenue !</h2>
				</header>
				<p>Site de <strong> statistiques </strong> d'accidentologie</p>
				<hr>
				<div class="row">
					<section class="4u">
						<span style="font-size:15em; color: #FA5858 " class="pennant"><span class="fa fa-globe"></span></span>
						</br>
						<a href="carte.php" class="button button-style1">Carte</a>
					</section>
					<section class="4u">
						<span style="font-size:15em; color: #FA5858 " class="pennant"><span class="fa fa-chart-bar"></span></span>
					</br>
						<a href="statistiques.php" class="button button-style1">Statistiques</a>
					</section>
				</div>
			</div>
		</div>

	<!-- /Homepage choice -->

	<!-- footer -->
		<?php
			include('footer.php');
			?>
	<!-- /footer -->
	</body>
</html>