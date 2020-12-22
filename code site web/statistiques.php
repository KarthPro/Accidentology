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

	<body onload = "javascript: genereGraphique(false);" >

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
			<div class="container">
				<div class="row">

				<!-- Content -->
					<div id="content" class="8u skel-cell-important">
						<section>
							<header>

								<!-- Creation of the tabs associated with the function change_onglet -->
								<div class="systeme_onglets">
        							<div class="onglets">
        								<!-- Création of the tab Classements -->
            							<span class="onglet_0 onglet" id="onglet_tableau" onclick="javascript:change_onglet('tableau');">Classements</span>
            							<!-- Création of the tab Graphiques -->
            							<span class="onglet_0 onglet" id="onglet_graphiques" onclick="javascript:change_onglet('graphiques');">Graphiques</span>
        							</div>

        							<!-- Creation of the contents of the tabs -->
        							<div class="contenu_onglets">

        								<!-- Creation of the content of the tab Classements -->
            							<div class="contenu_onglet" id="contenu_onglet_tableau">
            								<br>
                							<table id='table'>
												<caption>Classement des régions les plus accidentogènes en 2016</caption>
												<thead>
													<tr>
														<th>Classement</th>
														<th>Régions</th>
														<th>Taux d'accidents en 2016</th>
													</tr>
												</thead>
												<tbody>
													
															<?php include('regions.php'); ?>
														
												</tbody>
											</table>
            							</div>

            							<!-- Creation of the content of the tab Graphiques -->
            							<div class="contenu_onglet" id="contenu_onglet_graphiques">
            								<br>
											<?php// include('post_stats.php'); ?>
											<form id = "form_stats" name='form_stats' method="post" action="javascript: genereGraphique(true);">          
												<fieldset><legend><span class="number">1</span> Recherche</legend>
												<!-- Error management after submitting the current form -->
												<label for='date_d'>Date début :</label>
												<input type="date" max="2016-12-31" min="2005-01-01" id ="date_d" name="date_d" onChange="javascript: compareDate('date_d','date_f');" value='2016-01-01' required >
												<label for='date_f'>Date fin :  </label>
												<input type="date" max="2016-12-31" min="2005-01-01" id='date_f' name="date_f"  onChange="javascript: compareDate('date_f','date_d');"  value='2016-12-31' required >
										
											
													<label for='zone'>Zone : </label>
													<select name="zone">
														<?php include('departements.php'); ?>
													</select>
										
												</fieldset>					
											
												<fieldset><legend><span class="number">2</span> Graphiques</legend>
													
													<input type="radio" id="graphique_1" name="graphique" value="graphique_1" required >
													<label for="graphique_1">Comparaison du nombre de morts et d'accidents</label><br>
													
													<input type="radio" id="graphique_2" name="graphique" value="graphique_2" required >
													
													<label for="graphique_2">Évolution du nombre d'accidents au cours du temps</label><br>
													
													<input type="radio" id="graphique_3" name="graphique" value="graphique_3" required >
													<label for="graphique_3">États des victimes par tranche d'âge</label><br>
													
													<input type="radio" id="graphique_4" name="graphique" value="graphique_4" required >
													<label for="graphique_4">Les différentes catégories de véhicules accidentées</label><br>
													
													<input type="radio" id="graphique_5" name="graphique" value="graphique_5" required >
													<label for="graphique_5">Types d'obstacles percutés lors d'accidents</label><br>

													<input type="radio" id="graphique_6" name="graphique" value="graphique_6" required checked >
													<label for="graphique_6">Les causes d'accidents selon la météo</label><br>
													
													<input type="radio" id="graphique_7" name="graphique" value="graphique_7" required >
													<label for="graphique_7">Les causes d'accidents selon la luminosité</label><br>
												</fieldset>
											<br>
											<input id="valider" style="border-radius= 12px 4px 12px 12px" type="submit" value="Valider">
											</form>
											<div  id="loading" style = "display: inline-block; position :relative; left: 700px; bottom: 500px;  height: 100%; width: 100% margin: 0 auto;"><img src= "loading_pie_small.gif" alt="Loading..."></div>
											<div  id="chart" style = "position :relative; left: 475px; bottom:770px; display: none; min-width:100px; max-width: 900px; height: 800px; margin: 0 auto;"></div>
											
											
												<!-- Function allowing to change the tabs -->
										
										</div>
        							</div>
    							</div>
								
									
							</header>
						</section>
					</div>
				<!-- /Content -->


				<!-- Sidebar -->
					<div id="sidebar" class="4u">
					</div>
				<!-- /Sidebar -->

				</div>
			</div>
		</div>
	<!-- /Main -->

	<!-- footer -->
		<?php
			include('footer.php');
			?>
	<!-- /footer -->
	<script src="stats.js"></script>
	</body>
</html>