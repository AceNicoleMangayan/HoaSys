var v_scroll_ajax_req = [];
var v_scroll_data_params = {};
const no_record_display = (elmentid) =>{
    let scroll_option = $('#'+elmentid).data('vscrolloption');
    let no_record_style = "";
    if(scroll_option.position == 'relative'){
        no_record_style='style="font-size: 1.3rem;font-weight: 400;color: #939fab;"';
    }
    item_layout =  '<div class="m-widget3__item px-3 py-2 '+scroll_option.id+'_item">'+
                            '<div class="text-center" '+no_record_style+'>'+
                                    'No '+scroll_option.list_option.item_label+'s Found'+
                            '</div>'+
                        '</div>';
    $($('#'+scroll_option.id).find('#'+scroll_option.id+'_list')).html(item_layout);
}
const v_scroll_ajax = (v_scroll_id, first_init_bool = 1) =>{
    let scroll_option = $('#'+v_scroll_id).data('vscrolloption');
    let limit_increment = 0;
    if(!first_init_bool){
        limit_increment = parseInt($($('#'+scroll_option.id).find('#'+scroll_option.id+'_list')).data('limit')) + scroll_option.list_option.limiter;  
    }
    $($('#'+scroll_option.id).find('#'+scroll_option.id+'_list')).data('limit', limit_increment);
    v_scroll_data_params.limit = $($('#'+scroll_option.id).find('#'+scroll_option.id+'_list')).data('limit');
    // if (v_scroll_ajax_req[scroll_option.id] != null){
    //     v_scroll_ajax_req[scroll_option.id].abort();
    //     $('#'+scroll_option.id+'_loader').hide();
    // }
    // v_scroll_ajax_req[scroll_option.id] = $.ajax({
    $.ajax({
        type: 'POST',
        url: scroll_option.list_option.ajax_url,
        data: scroll_option.list_option.ajax_params,
        cache: false,
        beforeSend: function() {
            if(scroll_option.position == 'absolute'){
                $('#'+scroll_option.id+'_loader').show();
            }else{
                mApp.block('#'+scroll_option.id, {
                    overlayColor: '#000000',
                    type: 'loader',
                    state: 'success',
                    size: 'lg',
                    centerY: false,
                    centerX: true,
                    css:{
                        position: 'absolute',
                        margin: 'auto'
                    }
                });
            }
        },
        success: function(data) {
            if(scroll_option.position == 'absolute'){
                $('#'+scroll_option.id+'_loader').hide();;
            }else{
                mApp.unblock('#'+scroll_option.id);
            }
            list_items = [];
            list_items = JSON.parse(data.trim());
            let current_list = [];            
            if(list_items.length > 0){
                let item_layout = "";
                let check_box_left = "";
                let check_box_right = "";
                $.each(list_items, function(index, value){
                    check_box_left = "";
                    check_box_right = "";
                    if(scroll_option.list_option.action_btn == true){
                        if(scroll_option.list_option.checkbox == true){
                            check_box_left = '<div class="col item_checkbox px-0">'+
                                                '<label class="m-checkbox m-checkbox--solid m-checkbox--single m-checkbox--success" style="font-size: 17px; font-weight: 500; color: #5d4747;width: unset !important;margin-bottom: unset;">'+
                                                '<input type="checkbox" class="'+scroll_option.id+'_item_checkbox" value="'+value.id+'">'+
                                                '<span></span>'+
                                                '</label>'+
                                            '</div>';
                        }
                        if(value.action != undefined){
                            check_box_right = '<div class="m-widget3__status m--font-info" style="padding-top: unset;display: unset;float: none;">'+
                                                 value.action+
                                            '</div>';
                        }
                    }else{
                        if(scroll_option.list_option.checkbox == true){
                            if(scroll_option.list_option.checkbox_position == 'left'){
                                check_box_left = '<div class="col item_checkbox px-0">'+
                                                    '<label class="m-checkbox m-checkbox--solid m-checkbox--single m-checkbox--success" style="font-size: 17px; font-weight: 500; color: #5d4747;width: unset !important;margin-bottom: unset;">'+
                                                    '<input type="checkbox" class="'+scroll_option.id+'_item_checkbox" value="'+value.id+'">'+
                                                    '<span></span>'+
                                                    '</label>'+
                                                '</div>';
                            }else if(scroll_option.list_option.checkbox_position == 'right'){
                                check_box_right = '<div class="m-widget3__status m--font-info" style="padding-top: unset;display: unset;float: none;">'+
                                                    '<div style="display: inline-flex;align-items: center;vertical-align:middle;">'+
                                                        '<label class="m-checkbox m-checkbox--solid m-checkbox--single m-checkbox--success" style="font-size: 17px; font-weight: 500; color: #5d4747;width: unset !important;margin-bottom: unset;">'+
                                                            '<input type="checkbox" class="'+scroll_option.id+'_item_checkbox" value="'+value.id+'">'+
                                                            '<span></span>'+
                                                        '</label>'+
                                                    '</div>'+
                                                '</div>';
                            }
                        }
                    }
                    let img = "";
                    if(value.img != undefined && value.img != null){
                        let img_style = (scroll_option.list_option.img_round == true)? "": 'style="border-radius:unset !important"';
                            img = '<div class="col px-0">'+
                                '<img class="m-widget3__img" src="'+value.img+'" alt="" '+img_style+'>'+
                            '</div>';
                        
                    }
                    item_layout +=  '<div class="m-widget3__item px-3 py-2 '+scroll_option.id+'_item" data-itemid="'+value.id+'">'+
                                        '<div class="m-widget3__header">'+
                                            '<div class="m-widget3__user-img '+scroll_option.id+'_item_img" style="margin: unset;display: inline-flex;align-items: center;vertical-align: middle;">'+
                                                // '<div class="" style="">'+
                                                    check_box_left+
                                                    img+
                                                // '</div>'+
                                            '</div>'+
                                            '<div class="m-widget3__info" style="padding-left: 1rem !important;">'+
                                                '<span class="m-widget3__username '+scroll_option.id+'_item_text">'+value.text+'</span><br>'+
                                                '<span class="m-widget3__time '+scroll_option.id+'_item_subtext">'+value.subtext+'</span>'+
                                            '</div>'+
                                            check_box_right+
                                        '</div>'+
                                    '</div>';
                });
                if(first_init_bool){
                    $($('#'+scroll_option.id).find('#'+scroll_option.id+'_list')).html(item_layout);
                }else{
                    $($('#'+scroll_option.id).find('#'+scroll_option.id+'_list')).append(item_layout);   
                }
                $('#'+scroll_option.id).data('record_exist', true);
            }else{
                if(!first_init_bool){
                    current_list =  $($('#'+scroll_option.id).find('#'+scroll_option.id+'_list')).find('.m-widget3__item');
                    if(current_list.length < 1){
                        $($('#'+scroll_option.id).find('#'+scroll_option.id+'_list')).html(item_layout);
                        no_record_display(scroll_option.id);
                    }
                }else{
                    no_record_display(scroll_option.id);
                }
                $('#'+scroll_option.id).data('record_exist', false);
            }
        },
        error: function(xhr) { // if error occured
            // alert("Error occured.please try again");
            console.log(xhr.statusText + xhr.responseText);
            // $(placeholder).removeClass('loading');
        },
        complete: function() {
            if(scroll_option.position == 'absolute'){
                $('#'+scroll_option.id+'_loader').hide();;
            }else{
                mApp.unblock('#'+scroll_option.id);
            }
        },
    });
}
const update_v_scroll_params = (v_scroll_id, first_init_bool = 1) =>{
    let scroll_option = $('#'+v_scroll_id).data('vscrolloption');
    v_scroll_data_params = (scroll_option.list_option.ajax_params != undefined && scroll_option.list_option.ajax_params != "")? scroll_option.list_option.ajax_params : {};
    v_scroll_data_params.limiter = scroll_option.list_option.limiter;
    if(scroll_option.search != undefined){
        v_scroll_data_params.search = $('#'+scroll_option.search).val();
    }else{
        if(scroll_option.search_bool == true){
            v_scroll_data_params.search = $('#'+scroll_option.id).find('#'+scroll_option.id+'_search').val();
        }
    }
    scroll_option.list_option.ajax_params = v_scroll_data_params;
    $('#'+v_scroll_id).data('vscrolloption', scroll_option);
    v_scroll_ajax(v_scroll_id, first_init_bool);
}
$.extend({
    v_scroll_2:function(options, end_scroll, check_box, clicked_item, on_search){
        let v_scroll_options = options;
        v_scroll_ajax_req[v_scroll_options.id] = null;
        let selected_checkbox = [];
        let selected_checkbox_all = [];
        let checkbox_border_stat = [];
        let position = "relative";
        let layout = ""
        let style = "";
        let loader = "";
        let background_color = 'white';
        let z_index ="";
        let width_val = "";
        if (v_scroll_options.list_option.item_label == undefined || v_scroll_options.list_option.item_label == ""){
            options.list_option.item_label = "Record";  
        } 
        if(v_scroll_options.background_color != undefined && v_scroll_options.background_color != ""){
            background_color = v_scroll_options.background_color;
        }
        if (v_scroll_options.position == 'relative' || v_scroll_options.position == 'absolute') {
            position = v_scroll_options.position;
        }
        style = 'max-height:'+ v_scroll_options.height+' ;overflow: hidden scroll;';
        
        if(position == 'absolute'){
            if(v_scroll_options.background_color == undefined || v_scroll_options.background_color == ""){
                background_color = "#f4f5f8";
            }
            z_index = 'z-index: 99 !important;';
            if(v_scroll_options.list_option.show_on_search == true){
                style += 'display: none;';
            }
            loader = '<div class="col-12 text-center py-2" id="'+v_scroll_options.id+'_loader" style="background: '+background_color+';font-size: 0.8rem;color: #0000007a; display: none;"><span class="m-loader m-loader--sm m-loader--success" style="width: 30px; display: inline-block;top: -5px;"></span>Loading Records</div>';
        }
        if(v_scroll_options.search == undefined || v_scroll_options.search == ""){
            if(v_scroll_options.search_bool == true){
                layout += '<div class="m-input-icon m-input-icon--right">'+
                                '<input type="text" data-id="0" class="form-control m-input font-12 search_field" style="padding-bottom:12px" placeholder="Search '+v_scroll_options.list_option.item_label+'..." id="'+v_scroll_options.id+'_search">'+
                                '<span class="m-input-icon__icon m-input-icon__icon--right"><span><i class="fa fa-search"></i></span></span>'+
                            '</div>';
            }
        }
        style += 'background: '+background_color+';';
        if(v_scroll_options.width != undefined || v_scroll_options.width != ""){
            width_val = 'width:'+v_scroll_options.width+"%;";
        }
        layout += '<div class="col-12 px-0" style="position:'+position+' !important;'+z_index+width_val+'">'+
                    '<div class="col-12 px-0 m-widget3" id="'+v_scroll_options.id+'_list" style="'+style+'"></div>'+
                    loader+
                '</div>';
        $('#'+v_scroll_options.id).html(layout);
        $('#'+v_scroll_options.id).data('vscrolloption', options);
        $($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).data('limit', 0);
        $($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).data("checkbox", 0);
        $($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).data('checked_values', selected_checkbox);
        update_v_scroll_params(v_scroll_options.id);
        //SCROLL
        $($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).scroll(function() {
            let top_height = $(this).scrollTop() + $(this).innerHeight();
            // console.log("top + height: " +top_height);
            // console.log("scroll height: " +$(this)[0].scrollHeight);
            if($(this).scrollTop() + $(this).innerHeight() + 0.6666660308838 >= $(this)[0].scrollHeight) {
                update_v_scroll_params(v_scroll_options.id, 0);
                end_scroll($(this)[0].scrollHeight);
            }
        });
        
        //SEARCH
        if(v_scroll_options.search != undefined){
            $('#'+v_scroll_options.search).on('keyup', function(){
                console.log('searching');
                update_v_scroll_params(v_scroll_options.id);
                if($(this).val().trim() != ""){
                    if(v_scroll_options.list_option.show_on_search == true){
                        $($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).fadeIn();
                    }
                }else{
                    if(v_scroll_options.list_option.show_on_search == true){
                        $($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).hide();
                    }
                }
                if(on_search != undefined){
                    on_search($(this).val().trim());
                }
            });
        }else{
            if(v_scroll_options.search_bool == true){
                $('#'+v_scroll_options.id).on('keyup', '#'+v_scroll_options.id+'_search', function(){
                    update_v_scroll_params(v_scroll_options.id);
                    if($(this).val().trim() != ""){
                        if(v_scroll_options.list_option.show_on_search == true){
                            $($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).fadeIn();
                        }
                    }else{
                        if(v_scroll_options.list_option.show_on_search == true){
                            $($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).hide();
                        }
                    }
                    if(on_search != undefined){
                        on_search($(this).val().trim());
                    }
                });
            }
        }
        //ITEM CLICK
        $($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).on('click', '.'+v_scroll_options.id+'_item', function(e){
            let item = [];
            let clicked_item_portion = $(e.target).prop('class');
            if(!clicked_item_portion.includes("m-widget3")) return;
            if(v_scroll_options.list_option.hide_on_item_click == true){
                $($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).hide();
            }
            item['element'] = this;
            item['id'] = $(this).data('itemid');
            item['text'] = $(this).find('.'+v_scroll_options.id+'_item_text').text();
            item['subtext'] = $(this).find('.'+v_scroll_options.id+'_item_subtext').text();
            clicked_item(item);
        })
        // CHECKBOX
        $($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).on('click', 'input[type=checkbox]', function(e){
            let checkbox_all = 0;
            let checkbox_under = [];
            let check_box_data = [];
            let current_checkbox_stat = false;
            if(($(this).val() == 0) && ($(this).data('value') > 0)){//check all specific
                checkbox_under  = $($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).find('input[data-value="'+$(this).data('value')+'"]').map(function () {
                    if($(this).val() > 0){
                        return $(this).val();
                    }
                }).get();
                if($(this).is(':checked')){
                    $($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).find('input[data-value="'+$(this).data('value')+'"]').prop('checked', true);
                    checkbox_border_stat = $($($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).find('input[data-value="'+$(this).data('value')+'"]')).parents('.m-widget2__item');
                    if(checkbox_border_stat.length > 0){
                        $($($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).find('input[data-value="'+$(this).data('value')+'"]')).parents('.m-widget2__item').removeClass('m-widget2__item--metal');
                        $($($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).find('input[data-value="'+$(this).data('value')+'"]')).parents('.m-widget2__item').addClass('m-widget2__item--success');
                    }
                }else{
                    $($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).find('input[data-value="'+$(this).data('value')+'"]').prop('checked', false);
                    checkbox_border_stat = $($($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).find('input[data-value="'+$(this).data('value')+'"]')).parents('.m-widget2__item');
                    if(checkbox_border_stat.length > 0){
                        $($($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).find('input[data-value="'+$(this).data('value')+'"]')).parents('.m-widget2__item').addClass('m-widget2__item--metal');
                        $($($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).find('input[data-value="'+$(this).data('value')+'"]')).parents('.m-widget2__item').removeClass('m-widget2__item--success');
                    }
                }
            }else if(($(this).val() == 0) && ($(this).data('value') == 0)){ // check all
                checkbox_under  = $($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).find('input[type=checkbox]').map(function () {
                    if($(this).val() > 0){
                        return $(this).val();
                    }
                }).get(); 
                if($(this).is(':checked')){
                    checkbox_all = 1;
                    $($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).find('input[type=checkbox]').prop('checked', true);
                    $($($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).find('input[type=checkbox]')).parents('.m-widget2__item').removeClass('m-widget2__item--metal');
                    $($($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).find('input[type=checkbox]')).parents('.m-widget2__item').addClass('m-widget2__item--success');
                }else{
                    checkbox_all = 0;
                    $($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).find('input[type=checkbox]').prop('checked', false);
                    $($($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).find('input[type=checkbox]')).parents('.m-widget2__item').addClass('m-widget2__item--metal');
                    $($($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).find('input[type=checkbox]')).parents('.m-widget2__item').removeClass('m-widget2__item--success');
                }
            }
            if($(this).is(':checked')){
                current_checkbox_stat = true;
            }else{
                current_checkbox_stat = false;
                if($(this).val() > 0){
                    $($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).find('input[data-value="'+$(this).data('value')+'"][value="0"]').prop('checked', false);
                    $($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).find('input[data-value="0"][value="0"]').prop('checked', false);
                }
            }
            selected_checkbox = $($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).find('input[type=checkbox]:checked').map(function () {
                if($(this).val() > 0){
                    return $(this).val(); 
                }
            }).get();
            selected_checkbox_all  = $($('#'+v_scroll_options.id).find('#'+v_scroll_options.id+'_list')).find('input[type=checkbox]:checked').map(function () {
                if($(this).val() == 0){
                    return $(this).data('value'); 
                }
            }).get();
            $('#'+v_scroll_options.id).data('checked_values', selected_checkbox);
            $('#'+v_scroll_options.id).data('checked_values_all', selected_checkbox_all);
            $('#'+v_scroll_options.id).data('checked_all', checkbox_all);
            $('#'+v_scroll_options.id).data('checkbox_under', checkbox_under);
            check_box_data['checked_all'] = checkbox_all;
            check_box_data['checked_boxes'] = selected_checkbox;
            check_box_data['checked_boxes_all'] = selected_checkbox_all;
            check_box_data['checked_boxes_under'] = checkbox_under;
            check_box_data['current_checked_box_value'] = $(this).val();
            check_box_data['current_checked_box_data_value'] = $(this).data('value');
            check_box_data['current_checked_box_status'] = current_checkbox_stat;
            // for m-widget 2 checkboxes
            let checkbox_layout = $(this).parents('.m-widget2__item');
            if(checkbox_layout.length > 0){
                if(current_checkbox_stat){
                    $(this).parents('.m-widget2__item').removeClass('m-widget2__item--metal');
                    $(this).parents('.m-widget2__item').addClass('m-widget2__item--success');
                }else{
                    $(this).parents('.m-widget2__item').addClass('m-widget2__item--metal');
                    $(this).parents('.m-widget2__item').removeClass('m-widget2__item--success');
                }
            }
            check_box(check_box_data);
        });
        
    },
})

const v_scroll_2_method = {
    reload: function(element_id, method, specific_option, data){
        let scroll_option = $('#'+element_id).data('vscrolloption');
        switch (method) {
            case 'update':
            scroll_option.list_option[specific_option] = data; 
            default:
                break;
        }
        $('#'+element_id).data('vscrolloption', scroll_option);
       update_v_scroll_params(element_id);
    },
    update_ajax_params: function(element_id, data){
        let scroll_option = $('#'+element_id).data('vscrolloption');
        scroll_option.list_option.ajax_params = data;
        $('#'+element_id).data('vscrolloption', scroll_option); 
    },
    recheck_list: function(element_id){
        let scroll_option = $('#'+element_id).data('vscrolloption');
        let current_list =  $($('#'+element_id).find('#'+element_id+'_list')).find('.m-widget3__item');
        if(current_list.length < 1){
            no_record_display(scroll_option.id);
        }
    }
}