$ = jQuery;

$(function(){
    awl_reload_elements();
});

function awl_reload_elements(){
    var $input = $('input[name=awl_wplink_styling]');
    var $table = $('#awl_styling_options');
    var value = [];
    $table.find('tr.element').each(function(){

        var name = $(this).find('input[name=name]').val();
        var selector = $(this).find('input[name=selector]').val();
        if(name!='' && selector!=''){
            var element = {
                name    : name,
                selector: selector
            };
            value.push(element);
        }
    });
    $input.val(JSON.stringify(value));
}

function awl_change_element(input){
    var $input = $(input);
    var value = $input.val();
    if($input.attr('name')=='selector'){
        value = value.replace(/[^a-z0-9_\s-]/gi, '').replace(/[\s-]+/gi,' ');
        $input.val(value);
    }

    awl_reload_elements();
}

function awl_add_element(){
    var $table = $('#awl_styling_options');
    var new_element_id = 'element_'+Math.floor((Math.random() * 100000) + 100);
    var new_element = $('#awl_defaultelement').html();
    $table.append('<tr id="'+new_element_id+'" class="element">'+new_element+'</tr>');
    awl_reload_elements();
}

function awl_remove_element(trigger){
    var $trigger = $(trigger);
    $trigger.parents('tr')[0].remove();
    awl_reload_elements();
}
