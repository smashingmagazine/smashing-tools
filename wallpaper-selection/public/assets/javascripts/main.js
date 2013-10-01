$(document).ready(function () {
    
    g_on_going_refresh = false;
    
    function recomputeElementsIndexes() {
        $('.sortable-wallpapers').find('h2').each(function() {
            $(this).attr('data-position', $(this).parent().index() + 1);
        });
        
        $('.sortable-menu li').each(function() {
            $(this).attr('data-position', $(this).index() + 1);
        });
    }

    $(".sortable-menu").on('click', '.delete-wallpaper', function () {
        
        parent_list_element = $(this).parent();
        
        wallpaper_to_move = $('.sortable-wallpapers li:nth-child(' + (parent_list_element.index() + 1) + ')');
        
        parent_list_element.appendTo($(".wallpaper-menu-trash"));
        
        console.log(parent_list_element.index())
        
        wallpaper_to_move.appendTo($(".sortable-wallpapers-trash"));
        
        remove_button = parent_list_element.children('a');
        remove_button.removeClass("btn-danger");
        remove_button.addClass("btn-success");
        
        remove_button.removeClass("delete-wallpaper");
        remove_button.addClass("recycle-wallpaper");
        
        remove_icon = parent_list_element.find('.icon-remove');
        remove_icon.removeClass("icon-remove");
        remove_icon.addClass("icon-ok");
        
        parent_list_element.find('.move-anchor').addClass("hidden");
        $(this).attr('data-position', -1);
        parent_list_element.addClass("no-number");
        
        recomputeElementsIndexes();
    });
    
    $(".wallpaper-menu-trash").on('click', '.recycle-wallpaper', function () {
        parent_list_element = $(this).parent();
        
        wallpaper_to_move = $('.sortable-wallpapers-trash li:nth-child(' + (parent_list_element.index() + 1) + ')');
        
        parent_list_element.appendTo($(".sortable-menu"));
        
        wallpaper_to_move.appendTo($(".sortable-wallpapers"));
        
        remove_button = parent_list_element.children('a');
        remove_button.removeClass("btn-success");
        remove_button.addClass("btn-danger");
        
        remove_button.removeClass("recycle-wallpaper");
        remove_button.addClass("delete-wallpaper");
        
        remove_icon = parent_list_element.find('.icon-ok');
        remove_icon.removeClass("icon-ok");
        remove_icon.addClass("icon-remove");
        
        parent_list_element.find('.move-anchor').removeClass("hidden");
        parent_list_element.removeClass("no-number");
        
        recomputeElementsIndexes();
    });
    
    $( ".sortable-wallpapers" ).sortable({
        handle: ".move-anchor",
        
        update: function(event, ui) {
            
            start_index_element = ui.item.find('h2').first().attr('data-position') - 1;
            
            menu_li_to_move = $('.sortable-menu li:nth-child(' + (start_index_element + 1) + ')');
            insert_before_me = $('.sortable-menu li:nth-child(' + (ui.item.index() + 1) + ')');
            
            if (ui.item.index() < start_index_element) {
                insert_before_me.before(menu_li_to_move);
            } else {
                insert_before_me.after(menu_li_to_move);
            }
            
            recomputeElementsIndexes();
        },
    });
    
    $( ".sortable-menu" ).sortable({
        
        handle: ".move-anchor",
        
        update: function(event, ui) {
            
            start_index_element = ui.item.first().attr('data-position') - 1;
            
            menu_li_to_move = $('.sortable-wallpapers li:nth-child(' + (start_index_element + 1) + ')');
            insert_before_me = $('.sortable-wallpapers li:nth-child(' + (ui.item.index() + 1) + ')');
            
            if (ui.item.index() < start_index_element) {
                insert_before_me.before(menu_li_to_move);
            } else {
                insert_before_me.after(menu_li_to_move);
            }
            
            recomputeElementsIndexes();
        },
    });
    
    
    $(".sortable-wallpapers").on('click', '.save-wallpaper', function () {
        
        // We need to construct a request.
        
        wallpaper_id = $(this).parent().attr('data-position');
        wallpaper_wordpress_description = $(this).find('.wallpaper-text').val();
        wallpaper_list_index = $(this).find('.wallpaper-name').attr('data-position');
        wallpaper_rejected = 0;
        
        $.post('update_wallpaper_choice.php', {
            id:  wallpaper_id,
            wordpress_description:  wallpaper_wordpress_description,
            list_index:  wallpaper_list_index,
            rejected:  wallpaper_rejected
        }, function(data) {
            console.log(data);
        });
        
    });
    
    $('.save-all-button').on('click', function () {
        
        // We need to construct a request.
        
        $all_wallpapers = $('.sortable-wallpapers li')
        $all_rejected_wallpapers = $('.sortable-wallpapers-trash li')
        
        array_of_data = new Array();
        
        $all_wallpapers.each(function() {
            array_of_data.push({
                wallpaper_id: $(this).attr('data-id'),
                wordpress_description: $(this).find('.wallpaper-text').val(),
                list_index: $(this).find('.wallpaper-name').attr('data-position'),
                rejected: 0
            });
        })

        $all_rejected_wallpapers.each(function() {
            array_of_data.push({
                wallpaper_id: $(this).attr('data-id'),
                wordpress_description: $(this).find('.wallpaper-text').val(),
                list_index: -1,
                rejected: 1
            });
        })
        
        $.post('update_wallpaper_choice.php', {
            'wallpapers': JSON.stringify(array_of_data)
        }, function(data) {
            console.log(data);
        });
        
    });
    
    $('.refresh-button').on('click', function () {
        if (!g_on_going_refresh) {
            
            $(this).addClass('icon-spin');
            
            $.get('refresh_wallpapers.php', function(data) {
                if (data == "OK") {
                    $(this).removeClass('icon-spin');
                }
            });
        }
        
    });
})