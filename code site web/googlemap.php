<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="css/style_carte.css">
    <meta charset="utf-8">
    <title>Carte</title>
    <style>
      html, body {
        height: 95%;
      }
    </style>

  </head>
  <body>
    <div class="rows">
      <form id = "form_map" name = "form_map" method="post" action="googlemap.php">                                                                                          <!--The form returns the choices made by the user on the same page.-->
	  <ul class="default">                                                                                                                 <!--These choices will filter the data send on the map -->

         <li>
            <label for=TypeDate> Type de recherche </label>
            <br>
            <select name="TypeDate" id="TypeDate" required onchange="AfficheLeChoix();" >
              <option value="Intervalle" name="Intervalle" selected  > Intervalle de date</option>                
              <option value="Jour" name="Jour" <?php if (isset($_POST['TypeDate'])) { if(($_POST['TypeDate'])=='Jour'){echo'selected';}} ?>> Jour précis </option>  <!-- -->
            </select>
            
          </li>

          <script>                                                                                                  // the function makes visible or hidden the blocks (div id="ChoixIntervalle" and div id="ChoixJour") 
            function AfficheLeChoix()                                                                               // according to the user's choice
              {                                                                                                       
                var selection=document.getElementById('TypeDate');                                                    
                var choix=selection.options[selection.selectedIndex].value;


                if(choix=='Intervalle')
                {
                  document.getElementById('ChoixIntervalle').style.visibility='visible'
                }
                else 
                {
                  document.getElementById('ChoixIntervalle').style.visibility='hidden';

                }
                if(choix=='Jour')
                {
                  document.getElementById('ChoixJour').style.visibility='visible'
                }
                else 
                {
                  document.getElementById('ChoixJour').style.visibility='hidden';

                }

                
              }

             function compareDate(id,id_2)                                                                           //check if the interval is in accordance with the specifications, otherwise
                      {                                                                                              //display an error message to the user
                        var element = document.getElementById(id);
                        var element_2 = document.getElementById(id_2);
                        var date_one = new Date(element.value);
                        var date_two = new Date(element_2.value);
                        element.setCustomValidity("");
                        element_2.setCustomValidity("");
                        if(id=='dateD' && typeof(date_two)!="undefined")
                        {
                          if(date_one >= date_two)
                            element.setCustomValidity("La date de début doit être strictement inférieure à la date de fin");
                          else
                            element.setCustomValidity("");
                        }
                        else if(id=='dateF' && typeof(date_two)!="undefined")
                        {
                          if(date_one <= date_two)
                            element.setCustomValidity("La date de fin doit être strictement supérieure à la date de début");
                          else
                            element.setCustomValidity("");
                        }
                        
                      } 

          </script>
                                                                                                                               
                                                                                                                                  <!-- the php programs displays the date chosen by the user after submit-->

          <div id="ChoixIntervalle" style="position:absolute; <?php if (isset($_POST['TypeDate'])) { if(($_POST['TypeDate'])=='Jour'){echo'visibility:hidden;';}} ?>";>
            <li>
              <label for="dateD">Date de début </label>
              <br>
              <input type="date" max="2016-12-31" min="2005-01-01" name="dateD" id="dateD" <?php if (isset($_POST['dateD'])) { ?> value=<?php echo $_POST['dateD']; }else{?> value=<?php echo '2016-01-01';} ?>
              onChange="javascript: compareDate('dateD','dateF');">
              <br>
              <label for="dateF">Date de fin </label>
              <br>
              <input type="date" max="2016-12-31" min="2005-01-01"  name="dateF" id="dateF" <?php if (isset($_POST['dateF'])) { ?> value=<?php echo $_POST['dateF']; }else{?> value=<?php echo '2016-12-31';} ?>
              onChange="javascript: compareDate('dateF','dateD');">
            </li>
           </div>

          <div id="ChoixJour" style="visibility:hidden;<?php if (isset($_POST['TypeDate'])) { if(($_POST['TypeDate'])=='Jour'){echo'visibility:visible;';}} ?>">
            <li>
                <label for="jourR">Jour recherché</label>
              <br>
              <input type="date" max="2016-12-31" min="2005-01-01" name="jourR" id="jourR" <?php if (isset($_POST['jourR'])) { ?> value=<?php echo $_POST['jourR']; } ?>>
            </li>
          </div>

          <li>
          </li>

          <li>
              <br>
              <br>                                                                                                                  
                                                                                                                                   <!-- the php programs displays the filter check by the user after submit-->
            <p class="gras"><strong>Conditions Météorologiques : </strong></p>   
              <input type="checkbox" name="ConditionsNormales" id="ConditionsNormales" value="ConditionsNormales" <?php if (isset($_POST['ConditionsNormales'])) { ?> checked  <?php } ?> > 
              <label for="conditions normales">Conditions normales</label>
              <br>
              <input type="checkbox" name="PluieLégère" id="PluieLégère" value="PluieLégère" <?php if (isset($_POST['PluieLégère'])) { ?> checked  <?php } ?> >
              <label for="pluie légère">Pluie légère</label>
              <br> 
              <input type="checkbox" name="PluisForte" id="PluisForte" value="PluisForte" <?php if (isset($_POST['PluisForte'])) { ?> checked  <?php } ?> >
              <label for="pluie très forte">Pluie très forte</label>
              <br>
              <input type="checkbox" name="NeigesGrêle" id="NeigesGrêle" value="NeigesGrêle" <?php if (isset($_POST['NeigesGrêle'])) { ?> checked  <?php } ?> >
              <label for="neige/grêle">Neige/Grêle</label>
              <br>  
              <input type="checkbox" name="Brouillard" id="brouillard" value="Brouillard" <?php if (isset($_POST['Brouillard'])) { ?> checked  <?php } ?> >
              <label for="brouillad">Brouillard</label>
              <br> 
              <input type="checkbox" name="VentFort" id="VentFort" value="VentFort" <?php if (isset($_POST['VentFort'])) { ?> checked  <?php } ?>>
              <label for="vent fort">Vent fort</label>
              <br>
              <input type="checkbox" name="Autres" id="Autres" value="Autres" <?php if (isset($_POST['Autres'])) { ?> checked  <?php } ?> >
              <label for="Autres">Autres</label>
              <br> 
            </li>   

          <li> 
                                                                                                                                     <!-- the php programs displays the filter check by the user after submit-->           
            <p class="gras"><strong>Gravités des accidents : </strong></p>
              <input type="checkbox" name="morts" id="morts" value="morts" <?php if (isset($_POST['morts'])) { ?> checked  <?php } ?> >
              <label for="morts">Morts</label>
              <br>
              <input type="checkbox" name="BlessésGraves" id="BlessésGraves" value="blessés graves" <?php if (isset($_POST['BlessésGraves'])) { ?> checked  <?php } ?> >
              <label for="blessés graves">Blessés graves</label>
              <br> 
              <input type="checkbox" name="blessés" id="blessés" value="blessés" <?php if (isset($_POST['blessés'])) { ?> checked  <?php } ?> >
              <label for="blessés">Blessés</label>
              <br>
              <input type="checkbox" name="indemnes" id="indemnes" value="indemnes" <?php if (isset($_POST['indemnes'])) { ?> checked  <?php } ?>>
              <label for="indemnes">Indemnes</label>
              <br> 
            </li>

                                                                                                                                     <!-- the php programs displays the filter check by the user after submit-->
            <p class="gras"><strong>Tranches d'âge : </strong></p>
              <input type="checkbox" name="enfants" id="enfants" value="enfants" <?php if (isset($_POST['enfants'])) { ?> checked  <?php } ?>>
              <label for="enfants">Enfants</label>
              <br>
              <input type="checkbox" name="ado" id="ado" value="ado" <?php if (isset($_POST['ado'])) { ?> checked  <?php } ?> >
              <label for="ado">Adolescents (15-17ans)</label>
              <br>
              <input type="checkbox" name="jeunes" id="jeunes" value="jeunes" <?php if (isset($_POST['jeunes'])) { ?> checked  <?php } ?> >
              <label for="jeunes">Jeunes (18-24ans)</label>
              <br> 
              <input type="checkbox" name="adultes" id="adultes" value="adultes" <?php if (isset($_POST['adultes'])) { ?> checked  <?php } ?>>
              <label for="adultes">Adultes (25-60ans)</label>
              <br>  
              <input type="checkbox" name="senior" id="senior" value="senior" <?php if (isset($_POST['senior'])) { ?> checked  <?php } ?>>
              <label for="senior">Seniors (60+)</label>
              <br> 
            </li>

            <li>
                                                                                                                                      <!-- the php programs displays the filter check by the user after submit-->
              <p class="gras"><strong>Types de véhicules : </strong></p>
              <input type="checkbox" name="4r" id="4r" value="4roues" <?php if (isset($_POST['4r'])) { ?> checked  <?php } ?>>
              <label for="4r">4 roues</label>
              <br>
              <input type="checkbox" name="2r" id="2r" value="2roues" <?php if (isset($_POST['2r'])) { ?> checked  <?php } ?>>
              <label for="2r">2 roues</label>
              <br>
              <input type="checkbox" name="autresV" id="autresV" value="autresV" <?php if (isset($_POST['autresV'])) { ?> checked  <?php } ?>>
              <label for="autresV">Autres types de véhicules </label>
              <br>   
            </li>
            <li>
              <br>
              <input type="submit" value="Soumettre">
             </li>
          </ul>
		</form>
      </div>
    </div>

    <div id="map"></div>                                                                                                                        
    <script data-cfasync="false">                                                                                                            //script googlemap : https://developers.google.com/maps/documentation/javascript/examples/

      function initMap() {

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 5.5,
          center: {lat: 48.952699, lng:2.224265}
        });


        var labels = '';

        var markers = locations.map(function(location, i) {
          return new google.maps.Marker({
            position: location,
            label: labels[i % labels.length]
          });
        });

        // Add a marker clusterer to manage the markers.
        var markerCluster = new MarkerClusterer(map, markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
      }
      var locations = [
        <?php                                                                                                                  // SQL query to the database, to send the result on the map. Program under development


        if (isset($_POST['TypeDate'])) {                                                                                       // check if the form has been sent
            																												   

              try
              {
                $bdd = new PDO('mysql:host=localhost;dbname=accidents_routiers;charset=utf8', 'root', '');                     // connection to the database

              }
              catch (Exception $e)
              {
                      die('Erreur : ' . $e->getMessage());
              }


              $requete="SELECT lat,longi FROM caracteristiques WHERE ( longi<>0 and lat<>0 and ";                               //Construction of the query

              $nb_sous_requete=0;                                                                                               //Counts the number of subqueries ( to close the brackets at the end of the query)

              $derniereSousRequeteSurUsagers=false;                                                                             //false: if the last query is on the caracteristiques table 
                                                                                                                                //true:  if the last query is on the usagers table
              																													                                                        //$derniereSousRequeteSurUsagers will be useful for the transition between subqueries


              if($_POST['TypeDate']=='Intervalle'){                                                                             //Interval of date
                $requete .=" date_acc BETWEEN '".$_POST['dateD']."' and '".$_POST['dateF']."' )";
              }


              if($_POST['TypeDate']=='Jour'){                                                                                   //Specific day
                 $requete .=" date_acc='".$_POST['jourR']."' )";
              }


              // INFORMATION DATABASE : https://www.data.gouv.fr/s/resources/base-de-donnees-accidents-corporels-de-la-circulation/20170915-155209/Description_des_bases_de_donnees_ONISR_-Annees_2005_a_2016.pdf


              // "Conditions Météorologiques :"



              if((isset($_POST['ConditionsNormales']))or(isset($_POST['PluisForte']))or(isset($_POST['PluieLégère']))or(isset($_POST['NeigesGrêle']))or(isset($_POST['Brouillard']))or(isset($_POST['VentFort']))or(isset($_POST['Autres']))){

                 $requete .=" and num_c IN ( SELECT num_c FROM caracteristiques WHERE (  ";
                 $or=false;                                                                                                     //manages the transition between the conditions of the same query
                 $nb_sous_requete++;

                if(isset($_POST['ConditionsNormales'])){                                                                                               
                  $requete .=" atm='1'";
                  $or=true;
                }
                if(isset($_POST['PluieLégère'])){
                  if($or){
                    $requete .=" or ";

                  }                    
                  $requete .="  atm='2' ";
                  $or=true;
                }                                  
                if(isset($_POST['PluisForte'])){
                  if($or){
                    $requete .=" or ";

                  }
                  $requete .="  atm='3' ";
                  $or=true;
                }
                if(isset($_POST['NeigesGrêle'])){
                  if($or){
                    $requete .=" or ";

                  }  
                  $requete .="  atm='4' ";
                  $or=true;
                }
                if(isset($_POST['Brouillard'])){
                  if($or){
                    $requete .=" or ";

                  }  
                  $requete .="  atm='5' ";
                  $or=true;
                }
                if(isset($_POST['VentFort'])){
                  if($or){
                    $requete .=" or ";

                  }  
                  $requete .="  atm='6' ";
                  $or=true;
                }
                if(isset($_POST['Autres'])){
                  if($or){
                    $requete .=" or ";

                  }  
                  $requete .="  atm='7' or atm='8' or atm='9' ";
                }                   
                 $requete .=" ) ";


             }

              // "Conditions Météorologiques :"             

              if((isset($_POST['morts']))or(isset($_POST['blessés']))or(isset($_POST['BlessésGraves']))or(isset($_POST['indemnes']))){

                 $requete .=" and num_c IN ( SELECT num_u FROM usagers WHERE (  ";
                 $derniereSousRequeteSurUsagers=true;
                 $or=false;                                                                                                     //manages the transition between the conditions of the same query
                 $nb_sous_requete++;

                if(isset($_POST['morts'])){                                                                                               
                  $requete .=" grav='2'";
                  $or=true;
                }
                if(isset($_POST['BlessésGraves'])){
                  if($or){
                    $requete .=" or ";

                  }                    
                  $requete .="  grav='3' ";
                  $or=true;
                }                                  
                if(isset($_POST['blessés'])){
                  if($or){
                    $requete .=" or ";

                  }
                  $requete .="  grav='4' ";
                  $or=true;
                }
                if(isset($_POST['indemnes'])){
                  if($or){
                    $requete .=" or ";

                  }  
                  $requete .="  grav='1' ";
                  $or=true;
                }
                 
                 $requete .=" ) ";


             }

              // "Gravités des accidents :"            

              if((isset($_POST['enfants']))or(isset($_POST['ado']))or(isset($_POST['jeunes']))or(isset($_POST['adultes']))or(isset($_POST['senior']))){

                 if($derniereSousRequeteSurUsagers){
                  $requete .=" and num_u IN ( SELECT num_u FROM usagers WHERE (  ";                                             //if the last query is on the usagers table

                 }
                 else{
                  $requete .=" and num_c IN ( SELECT num_u FROM usagers WHERE (  ";                                             //if the last query is on the caracteristiques table
                 }

                 $derniereSousRequeteSurUsagers=true;                  

                 $or=false;                                                                                                     //manages the transition between the conditions of the same query
                 $nb_sous_requete++;

                if(isset($_POST['enfants'])){                                                                                               
                  $requete .=" ((YEAR(date_acc)-an_nais)<=15) ";
                  $or=true;
                }
                if(isset($_POST['ado'])){
                  if($or){
                    $requete .=" or ";

                  }                    
                  $requete .="  ((YEAR(date_acc)-an_nais)>15 and (YEAR(date_acc)-an_nais)<=17 ) ";
                  $or=true;
                }                                  
                if(isset($_POST['jeunes'])){
                  if($or){
                    $requete .=" or ";

                  }
                  $requete .="  ((YEAR(date_acc)-an_nais)>18 and (YEAR(date_acc)-an_nais)<=24 ) ";
                  $or=true;
                }
                if(isset($_POST['adultes'])){
                  if($or){
                    $requete .=" or ";

                  }  
                  $requete .="  ((YEAR(date_acc)-an_nais)>25 and (YEAR(date_acc)-an_nais)<=60) ";
                  $or=true;
                }
                if(isset($_POST['senior'])){
                  if($or){
                    $requete .=" or ";

                  }  
                  $requete .="  ((YEAR(date_acc)-an_nais)>=61) ";
                  $or=true;
                }
                 
                 $requete .=" ) ";


             }

              // "Tranches d'âges :"


              if((isset($_POST['enfants']))or(isset($_POST['ado']))or(isset($_POST['jeunes']))or(isset($_POST['adultes']))or(isset($_POST['senior']))){

                 if($derniereSousRequeteSurUsagers){
                  $requete .=" and num_u IN ( SELECT num_u FROM usagers WHERE (  ";                                              //if the last query is on the usagers table

                 }
                 else{
                  $requete .=" and num_c IN ( SELECT num_u FROM usagers WHERE (  ";                                             //if the last query is on the caracteristiques table
                 }

               

                 $or=false;                                                                                                     //manages the transition between the conditions of the same query
                 $nb_sous_requete++;

                if(isset($_POST['enfants'])){                                                                                               
                  $requete .=" ((YEAR(date_acc)-an_nais)<=15) ";
                  $or=true;
                }
                if(isset($_POST['ado'])){
                  if($or){
                    $requete .=" or ";

                  }                    
                  $requete .="  ((YEAR(date_acc)-an_nais)>15 and (YEAR(date_acc)-an_nais)<=17 ) ";
                  $or=true;
                }                                  
                if(isset($_POST['jeunes'])){
                  if($or){
                    $requete .=" or ";

                  }
                  $requete .="  ((YEAR(date_acc)-an_nais)>18 and (YEAR(date_acc)-an_nais)<=24 ) ";
                  $or=true;
                }
                if(isset($_POST['adultes'])){
                  if($or){
                    $requete .=" or ";

                  }  
                  $requete .="  ((YEAR(date_acc)-an_nais)>25 and (YEAR(date_acc)-an_nais)<=60) ";
                  $or=true;
                }
                if(isset($_POST['senior'])){
                  if($or){
                    $requete .=" or ";

                  }  
                  $requete .="  ((YEAR(date_acc)-an_nais)>=61) ";

                }
                 
                $requete .=" ) ";


             }


              // "Types de véhicules :"


              if((isset($_POST['4r']))or(isset($_POST['2r']))or(isset($_POST['poidslourd']))or(isset($_POST['autresV']))){

                 if($derniereSousRequeteSurUsagers){
                  $requete .=" and num_u IN ( SELECT num_v FROM vehicules WHERE (  ";                                           //if the last query is on the usagers table

                 }
                 else{
                  $requete .=" and num_c IN ( SELECT num_v FROM vehicules WHERE (  ";                                           //if the last query is on the caracteristiques table
                 }
      
                 $or=false;                                                                                                     //manages the transition between the conditions of the same query
                 $nb_sous_requete++;

                if(isset($_POST['4r'])){
                    
                  $requete .=" catv='7' or catv='8' or catv='9' or catv='10' or catv='11' or catv='12' or catv='35' or catv='36'  ";
                  $or=true;

                }  
                if(isset($_POST['2r'])){
                  if($or){
                    $requete .=" or ";

                  }                    
                  $requete .=" catv='1' or catv='2' or catv='4' or catv='5' or catv='6' or catv='30' or catv='31' or catv='32' or catv='33'  or catv='34' ";
                  $or=true;

                }  
                if(isset($_POST['poidslourd'])){
                  if($or){
                    $requete .=" or ";

                  }                    
                  $requete .=" catv='13' or catv='14' or catv='15' or catv='16' or catv='17' or catv='37' or catv='38' ";
                  $or=true;
                } 
                if(isset($_POST['autresV'])){
                  if($or){
                    $requete .=" or ";

                  }                    
                  $requete .=" catv='3' or catv='18' or catv='19' or catv='20' or catv='21'  or catv='39' or catv='40' or catv='99' ";

                }  
                                               

                 
                 $requete .=" ) ";


             }             


              




  	        for($i=0;$i<$nb_sous_requete;$i++){                                                                         // close the open brackets at the end of the query
  	            $requete .=" ) ";
  	        }


      			try
      			{
      				$reponse = $bdd->query($requete);
      			}
      			catch(PDOException $e)																						                                        	    // Display error if the connection fails 
      			{
      				echo 'Échec de la connexion à la base de données<br/>';
      				echo 'Error : '.$e->getMessage().'<br />';
      				echo 'N° : '.$e->getCode();
      				exit();
      			} 
      			
              



              while ($donnees = $reponse->fetch())

              {

                  echo '{lat:' . $donnees['lat'] . ', lng:' . $donnees['longi'] .'},' ;

              }



              $reponse->closeCursor();
      }
       if(!isset($_POST['TypeDate'])){                                                                                       //map display when the user come on the Web page

         try
            {
              $bdd = new PDO('mysql:host=localhost;dbname=accidents_routiers;charset=utf8', 'root', '');                     // connection to the database

            }
            catch (Exception $e)
            {
                    die('Erreur : ' . $e->getMessage());
            }


         try
            {
              $reponse = $bdd->query('SELECT lat,longi FROM caracteristiques WHERE lat<>0 and longi<>0 and YEAR(date_acc)=2016');
            }
            catch(PDOException $e)                                                                                           // Display error if the connection fails 
            {
              echo 'Échec de la connexion à la base de données<br/>';
              echo 'Error : '.$e->getMessage().'<br />';
              echo 'N° : '.$e->getCode();
              exit();
            }
            
              



            while ($donnees = $reponse->fetch())

            {

                echo '{lat:' . $donnees['lat'] . ', lng:' . $donnees['longi'] .'},' ;

            }



            $reponse->closeCursor();
      
      }
      ?>  
      ]
    </script>
    <script data-cfasync="false" src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
    </script>
    <script data-cfasync="false"
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDe9NZQ6lhiiFhVzx1jCGnOAOrALxRUMwU&callback=initMap">
    </script>
  </body>
</html>

