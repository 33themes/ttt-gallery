<div id="tttgallery-page" class="wrap">
    <div id="icon-upload" class="icon32">
        <br>
    </div>
    <h2><?php _e('Galeries lists', parent::sname ) ; ?><a href="media-new.php" class="add-new-h2 tttgallery_create"><?php _e('New gallery',parent::sname); ?></a></h2>


    <script type="text/html" id="tttgallery-tmpl-page-thead">
        <tr>
            <th scope="col" id="ssid" class="manage-column column-ssid" style="">ID</th>
            <th scope="col" id="icon" class="manage-column column-icon" style=""></th>
            <th scope="col" id="title" class="manage-column column-title sortable desc" style="">
                <?php _e('Description',parent::sname); ?>
            </th>
            <th scope="col" id="createdat" class="manage-column column-createdat" style=""><?php _e('Created at',parent::sname);?></th>
            <th scope="col" id="updatedat" class="manage-column column-updatedat" style=""><?php _e('Updated at',parent::sname);?></th>
            <th scope="col" id="usedat" class="manage-column column-usedat" style=""><?php _e('Used at',parent::sname);?></th>
        </tr>
    </script>
    <script type="text/html" id="tttgallery-tmpl-page-item">
        <tr id="post-<%=id%>" class="alternate author-self status-inherit new tttgallery-gallery" valign="top" data-galleryid="<%=id%>" data-items="<%=_.map(medias,function(num, key) { return num.id; })%>">

            <td class="ssid column-ssid">
                <%=id%>
            </td>
            <td class="column-icon media-icon">
                <a class="tttgallery-invoke-edit" href="#">
                    <% if (typeof(medias[0]) != "undefined") { %>
                        <% if (typeof(medias[0].sizes) != "undefined" && typeof(medias[0].sizes.thumbnail) != "undefined") { %>
                            <img width="60" height="60" src="<%=medias[0].sizes.thumbnail.url%>" class="attachment-80x60">
                        <% } %>
                    <% } %>
                </a>
            </td>
            <td class="title column-title">
                <strong>
                    <a class="tttgallery-invoke-edit tttgallery-description" href="#"><%=description%></a>
                </strong>
                <p><?php _e('Elements in',parent::sname);?>: <strong><%=_.size(medias)%></strong></p>
                <p><%=_.map(medias,function(num, key) { return num.id; })%></p>
                <div class="row-actions">
                    <span class="edit">
                        <a class="tttgallery-invoke-edit" href="#"><?php _e('Edit',parent::sname); ?></a> | </span><span class="delete"><a class="submitdelete tttgallery-invoke-remove"  href="#"><?php _e('Delete permanently',parent::sname); ?></a>
                    </span>
                </div>
            </td>
            <td class="ssid column-createdat"><%=created_at%></td>
            <td class="ssid column-updatedat"><%=updated_at%></td>
            <td class="ssid column-usedat"><%=used_at%></td>

        </tr>
    </script>

    <form id="posts-filter" action="" method="get">
        <p class="search-box">
            <label class="screen-reader-text" for="media-search-input"><?php _e('Search',parent::sname); ?>:</label>
            <input type="search" id="media-search-input" name="s" value="">
            <input type="submit" name="" id="search-submit" class="button" value="Buscar medios">
        </p>
        <br>
        <br>
        
        <table class="wp-list-table widefat fixed media tttgallery-page-list" cellspacing="0">
            <thead>
            </thead>
            
            <tfoot>
            </tfoot>

            <tbody id="the-list">
        
            </tbody>

        </table>
    </form>

</div>
<style type="text/css">
#tttgallery-page .new {
    opacity: 0.1;
    background: yellow;
}
</style>
<script type="text/javascript">
(function ( $ ) {
    
    $.fn.tttgallery_page = function( options ) {
        
        // This is the easiest way to have default options.
        var settings = $.extend({
            tpmlItem: "#tttgallery-tmpl-page-item",
        }, options );

        var page = 0;


        this.on('loadNext',function() {
            var data = {
                'page': page,
                'action': 'ttt-gallery_list'
            };
            var el = $(this);
            
            $.getJSON(tttgalleryConf.ajax, data, function(response) {
                for (var i in response) {
                    $(el).trigger('tttgallery:addItem', response[i] );
                }
            });

            page++;
        });

        this.on('tttgallery:addItem',function( event, args ) {
            $(this).append( _.template( $(settings.tpmlItem).html(), args ) );

            var elGallery = $(".tttgallery-gallery[data-galleryid='"+args.id+"']");
            
            $( elGallery ).on('tttgallery:description',function(event) {
                $(this).attr('data-description', $('.tttgallery-description',this).html() );
            });

            $( elGallery ).on('tttgallery:ids',function(event) {
                // var ids = [];
                // $('li',this).each(function() {
                //     ids.push( $(this).attr('data-id') );
                // });
                // console.log( this, ids );
                // $(this).attr('data-items', ids.join(',') );
            });

            $( elGallery ).on('tttgallery_update:start',function(event, id, args ) {
                $(this).addClass('new');
            });

            $( elGallery ).on('tttgallery_update:done',function(event, args ) {

                var html = $( _.template(  $( settings.tpmlItem ).html() , args  ) ).contents();
                
                $(this).html( html );
                
                $('a.tttgallery-invoke-edit',this).ttt_gallery_edit();
                //$('a.tttgallery-invoke-remove',this).ttt_gallery_remove();
            
                $(this).animate({ opacity: 1, background: 'auto' },1000).removeClass('new');
            });

            $('a.tttgallery-invoke-edit', elGallery).ttt_gallery_edit();
            $('a.tttgallery-invoke-remove', elGallery).ttt_gallery_remove();
            
            $(elGallery).animate({ opacity: 1, background: 'auto' },1000).removeClass('new');
        });

        this.trigger('loadNext');
    
        return this;
    };

}( jQuery ));

jQuery(document).ready(function($) {

        var tpml_thead = $("#tttgallery-tmpl-page-thead").html();

    $(".tttgallery-page-list thead").html(_.template( tpml_thead ));
    $(".tttgallery-page-list tfoot").html(_.template( tpml_thead ));

    $('#the-list').tttgallery_page();
    $('a.tttgallery_create').ttt_gallery_create({ p: '#the-list' });

    $(window).scroll(function() {
        if($(window).scrollTop() == $(document).height() - $(window).height()) {
            $('#the-list').trigger('loadNext');
        }
    });

});
</script>
