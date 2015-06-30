/**
 * Created by jorge on 29/06/15.
 */

$(document).ready(function() {
    Alert.init();
});

var Alert = {
    id : 'ds-alert',
    idBottom: 'alert ds-alert-bottom',
    classSuccess : 'alert alert-success',
    classError : 'alert alert-danger',
    classInformation : 'alert alert-info',
    classWarning : 'alert alert-warning',
    delay : 500,
    duration : 5000,
    width: 600,
    position: 'top',

    init : function() {

        percentage = (Alert.width * 100) / $(window).width();
        left = Math.floor((100 - percentage) / 2);

        style_init = {width: Alert.width, left: left+'%', top: '-70px'};

        if(Alert.position == 'bottom'){
            style_init = {width: Alert.width, left: left+'%', bottom: '-70px'};
        }

        $('#' + Alert.id).css(style_init);

        if($('#' + Alert.id).delay(Alert.delay).length) {
            Alert.animation();
        }
    },
    error : function(message, duration) {
        Alert.show(message, Alert.classError, duration);
    },
    success : function(message, duration) {
        Alert.show(message, Alert.classSuccess, duration);
    },
    information : function(message, duration) {
        Alert.show(message, Alert.classInformation, duration);
    },
    warning : function(message, duration) {
        Alert.show(message, Alert.classWarning, duration);
    },
    show : function(message, css_class, duration) {

        $('#' + Alert.id).remove();
        var alertDiv = $('<div></div>').attr('id', Alert.id).attr('class', css_class);

        percentage = (Alert.width * 100) / $(window).width();
        left = Math.floor((100 - percentage) / 2);

        style_init = {width: Alert.width, left: left+'%', top: '-70px'};

        if(Alert.position == 'bottom'){
            style_init = {width: Alert.width, left: left+'%', bottom: '-70px'};
        }

        alertDiv.css(style_init);

        $('body').prepend(alertDiv);
        $('#' + Alert.id).html(message);
        Alert.animation(duration);
    },
    animation : function(duration) {
        if(duration == undefined)
            duration = Alert.duration;

        var id = ( Alert.position == 'bottom' ) ? Alert.idBottom : Alert.idTop ;

        style_show = {top:'80px', opacity: 1};
        style_hide = {top:'-70px', opacity: 0};

        if(Alert.position == 'bottom'){
            style_show = {bottom:'40px', opacity: 1};
            style_hide = {bottom:'-70px', opacity: 0};
        }

        $('#' + Alert.id).animate(style_show, 600);

        window.setTimeout(function() {
            $('#' + Alert.id).animate(style_hide, 600, null, function(){
                $(this).remove();
            });
        }, duration);
    }
}