<?php

function queryAmazon(){
	//error_log("queryAmazon ++++++ --- ");
	
	// The $_REQUEST contains all the data sent via ajax
    if ( isset($_REQUEST) ) {
		$asin = $_REQUEST['asin'];
		$ribbonText = $_REQUEST['ribbonText'];
		$buyBtnText = $_REQUEST['buyBtnText'];
		$publishPrice = $_REQUEST['publishPrice'];
		$picUrl = $_REQUEST['picUrl'];
		$affliateUrl = $_REQUEST['affliateUrl'];
		$productName = $_REQUEST['productName'];
		$productTitle = $_REQUEST['productTitle'];
		$shortDesc = $_REQUEST['shortDesc'];
		$mobileFlag = $_REQUEST['mobileFlag'];
		$layout = $_REQUEST['layout'];
		$bannerWidthPercent = $_REQUEST['bannerWidthPercent'];
		$bannerFloat = $_REQUEST['bannerFloat'];
		$bannerRightWidthPercent = $_REQUEST['bannerRightWidthPercent'];
		$bannerLeftWidthPercent = $_REQUEST['bannerLeftWidthPercent'];
    }
	
	if($mobileFlag === 'yes'){
		$displayDevice = '-m';
	}else{
		$displayDevice = '';
	}
	

	$adverbAttributes = shortcode_atts( array(
        'asin' => '',
		'ribbonText' => 'Our Pick',
		'buyBtnText' => 'Buy here',
		'publishPrice' => 0,
		'picUrl' => 'Coming Soon PIC URL',
		'affliateUrl' => 'http://candytech.in',
		'productName' => 'Sample Product',
		'productTitle' => 'Product Title',
		'shortDesc' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer varius suscipit urna. Ut ornare quam vitae neque elementum, et dictum nulla tincidunt.',
		'displayDevice' => $displayDevice,
		'layout' => 'imageDisclaimerLeftLayout',
		'bannerWidthPercent' => '60',
		'bannerFloat' => 'right',
		'bannerRightWidthPercent' => '60',
		'bannerLeftWidthPercent' => '32'
    ), $_REQUEST );
	
	if($mobileFlag === 'yes'){
		$adverbAttributes['displayDevice'] = '-m';
	}else{
		$adverbAttributes['displayDevice'] = '';
	}

	/* Later Usage */
	/*
	switch ($mobileflag) {
    case "450px":
        echo "write logic for 450px device";
        break;
    case "650px":
        echo "write logic for 650px device";
        break;
    case "850px":
        echo "write logic for 850px device";
        break;
	case "1150px": 
        echo "write logic for 1150px device";
        break;
	case "1450px":
        echo "write logic for 1450px device";
        break;
	case "1750px":
        echo "write logic for 1750px device";
        break;
    default:
        echo "Your favorite color is neither red, blue, nor green!";
	
	}*/
	
	//error_log('json adverb attribute to be used to layout design '.json_encode($adverbAttributes));
    
	//$response = ''; 
	//getAmazonPrice("in", $asin);
	//preparing affiliate URL from affTag
	//if(!isset($affliateurl) || trim($affliateurl)===''){
	//$affliateurl = $response['url'].'&tag=candytech-21';
	//}
	
	//echo imageBuyBtnDisclaimerLeftLayout($adverbAttributes);
	//echo imageDisclaimerLeftLayout($adverbAttributes);
	//echo imageLeftLayout($adverbAttributes);

	if(!(!isset($_REQUEST['layout']) || trim($_REQUEST['layout'])==='')){
		$layout = $_REQUEST['layout'];
		switch ($layout) {
			case "imageBuyBtnDisclaimerLeftLayout":
				echo imageBuyBtnDisclaimerLeftLayout($adverbAttributes);
				break;
			case "imageDisclaimerLeftLayout":
				echo imageDisclaimerLeftLayout($adverbAttributes);
				break;
			case "imageLeftLayout":
				echo imageLeftLayout($adverbAttributes);
				break;
			default:
				echo imageBuyBtnDisclaimerLeftLayout($adverbAttributes);
		}
	}

	
	// Always die in functions echoing ajax content
	wp_die();
}
 
function getAmazonPrice($region, $asin) {
	//error_log("getAmazonPrice ++++++ ");
	$xml = aws_signed_request($region, array(
		"Operation" => "ItemLookup",
		"ItemId" => $asin,
		"IncludeReviewsSummary" => False,
		"ResponseGroup" => "Medium,OfferSummary",
	));
	
	//error_log($xml);
 
	$item = $xml->Items->Item;
	$title = htmlentities((string) $item->ItemAttributes->Title);
	// $longDesc = htmlentities((string) $item->EditorialReviews->EditorialReview->Content);
	$longDesc = htmlentities((string) $item->ItemAttributes->Feature);
	$url = htmlentities((string) $item->DetailPageURL);
	$image = htmlentities((string) $item->MediumImage->URL);
	$price = htmlentities((string) $item->OfferSummary->LowestNewPrice->Amount);
	$currencyCode = htmlentities((string) $item->OfferSummary->LowestNewPrice->CurrencyCode);
	$qty = htmlentities((string) $item->OfferSummary->TotalNew);
 
	if ($qty !== "0") {
		$response = array(
			"currencyCode" => $currencyCode,
			"price" => number_format((float) ($price / 100), 2, '.', ''),
			"image" => $image,
			"url" => $url,
			"title" => $title,
			"longDesc" => $longDesc
		);
	}
	//error_log("response from getAmazonPrice");
	//error_log("".json_encode($response));
 
	return $response;
}
 
function getPage($url) {
 
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_FAILONERROR, true);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	$html = curl_exec($curl);
	curl_close($curl);
	return $html;
}
 
function awsSignedRequest($region, $params) {
    //error_log("aws_signed_request ++++++ ");
	$public_key = "AKIAIJ3VVUCIRKJCDBHQ";
	$private_key = "4+Y/3blHIX5nF/6OtVGAdFPIINAFVYGpwGGKUvd2";
	 
	$method = "GET";
	$host = "ecs.amazonaws." . $region;
	$host = "webservices.amazon." . $region;
	$uri = "/onca/xml";
 
	$params["Service"] = "AWSECommerceService";
	$params["AssociateTag"] = "nixagilecom-21"; // Put your Affiliate Code here
	$params["AWSAccessKeyId"] = $public_key;
	$params["Timestamp"] = gmdate("Y-m-d\TH:i:s\Z");
	$params["Version"] = "2016-07-19";
 
	ksort($params);
 
	$canonicalized_query = array();
	foreach ($params as $param => $value) {
		$param = str_replace("%7E", "~", rawurlencode($param));
		$value = str_replace("%7E", "~", rawurlencode($value));
		$canonicalized_query[] = $param . "=" . $value;
	}
 
	$canonicalized_query = implode("&", $canonicalized_query);
 
	$string_to_sign = $method . "\n" . $host . "\n" . $uri . "\n" . $canonicalized_query;
	$signature = base64_encode(hash_hmac("sha256", $string_to_sign, $private_key, True));
	$signature = str_replace("%7E", "~", rawurlencode($signature));
 
	$request = "http://" . $host . $uri . "?" . $canonicalized_query . "&Signature=" . $signature;
	$response = getPage($request);
 
	//error_log($request);
	//error_log($response);
 
	$pxml = @simplexml_load_string($response);
	if ($pxml === False) {
		return False;// no xml
	} else {
		//error_log("printing pxml ".$pxml);
		return $pxml;
	}
}

/* Layout Design
product
 product-inner
  product-ribbon
  product-left
   product-thumbnail
   buy-product
    product-pricebox(buy-button)
    product-disclaimer
     product-publish-price
  product-right
   product-title
   product-name
   product-desc
*/
function imageBuyBtnDisclaimerLeftLayout($adverbAtts){
	$displayDevice = $adverbAtts['displayDevice'];
	$ribbonText = $adverbAtts['ribbonText'];
	$affliateUrl = $adverbAtts['affliateUrl'];
	$picUrl = $adverbAtts['picUrl'];
	$buyBtnText = $adverbAtts['buyBtnText'];
	$publishPrice = $adverbAtts['publishPrice'];
	$productTitle = $adverbAtts['productTitle'];
	$productName = $adverbAtts['productName'];
	$shortDesc = $adverbAtts['shortDesc'];
	$layout = $adverbAtts['layout'];
	$bannerWidthPercent = $adverbAtts['bannerWidthPercent'];
	$bannerFloat = $adverbAtts['bannerFloat'];
	$bannerRightWidthPercent = $adverbAtts['bannerRightWidthPercent'];
	$bannerLeftWidthPercent = $adverbAtts['bannerLeftWidthPercent'];
	
	$output = '';
	// Building Ribbon
	$ribbonDiv = '';
	$ribbonDiv = productRibbonHtml($displayDevice, $ribbonText);
	
    // Building Left
    $leftDiv = '';
	$productThumbnailDiv = productThumbnail($displayDevice, $affliateUrl, $picUrl);
	$buyBtnDiv = buyButtonHtml($displayDevice, $buyBtnText, $affliateUrl);
    $prodDisclaimerDiv = productDisclaimerHtml($displayDevice, $publishPrice);
	$buyProductDiv = buyProductHtml($displayDevice, $buyBtnDiv.$prodDisclaimerDiv);
	$leftDiv .= productLeftHtml($displayDevice, $productThumbnailDiv.$buyProductDiv, $bannerLeftWidthPercent);
	
	// Building Right
	$rightDiv = '';
	$productTitleDiv = productTitleHtml($displayDevice, $affliateUrl, $productTitle );
	$productNameDiv = productNameHtml($displayDevice, $affliateUrl, $productName);
	$productShortDescDiv = productShortdescHtml($displayDevice, $shortDesc);
	$rightDiv = productRightHtml($displayDevice, $productTitleDiv.$productNameDiv.$productShortDescDiv, $bannerRightWidthPercent );
	
	// Building Product Inner Div
	$prodInnerDiv = productInnerHtml($displayDevice, $ribbonDiv.$leftDiv.$rightDiv);
	
	// Building Product Div
	$output .= productHtml($displayDevice, $prodInnerDiv, $bannerWidthPercent, $bannerFloat);
	
   return $output;
}



/* Layout Design
product
 product-inner
  product-ribbon
  product-left
   product-thumbnail
  product-right
   product-title
   product-name
   product-desc
   buy-product
    product-pricebox(buy-button)
	product-disclaimer
     product-publish-price
*/
function imageDisclaimerLeftLayout($adverbAtts){
	$displayDevice = $adverbAtts['displayDevice'];
	$ribbonText = $adverbAtts['ribbonText'];
	$affliateUrl = $adverbAtts['affliateUrl'];
	$picUrl = $adverbAtts['picUrl'];
	$buyBtnText = $adverbAtts['buyBtnText'];
	$publishPrice = $adverbAtts['publishPrice'];
	$productTitle = $adverbAtts['productTitle'];
	$productName = $adverbAtts['productName'];
	$shortDesc = $adverbAtts['shortDesc'];
	$layout = $adverbAtts['layout'];
	$bannerWidthPercent = $adverbAtts['bannerWidthPercent'];
	$bannerFloat = $adverbAtts['bannerFloat'];
	$bannerRightWidthPercent = $adverbAtts['bannerRightWidthPercent'];
	$bannerLeftWidthPercent = $adverbAtts['bannerLeftWidthPercent'];
	
	$output = '';
	// Building Ribbon
	$ribbonDiv = '';
	$ribbonDiv = productRibbonHtml($displayDevice, $ribbonText);
	
    // Building Left
    $leftDiv = '';
	$productThumbnailDiv = productThumbnail($displayDevice, $affliateUrl, $picUrl);
	
    $prodDisclaimerDiv = productDisclaimerHtml($displayDevice, $publishPrice);

	$leftDiv .= productLeftHtml($displayDevice, $productThumbnailDiv.$prodDisclaimerDiv, $bannerLeftWidthPercent);
	
	// Building Right
	$rightDiv = '';
	$productTitleDiv = productTitleHtml($displayDevice, $affliateUrl, $productTitle );
	$productNameDiv = productNameHtml($displayDevice, $affliateUrl, $productName);
	$productShortDescDiv = productShortdescHtml($displayDevice, $shortDesc);
	$buyBtnDiv = buyButtonHtml($displayDevice, $buyBtnText, $affliateUrl);
	$buyProductDiv = buyProductHtml($displayDevice, $buyBtnDiv);
	$rightDiv = productRightHtml($displayDevice, $productTitleDiv.$productNameDiv.$productShortDescDiv.$buyProductDiv, $bannerRightWidthPercent );
	
	// Building Product Inner Div
	$prodInnerDiv = productInnerHtml($displayDevice, $ribbonDiv.$leftDiv.$rightDiv);
	
	// Building Product Div
	$output .= productHtml($displayDevice, $prodInnerDiv, $bannerWidthPercent, $bannerFloat);
	
   return $output;
}



/* Layout Design
product
 product-inner
  product-ribbon
  product-left
   product-thumbnail
   product-disclaimer
  product-right
   product-title
   product-name
   product-desc
   buy-product
    product-pricebox(buy-button)
     product-publish-price
*/
function imageLeftLayout($adverbAtts){
	$displayDevice = $adverbAtts['displayDevice'];
	$ribbonText = $adverbAtts['ribbonText'];
	$affliateUrl = $adverbAtts['affliateUrl'];
	$picUrl = $adverbAtts['picUrl'];
	$buyBtnText = $adverbAtts['buyBtnText'];
	$publishPrice = $adverbAtts['publishPrice'];
	$productTitle = $adverbAtts['productTitle'];
	$productName = $adverbAtts['productName'];
	$shortDesc = $adverbAtts['shortDesc'];
	$layout = $adverbAtts['layout'];
	$bannerWidthPercent = $adverbAtts['bannerWidthPercent'];
	$bannerFloat = $adverbAtts['bannerFloat'];
	$bannerRightWidthPercent = $adverbAtts['bannerRightWidthPercent'];
	$bannerLeftWidthPercent = $adverbAtts['bannerLeftWidthPercent'];
	
	$output = '';
	// Building Ribbon
	$ribbonDiv = '';
	$ribbonDiv = productRibbonHtml($displayDevice, $ribbonText);
	
    // Building Left
    $leftDiv = '';
	$productThumbnailDiv = productThumbnail($displayDevice, $affliateUrl, $picUrl);
	$leftDiv .= productLeftHtml($displayDevice, $productThumbnailDiv, $bannerLeftWidthPercent);
	
	// Building Right
	$rightDiv = '';
	$productTitleDiv = productTitleHtml($displayDevice, $affliateUrl, $productTitle );
	$productNameDiv = productNameHtml($displayDevice, $affliateUrl, $productName);
	$productShortDescDiv = productShortdescHtml($displayDevice, $shortDesc);
	$buyBtnDiv = buyButtonHtml($displayDevice, $buyBtnText, $affliateUrl);
    $prodDisclaimerDiv = productDisclaimerHtml($displayDevice, $publishPrice);
	$buyProductDiv = buyProductHtml($displayDevice, $buyBtnDiv.$prodDisclaimerDiv);
	$rightDiv = productRightHtml($displayDevice, $productTitleDiv.$productNameDiv.$productShortDescDiv.$buyProductDiv, $bannerRightWidthPercent );
	
	// Building Product Inner Div
	$prodInnerDiv = productInnerHtml($displayDevice, $ribbonDiv.$leftDiv.$rightDiv);
	
	// Building Product Div
	$output .= productHtml($displayDevice, $prodInnerDiv, $bannerWidthPercent, $bannerFloat);
	
   return $output;
}



/* Building Blocks */
function productHtml($displayDevice, $productInnerHTML, $bannerWidthPercent, $bannerFloat){
	//error_log('Product Banner Width: '.$bannerWidthPercent);
	//error_log('Product Banner Float: '.$bannerFloat);
	if($displayDevice===''){
		$productStyle = '';
		$bannerWidthCss = '';
		$bannerFloatCss = '';
		if(!(!isset($bannerWidthPercent) || trim($bannerWidthPercent)==='')){
			$bannerWidthCss .= 'width:'.$bannerWidthPercent.'%';
		}
		//error_log('Product Banner Width css: '.$bannerWidthCss);
		
		if(!(!isset($bannerFloat) || trim($bannerFloat)==='')){
			$bannerFloatCss .= 'float:'.$bannerFloat;
		}
		//error_log('Product Banner Float css: '.$bannerFloatCss);
		
		if(  !(!isset($bannerWidthCss) || trim($bannerWidthCss)==='') && !(!isset($bannerFloatCss) || trim($bannerFloatCss)==='')){
			$productStyle = 'style="'.$bannerWidthCss.';'.$bannerFloatCss.'"';
		}
	}
	$output = '';
	$output .= '<div class="product'.$displayDevice.' clearfix'.$displayDevice.' product-align-right'.$displayDevice.'" '.$productStyle.'">';
	$output .= $productInnerHTML;
	$output .= '</div>';
	return $output;
}

function productInnerHtml($displayDevice, $ribbonLeftRightHTML){
	$output = '';
	$output .= '<div class="product-inner'.$displayDevice.'">';
	$output .= $ribbonLeftRightHTML;
	$output .= '</div>';
	return $output;
}

function productRibbonHtml($displayDevice, $ribbonText){
	//error_log('banner ribbon Text: '.$ribbonText);
	$output = '';
	if(!(!isset($ribbonText) || trim($ribbonText)==='')){
		$output .= '<div class="product-ribbon'.$displayDevice.'"><span>'.$ribbonText.'</span></div>';
	}
	return $output;
}

function productLeftHtml($displayDevice, $buyBtnThumbnailInnerHTML, $bannerLeftWidthPercent){
	if(!($displayDevice==='')){
		$bannerLeftWidthPercent = '100';
	}
	//error_log('banner left width: '.$bannerLeftWidthPercent);
	$output = '';
	if(!($bannerLeftWidthPercent === '0')){
		if(!(!isset($buyBtnThumbnailInnerHTML) || trim($buyBtnThumbnailInnerHTML)==='')){
			$output .= '<div class="product-left'.$displayDevice.'" style="width:'.$bannerLeftWidthPercent.'%">';
			$output .= $buyBtnThumbnailInnerHTML;
			$output .= '</div>';
		}	
	}
	return $output;
}

function productRightHtml($displayDevice, $titleNameDescInnerHTML, $bannerRightWidthPercent){
	if(!($displayDevice==='')){
		$bannerRightWidthPercent = '100';
	}
	//error_log('banner right width: '.$bannerRightWidthPercent);
	$output = '';
	if(!($bannerRightWidthPercent === '0')){
		if(!(!isset($titleNameDescInnerHTML) || trim($titleNameDescInnerHTML)==='')){
			$output .= '<div class="product-right'.$displayDevice.'" style="width:'.$bannerRightWidthPercent.'%">';
			$output .= $titleNameDescInnerHTML;
			$output .= '</div>';
		}
	}
		
	return $output;
}

function productThumbnail($displayDevice, $affliateUrl, $picUrl){
	//error_log('banner picURL: '.$picUrl);
	$output = '';
	if(!(!isset($picUrl) || trim($picUrl)==='')){
		$output .= '<a class="product-thumbnail'.$displayDevice.'" href="'.$affliateUrl.'" rel="nofollow" target="_blank"><img src="'.$picUrl.'" itemprop="image"></a>';
	}
	return $output;
}

function buyProductHtml($displayDevice, $buyButtonDisclaimerInnerHTML){ /* Buy Button Assembly */
	$output = '';
	if(!(!isset($buyButtonDisclaimerInnerHTML) || trim($buyButtonDisclaimerInnerHTML)==='')){
		$output .= '<div class="buy-product'.$displayDevice.'">';
		$output .= $buyButtonDisclaimerInnerHTML;
		$output .= '</div>';
	}
	return $output;
}

function buyButtonHtml($displayDevice, $buyBtnText, $affliateUrl){
	//error_log('banner buy button text: '.$buyBtnText);
	//error_log('banner Affliate URL: '.$affliateUrl);
	$output = '';
	if(!(!isset($buyBtnText) || trim($buyBtnText)==='')){
		$output .= '<div class="product-pricebox'.$displayDevice.'" style="">';
		$output .= '<a href="'.$affliateUrl.'" rel="nofollow" target="_blank">';
		$output .= $buyBtnText;	
		$output .= '</a>';
		$output .= '</div>';
	}
	return $output;
}

function productDisclaimerHtml($displayDevice, $publishPrice ){
	//error_log('banner product disclaimer: '.$publishPrice);
	$output = '';
	if(!(!isset($publishPrice) || trim($publishPrice)==='')){
		$output .= '<div class="product-disclaimer'.$displayDevice.'">';
		$output .= '<p class="product-publish-price">*At the time of publishing, the price was Rs '.$publishPrice.'.</p>';
		$output .= '</div>';
	}
	return $output;
}

function productTitleHtml($displayDevice, $affliateUrl, $productTitle ){
	//error_log('banner product title: '.$productTitle);
	$output = '';
	if(!(!isset($productTitle) || trim($productTitle)==='')){
		$output .= '<div class="product-title'.$displayDevice.'">';
		$output .= '<a href="'.$affliateUrl.'" itemprop="name url" rel="nofollow" target="_blank" class="product-title'.$displayDevice.' product-name'.$displayDevice.'">'.$productTitle.'</a>';
		$output .= '</div>';
	}
	return $output;
}

function productNameHtml($displayDevice, $affliateUrl, $productName){
	//error_log('banner product name: '.$productName);
	$output = '';
	if(!(!isset($productName) || trim($productName)==='')){
		$output .= '<a href="'.$affliateUrl.'" class="product-make-model'.$displayDevice.' product-name'.$displayDevice.'" rel="nofollow" target="_blank">'.$productName.'</a>';
	}
	return $output;
}

function productShortdescHtml($displayDevice, $shortDesc){
	//error_log('banner short description: '.$shortDesc);
	$output = '';
	if(!(!isset($shortDesc) || trim($shortDesc)==='')){
		$output .= '<div class="product-text'.$displayDevice.'" itemprop="description">'.$shortDesc.'.</div>';	
	}
	return $output;
}
/* Building Blocks */




/***

function buildHtml($item_detail, $ribbon_text, $buybtntext, $publish_price, $pic_url, $affliate_url, $product_name, $product_title, $short_desc, $mobileflag){
	
	$mobile_identifier = ($mobileflag === "yes") ? '-m' : ''; // returns true
	
	$output = '';	
	$output .= '<div class="product'.$mobile_identifier.' clearfix'.$mobile_identifier.' product-align-right'.$mobile_identifier.'">';
	$output .= '<div class="product-inner'.$mobile_identifier.'">';
	$output .= '<div class="product-ribbon'.$mobile_identifier.'"><span>'.$ribbon_text.'</span></div>';
	$output .= '<div class="product-left'.$mobile_identifier.'">';
	$output .= '<a class="product-thumbnail'.$mobile_identifier.'" href="'.$affliate_url.'" rel="nofollow" target="_blank"><img src="'.$pic_url.'" itemprop="image"></a>';
	//$output .= '<div class="buy-product'.$mobile_identifier.'">';
	//$output .= '<div class="product-pricebox'.$mobile_identifier.'" style="">';
	//$output .= '<a href="'.$affliate_url.'" rel="nofollow" target="_blank">';
	//$output .= $buybtntext;	
	//$output .= '</a>';
	//$output .= '</div>';
	//$output .= '<div class="product-disclaimer'.$mobile_identifier.'">';
	//$output .= '<p class="js-price-change-notice">*At the time of publishing, the price was Rs '.$publish_price.'.</p>';
	//$output .= '</div>';
	//$output .= '</div>';
	$output .= '</div>';
	$output .= '<div class="product-right'.$mobile_identifier.'">';
	$output .= '<div class="product-title'.$mobile_identifier.'">';
	$output .= '<a href="'.$affliate_url.'" itemprop="name url" rel="nofollow" target="_blank" class="product-title'.$mobile_identifier.' product-name'.$mobile_identifier.'">'.$product_title.'</a>';
	$output .= '</div>';
	$output .= '<a href="'.$affliate_url.'" class="product-make-model'.$mobile_identifier.' product-name'.$mobile_identifier.'" rel="nofollow" target="_blank">'.$product_name.'</a>';
	$output .= '<div class="product-text'.$mobile_identifier.'" itemprop="description">'.$short_desc.'.</div>';
	/*Buy Button HTML Generator*
	$output .= '<div class="buy-product'.$mobile_identifier.'">';
	$output .= '<div class="product-pricebox'.$mobile_identifier.'" style="">';
	$output .= '<a href="'.$affliate_url.'" rel="nofollow" target="_blank">';
	$output .= $buybtntext;	
	$output .= '</a>';
	$output .= '</div>';
	$output .= '<div class="product-disclaimer'.$mobile_identifier.'">';
	$output .= '<p class="js-price-change-notice">*At the time of publishing, the price was Rs '.$publish_price.'.</p>';
	$output .= '</div>';
	$output .= '</div>';
	/**
	$output .= '</div>';
	$output .= '</div>	</div>';
	
	return $output;
}
**/


add_action( 'wp_ajax_aws_signed_request', 'queryAmazon' );
add_action('wp_ajax_nopriv_aws_signed_request', 'queryAmazon' );


add_action( 'wp_ajax_queryamazon', 'queryAmazon' );
add_action('wp_ajax_nopriv_queryamazon', 'queryAmazon' );

 
?>