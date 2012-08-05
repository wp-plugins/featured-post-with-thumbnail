<?php
/**
 * WIDGET SECTION
 * ----------------------------------------------------------------------- */
/* /



  /*
 * Aggiungiamo la nostra funzione al gancio widgets_init
 * Add our function to widgets_init hook
 */
add_action('widgets_init', 'my_widget_featured_posts');

/*
 * Funzione che registra il nostro widget
 * Register our widget
 */

function my_widget_featured_posts() {
	register_widget('Featured_posts');
}

class Featured_posts extends WP_Widget {

	/**
	 *
	 * @var string widget classname
	 */
	private $classname = 'widget_featured-posts';
	/**
	 *
	 * @var string widget description
	 */
	private $description = "This widget allows you to add in your blog's sidebar
a list of featured posts.";
	/**
	 *
	 * @var integer widget width
	 */
	private $width = 300;
	/**
	 *
	 * @var integer widget height
	 */
	private $height = 350;
	/**
	 *
	 * @var string widget title
	 */
	private $widgetName = 'Featured Posts';
	/**
	 *
	 * @var integer default thumbnails width
	 */
	private $defaultThumbWidth = '73';
	/**
	 *
	 * @var integer default thumbnails height
	 */
	private $defaultThumbHeight = '73';

	/**
	 * Costruttore.
	 */
	function Featured_posts() {
		/* Impostazione del widget */
		$widget_ops = array('classname' => $this->classname,
			 'description' => __($this->description));

		/* Impostazioni di controllo del widget */
		$control_ops = array('width' => $this->width, 'height' => $this->height,
			 'id_base' => $this->classname);

		/* Creiamo il widget */
		$this->WP_Widget($this->classname,
				  __($this->widgetName, YIW_TEXT_DOMAIN), $widget_ops, $control_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$arguments = array(
			 'title' => $instance['title'],
			 'numberposts' => $instance['showposts'],
			 'orderby' => $instance['orderby'],
			 'widththumb' => $instance['width-thumb'],
			 'heightthumb' => $instance['height-thumb'],
			 'beforetitle' => $before_title,
			 'aftertitle' => $after_title,
			 'show' => $instance['show'],
			 'category' => $instance['category']
		);
		global $featured_post_plugin_path;
		/* Before widget (definito dal tema). */
		echo $before_widget;
		featured_posts_YIW($arguments);
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		/* Strip tags (if needed) and update the widget settings. */
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['showposts'] = $new_instance['showposts'];
		$instance['orderby'] = $new_instance['orderby'];
		$instance['width-thumb'] = $new_instance['width-thumb'];
		$instance['height-thumb'] = $new_instance['height-thumb'];
		$instance['show'] = $new_instance['show'];
		$instance['category'] = $new_instance['category'];
		return $instance;
	}

	private function showTitleForm($instance) {
		echo '<p>';
		echo '<label for="' . $this->get_field_id('title') . '">' .
		_e('Title:', YIW_TEXT_DOMAIN) . '</label>';
		echo '<input id="' . $this->get_field_id('title') . '" name="' .
		$this->get_field_name('title') . '" value="' . $instance['title'] .
		'" style="width:100%;" class="widefat" />';
		echo '</p>';
	}

	private function showNumberPostsForm($instance) {
		echo '<p>';
		echo '<label for="' . $this->get_field_id('showposts') . '">' .
		_e('How many posts do you want to display?', YIW_TEXT_DOMAIN) . '</label>';
		echo '<select name="' . $this->get_field_name('showposts') .
		'" id="' . $this->get_field_id('showposts') . '" >';
		for ($i = 0; $i <= 100; $i++) {
			echo '<option class="level-0" value="' . $i . '"' . selected($instance['showposts'], $i) . '>' . $i . '</option>';
		}
		echo '</select>';
		echo '</p>';
	}

	private function showOrderTypeForm($instance) {
		echo '<p>';
		echo '<label for="' . $this->get_field_id('orderby') . '">' .
		__('Choose type of order:', YIW_TEXT_DOMAIN) . '</label>';
		echo '<select name="' . $this->get_field_name('orderby') . '" id="' .
		$this->get_field_id('orderby') . '" >';
		echo '<option class="level-0" value="rand" ' . selected($instance['orderby'], 'random') . ' >' .
		__('Random', YIW_TEXT_DOMAIN) . '</option>';
		echo '<option class="level-0" value="title" ' . selected($instance['orderby'], 'title') . ' >' .
		__('Title', YIW_TEXT_DOMAIN) . '</option>';
		echo '<option class="level-0" value="date" ' . selected($instance['orderby'], 'date') . ' >' .
		__('Date', YIW_TEXT_DOMAIN) . '</option>';
		echo '<option class="level-0" value="author" ' . selected($instance['orderby'], 'author') . ' >' .
		__('Author', YIW_TEXT_DOMAIN) . '</option>';
		echo '<option class="level-0" value="modified" ' . selected($instance['orderby'], 'modified') . ' >' .
		__('Modified', YIW_TEXT_DOMAIN) . '</option>';
		echo '<option class="level-0" value="ID" ' . selected($instance['orderby'], 'ID') . ' >' .
		__('ID', YIW_TEXT_DOMAIN) . '</option>';
		echo '</select>';
		echo '</p>';
	}

	/**
	 *
	 * @param <type> $instance
	 */
	private function showWidthHeightForm($instance) {
		echo '<p>';
		echo '<label for="' . $this->get_field_id('width-thumb') . '">' .
		__('Width Thumbnail', YIW_TEXT_DOMAIN) . '</label>';
		echo '<input id="' . $this->get_field_id('width-thumb') .
		'" name="' . $this->get_field_name('width-thumb') . '" value="' .
		$instance['width-thumb'] . '" style="width:20%;" class="widefat" />';
		echo '</p>';
		echo '<p>';
		echo '<label for="' . $this->get_field_id('height-thumb') . '">' .
		__('Height Thumbnail', YIW_TEXT_DOMAIN) . '</label>';
		echo '<input id="' . $this->get_field_id('height-thumb') .
		'" name="' . $this->get_field_name('height-thumb') . '" value="' .
		$instance['height-thumb'] . '" style="width:20%;" class="widefat" />';
		echo '</p>';
	}

	private function showFeaturedOrCategory($instance) {
		echo '<p>';
		echo '<label for="' . $this->get_field_id('show') . '">' . _e("Featured or category?", YIW_TEXT_DOMAIN) . '</label>';
		echo '<select id="' . $this->get_field_id('show') . '" name="' . $this->get_field_name('show') . '">';
		echo '<option value="featured" ' . selected($instance['show'], 'featured') . '>Featured</option>';
		echo '<option value="category" ' . selected($instance['show'], 'category') . '>Category</option>';
		echo '</select>';
		echo '</p>';

		echo '<p>';
		echo '<label for="' . $this->get_field_id('category') . '">' . _e('Category', YIW_TEXT_DOMAIN) . '</label>';
		echo '<select id="' . $this->get_field_id('category') . '" name="' . $this->get_field_name('category') . '">';
		$categories = get_categories($args);
		foreach ($categories as $c) {
			echo '<option value="'. $c->cat_ID .'" ' . selected($instance['category'], $c->cat_ID) . '>'. $c->name .'</option>';
		}
		echo '</select>';
		echo '</p>';
	}

	public function form($instance) {
		/* Impostazioni di default del nostro widget */
		$defaults = array(
			 'title' => __($this->widgetName, YIW_TEXT_DOMAIN),
			 'showposts' => '', 'orderby' => '',
			 'width-thumb' => $this->defaultThumbWidth,
			 'height-thumb' => $this->defaultThumbHeight,
			 'show' => 'featured',
			 'category' => 'uncategorized');

		$instance = wp_parse_args((array) $instance, $defaults);
		$this->showTitleForm($instance);
		$this->showNumberPostsForm($instance);
		$this->showOrderTypeForm($instance);
		$this->showWidthHeightForm($instance);
		$this->showFeaturedOrCategory($instance);
	}

}

//end class Featured_posts
?>
