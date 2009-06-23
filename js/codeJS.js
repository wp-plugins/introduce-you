jQuery(document).ready(function() {

	// include translation file according to WP_LOCALE_SOLUO defined
	//

	function translate( myString ) {
		if ( typeof( translationFile ) != "undefined" && typeof( translationFile[myString] ) != "undefined" ) {
			return translationFile[myString].toString();
		} else {
			return myString;
		}
	}
	
	function printf(S, L) {
		if ( typeof( L ) != "Array" ) L = Array( L );
		var nS = "";
		var tS = S.split("%s");
		if (tS.length != L.length+1) throw "Input error";

		for(var i=0; i<L.length; i++)
			nS += tS[i] + L[i];
		return nS + tS[tS.length-1];
	}
	
	
	/* Ajout du bouton d insertion */
	jQuery(".savesend > input").after( printf( "<input class=\"button IntroduceImageButton\" type=\"submit\" value=\"%s\" />", translate("Define as my photo") ) );

	// Tous les click sont écoutés
	jQuery("body").click(function(event) {
		// On souhaite définir une image comme image de fond
		if( jQuery(event.target).is(".IntroduceImageButton") ) {
			var t = this;
			IntroduceImageButton = event.target;
			// Sélection de l'URL par le bouton
			var fileurl = jQuery(IntroduceImageButton).parents("tr").siblings().find("button.urlfile").attr("title");
			var attachementId = jQuery(IntroduceImageButton).siblings("input.button").attr("name");
			attachementId = attachementId.substring(5, attachementId.length - 1);
			//console.log(fileurl);
			jQuery("#IntroduceYouImageUrl", top.document).val(fileurl);
			jQuery("#IntroduceYouImageId", top.document).val(attachementId);
			jQuery("#IntroduceYouImageTarget", top.document).attr("href",fileurl);
			jQuery("#IntroduceYouImage", top.document).attr("src",fileurl);
			top.tb_remove();
			return false;
		}

	});
	
	jQuery().ajaxComplete(function(event){
		/*
		<td class="savesend">
			<input type="submit" value="Insert into Post" name="send[271]" class="button"/>
			<input type="submit" value="Define as background" class="button bgButton"/>
		</td>
		*/
		jQuery(".savesend").filter(function(index) {
			return jQuery(".IntroduceImageButton",this).length == 0;
		}).find("input:first").after( printf( "<input class=\"button IntroduceImageButton\" type=\"submit\" value=\"%s\" />", translate("Define as my photo") ) );

	});

});
