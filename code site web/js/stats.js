//Sources : W3schools.com , Highcharts.com 

var anc_onglet = 'tableau';				
change_onglet(anc_onglet);				// Open the 'Classements' tab during the "Statistiques" page loading
var graph_courant = false;				// Set the current running chart to none

function change_onglet(name)	// Allow to permute between the following tabs : "Classements" and "Graphiques" 
{
	// Recover the tab when that is not selected
	document.getElementById('onglet_'+anc_onglet).className = 'onglet_0 onglet';
	// Recover information of the tab when that is selected and displays it
	document.getElementById('onglet_'+name).className = 'onglet_1 onglet';
	// Not posting of the contents of the tab when that is not selected
	document.getElementById('contenu_onglet_'+anc_onglet).style.display = 'none';
	// Posting of the contents of the mitre when that is not selected
	document.getElementById('contenu_onglet_'+name).style.display = 'block';
	anc_onglet = name;
}

function compareDate(id,id_2)								// Compare the 2 dates in input and return a warning message if the date at the beginning of the interval 
{															// is after the date at the end of it. The opposite works too : it returns also a warning message and prevent the user 
	var element = document.getElementById(id);				// from submitting the "form_stats" form until he corrected this input error
	var element_2 = document.getElementById(id_2);			// Function inspired by : developpez.net
	var date_one = new Date(element.value);					// Create 2 Date object from date strings to be able to compare them
	var date_two = new Date(element_2.value);
	element.setCustomValidity("");							//Reset the warning message to none for both date inputs 
	element_2.setCustomValidity("");
	if(id=='date_d' && typeof(date_two)!="undefined")
	{
		if(date_one > date_two)					//Set a warning message when the beginning date of the interval is superior to the date at the end of the interval 
			element.setCustomValidity("La date de début de l'intervalle ne peut être supérieur à celle de fin");
		else
			element.setCustomValidity("");		// Else no warning message
	}
	else if(id=='date_f' && typeof(date_two)!="undefined")	
	{
		if(date_one < date_two)			//Set a warning message when the  date at the end of the interval is inferior to the date at the beginning of the interval 
			element.setCustomValidity("La date de fin de l'intervalle ne peut être inférieur à celle du début");
		else
			element.setCustomValidity("");		// Else no warning message
	}
}

function showChart(id)			//Display the chart
{
	document.getElementById(id).style.display = "inline-block";
}

function hideChart(id)			//Hide the chart
{
	document.getElementById(id).style.display = "none";
}

function genereGraphique(visit)		//Create the chart using the inputs from the form and the data of the database
{
	if (graph_courant)
	{
		hideChart('chart');
	}
	document.getElementById('loading').style.display = "block";			//Display the loading logo
	//Set the form inputs values to variables
	var date_debut = document.forms["form_stats"].date_d.value;	
	var date_fin = document.forms["form_stats"].date_f.value;
	var zone = document.forms["form_stats"].zone.value;
	var graphique = document.forms["form_stats"].graphique.value;
	//Load a preset chart at the first "statistiques" page loading, else a chart chosen by the user submitting the form.
	if (visit)
		obj = { "chart": graphique, "zone": zone, "date_d": date_debut, "date_f": date_fin};
	else
		obj = { "chart": "graphique_6", "zone":" 1000", "date_d": "2016-01-01", "date_f": "2016-12-31"};	//Preset data : Chart n°6 (Weather chart) in France in 2016
	dbParam = JSON.stringify(obj);
	requete = new XMLHttpRequest();					//Object that allow to send a request to the database using a PHP script
	requete.onreadystatechange = function() 		// Assess the state of the request, whether it's ready or not
	{
		if (this.readyState == 4 && this.status == 200)		// Generate the corresponding chart the user asked once the request is done and the data is retrieved
		{	
			reponse = JSON.parse(this.responseText);
			document.getElementById('loading').style.display = "none";	// Hide the loading gif image
			switch(graphique)						// Select the chart type to create 
					{		// From here on to line 338, the javascript code comes mostly from Highcharts itself, for more information on the variables and the options, see Highcharts.com  
					// Some comments will be added where modifications have been made
					// More information on the charts in 'post_stats.php' comments and in our documentation (Cahier des charges)
					// The data integration is different for almost every chart type, more info on Highcharts demo charts (which show the code to add to generate the selected chart) 
					// See Highcharts.com/demo, check also "post_stats.php" code and comments to see how to do it from A to Z
						case "graphique_1":	// Chart 1 : Highcharts Column chart
								Highcharts.chart('chart', {
								chart: {
									type: 'column'
								},
								title: {
									text: "Comparaison du nombre de morts et d'accidents"
								},
								subtitle: {
									text: 'Source: <a href="https://www.data.gouv.fr/fr/datasets/base-de-donnees-accidents-corporels-de-la-circulation/#_">data.gouv.fr</a>'
								},
								xAxis: {
									categories: [
										"",
									  ""
									],
									crosshair: true
								},
								yAxis: {
									min: 0,
									title: {
										text: ''
									}
								},
								tooltip: {
									pointFormat: '{series.name}: <b>{point.y:.0f}</b>',
								},
								plotOptions: {
									column: {
										pointPadding: 0.2,
										borderWidth: 0
									}
								},
								series: reponse	//Add the retrieved data to the chart
							});
						break;
						case "graphique_2":	// Chart 2 : Line chart
								Highcharts.chart('chart', {
								chart: {
									type: 'line'
								},
								title: {
									text: "Evolution du nombre d'accidents au cours du temps"
								},
								subtitle: {	// Link to the data source
									text: 'Source: <a href="https://www.data.gouv.fr/fr/datasets/base-de-donnees-accidents-corporels-de-la-circulation/#_">data.gouv.fr</a>'
								},
								xAxis: {
									categories: reponse[1]	//Add the retrieved data to the chart corresponding to the year and month of the accidents
								},
								yAxis: {
									title: {
										text: "Nombre d'accidents"
									}
								},
								plotOptions: {
									line: {
										dataLabels: {
											enabled: true
										},
										enableMouseTracking: false
									}
								},
								series: [ reponse[0]	//Add the retrieved data to the chart corresponding to the number of accidents by month
								]
							});
						break;
						case "graphique_3":	//Chart 3 : Bar chart
								Highcharts.chart('chart', {
									chart: {
										type: 'bar'
									},
									title: {
										text: "États des victimes par tranche d'âge"
									},
									subtitle: {
										text: 'Source: <a href="https://www.data.gouv.fr/fr/datasets/base-de-donnees-accidents-corporels-de-la-circulation/#_">data.gouv.fr</a>'
									},
									xAxis: {
										categories: ['Enfants (0-14)', 'Adolescents (15-17)', 'Jeunes (18-24)', 'Adultes(25-60)', 'Seniors(60+)'],
										title: {
											text: null
										}
									},
									yAxis: {
										min: 0,
										title: {
											text: "Nombre de victimes d'accidents",
											align: 'high'
										},
										labels: {
											overflow: 'justify'
										}
									},
									tooltip: {	
										valueSuffix: ' personnes'
									},
									plotOptions: {
										bar: {
											dataLabels: {
												enabled: true
											}
										}
									},
									legend: {
										layout: 'vertical',
										align: 'right',
										verticalAlign: 'top',
										x: -40,
										y: 80,
										floating: true,
										borderWidth: 1,
										backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
										shadow: true
									},
									credits: {
										enabled: false
									},
									series: reponse
								});
						break;
						case "graphique_4":	var options = {	// Chart 4 : Pie chart 
								chart: {
									renderTo: 'chart',
									plotBackgroundColor: null,
									plotBorderWidth: null,
									plotShadow: false,
									type: 'pie'
								},
								title: {
									text: "Les différentes catégories de véhicules accidentées"
								},
								tooltip: {
									pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
								},
								plotOptions: {
									pie: {
										allowPointSelect: true,
										cursor: 'pointer',
										dataLabels: {
											enabled: false
										},
										showInLegend: true
									}
								},
								series: [{
									name: 'Conditions',
									colorByPoint: true, 
									data: reponse		
								}]
								}
								var chart = new Highcharts.Chart(options);
						break;
						case "graphique_5":	// Chart 5 : Colmun chart
								Highcharts.chart('chart', {
								chart: {
									type: 'column'
								},
								title: {
									text: "Types d'obstacles percutés lors d'accidents"
								},
								subtitle: {
									text: 'Source: <a href="https://www.data.gouv.fr/fr/datasets/base-de-donnees-accidents-corporels-de-la-circulation/#_">data.gouv.fr</a>'
								},
								xAxis: {
									 categories: ['Obstacles'],
									crosshair: true
								},
								yAxis: {
									min: 0,
									title: {
										text: ''
									}
								},
								tooltip: {
									//headerFormat: '<span style="font-size:10px">{point.key}</span>',
									pointFormat: '{series.name}: <b>{point.y:.0f}</b>',
									//shared: true,
									//useHTML: true
								},
								plotOptions: {
									column: {
										pointPadding: 0.2,
										borderWidth: 0
									}
								},
								series: reponse	//Add the retrieved data to the chart
							});
						break;
						case "graphique_6":	// Chart 6 : Pie chart
								var options = {
								chart: {
									renderTo: 'chart',
									plotBackgroundColor: null,
									plotBorderWidth: null,
									plotShadow: false,
									type: 'pie'
								},
								title: {
									text: "Les causes d'accidents selon la météo"
								},
								tooltip: {
									pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
								},
								plotOptions: {
									pie: {
										allowPointSelect: true,
										cursor: 'pointer',
										dataLabels: {
											enabled: false
										},
										showInLegend: true
									}
								},
								series: [{
									name: 'Conditions',
									colorByPoint: true, 
									data: reponse	//Add the retrieved data to the chart
								}]
								}
								var chart = new Highcharts.Chart(options);
						break;
						case "graphique_7":	// Chart 7 : Pie chart
								var options = {
								chart: {
									renderTo: 'chart',
									plotBackgroundColor: null,
									plotBorderWidth: null,
									plotShadow: false,
									type: 'pie'
								},
								title: {
									text: "Les causes d'accidents selon la luminosité"
								},
								tooltip: {
									pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
								},
								plotOptions: {
									pie: {
										allowPointSelect: true,
										cursor: 'pointer',
										dataLabels: {
											enabled: false
										},
										showInLegend: true
									}
								},
								series: [{
									name: 'Conditions',
									colorByPoint: true, 
									data: reponse	//Add the retrieved data to the chart
								}]
								}
								var chart = new Highcharts.Chart(options);
						break;
					}
			showChart('chart');		// Display the chart after creating it
			graph_courant = true;	// Indicate that at least one chart has already been loaded in the page
		}
	};
	requete.open("POST", "post_stats.php", true);		//Request to retrieve data from the database
	requete.setRequestHeader("Content-type", "application/x-www-form-urlencoded");	// Encode the parameters sent to the 'post_stats.php'
	requete.send("envoi=" + dbParam);		// Use the user inputs as parameters to get the wanted data  	
}						