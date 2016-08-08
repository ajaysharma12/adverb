<?php
/**
 * scode function file
 */

function adverbShortcode( $atts ) {
	//error_log("+++ in adverbShortcode +++",0);
	//error_log(json_encode($atts),0);

	$pull_quote_atts = shortcode_atts( array(
		'adid' => 0, 
        'asin' => '0000xx0000',
		'ribbon_text' => 'Our Ribbon Text',
		'buy_btn_text' => 'Buy here',
		'publish_price' => 000,
		'pic_url' => 'Coming Soon PIC URL',
		'affliate_url' => 'http://candytech.in',
		'product_name' => 'Adverb v1.0',
		'product_title' => 'Product Coming Soon',
		'short_desc' => 'Pending Release',
		'layout' => "imageDisclaimerLeftLayout",
		'banner_width_percent' => "60", 
		'banner_float' => "right", 
		'banner_right_width_percent' => "32", 
		'banner_left_width_percent' => "60"
    ), $atts );
	
	$pull_quote_atts[ 'adid' ] = (!(!isset($pull_quote_atts[ 'adid' ]) || trim($pull_quote_atts[ 'adid' ])==='')) ? $pull_quote_atts[ 'adid' ] : '0';
	$pull_quote_atts[ 'asin' ] = (!(!isset($pull_quote_atts[ 'asin' ]) || trim($pull_quote_atts[ 'asin' ])==='')) ? $pull_quote_atts[ 'asin' ] : '0000xx0000';
	$pull_quote_atts[ 'ribbon_text' ] = (!(!isset($pull_quote_atts[ 'ribbon_text' ]) || trim($pull_quote_atts[ 'ribbon_text' ])==='')) ? $pull_quote_atts[ 'ribbon_text' ] : 'Our Ribbon Text';
	$pull_quote_atts[ 'buy_btn_text' ] = (!(!isset($pull_quote_atts[ 'buy_btn_text' ]) || trim($pull_quote_atts[ 'buy_btn_text' ])==='')) ? $pull_quote_atts[ 'buy_btn_text' ] : 'Buy here';
	$pull_quote_atts[ 'publish_price' ] = (!(!isset($pull_quote_atts[ 'publish_price' ]) || trim($pull_quote_atts[ 'publish_price' ])==='')) ? $pull_quote_atts[ 'publish_price' ] : '000';
	$pull_quote_atts[ 'pic_url' ] = (!(!isset($pull_quote_atts[ 'pic_url' ]) || trim($pull_quote_atts[ 'pic_url' ])==='')) ? $pull_quote_atts[ 'pic_url' ] : 'Coming Soon PIC URL';
	$pull_quote_atts[ 'affliate_url' ] = (!(!isset($pull_quote_atts[ 'affliate_url' ]) || trim($pull_quote_atts[ 'affliate_url' ])==='')) ? $pull_quote_atts[ 'affliate_url' ] : 'http://candytech.in';
	$pull_quote_atts[ 'product_name' ] = (!(!isset($pull_quote_atts[ 'product_name' ]) || trim($pull_quote_atts[ 'product_name' ])==='')) ? $pull_quote_atts[ 'product_name' ] : 'Adverb v1.0';
	$pull_quote_atts[ 'product_title' ] = (!(!isset($pull_quote_atts[ 'product_title' ]) || trim($pull_quote_atts[ 'product_title' ])==='')) ? $pull_quote_atts[ 'product_title' ] : 'Product Coming Soon';
	$pull_quote_atts[ 'short_desc' ] = (!(!isset($pull_quote_atts[ 'short_desc' ]) || trim($pull_quote_atts[ 'short_desc' ])==='')) ? $pull_quote_atts[ 'short_desc' ] : 'Pending Release';
	$pull_quote_atts[ 'layout' ] = (!(!isset($pull_quote_atts[ 'layout' ]) || trim($pull_quote_atts[ 'layout' ])==='')) ? $pull_quote_atts[ 'layout' ] : 'imageDisclaimerLeftLayout';
	$pull_quote_atts[ 'banner_width_percent' ] = (!(!isset($pull_quote_atts[ 'banner_width_percent' ]) || trim($pull_quote_atts[ 'banner_width_percent' ])==='')) ? $pull_quote_atts[ 'banner_width_percent' ] : '60';
	$pull_quote_atts[ 'banner_float' ] = (!(!isset($pull_quote_atts[ 'banner_float' ]) || trim($pull_quote_atts[ 'banner_float' ])==='')) ? $pull_quote_atts[ 'banner_float' ] : 'right';
	$pull_quote_atts[ 'banner_right_width_percent' ] = (!(!isset($pull_quote_atts[ 'banner_right_width_percent' ]) || trim($pull_quote_atts[ 'banner_right_width_percent' ])==='')) ? $pull_quote_atts[ 'banner_right_width_percent' ] : '32';
	$pull_quote_atts[ 'banner_left_width_percent' ] = (!(!isset($pull_quote_atts[ 'banner_left_width_percent' ]) || trim($pull_quote_atts[ 'banner_left_width_percent' ])==='')) ? $pull_quote_atts[ 'banner_left_width_percent' ] : '60';

	
	//error_log("printing pull_quote_atts");
	//error_log(json_encode($pull_quote_atts),0);
	
	$market_html = build_market_placeholder($pull_quote_atts);
	
	return $market_html;
}


function build_market_placeholder( $pull_quote_atts ){
	$output = '';
	$output .= '<input id="adid" type="hidden" value="'.$pull_quote_atts[ 'adid' ].'"></input>';
	$output .= '<input id="asin'.$pull_quote_atts[ 'adid' ].'" type="hidden" value="'.$pull_quote_atts[ 'asin' ].'"></input>';
	$output .= '<input id="ribbonText'.$pull_quote_atts[ 'adid' ].'" type="hidden" value="'.$pull_quote_atts[ 'ribbon_text' ].'"></input>';
	$output .= '<input id="buyBtnText'.$pull_quote_atts[ 'adid' ].'" type="hidden" value="'.$pull_quote_atts[ 'buy_btn_text' ].'"></input>';
	$output .= '<input id="publishPrice'.$pull_quote_atts[ 'adid' ].'" type="hidden" value="'.$pull_quote_atts[ 'publish_price' ].'"></input>';
	$output .= '<input id="picUrl'.$pull_quote_atts[ 'adid' ].'" type="hidden" value="'.$pull_quote_atts[ 'pic_url' ].'"></input>';
	$output .= '<input id="affliateUrl'.$pull_quote_atts[ 'adid' ].'" type="hidden" value="'.$pull_quote_atts[ 'affliate_url' ].'"></input>';
	$output .= '<input id="productName'.$pull_quote_atts[ 'adid' ].'" type="hidden" value="'.$pull_quote_atts[ 'product_name' ].'"></input>';
	$output .= '<input id="productTitle'.$pull_quote_atts[ 'adid' ].'" type="hidden" value="'.$pull_quote_atts[ 'product_title' ].'"></input>';
	$output .= '<input id="shortDesc'.$pull_quote_atts[ 'adid' ].'" type="hidden" value="'.$pull_quote_atts[ 'short_desc' ].'"></input>';
	
	$output .= '<input id="layout'.$pull_quote_atts[ 'adid' ].'" type="hidden" value="'.$pull_quote_atts[ 'layout' ].'"></input>';
	$output .= '<input id="bannerWidthPercent'.$pull_quote_atts[ 'adid' ].'" type="hidden" value="'.$pull_quote_atts[ 'banner_width_percent' ].'"></input>';
	$output .= '<input id="bannerFloat'.$pull_quote_atts[ 'adid' ].'" type="hidden" value="'.$pull_quote_atts[ 'banner_float' ].'"></input>';
	$output .= '<input id="bannerRightWidthPercent'.$pull_quote_atts[ 'adid' ].'" type="hidden" value="'.$pull_quote_atts[ 'banner_right_width_percent' ].'"></input>';
	$output .= '<input id="bannerLeftWidthPercent'.$pull_quote_atts[ 'adid' ].'" type="hidden" value="'.$pull_quote_atts[ 'banner_left_width_percent' ].'"></input>';
	
	$output .= '<span id="mktHint'.$pull_quote_atts[ 'adid' ].'"> </span>';
	return $output;
}
	
add_shortcode('adverb', 'adverbShortcode');

?>