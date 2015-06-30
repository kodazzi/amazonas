$(function() {
    var url = window.location;

    var element = $('ul.sidebar-menu a').filter(function() {
        return this.href == url || url.href.indexOf(this.href) == 0;
    }).parent().addClass('active').parent().addClass('active').parent();

    if (element.is('li')) {
        element.addClass('active');
    }

    element = element.parent();

    if(element.is('ul') && element.hasClass('treeview-menu')){
        element.addClass('active').addClass('menu-open').css({'display':'block'});
    }
});