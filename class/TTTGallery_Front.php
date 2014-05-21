<?php

class TTTGallery_Front extends TTTGallery_Common {

	public $shortcode_counter;
	public $template_styles;

	public function __construct() {
		$this->init();
	}

	public function init() {
		parent::init();
	}

}

class TTTGallery extends TTTGallery_Front {
	public function __construct( $post_id = false, $template = 'default' ) {
		parent::__construct();
		
		$this->post_id = $post_id;
		$this->meta = get_post_meta( $this->post_id, 'tttgallery', true);
		$this->template = $template;
	}
	
	public function have_galleries() {
		$c = count($this->meta);
		if ($c > 0) return $c;
		
		return false;
	}
	
	public function next_gallery() {
		
		$this->s = array_shift($this->meta);
		if (!$this->s) return false;
		
		return $this->s;
	}
	
	public function the_gallery() {
		echo $this->shortcode_callback(array( 'num' => count($this->meta)+1, 'template' => $this->template ));
	}
}


?>
