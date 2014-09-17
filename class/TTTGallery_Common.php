<?php

class TTTGallery_Common {

    const sname = 'tttgallery';

    public function init() {
        global $wpdb;

        $this->wpdb =& $wpdb;
        $this->table_name = $wpdb->prefix.'tttgallery';
        $this->version_control();
        $this->load_shortcodes();
    }

    public function _s( $s = false ) {
        if ( $s === false) return self::name;
        return self::sname.'_'.$s;
    }
    
    public function del( $name ) {
        return delete_option( self::sname . '_' . $name );
    }
    
    public function get( $name ) {
        return get_option( self::sname . '_' . $name );
    }
    
    public function set( $name, $value ) {
        if (!get_option( self::sname . '_' . $name ))
            add_option( self::sname . '_' . $name, $value);
        
        update_option( self::sname . '_' . $name , $value);
    }

    public function version_control() {
        $this->set('version', 0 );

        // Create & Install tables
        if ( (float) $this->get('version') < (float) TTTVERSION_GALLERY ) {
        
            global $wpdb;
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            $dir = TTTINC_GALLERY.'/sql';

            if (is_dir($dir)) {
                if ($dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {
                        if (preg_match('/^([0-9\.]+)\.inc\.php$/',$file,$regs)) {
                            $versions[] = $regs[1];
                        }
                    }
                closedir($dh);
                }
            }

            sort($versions);
            foreach( $versions as $v ) {
                if ( (float) $v >= (float) TTTVERSION_GALLERY ) break;
                 require_once( TTTINC_GALLERY.'/sql/'.$v.'.inc.php');
            }
            
            $this->set('version', TTTVERSION_GALLERY );
        }
    }


    /* SHOTCodes */
    public function load_shortcodes() {
        add_shortcode('tttgallery',array( &$this, 'shortcode_callback') );
        add_shortcode('tttgalleryimage',array( &$this, 'shortcode_image_callback') );
        add_shortcode('ttt-gallery',array( &$this, 'shortcode_callback') );
        add_shortcode('ttt-gallery-image',array( &$this, 'shortcode_image_callback') );
    }

    public function load_styles( $template = 'default' ) {
        if ( !isset($this->template_styles[ $template ]) ) {
            $_s = array(
                get_stylesheet_directory().'/ttt-gallery/'.$template.'/styles.php',
                get_template_directory().'/ttt-gallery/'.$template.'/styles.php',
                TTTINC_GALLERY . '/template/front/'.$template.'/styles.php'
            );
            foreach( $_s as $_template ) {
                if (!is_file($_template) || !is_readable($_template)) continue;
                
                require_once $_template;
                break;
            }
        }
    
    }

    public function template( $ttt_gallery, $extras ) {


        $this->load_styles( $ttt_gallery->template );
    
        ob_start();
        $_s = array(
            get_stylesheet_directory().'/ttt-gallery/'.$ttt_gallery->template.'/template.php',
            get_template_directory().'/ttt-gallery/'.$ttt_gallery->template.'/template.php',
            TTTINC_GALLERY . '/template/front/'.$ttt_gallery->template.'/template.php'
        );

        if (is_array($extras))
        extract($extras);

        foreach( $_s as $_template ) {
            if (!is_file($_template) || !is_readable($_template)) continue;
            
            require $_template;
            break;
        }

        return ob_get_clean();
    }

    public function shortcode_callback( $attr ) {

        if ( ! $_id = get_the_ID() )
            $_id = 0;

        if ( isset($attr['id']) ) {
            $_gallery_id = (int) $attr['id'];
            $_id = -1;
            $gallery = $this->query_gallery( array($_gallery_id) );
        }
        else {
            $_post = false;

            if ( isset($attr['post']) ) {
                $_post = $attr['post'];
                $_id = $_post;
            }

            if ( !isset($this->shortcode_counter[ $_id ]) || $this->shortcode_counter[ $_id ] <= 0 )
                $this->shortcode_counter[ $_id ] = 1;

            $gallery = $this->get_post_gallery( $this->shortcode_counter[$_id], $_post );
        }

        if (!is_array($gallery)) return false;
        $gallery = array_shift($gallery);
        if (!$gallery) return false;

        if ( isset($attr['template']) )
            $gallery->template = $attr['template'];

        if ( !$gallery->template ) 
            $gallery->template = 'default';

        $gallery->rel = $_id.'-'.$gallery->id;

        $this->shortcode_counter[$_id]++;

        return $this->template( $gallery, $attr );
    }

    public function shortcode_image_callback( $attr ) {
        if ( ! $_id = get_the_ID() )
            $_id = 0;

        if ( isset($attr['id']) ) {
            $_gallery_id = (int) $attr['id'];
            $_id = -1;
            $gallery = $this->query_gallery( array($_gallery_id) );
        }
        else {
            $_post = false;

            if ( isset($attr['post']) ) {
                $_post = $attr['post'];
                $_id = $_post;
            }

            if ( !isset($this->shortcode_image_counter[ $_id ]) || $this->shortcode_image_counter[ $_id ] <= 0 )
                $this->shortcode_image_counter[ $_id ] = 1;

            if ( isset($attr['position']) )
                $_position = $attr['position']-1;
            else
                $_position = $this->shortcode_image_counter[ $_id ];

            for ( $findnext=1; $findnext <= $_position; $findnext++ ) {
                
                $gallery = $this->get_post_gallery( $findnext, $_post );
                if (!is_array($gallery)) break;

                $gallery = array_shift($gallery);

                if ( count($gallery->medias) > $_position-1 ) {
                    break;
                }
            }
        }
        
        if ( !isset($this->shortcode_image_counter[ $_id ]) || $this->shortcode_image_counter[ $_id ] <= 0 )
                $this->shortcode_image_counter[ $_id ] = 1;
                
        if (is_array($gallery)) $gallery = $gallery[0];

        //if (!is_array($gallery)) return false;
        //$gallery = array_shift($gallery);
        if (!$gallery) return false;

        if ( isset($attr['position']) )
            $_position = $attr['position']-1;
        else
            $_position = $this->shortcode_image_counter[ $_id ]-1;
        
        $newmedias = $gallery->medias[$_position];
        $gallery->medias = array( $newmedias );

        if ( isset($attr['template']) )
            $gallery->template = $attr['template'];

        if ( !$gallery->template ) 
            $gallery->template = 'default-image';

        $gallery->rel = $_id.'-'.$gallery->id;

        $this->shortcode_image_counter[$_id]++;

        return $this->template( $gallery, $attr );
    }


    /*
     * Internal functions
     */

    public function get_post_gallery( $num = 1, $post = false ) {
        if (!$post) $post = get_the_ID();

        $num--;
        $meta = get_post_meta( $post, 'tttgallery', true);

        if ( isset($meta[ $num ]) )
            return $this->query_gallery( (array) $meta[ $num ] );

        return false;
    }

    public function query_gallery( $_gallery = false, $_required = false, $page = false, $search = false ) { 

        if ( is_array($_gallery) ) {
            foreach ( $_gallery as $_t ) {
                if (is_numeric($_t) and $_t > 0) {
                    $_clean[] = $_t;
                }
            }
            $_gallery = $_clean;
        }


        $_sql = "SELECT * FROM ".$this->table_name;
        if ( $_gallery ) {
            $_sql .= " WHERE id IN (".implode(',',$_gallery).")";
            $_sql .= " ORDER BY FIELD(id, ".implode(',',$_gallery).") ";
        }
        elseif ( $search ) {
            $search = '%'.$search.'%';
            $search = preg_replace('/\s+/','%',$search);
            $search = preg_replace('/%+/','%',$search);
            $_sql .= " WHERE `description` LIKE '".$search."'";
            $_sql .= " ORDER BY created_at DESC ";
        }
        elseif ( $_required == true ) {
            return false;
        }
        else {
            $_sql .= " ORDER BY created_at DESC ";
        }

        if ( $page !== false ) {
            $_sql .= ' LIMIT '.($page*10).', 10';
        }


        $rows = $this->wpdb->get_results( $_sql );

        foreach($rows as $row) {
            $row->medias = $this->query_attachements( preg_split('/,/', $row->medias ) );
            $gallery[] = $row;
        }
        return $gallery;
    }

    public function query_attachements( $_medias = false ) {

        $_query = array(
            'orderby' => 'post__in',
            'order' => 'ASC',
            'posts_per_page' => '-1',
            'post__in' => (array) $_medias,
        );

             
        $query = isset( $_query ) ? (array) $_query : array();
        $query = array_intersect_key( $query, array_flip( array(
            's', 'order', 'orderby', 'posts_per_page', 'paged', 'post_mime_type',
            'post_parent', 'post__in', 'post__not_in',
        ) ) );
        
        $query['post_type'] = array('attachment');
        $query['post_status'] = 'inherit';
        if ( current_user_can( get_post_type_object( 'attachment' )->cap->read_private_posts ) )
            $query['post_status'] .= ',private';


        $query = apply_filters('tttgallery-query_attachements_query',$query);
        $query = new WP_Query( $query );
        
        $posts = array_map( apply_filters('tttgallery-query_attachements_arraymap','wp_prepare_attachment_for_js'), $query->posts );
        return array_filter( $posts );
    }
}

?>
