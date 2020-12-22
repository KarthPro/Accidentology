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
	<!-- Header --> 

	<!-- Main -->
		<div id="main">
			<div id="content" class="container">
				<section>
					<header>
						<h2>Sources</h2>
					</br>
						<span class="byline">Développeurs : Dylan Brudey/Elodie Roussel/Tony Letourneur/Prousoth Karthigesu</span>
						<span class="byline">Encadrante : Flavie Tonon</span>
					</header>
					<p>Pour que ce projet est lieu et soit créé, nous avons eu recours a plusieurs sites, frameworks, librairies, bibliothèques... afin de nous aider à le construire. Ces références nous ont particulièrement aidé à la construction de notre site web où vous êtes actuellement. Nous allons ainsi vous présenté, ci-dessous, les sources les plus importantes utilisées au cours de notre projet.</p>

					<p>La partie développement de notre projet se caractérise en deux grandes parties développées simultanément :</p>

						<ul>
							<li>la création du site web,</li>
							<li>la création de la base de données.</li>
						</ul>

					<p class="important">Le site web</p>

					<p>La base de notre site web s'inspire d'un modèle responsive design du framework <a href = "https://templated.co/" target="_blank">Templated</a>.<br> Nous avons de plus eu recours à plusieurs librairies pour les différents langages de programmation utilisés :<br>
					<a href = "https://javascript.developpez.com/cours/librairies-javascript-vraiment-utiles/" target="_blank">Librairies Javascript</a>
					<br>
					<a href = "http://jcrozier.developpez.com/tutoriels/web/php/classes-et-librairies-utiles-developpeurs/" target="_blank">Librairies PHP</a><br>
					</p>

					<p>Pour la partie carte, nous avons eu recours à <a href = "https://developers.google.com/maps/documentation/javascript/" target="_blank">Google Maps</a> afin de créer et placer la carte qui présente géographiquement les accidents sur notre site.</p>
					<p>Pour la partie graphiques, nous avons eu recours à la bibliothèque <a href = "https://www.highcharts.com" target="_blank">Highcharts</a> afin de mettre en forme nos données sous forme de graphiques.</p>

					<p class="important">La base de données</p>

					<p>La base de données a-t-elle été créé à partir de <a href = "https://www.data.gouv.fr/fr/datasets/base-de-donnees-accidents-corporels-de-la-circulation/#" target="_blank">données</a> récupérées sur un site open-data. Peu d'outils ont été nécessaires pour sa création, certains sont listés ci-dessous :<br>
					<a href = "https://www.mysql.com" target="_blank">MYSql</a>
					<br>
					<a href = "https://www.phpmyadmin.net" target="_blank">PHPMyAdmin</a>
					</p>
						
				</section>
			</div>
		</div>
	<!-- /Main -->

	<!-- footer -->
		<?php 
			include('footer.php');
			?>
	<!-- /footer -->
	</body>
</html>