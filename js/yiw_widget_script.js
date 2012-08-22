function hideShowCat(){
	var $cat = jQuery('#widget-widget_featured-posts-4-category'),
		$show = jQuery('#widget-widget_featured-posts-4-show'),
		$selected = jQuery("#widget-widget_featured-posts-4-show option:selected");
		
	if($selected.text() == "Featured") $cat.hide();
	
	$show.change(function () {
		jQuery("#widget-widget_featured-posts-4-show option:selected").each(function() {
			(jQuery(this).text() == 'Category') ? $cat.show() : $cat.hide();
		});
		
	});
}

jQuery(document).ready(hideShowCat).ajaxSuccess(hideShowCat);

