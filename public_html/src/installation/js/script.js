$(document).ready(function(){
    $("form#form-installation-step1").submit(function(){
        step1(this);
        return false;
    });
});

function step1(f){
    var url = $(f).attr('action');
    var btn = $(f).find('button');
    btn.button('loading');

    $.ajax({
        url: url,
        type: "POST",
        data: $(f).serialize(),
        dataType: 'html',
        cache: false,
        success: function (result){

            var json = eval("(" + result + ")");

            if(typeof (json.status) != "undefined"){
                if(json.status == 'ok'){
                    $("#content-form-steps").html(json.html);
                }else{
                    if(typeof (json.msg) != "undefined"){
                        $("#global-message").html('<p class="alert alert-danger">'+json.msg+'</p>');
                    }

                    if(typeof (json.fields) != "undefined"){
                        $( json.fields ).each(function(i, field){
                            var parent = $('#'+field.id).parent();
                            var e = parent.find('p.text-danger');

                            if(field.status == 'ok'){
                                e.remove();
                                parent.removeClass('form-group-danger')
                            }else{
                                $("p.text-info").remove();
                                if( e.length == 0 ){
                                    parent.addClass('form-group-danger');
                                    parent.append('<p class="text-danger">'+field.error+'</p>');
                                }else{
                                    e.text(field.error);
                                }
                            }
                        })
                    }
                }
            }

            btn.button('reset');

            //$('form#ds-form-login')[0].reset();
            //btn.button('reset');
        },
        error: function(){
            $("div#ds-msg-global").html('<p class="alert alert-danger">Ocurrio un error, por favor intente mas tarde.</p>');
            btn.button('reset');
        }
    });
}

function step2(f){
    var url = $(f).attr('action');
    var btn = $(f).find('button');

    btn.button('loading');

    $.ajax({
        url: url,
        type: "POST",
        data: $(f).serialize(),
        dataType: 'html',
        cache: false,
        success: function (result){

            var json = eval("("+result+")");

            if(typeof (json.status) != "undefined"){
                if(json.status == 'ok'){
                    // redirecciona al login
                    window.location = json.url;
                }else{
                    if(typeof (json.msg) != "undefined"){
                        $("#global-message").html('<p class="alert alert-danger">'+json.msg+'</p>');
                    }

                    if(typeof (json.fields) != "undefined"){
                        $(json.fields).each(function(i, field){
                            var parent = $('#'+field.id).parent();
                            var e = parent.find('p.text-danger');

                            if(field.status == 'ok'){
                                e.remove();
                                parent.removeClass('form-group-danger')
                            }else{
                                $("p.text-info").remove();
                                if( e.length == 0 ){
                                    parent.addClass('form-group-danger');
                                    parent.append('<p class="text-danger">'+field.error+'</p>');
                                }else{
                                    e.text(field.error);
                                }
                            }
                        })
                    }
                }
            }

            //$('form#ds-form-login')[0].reset();
            btn.button('reset');
        },
        error: function(){
            $("div#ds-msg-global").html('<p class="alert alert-danger">Ocurrio un error, por favor intente mas tarde.</p>');
            btn.button('reset');
        }
    });
}