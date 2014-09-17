<?php

class TTTGallery extends TTTGallery_Common {
    public function __construct( $post_id = false, $template = 'default' ) {
        parent::init();
        
        $this->post_id = $post_id;
        $this->meta = get_post_meta( $this->post_id, 'tttgallery', true);
        $this->template = $template;
    }

    public function get_the_post_thumbnail( $size = 'post-thumbnail', $attr = '' ) {

        $post_id = $this->post_id;


        $gallery = $this->get_post_gallery( false, $this->post_id );
        
        $post_thumbnail = array_shift( $gallery );
        $post_thumbnail_id = $post_thumbnail->medias[0]['id'];

        $size = apply_filters( 'post_thumbnail_size', $size );
        if ( $post_thumbnail_id ) {
            do_action( 'begin_fetch_post_thumbnail_html', $post_id, $post_thumbnail_id, $size );
            if ( in_the_loop() )
                update_post_thumbnail_cache();

            $html = wp_get_attachment_image( $post_thumbnail_id, $size, false, $attr );

            do_action( 'end_fetch_post_thumbnail_html', $post_id, $post_thumbnail_id, $size );
        }
        else {
            $html = '';
        }

        return apply_filters( 'post_thumbnail_html', $html, $post_id, $post_thumbnail_id, $size, $attr );

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
