<?php

    $global_store                                   = array();

	/*** AGB COMPLIANCE TEXT ***/
	$global_store['agb_compliance']                 = array();

    /*** PRODUCTS ***/
    $global_store['products']                       = array();

    /*** FORM-FIELDS ***/
    $global_store['form']                           = array();

    /*** MAIL RELATED ***/
    $global_store['mail']                           = array();

	/*** MESSAGE ***/
	$global_store['message']                        = array();

	/*** ERROR ***/
	$global_store['error']                          = array();
	
    /*** LANGUAGES ***/
    $global_store['lang']                           = "en";
    $global_store['active_lang']                    = "en";

    /*** CURRENCY ***/
    $global_store['currency']                       = "SFr. ";




    //MESSAGE:: DE
	$global_store['message']['success']['de']       = "Vielen Dank...<br />Ihre Bestellung wurde erfolgreich gespeichert.<br />Sie sollten in Kürze per E-Mail eine Bestätigung erhalten.";
	$global_store['message']['failure']['de']       = "Es gab ein Problem bei Abwicklung Ihrer Bestellung.<br />Bitte klicken Sie hier, um es erneut zu versuchen.";
	$global_store['message']['feedback']['de']      = "Feedback";

    //MESSAGE:: FR
	$global_store['message']['success']['fr']       = "Merci beaucoup...<br />Votre commande a été enregistré avec reussit.<br />Vous devriez recevoir une confirmation par courriel dans quelque minutes.";
	$global_store['message']['failure']['fr']       = "Nous n'avons pas pu traiter votre commande.<br />Veuillez cliquer <a href=''>ici</a> pour ressayer de nouveau.";
	$global_store['message']['feedback']['fr']      = "Feedback";

    //MESSAGE:: EN
	$global_store['message']['success']['en']       = "Thank You...<br />Your Order has been successfully saved.<br />You should be receiving a confirmation by email shortly.";
	$global_store['message']['failure']['en']       = "There was a Problem processing your Order.<br /> Please try click <a href=''>here</a> to try again.";
	$global_store['message']['feedback']['en']      = "Feedback";


    //ERRORS:: DE
	$global_store['error']['raw']['de']             = "";
	$global_store['error']['num']['de']             = "Nicht numerische Zeichen oder leere Zeichenfolge in der anstelle einer Zahl gefunden.";
	$global_store['error']['tel']['de']             = "Telefonnummer wurde entweder falsch formatiert oder leer.";
	$global_store['error']['date']['de']            = "Datum wurde entweder nicht richtig formatiert oder leer.";
	$global_store['error']['bool']['de']            = "";
	$global_store['error']['html']['de']            = "";
	$global_store['error']['email']['de']           = "Leere Email Wert oder E-Mail wurde nicht richtig formatiert.";
	$global_store['error']['string']['de']          = "Leerer Wert oder fremde Zeichen im Formularfeld eingebettet. String oder alphanumerische Zeichen erwartet.";
	$global_store['error']['errors']['de']          = "Fehlern";
	$global_store['error']['from']['de']            = "Der Parameter <strong>\"from\"</strong> wurde nicht richtig formatiert. <br/>Das richtige Format ist <strong>YYYY-MM-DD</strong>.";
	$global_store['error']['till']['de']            = "Der Parameter <strong>\"to\"</strong> wurde nicht korrekt formatiert. <br/>Das richtige Format ist <strong>YYYY-MM-DD</strong>";
    $global_store['error']['out_of_bounds']['de']   = "Datum: außerhalb der Grenzen! Die angegebenen Daten des Aufenthaltes sind nicht im akzeptablen Bereich.";
    $global_store['error']['recheck_data']['de']    = "Bitte überprüfen Sie die markierten Texte/Felder unten für mögliche Fehlern.";
    $global_store['error']['param']['de']           = "Parameterfehler";
	$global_store['error']['recaptcha_error']['de'] = "reCAPTCHA was either empty or incorrectly entered.";
	$global_store['error']['alnum']['de']           = "Alphanumeric Characters expected, empty or foreign characters found.";
	$global_store['error']['misc']['de']            = "Alphanumeric Characters expected, empty or foreign characters found.";
	$global_store['error']['pass_mismatch']['de']   = "Passwords do not match.";
	$global_store['error']['username_in_use']['de'] = "This Username already exists. Please try another combination.";



	//ERRORS:: FR
	$global_store['error']['raw']['fr']             = "";
	$global_store['error']['num']['fr']             = "Contrôler ";
	$global_store['error']['tel']['fr']             = "Soit le Numéro de téléphone etait vide ou pas correctement formatée.";
	$global_store['error']['date']['fr']            = "Soit le Champ est vide soit la date n'a pas été correctement formaté.";
	$global_store['error']['bool']['fr']            = "";
	$global_store['error']['html']['fr']            = "";
	$global_store['error']['email']['fr']           = "Verifier que le champ <em>Email</em> n'est ni vide ni mal formatée.";
	$global_store['error']['string']['fr']          = "Caractères Alphanumériques attendus. Aucune Caractères ou Caractères étrangers trouvés.";
	$global_store['error']['errors']['fr']          = "Erreurs";
    $global_store['error']['from']['fr']            = "Le paramètre <strong>\"from\"</strong> n'etait pas formaté correctement. <br/>Le format requis est: <strong>YYYY-MM-DD</strong>.";
    $global_store['error']['till']['fr']            = "Le paramètre <strong>\"to\"</strong> n'etait pas formaté correctement. <br/>Le format requis est: <strong>YYYY-MM-DD</strong>.";
    $global_store['error']['out_of_bounds']['fr']   = "Date: hors limites! Les dates indiquées ne sont pas dans la plage acceptable.";
    $global_store['error']['recheck_data']['fr']    = "Veuillez, s'il vous plaît, re-vérifier les textes/champs soulignés ci-dessous pour des erreurs possibles!";
    $global_store['error']['param']['fr']           = "Erreurs de Paramètres";
	$global_store['error']['recaptcha_error']['fr'] = "reCAPTCHA was either empty or incorrectly entered.";
	$global_store['error']['alnum']['fr']           = "Alphanumeric Characters expected, empty or foreign characters found.";
	$global_store['error']['misc']['fr']            = "Alphanumeric Characters expected, empty or foreign characters found.";
	$global_store['error']['pass_mismatch']['fr']   = "Passwords do not match.";
	$global_store['error']['username_in_use']['fr'] = "This Username already exists. Please try another combination.";




	//ERRORS:: EN
	$global_store['error']['raw']['en']             = "";
	$global_store['error']['num']['en']             = "A Non-Numeric Character or Empty String was found in the Place of a NUMBER.";
	$global_store['error']['tel']['en']             = "Telephone Number was either not Properly Formatted or Empty.";
	$global_store['error']['date']['en']            = "Empty Value or Date was not Properly Formatted.";
	$global_store['error']['bool']['en']            = "";
	$global_store['error']['html']['en']            = "";
	$global_store['error']['email']['en']           = "Empty Email value or Email was not Properly Formatted.";
	$global_store['error']['string']['en']          = "Empty Value or foreign character(s) embedded within Form field. STRING/Alphanumeric Characters expected.";
	$global_store['error']['errors']['en']          = "Errors";
    $global_store['error']['from']['en']            = "The Parameter <strong>\"from\"</strong> was not correctly formatted. <br/>The expected Format is <strong>YYYY-MM-DD</strong>.";
    $global_store['error']['till']['en']            = "The Parameter <strong>\"to\"</strong> was not correctly formatted. <br/>The expected Format is <strong>YYYY-MM-DD</strong>.";
    $global_store['error']['recheck_data']['en']    = "Please re-check the Highlighted Texts/Fields below for Possible Errors!";
    $global_store['error']['out_of_bounds']['en']   = "Date: out of bounds! The given dates are not within acceptable range.";
    $global_store['error']['param']['en']           = "Parameter Errors";
	$global_store['error']['recaptcha_error']['en'] = "reCAPTCHA was either empty or incorrectly entered.";
	$global_store['error']['alnum']['en']           = "Alphanumeric Characters expected, empty or foreign characters found.";
	$global_store['error']['misc']['en']            = "Alphanumeric Characters expected, empty or foreign characters found.";
	$global_store['error']['pass_mismatch']['en']   = "Passwords do not match.";
	$global_store['error']['username_in_use']['en'] = "This Username already exists. Please try another combination.";




    //AGB COMPLIANCE:: DE
	$global_store['agb_compliance']['de']           = "Durch Ankreuzung dieser Checkbox bestätigen Sie unseren AGB gelesen zu haben und damit einverstanden zu sein & bla bla bla ...";

    //AGB COMPLIANCE:: FR
	$global_store['agb_compliance']['fr']           = "By clicking this Checkbox you agree to have read our GTC and bla bla...";

    //AGB COMPLIANCE:: EN
	$global_store['agb_compliance']['en']           = "By clicking this Checkbox you agree to have read our GTC and bla bla...";





	//MAIL:: GLOBAL CONFIG
	$global_store['mail']['default']                = "henrypoizcampbell@gmail.com";
	$global_store['mail']['return']                 = $global_store['mail']['default'];
	$global_store['mail']['mail_from']              = $global_store['mail']['default'];
	$global_store['mail']['cc']                     = "";
	$global_store['mail']['bcc']                    = "";
	$global_store['mail']['sender']                 = "Demo Bread Premium";

	//MAIL:: DE
	$global_store['mail']['success']['de']          = "";
	$global_store['mail']['failure']['de']          = "";
	$global_store['mail']['subject']['de']          = "Bestätigung: Brötchen Service - Demo Bread Premium";

	//MAIL:: FR
	$global_store['mail']['success']['fr']          = "";
	$global_store['mail']['failure']['fr']          = "";
	$global_store['mail']['subject']['fr']          = "Confirmation du Services Boulangerie - Demo Bread Premium";


	//MAIL:: EN
	$global_store['mail']['success']['en']          = "";
	$global_store['mail']['failure']['en']          = "";
	$global_store['mail']['subject']['en']          = "Bakery Service Confirmation - Demo Bread Premium";





    //FORM:: DE
    $global_store['form']['de']['first_name']       = "Vorname";
    $global_store['form']['de']['last_name']        = "Name";
    $global_store['form']['de']['telephone']        = "Telefonnummer";
    $global_store['form']['de']['room_nr']          = "Apartment Nr.";
    $global_store['form']['de']['email']            = "E-Mail";
    $global_store['form']['de']['charge_card']      = "Kreditkarte berechnen";
    $global_store['form']['de']['start_date']		= "Von";
    $global_store['form']['de']['stop_date'] 		= "Bis";
    $global_store['form']['de']['stay_duration']    = "Aufenthaltsdauer";
	$global_store['form']['de']['submit']    		= "Abschicken";
	$global_store['form']['de']['from']    		    = "from";
	$global_store['form']['de']['to']    		    = "to";
	$global_store['form']['de']['title']    		= "Title";
	$global_store['form']['de']['size']    		    = "Size";
	$global_store['form']['de']['size_unit']        = "Size-Unit";
	$global_store['form']['de']['weight']    		= "Weight";
	$global_store['form']['de']['alias']    		= "Alias";
	$global_store['form']['de']['color']    		= "Color";
	$global_store['form']['de']['price']    		= "Price";
	$global_store['form']['de']['price_n']    		= "Normal Price";
	$global_store['form']['de']['price_d']    		= "Discount Price";
	$global_store['form']['de']['manufacturer']    	= "Manufacturer";
	$global_store['form']['de']['sold_on_discount'] = "Sold On Discount";
	$global_store['form']['de']['milage']           = "Mileage";
	$global_store['form']['de']['year']             = "Year";
	$global_store['form']['de']['published']        = "Status";
	$global_store['form']['de']['publish_up']       = "Start Publishing";
	$global_store['form']['de']['publish_down']     = "Stop Publishing";
	$global_store['form']['de']['description']      = "Description";
	$global_store['form']['de']['ordering']         = "Ordering";
	$global_store['form']['de']['sku']              = "SKU";

	//PRODUCT DELIVERY FORM-FIELDS TRANSLATION
	$global_store['form']['de']['delivery_city']        = "City";
	$global_store['form']['de']['delivery_state']       = "State";
	$global_store['form']['de']['delivery_address']     = "Address";
	$global_store['form']['de']['delivery_name']        = "Name";
	$global_store['form']['de']['delivery_email']       = "E-Mail";
	$global_store['form']['de']['delivery_tel']         = "Telephone";
	$global_store['form']['de']['delivery_message']     = "Message";
	$global_store['form']['de']['published']            = "Status";
	$global_store['form']['de']['recaptcha_challenge']  = "reCAPTCHA";
	$global_store['form']['de']['recaptcha_response']   = "reCAPTCHA";
	$global_store['form']['de']['task']                 = "Task";

	//REGISTRATION FORM-FIELDS TRANSLATION
	$global_store['form']['de']['last_name']        = "Last Name";
	$global_store['form']['de']['first_name']       = "First Name";
	$global_store['form']['de']['username']         = "Username";
	$global_store['form']['de']['password']         = "Password";
	$global_store['form']['de']['address']          = "Address";
	$global_store['form']['de']['city']             = "City";
	$global_store['form']['de']['state']            = "State";
	$global_store['form']['de']['country']          = "Country";
	$global_store['form']['de']['telephone']        = "Telephone";




    //FORM:: FR
    $global_store['form']['fr']['first_name']		= "Prénom";
    $global_store['form']['fr']['last_name'] 		= "Nom de Famille";
    $global_store['form']['fr']['telephone'] 		= "Téléphone";
    $global_store['form']['fr']['room_nr']   		= "N&deg;. de l'Apartement";
	$global_store['form']['fr']['email']     		= "Email";
    $global_store['form']['fr']['charge_card']      = "Charger Carte de Crédit";
    $global_store['form']['fr']['start_date']		= "De";
    $global_store['form']['fr']['stop_date'] 		= "À";
    $global_store['form']['fr']['stay_duration']    = "Duration";
    $global_store['form']['fr']['submit']    		= "Submit";
    $global_store['form']['fr']['from']    		    = "from";
    $global_store['form']['fr']['to']    		    = "to";
	$global_store['form']['fr']['title']    		= "Title";
	$global_store['form']['fr']['size']    		    = "Size";
	$global_store['form']['fr']['size_unit']        = "Size-Unit";
	$global_store['form']['fr']['weight']    		= "Weight";
	$global_store['form']['fr']['alias']    		= "Alias";
	$global_store['form']['fr']['color']    		= "Color";
	$global_store['form']['fr']['price']    		= "Price";
	$global_store['form']['fr']['price_n']    		= "Normal Price";
	$global_store['form']['fr']['price_d']    		= "Discount Price";
	$global_store['form']['fr']['manufacturer']    	= "Manufacturer";
	$global_store['form']['fr']['sold_on_discount'] = "Sold On Discount";
	$global_store['form']['fr']['milage']           = "Mileage";
	$global_store['form']['fr']['year']             = "Year";
	$global_store['form']['fr']['published']        = "Status";
	$global_store['form']['fr']['publish_up']       = "Start Publishing";
	$global_store['form']['fr']['publish_down']     = "Stop Publishing";
	$global_store['form']['fr']['description']      = "Description";
	$global_store['form']['fr']['ordering']         = "Ordering";
	$global_store['form']['fr']['sku']              = "SKU";

	//PRODUCT DELIVERY-FORM FIELDS TRANSLATION
	$global_store['form']['fr']['delivery_city']        = "City";
	$global_store['form']['fr']['delivery_state']       = "State";
	$global_store['form']['fr']['delivery_address']     = "Address";
	$global_store['form']['fr']['delivery_name']        = "Name";
	$global_store['form']['fr']['delivery_email']       = "E-Mail";
	$global_store['form']['fr']['delivery_tel']         = "Telephone";
	$global_store['form']['fr']['delivery_message']     = "Message";
	$global_store['form']['fr']['published']            = "Status";
	$global_store['form']['fr']['recaptcha_challenge']  = "reCAPTCHA";
	$global_store['form']['fr']['recaptcha_response']   = "reCAPTCHA";
	$global_store['form']['fr']['task']                 = "Task";

	//REGISTRATION FORM-FIELDS TRANSLATION
	$global_store['form']['fr']['last_name']        = "Last Name";
	$global_store['form']['fr']['first_name']       = "First Name";
	$global_store['form']['fr']['username']         = "Username";
	$global_store['form']['fr']['password']         = "Password";
	$global_store['form']['fr']['address']          = "Address";
	$global_store['form']['fr']['city']             = "City";
	$global_store['form']['fr']['state']            = "State";
	$global_store['form']['fr']['country']          = "Country";
	$global_store['form']['fr']['telephone']        = "Telephone";




    //FORM:: EN
    $global_store['form']['en']['first_name']		= "First Name";
    $global_store['form']['en']['last_name'] 		= "Last Name";
    $global_store['form']['en']['telephone'] 		= "Telephone";
    $global_store['form']['en']['room_nr']   		= "Apartment Nr.";
	$global_store['form']['en']['email']     		= "Email";
    $global_store['form']['en']['charge_card']      = "Charge Credit Card";
    $global_store['form']['en']['start_date']		= "From";
    $global_store['form']['en']['stop_date'] 		= "Till";
    $global_store['form']['en']['stay_duration']    = "Stay Duration";
	$global_store['form']['en']['submit']    		= "Submit";
    $global_store['form']['en']['from']    		    = "from";
    $global_store['form']['en']['to']    		    = "to";
	$global_store['form']['en']['title']    		= "Title";
	$global_store['form']['en']['size']    		    = "Size";
	$global_store['form']['en']['size_unit']        = "Size-Unit";
	$global_store['form']['en']['weight']    		= "Weight";
	$global_store['form']['en']['alias']    		= "Alias";
	$global_store['form']['en']['color']    		= "Color";
	$global_store['form']['en']['price']    		= "Price";
	$global_store['form']['en']['price_n']    		= "Normal Price";
	$global_store['form']['en']['price_d']    		= "Discount Price";
	$global_store['form']['en']['manufacturer']    	= "Manufacturer";
	$global_store['form']['en']['sold_on_discount'] = "Sold On Discount";
	$global_store['form']['en']['milage']           = "Mileage";
	$global_store['form']['en']['year']             = "Year";
	$global_store['form']['en']['published']        = "Status";
	$global_store['form']['en']['publish_up']       = "Start Publishing";
	$global_store['form']['en']['publish_down']     = "Stop Publishing";
	$global_store['form']['en']['description']      = "Description";
	$global_store['form']['en']['ordering']         = "Ordering";
	$global_store['form']['en']['sku']              = "SKU";


	//PRODUCT DELIVERY-FORM FIELDS TRANSLATION
	$global_store['form']['en']['delivery_city']        = "City";
	$global_store['form']['en']['delivery_state']       = "State";
	$global_store['form']['en']['delivery_address']     = "Address";
	$global_store['form']['en']['delivery_name']        = "Name";
	$global_store['form']['en']['delivery_email']       = "E-Mail";
	$global_store['form']['en']['delivery_tel']         = "Telephone";
	$global_store['form']['en']['delivery_message']     = "Message";
	$global_store['form']['en']['published']            = "Status";
	$global_store['form']['en']['recaptcha_challenge']  = "reCAPTCHA";
	$global_store['form']['en']['recaptcha_response']   = "reCAPTCHA";
	$global_store['form']['en']['task']                 = "Task";

	//REGISTRATION FORM-FIELDS TRANSLATION
	$global_store['form']['en']['last_name']        = "Last Name";
	$global_store['form']['en']['first_name']       = "First Name";
	$global_store['form']['en']['username']         = "Username";
	$global_store['form']['en']['password']         = "Password";
	$global_store['form']['en']['address']          = "Address";
	$global_store['form']['en']['city']             = "City";
	$global_store['form']['en']['state']            = "State";
	$global_store['form']['en']['country']          = "Country";
	$global_store['form']['en']['telephone']        = "Telephone";




    /*** MAIL-CONTENT TRANSLATIONS ***/
    $global_store['mail']['en']                     = array();
    $global_store['mail']['de']                     = array();
    $global_store['mail']['fr']                     = array();


    /*** MISCELLANEOUS TRANSLATIONS ***/
    $global_store['misc']['en']                     = array();
    $global_store['misc']['de']                     = array();
    $global_store['misc']['fr']                     = array();




    //MISC:: GLOBALS
	$global_store['misc']['max_bread']              = 20;


    //MISC:: DE
    $global_store['misc']['de']['price']     		= "Preis";
    $global_store['misc']['de']['product']   		= "Artikel";
    $global_store['misc']['de']['photo']     		= "Bild";
    $global_store['misc']['de']['gbk_page_title']   = "GästebuchFormular";
    $global_store['misc']['de']['fullname']         = "Name: ";
    $global_store['misc']['de']['stay_duration']    = "Aufent halt: ";
    $global_store['misc']['de']['apartment']        = "Apartement: ";
	$global_store['misc']['de']['from']		        = "Von: ";
	$global_store['misc']['de']['till'] 		    = "Bis: ";
	$global_store['misc']['de']['total']            = "Total CHF";
	$global_store['misc']['de']['total_price']      = "Total Preis";
	$global_store['misc']['de']['continue']         = "Fortfahren";
	$global_store['misc']['de']['choose_service']   = "Brötchen Optionen wählen.";
	$global_store['misc']['de']['chosen_services']  = "Ausgewählte Artikel(n)";
	$global_store['misc']['de']['order']            = "Verbindlich Bestellen";
	$global_store['misc']['de']['endorse']          = "Verbindlich Bestellen";
	$global_store['misc']['de']['cancel']           = "Abbestellen";
	$global_store['misc']['de']['update']           = "Bearbeiten";
	$global_store['misc']['de']['yes']              = "Ja";
	$global_store['misc']['de']['no']               = "Nein";
	$global_store['misc']['de']['go_back']          = "Zurück";

    //MISC:: FR
    $global_store['misc']['fr']['price']     		= "Prix";
    $global_store['misc']['fr']['product']   		= "Article";
    $global_store['misc']['fr']['photo']     		= "Image";
	$global_store['misc']['fr']['gbk_page_title']   = "Formulaire Livre d'Or";
	$global_store['misc']['fr']['fullname']         = "Nom : ";
	$global_store['misc']['fr']['stay_duration']    = "Séjour : ";
	$global_store['misc']['fr']['apartment']        = "Appartement : ";
	$global_store['misc']['fr']['from']		        = "Dès : ";
	$global_store['misc']['fr']['till'] 		    = "Jusqu' à : ";
	$global_store['misc']['fr']['total']            = "Totale SFR";
	$global_store['misc']['fr']['total_price']      = "Prix Totale";
	$global_store['misc']['fr']['continue']         = "Continuer";
	$global_store['misc']['fr']['choose_service']   = "Choisissez votre options du pain.";
	$global_store['misc']['fr']['chosen_services']  = "Articles choisi.";
	$global_store['misc']['fr']['order']            = "Commander";
	$global_store['misc']['fr']['endorse']          = "Commander";
	$global_store['misc']['fr']['cancel']           = "Annuler";
	$global_store['misc']['fr']['update']           = "Mettre à Jour";
	$global_store['misc']['fr']['yes']              = "Oui";
	$global_store['misc']['fr']['no']               = "Non";
	$global_store['misc']['fr']['go_back']          = "Retourné";

    //MISC:: EN
    $global_store['misc']['en']['price']     		= "Price";
    $global_store['misc']['en']['product']   		= "Product";
    $global_store['misc']['en']['photo']     		= "Image";
	$global_store['misc']['en']['gbk_page_title']   = "Guestbook Form";
	$global_store['misc']['en']['fullname']         = "Name: ";
	$global_store['misc']['en']['stay_duration']    = "Sojourn: ";
	$global_store['misc']['en']['apartment']        = "Room Nr: ";
	$global_store['misc']['en']['from']		        = "From: ";
	$global_store['misc']['en']['till'] 		    = "Till: ";
	$global_store['misc']['en']['total']            = "Total CHF";
	$global_store['misc']['en']['total_price']      = "Total Price";
	$global_store['misc']['en']['continue']         = "Continue";
	$global_store['misc']['en']['choose_service']   = "Choose Bread Options.";
	$global_store['misc']['en']['chosen_services']  = "Selected Articles.";
	$global_store['misc']['en']['order']            = "Place Order";
	$global_store['misc']['en']['endorse']          = "Place Order";
	$global_store['misc']['en']['cancel']           = "Cancel";
	$global_store['misc']['en']['update']           = "Update";
	$global_store['misc']['en']['yes']              = "Yes";
	$global_store['misc']['en']['no']               = "No";
	$global_store['misc']['en']['go_back']          = "Back";



/*****************************************************************************************/
/*****************************************************************************************/
/*****************************************************************************************/


    //PRICES
    // USE "CENTILES": EG. SFR 2.50 == 250 CENTS ;-)"$global_store['products'][0]['price']	= '100';
	$global_store['products'][0]['price']	        = 100;
	$global_store['products'][1]['price']	        = 130;
	$global_store['products'][2]['price']	        = 90;
	$global_store['products'][3]['price']	        = 100;
	$global_store['products'][4]['price']	        = 100;
	$global_store['products'][5]['price']	        = 100;
	$global_store['products'][6]['price']	        = 100;
	$global_store['products'][7]['price']	        = 150;
	$global_store['products'][8]['price']	        = 130;


    //THUMBNAILS
	$global_store['products'][0]['thumbnail']       = "./assets/images/thumbs/croissant.jpg";
	$global_store['products'][1]['thumbnail']       = "./assets/images/thumbs/hoernchen.jpg";
	$global_store['products'][2]['thumbnail']       = "./assets/images/thumbs/vollbrot.jpg";
	$global_store['products'][3]['thumbnail']       = "./assets/images/thumbs/broetchen.jpg";
	$global_store['products'][4]['thumbnail']       = "./assets/images/thumbs/baguette.jpg";
	$global_store['products'][5]['thumbnail']       = "./assets/images/thumbs/weggli.jpg";
	$global_store['products'][6]['thumbnail']       = "./assets/images/thumbs/schoggi_weggli.jpg";
	$global_store['products'][7]['thumbnail']       = "./assets/images/thumbs/sandwich.jpg";
	$global_store['products'][8]['thumbnail']       = "./assets/images/thumbs/donuts.jpg";



	//PRODUCTS
    //DEUTSCHE ÜBERSETZUNG
	$global_store['products'][0]['title']['de']     = "Croissant";
	$global_store['products'][1]['title']['de']     = "Hörnchen";
	$global_store['products'][2]['title']['de']     = "Vollbrot";
	$global_store['products'][3]['title']['de']     = "Brötchen";
	$global_store['products'][4]['title']['de']     = "Baguette";
	$global_store['products'][5]['title']['de']     = "Weggli";
	$global_store['products'][6]['title']['de']     = "Schöggi Weggli";
	$global_store['products'][7]['title']['de']     = "Sandwich";
	$global_store['products'][8]['title']['de']     = "Donuts";



	//PRODUCTS
    //TRADUCTIONS FRANÇAIS
	$global_store['products'][0]['title']['fr']     = "Croissant";
	$global_store['products'][1]['title']['fr']     = "Croissant";
	$global_store['products'][2]['title']['fr']     = "Pain Complet";
	$global_store['products'][3]['title']['fr']     = "Petit Pain";
	$global_store['products'][4]['title']['fr']     = "Baguette";
	$global_store['products'][5]['title']['fr']     = "Weggli";
	$global_store['products'][6]['title']['fr']     = "Weggli Chocolat";
	$global_store['products'][7]['title']['fr']     = "Sandwich";
	$global_store['products'][8]['title']['fr']     = "Donuts";



	//PRODUCTS
    //ENGLISH TRANSLATIONS
	$global_store['products'][0]['title']['en']     = "Croissant";
	$global_store['products'][1]['title']['en']     = "Croissants";
	$global_store['products'][2]['title']['en']     = "Full Bread";
	$global_store['products'][3]['title']['en']     = "Bun";
	$global_store['products'][4]['title']['en']     = "Baguette";
	$global_store['products'][5]['title']['en']     = "Weggli";
	$global_store['products'][6]['title']['en']     = "Chocolate Weggli";
	$global_store['products'][7]['title']['en']     = "Sandwich";
	$global_store['products'][8]['title']['en']     = "Donuts";