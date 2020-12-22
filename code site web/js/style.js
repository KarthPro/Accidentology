/*********************************************************************************/
/* Affichage                                                                     */
/*********************************************************************************/

function toggle_text(id) {
	if (document.getElementById(id).style.visibility == "hidden") 
		document.getElementById(id).style.visibility = "visible";
	else document.getElementById(id).style.visibility = "hidden";
}

/*********************************************************************************/
/* Date Choice                                                                   */
/*********************************************************************************/

$(function() {
$( "#datepicker" ).datepicker({
altField: "#datepicker",
closeText: 'Fermer',
prevText: 'Précédent',
nextText: 'Suivant',
currentText: 'Aujourd\'hui',
monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
weekHeader: 'Sem.',
dateFormat: 'yy-mm-dd'
});
});



function cocheImage(id){
	if(document.getElementById('data_'+id).checked){
		document.getElementById('data_'+id).checked = false;
		document.getElementById('image_'+id).style.border = '1px solid transparent';
	}else{
		document.getElementById('data_'+id).checked = true;
		document.getElementById('image_'+id).style.border = '1px solid black';
	}
	return false;
}
