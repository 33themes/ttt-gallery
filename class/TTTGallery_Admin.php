<?php

class TTTGallery_Admin extends TTTGallery_Common {
    
    public function init() {
        parent::init();

        if( current_user_can('edit_posts') ) {
            add_action('add_meta_boxes', array( &$this, 'add_meta_boxes' ) );
            add_action('admin_menu', array( &$this, 'menu' ) );
            $this->ajax();
        }
    }

    
    public function menu() {
        $s = add_submenu_page( 'upload.php', __('TTT Gallery title',parent::sname), __('TTT Galleries',parent::sname), 'edit_posts', 'ttt-gallery-menu', array( &$this, 'menu_page') );
    }

    public function enqueue_common() {
        
        add_action( 'print_media_templates', array( $this, 'print_media_templates' ) );

        wp_enqueue_media();
        wp_enqueue_style(  'ttt-gallery-css', plugins_url('template/admin/css/common.css' , dirname(__FILE__) ) );
        wp_enqueue_script( 'ttt-gallery-js', plugins_url('template/admin/js/common.js' , dirname(__FILE__) ), array( 'jquery','underscore' ) );
    }

    public function menu_page()  {

        $this->enqueue_common();

        wp_enqueue_style(  'ttt-gallery-page-css', plugins_url('template/admin/css/page.css' , dirname(__FILE__) ) );
        wp_enqueue_script( 'ttt-gallery-page-js', plugins_url('template/admin/js/page.js' , dirname(__FILE__) ), array( 'ttt-gallery-js' ) );
        wp_localize_script('ttt-gallery-page-js', 'tttgalleryConf',array(
            'ajax' => admin_url('admin-ajax.php'),
            'Nonce' => wp_create_nonce( 'ttt-gallery-metabox-nonce' ),
        ));



        require_once( TTTINC_GALLERY .'/template/admin/page.inc.php' );

    }
    
    
    public function print_media_templates() {
        
        // if ( ! isset( get_current_screen()->id ) || get_current_screen()->base != 'post' )
        //     return;
            
        ?>
        <script type="text/html" id="tmpl-ttt-gallery-setting">
            <h3><?php _e('TTT Gallery', parent::sname); ?></h3>
            <label>
                <input class="tttgallery-description" type="text" value="test" placeholder="<?php _e('My gallery description', parent::sname ); ?>"/>
            </label>
        </script>
        <?php
    }

    public function ajax() {
        add_action('wp_ajax_ttt-gallery_list', array( &$this, 'list_callback' ) );
        add_action('wp_ajax_ttt-gallery_create', array( &$this, 'create_callback' ) );
        add_action('wp_ajax_ttt-gallery_remove', array( &$this, 'remove_callback' ) );
        add_action('wp_ajax_ttt-gallery_removepost', array( &$this, 'removepost_callback' ) );
        add_action('wp_ajax_ttt-gallery_update', array( &$this, 'update_callback' ) );
        add_action('wp_ajax_ttt-gallery_order', array( &$this, 'order_callback' ) );
    }

    public function _header_callback() {
        header("Content-Type: application/json", true);
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }
    
    public function list_callback() {
        $this->_header_callback();

        echo json_encode( $this->query_gallery( false, false, $_REQUEST['page'], $_REQUEST['search'] ) );


        die();
    }

    public function create($params = false) {

        $wpdb->insert( $this->table_name, array(
            'medias' => join(',',$params['medias']),
            'created_at' => current_time('mysql'),
            'description' => $params['description']
        ));

        $id = $wpdb->insert_id;

        if ( isset($params['post']) ) {
            $post_id = $params['post'];
            if ( $meta = get_post_meta(  $post_id, 'tttgallery', true) ) {
                $meta[] = $id;
                $meta = $this->_reorder( $meta );
                update_post_meta( $post_id, 'tttgallery', $meta );
            }
            else {
                $meta[] = $id;
                $meta = $this->_reorder( $meta );
                add_post_meta( $post_id, 'tttgallery', $meta );
                update_post_meta( $post_id, 'tttgallery', $meta );
            }
        }

    }

    public function create_callback() {
        $this->_header_callback();

        global $wpdb;

        $this->create($_REQUEST);

        echo json_encode(array(
            'success'=>true,
            'id' => $id,
            'debug'=>$_REQUEST
        ));
        die();
    }

    public function remove($params) {

        if ( !isset($params['id']) || !is_numeric($params['id']) || $params['id'] < 1 ) return;

        global $wpdb;

        return $wpdb->query( $wpdb->prepare("DELETE FROM ".$this->table_name." WHERE id = ". (int) $_REQUEST['id'] ) );
    }

    public function remove_callback() {
        $this->_header_callback();
        
        if ( !isset($_REQUEST['id']) || !is_numeric($_REQUEST['id']) || $_REQUEST['id'] < 1 ) return;

        $this->remove($_REQUEST);

        echo json_encode(array(
            'success'=>true,
            'debug'=>$_REQUEST
        ));
        die();
    }

    public function removepost_callback() {
        $this->_header_callback();
        
        if ( !isset($_REQUEST['id']) || !is_numeric($_REQUEST['id']) || $_REQUEST['id'] < 1 ) return;

        $id = $_REQUEST['id'];

        if ( isset($_REQUEST['post']) ) {
            $post_id = $_REQUEST['post'];
            if ( $meta = get_post_meta(  $post_id, 'tttgallery', true) ) {
                
                $_i = array_search( $id, $meta );
                unset( $meta[ $_i ] );
                update_post_meta( $post_id, 'tttgallery', $meta );
            }
        }

        echo json_encode(array(
            'success'=>true,
            'id'=>$_REQUEST['id'],
            'debug'=>$_REQUEST
        ));
        die();
    }

    public function update($params = false) {
        if ( !isset($params['id']) || !is_numeric($params['id']) || $params['id'] < 1 ) return;

        global $wpdb;
        
        $id = $params['id'];


        $s = $wpdb->update( $this->table_name, array(
            'medias' => join(',',$params['medias']),
            'description' => $params['description']
        ),array(
            'id' => $id
        ),array(
            '%s','%s'
        ),array('%d') );


        if ( isset($params['post']) ) {
            $post_id = $params['post'];
            if ( $meta = get_post_meta(  $post_id, 'tttgallery', true) ) {
                $meta[] = $id;
                $meta = $this->_reorder( $meta );
                update_post_meta( $post_id, 'tttgallery', $meta );
            }
            else {
                $meta[] = $id;
                $meta = $this->_reorder( $meta );
                add_post_meta( $post_id, 'tttgallery', $meta );
                update_post_meta( $post_id, 'tttgallery', $meta );
            }
        }
    }

    public function update_callback() {
        $this->_header_callback();

        $this->update($_REQUEST);

        echo json_encode(array(
            'success'=>true,
            'id'=>$_REQUEST['id'],
        ));
        die();
    }

    public function _reorder($meta) {
        unset( $m );
        $meta = array_unique($meta);
        foreach ($meta as $value) {
            $m[] = $value;
        }
        return $m;
    }


    public function order_callback() {
        $this->_header_callback();
        
        if ( isset($_REQUEST['post']) ) {
            $post_id = $_REQUEST['post'];

            delete_post_meta( $post_id, 'tttgallery');
            $meta = $this->_reorder( $_REQUEST['galleries'] );
            add_post_meta( $post_id, 'tttgallery', $meta );

            // if ( $meta = get_post_meta(  $post_id, 'tttgallery', true) ) {
            //     $meta = $this->_reorder( $_REQUEST['galleries'] );
            //     update_post_meta( $post_id, 'tttgallery', $meta );
            // }
            // else {
            //     $meta = $this->_reorder( $_REQUEST['galleries'] );
            //     add_post_meta( $post_id, 'tttgallery', $meta );
            //     update_post_meta( $post_id, 'tttgallery', $meta );
            // }
        }

        echo json_encode(array(
            'success'=>true,
            'galleries'=>$meta,
        ));
        die();
    }


    public function add_meta_boxes() {
        $post_types=get_post_types();

        $screens = get_post_types('','names');
        unset( $screens['attachment'] );
        unset( $screens['revision'] );
        unset( $screens['nav_menu_item'] );

        $screens = apply_filters( 'ttt-gallery_post_types', $screens );

        foreach ($screens as $screen) {
            add_meta_box(
                $this->_s('metabox'),
                __( 'TTT Gallery metabox', parent::sname ),
                array( &$this, 'metabox' ),
                $screen
            );
        }

    }

    public function metabox() {
        
        $this->enqueue_common();
    
        $post_meta = get_post_meta( get_the_ID(), 'tttgallery',true );
        if ( $post_meta == '' ) $post_meta = false;

        wp_enqueue_style( 'ttt-gallery-metabox-css', plugins_url('template/admin/css/metabox.css' , dirname(__FILE__) ) );
        wp_enqueue_script( 'ttt-gallery-metabox-js', plugins_url('template/admin/js/metabox.js' , dirname(__FILE__) ), array( 'ttt-gallery-js','jquery-ui-draggable','jquery-ui-droppable' ) );
        wp_localize_script('ttt-gallery-metabox-js', 'tttgalleryPost', $this->query_gallery( $post_meta, true ) );
        wp_localize_script('ttt-gallery-metabox-js', 'tttgalleryConf',array(
            'ajax' => admin_url('admin-ajax.php'),
            'post' => get_the_ID(),
            'Nonce' => wp_create_nonce( 'ttt-gallery-metabox-nonce' ),
            'lere' => 'test',
        ));
        
        require_once( TTTINC_GALLERY .'/template/admin/metabox.inc.php' );
    }

    public function load_galleries() {
        
        return false;
    }

    public function have_galleries() {
        return false;
    }

    public function uninstall() {
        if ( ! current_user_can( 'activate_plugins' ) )
            return;

        $this->del('version');

        global $wpdb;
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $sql = "DROP TABLE `".$wpdb->prefix."tttgallery`";
        $wpdb->query( $sql );
    }



}

?>
