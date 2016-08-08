(function($) {
	$(document).ready( function( event ) {
		console.log( "is called when document is ready !" );
		var isMobile = window.matchMedia("only screen and (max-width: 760px)");
		console.log("check for mobile: " + isMobile); 
		var mobileFlag = "no";
		if (isMobile.matches) {
			console.log("request coming from mobile");
			mobileFlag = "yes";
		}

		$('input[id^="adid"]').each(function(i, obj) {
			var adid = obj.value;
			console.log("adid" + adid);

			$.ajax({
			url: the_ajax_script.ajaxurl,
			type: "POST",
			data: {
					action: "queryamazon",
				    asin : $("#asin"+adid).val(),
					ribbonText : $("#ribbonText"+adid).val(),
					buyBtnText : $("#buyBtnText"+adid).val(),
					publishPrice : $("#publishPrice"+adid).val(),
					picUrl : $("#picUrl"+adid).val(),
					affliateUrl : $("#affliateUrl"+adid).val(),
					productName : $("#productName"+adid).val(),
					productTitle : $("#productTitle"+adid).val(),
					shortDesc : $("#shortDesc"+adid).val(),
					layout : $("#layout"+adid).val(),
					bannerWidthPercent : $("#bannerWidthPercent"+adid).val(),
					bannerFloat : $("#bannerFloat"+adid).val(),
					bannerRightWidthPercent : $("#bannerRightWidthPercent"+adid).val(),
					bannerLeftWidthPercent : $("#bannerLeftWidthPercent"+adid).val(),
					mobileFlag : mobileFlag
				},
			success:function(data) {
				$("#mktHint"+adid).html("");
				$("#mktHint"+adid).html(data);
				console.log(data);
			},
			error: function(errorThrown){
				console.log(errorThrown);
			}
		});
		});
	})
})(jQuery);


function showHint(str) {
  var xhttp;
  if (str.length == 0) {
    document.getElementById("nameHint").innerHTML = "";
    return;
  }
  xhttp = new XMLHttpRequest({mozSystem: true});
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
      document.getElementById("nameHint").innerHTML = xhttp.responseText;
    }
  };
  xhttp.open("GET", "gethint.php?q="+str, true);
  xhttp.send();
};