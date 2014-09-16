
<div class="tttgallery-invoke-wrapper">
    <div class="tttgallery-content">
    </div>
    <br/>

    <hr/>
    <br/>

    <div class="tttgallery-search">
        <strong><?php _e('Search and add gallery',parent::sname); ?></strong>
        <input type="text" name="search" placeholder="Search.." />
        <div class="tttgallery-searchresult">
        </div>
    </div>
    
    <hr/>
    <br/>

    <a class="button tttgallery-invoke"><?php _e('Create gallery',parent::sname); ?></a>

</div>

<script type="text/html" id="tttgallery-tmpl-metabox-result">
<div class="item tttgallery-gallery" style="display:none;" data-galleryid="<%=id%>">
    <ul>
        <li><input class="button" type="button" value="<?php _e('Add',parent::sname); ?>"/></li>
        <% if (typeof(medias[0].sizes) != "undefined") { %>
        <li><img src="<%=medias[0].sizes.thumbnail.url%>" width="30" height="30" /></li>
        <% } %>
        <li><strong><%=description%></strong></li>
        <li><label><?php _e('Gallery size',parent::sname); ?></label> <%=_.size(medias)%></li>
    </ul>
</div>
</script>

<script type="text/html" id="tttgallery-tmpl-metabox">
<div class="tttgallery-metabox tttgallery-gallery" data-galleryid="<%=id%>" data-post="<?php the_ID(); ?>">
    <div class="drag"><span><%=id%></span></div>
    <div class="content">
        <h4 class="tttgallery-description"><%=description%></h4>
        <ul>
            <% _.each( medias, function( image ){ %>
            <li data-id="<%=image.id%>" class="<%=image.type%>">
                <% if (typeof(image.sizes) != "undefined") { %>
                <img src="<%=image.sizes.thumbnail.url%>" alt="<%=image.caption%>"/>
                <% } else { %>
                <div class="icon"><%=image.type%></div>
                <% } %>
                <p><%=image.title%></p>
            </li>
            <% }); %>
        </ul>
        <textarea readonly><?php _e('Calling the gallery examples', parent::sname);?>: [tttgallery] [tttgallery template="default"] [tttgallery id="<%=id%>"] [tttgallery id="<%=id%>" template="default"]</textarea>
        <br>
        <textarea readonly><?php _e('Calling each image examples' , parent::sname);?>: [ttt-gallery-image position="1"] [ttt-gallery-image id="<%=id%>" position="1" template="default"] </textarea>
        <br>
        <br>
        <a class="button tttgallery-invoke-edit"><?php _e('Edit gallery',parent::sname); ?></a>
        <a class="button tttgallery-invoke-remove"><?php _e('Remove gallery',parent::sname); ?></a>

    </div>
</div>
</script>

<script type="text/javascript">

jQuery(document).ready(function($) {

    $('.tttgallery-search input').on('change, keyup',function(event) {
        event.preventDefault();

        var now = new Date().getTime() / 1000;
        var s = parseInt(now, 10);
        var last = parseInt($(this).data('lastchange'));
        if ( isNaN(last) ) last = 0;
        var diff = s - last;

        if ( diff >= 2 ) {
            $(this).data('lastchange', s );
            $(this).data('lastvalue', $(this).val() );

            $('.items .tttgallery-searchresult').fadeOut();
            $('.tttgallery-searchresult').html('Loading...');

            var data = {
                'search': $(this).val(),
                'page': 0,
                'action': 'ttt-gallery_list'
            };

            var el = $(this);
            
            $.getJSON(tttgalleryConf.ajax, data, function(response) {
                $('.tttgallery-searchresult').html('');
                for (var i in response) {
                    $(el).trigger('tttgallery:addItem', response[i] );
                }
            });
        }
    });

    $('.tttgallery-search').on('tttgallery:addItem',function(event, args) {
        var args = args;
        var template = $("#tttgallery-tmpl-metabox-result").html();

        $('.tttgallery-searchresult').append( _.template( template , args ) );

        var elGallery = $("div.tttgallery-gallery[data-galleryid='"+args.id+"']", this);
        $( elGallery ).data('args',args);
        $( elGallery ).fadeIn();

        $('input',elGallery ).on('click',function() {
            event.preventDefault();
            var _p = $(this).parents('.tttgallery-gallery');
            $('.tttgallery-content').trigger('tttgallery:addItem', _p.data('args') );
            setTimeout(function() {
                console.log('order');
                $('.tttgallery-content').trigger('tttgallery:order');
            },500);
        });
    });

    $('.tttgallery-content').on('tttgallery:addItem',function(event, args) {

        var template = $("#tttgallery-tmpl-metabox").html();

        $(".tttgallery-content").append(_.template( template , args ) );
        // { id:args.id, medias: args.medias } ));

        var elGallery = $("div.tttgallery-gallery[data-galleryid='"+args.id+"']",this);

        $( elGallery ).on('tttgallery:description',function(event) {
            $(this).attr('data-description', $('.tttgallery-description',this).html() );
        });

        $( elGallery ).on('tttgallery:ids',function(event) {
            var ids = [];
            $('li',this).each(function() {
                ids.push( $(this).attr('data-id') );
            });
            $(this).attr('data-items', ids.join(',') );
        });

        $( elGallery ).on('tttgallery_update:start',function(event, id, args ) {
            $(this).fadeOut();
        });

        $( elGallery ).on('tttgallery_update:done',function(event, args ) {

            var template = $("#tttgallery-tmpl-metabox").html();

            var html = $( _.template( template , args ) ).contents();
            
            $(this).html( html );
            $(this).fadeIn();
            
            $('a.tttgallery-invoke-edit',this).ttt_gallery_edit();
            $('a.tttgallery-invoke-remove',this).ttt_gallery_removepost();
        });

        $('a.tttgallery-invoke-edit', elGallery).ttt_gallery_edit();
        $('a.tttgallery-invoke-remove', elGallery).ttt_gallery_removepost();
        $('.tttgallery-content').trigger('tttgallery:sortable');
    });
    
    $('.tttgallery-content').on('tttgallery:order',function(event, args) {
        var galleriesIds = [];
        $('.tttgallery-metabox').each(function() {
            galleriesIds.push( $(this).attr('data-galleryid')  );
        });


        // var data = {
        //     'post': tttgalleryConf.post,
        //     'galleries': galleriesIds,
        //     'action': 'ttt-gallery_order'
        // };
        // $.post(tttgalleryConf.ajax, data, function(response) {
        //     console.log( response );
        // });

        var data = {
            'action': 'ttt-gallery_order',
            'post': tttgalleryConf.post,
            'galleries': galleriesIds,
        };

        $.ajax({
            url: tttgalleryConf.ajax,
            type: 'post',
            context: this,
            data: data,
            complete: function(data) {
                response = jQuery.parseJSON(data.responseText);

                if ( response.success !== true ) {
                    console.log( 'error', response );
                    return false;
                }

            }
        });

    });

    $('.tttgallery-content').on('tttgallery:sortable',function(event,args) {
        $(this).sortable({
            handle: ".drag",
            revert: true,
            stop: function() {
                $('.tttgallery-content').trigger('tttgallery:order');
            }
        });
    });

    var template = $("#tttgallery-tmpl-metabox").html();

    if ( tttgalleryPost && tttgalleryPost[0] && tttgalleryPost[0].id ) {
        for ( var i in tttgalleryPost ) {

            $(".tttgallery-content").trigger('tttgallery:addItem', tttgalleryPost[i] );
            // { id: tttgalleryPost[i].id, medias: tttgalleryPost[i].medias }); 

            // $( "#tttgallery_metabox .tttgallery-content" ).sortable({
            //     handle: ".drag",
            //     revert: true,
            //     stop: function() {
            //         $('.tttgallery-content').trigger('tttgallery:order');

            //         // var galleriesIds = [];
            //         // $('.tttgallery-metabox').each(function() {
            //         //     galleriesIds.push( $(this).attr('data-galleryid')  );
            //         // });

            //         // var data = {
            //         //     'post': tttgalleryConf.post,
            //         //     'galleries': galleriesIds,
            //         //     'action': 'ttt-gallery_order'
            //         // };
            //         // $.post(tttgalleryConf.ajax, data, function(response) {
            //         //     
            //         // });
            //     }
            // });
        }
        $('.tttgallery-content').trigger('tttgallery:sortable');
    }

    $('a.tttgallery-invoke').ttt_gallery_create();

});
</script>
