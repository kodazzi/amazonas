
/**
 * Created by jorge on 26/06/15.
 */

(function($){

    $.fn.upload = function( options ) {
        var conf = $.extend( {
            url: "",
            actionDisplayFiles: "/admin/upload/display",
            actionUpload: "/admin/upload/upload",
            actionRename: "/admin/upload/rename",
            actionDelete: "/admin/upload/delete",
            path: "/upload/",
            allowedTypes: "jpg,jpeg,png",
            returnType: 'json',
            idTarget: "",
            autoSubmit: true,
            returnOnlyText: false,
            selectedFile: null,
            onSelect: function(file){}
        }, options );

        var modal = $(".k-modal");

        // Si ya existe la ventana no la crea.
        if(modal.length == 0){
            htmlModal();
            modal = $('body').find('.k-modal');
        }

        var btn = $(this);
        var form = modal.find('form.k-form-upload');

        // Agrega al formulario el valor de action.
        form.attr('action', conf.actionUpload);

        // Al mostrar la ventana lanza una peticion para encontrar las imagenes
        modal.on('shown.bs.modal', function (e) {
            showFileManager($(this), conf);
        });

        // Al cerrar la ventana se limpia el contenedor
        modal.on('hidden.bs.modal', function (e) {
            clearAll(this);
        });

        // Al hacer clic en el boton "seleccionar" se obtiene el archivo
        modal.find('.k-btn-select').on('click', function(){
            if(returnSelectedFile(modal, conf)){
                modal.modal('hide');
                conf.onSelect(conf.selectedFile);
            }
        });

        // Muestra la ventana
        btn.on('click', function(){
            modal.modal('show');
        });

        // Agrega evento click al boton upload para que muestre la ventana "Carga de archivos"
        form.find("button[type=button]").on('click', function(){
            form.find("input[type=file]").click();
        });

        // Agrega el evento change al campo tipo file.
        form.find('input[type="file"]').on('change', function() {
            changeInputFile(modal, form, this, conf);
        });
    }

    function showFileManager(modal, conf){

        var browser_files = modal.find('.k-list-files');

        $.ajax ({
            url: conf.actionDisplayFiles,
            type: "POST",
            dataType: 'json',
            data: {'allowed_types':conf.allowedTypes},
            cache: false,
            beforeSend: function(){
                browser_files.html('');
            },
            success: function ( result ){

                if(typeof (result.files) != "undefined"){}{

                    // Cada archivo del directorio lo carga en el contenedor
                    $.each(result.files, function(i, item) {
                        addFile(item, browser_files, modal, conf);
                    });
                }
            },
            error: function(){

            }
        });
    }

    function showSelectedFile(modal, thumbnail, conf){
        var path = $(thumbnail).find('img').attr('src');
        var thumbnails = $(modal).find('.k-list-files .thumbnail');
        var name = getNameFile(path);
        var id = $(thumbnail).attr('id');

        $.each(thumbnails, function(i, item) {
            $(item).removeClass('k-active');
        });

        $(thumbnail).addClass('k-active');

        var form = $('<form action="" method="post" class="form-horizontal" role="form"> \
                        <div class="form-group"> \
                            <label for="k-upload-file" class="col-lg-3 control-label">Nombre</label>\
                            <div class="col-lg-9" > \
                                <input type="text" class="form-control" name="k-file-new" value="'+name+'"/> \
                            </div> \
                        </div> \
                        <div class="form-group"> \
                            <div class="col-lg-12"> \
                                <input type="hidden" name="k-file-old" value="'+name+'"/> \
                                <input type="hidden" name="k-id-thumbnail" value="'+id+'"/> \
                                <button class="btn btn-primary btn-sm" type="submit">Guardar</button> &oacute; <a href="" class="text-danger k-btn-delete">Eliminar del sistema</a> \
                            </div> \
                        </div> \
                      </form> \
            ');

        form.on('submit', function(){
            renameFile(this, conf);
            return false;
        });

        form.find(".k-btn-delete").on('click', function(){
            deleteFile(modal, form, conf);
            return false;
        });

        var img = $("<img src='"+path+"' />");
        var caption = $("<div class='caption'></div>").append(form);
        var selected_thumbnail = $("<div class='thumbnail'></div>").append(img).append(caption);

        $(modal).find('.k-file-details .k-file-details-content').html(selected_thumbnail);
        $(modal).find('.k-file-details .k-file-details-values input[name=path-selected-file]').val(path);
    }

    function renameFile(form, conf){

        // Obtiene el id del thumbnail seleccionado
        var id_thumbnail = $(form).find('input[name=k-id-thumbnail]').val();

        $.ajax ({
            url: conf.actionRename,
            type: "POST",
            dataType: 'json',
            data: $(form).serialize(),
            cache: false,
            beforeSend: function(){},
            success: function (result){
                if(typeof (result.status) != "undefined"){}{
                    if(result.status == 'ok'){
                        $("#"+id_thumbnail).find('.caption').text(result.name);

                        // Se actualiza el campo k-file-old con el nuevo nombre.
                        $(form).find('input[name=k-file-old]').val(result.name);

                        Alert.success(result.msg);
                    }else{
                        Alert.error(result.msg);
                    }
                }
            },
            error: function(){}
        });
    }

    function deleteFile(modal, form, conf){
        // Obtiene el id del thumbnail seleccionado
        var id_thumbnail = $(form).find('input[name=k-id-thumbnail]').val();

        $.ajax ({
            url: conf.actionDelete,
            type: "POST",
            dataType: 'json',
            data: $(form).serialize(),
            cache: false,
            beforeSend: function(){},
            success: function (result){
                if(typeof (result.status) != "undefined"){}{
                    if(result.status == 'ok'){
                        $("#"+id_thumbnail).parent().remove();

                        // Limpia los detalles del archivo seleccionado
                        clearColDetails(modal);

                        Alert.success(result.msg);
                    }else{
                        Alert.error(result.msg);
                    }
                }
            },
            error: function(){}
        });
    }

    function changeInputFile(modal, form, input, conf){

        // Obtiene el nombre del archivo seleccionado.
        var filenameStr = $(input).val();

        // Verifica el tipo de archivo.
        if (! isFileTypeAllowed(input, conf, filenameStr)) {
            showMessage(modal, '<p class="text-danger"><span class="fa fa-exclamation-triangle"></span> El tipo de archivo seleccionado no est&aacute; permitido</p>' );
            return;
        }

        var bar = new createProgressBar();
        modal.find('.k-upload-result').html(bar.container);

        ajaxFormSubmit(modal, form, input, bar, conf);
    }

    function createProgressBar() {
        this.container = $('<div class="progress">');
        this.progress = $('<div class="progress-bar progress-bar-success progress-bar-striped" style="width: 0%">').appendTo(this.container);
        return this;
    }

    function ajaxFormSubmit(modal, form, input, bar, conf){

        var options = {
            cache: false,
            contentType: false,
            processData: false,
            forceSync: false,
            dataType: conf.returnType,
            beforeSend: function(xhr, o) {
                /*
                 bar.div_btn_options.html(btnAbort);

                 // Agrega el evento click al boton de abortar.
                 btnAbort.click(function(){
                 xhr.abort();
                 });
                 */

                //showPreload(obj, conf.preload);
            },
            uploadProgress: function( event, position, total, percentComplete ) {
                 bar.progress.width(percentComplete+'%');
                if(percentComplete == 100){
                    bar.progress.html('Listo!!');
                }else{
                    bar.progress.html(percentComplete+'%');
                }
            },
            success: function(result) {

                if( typeof( result.status ) == "undefined" ) {
                    showMessage(modal, '<p class="text-danger">Ocurrio un error, por favor intente mas tarde.</p>');
                }else{
                    if( result.status == 'ok' ){
                        addFile(result.path_http, modal.find('.k-list-files'), modal);
                    }else{
                        showMessage(modal, result.msg);
                    }
                }
            },
            error: function() {
                //showMessage( obj, '<p class="text-danger">Ocurrio un error, por favor intente mas tarde.</p>', 'error' );
            }
        };

        if (conf.autoSubmit) {
            form.ajaxSubmit(options);
        }
    }

    function addFile(file, content_browser, modal, conf){

        // Obtiene el nombre del archivo
        var name = getNameFile(file);
        var slug = name.replace(/[^a-zA-Z0-9]+/g, '-') // remueve todo lo que no sea numeros o letras
                       .replace(/\s+/g, '-') // todo lo que sea espacios lo cambia por -
                       .replace(/-+/g, '-')
                       .toLowerCase();

        var col = $("<div class='col-lg-2'></div>");
        var thumbnail = $("<div class='thumbnail' id='k-id-"+slug+"'></div>").appendTo(col);
        var img = $("<img src='"+file+"'/>").appendTo(thumbnail);
        var caption = $("<div class='caption'>"+name+"</div>").appendTo(thumbnail);

        $(thumbnail).on('click', function(){
            showSelectedFile(modal, this, conf);
        });

        content_browser.append(col);
    }

    function isFileTypeAllowed( input, conf, fileName ) {
        var fileExtensions = conf.allowedTypes.toLowerCase().split(",");
        var ext = fileName.split('.').pop().toLowerCase();
        if (conf.allowedTypes != "*" && jQuery.inArray(ext, fileExtensions) < 0) {
            return false;
        }
        return true;
    }

    function showMessage(modal, msg){
        modal.find('.k-upload-result').html(msg);
    }

    function returnSelectedFile(modal, conf){

        var name = $(modal).find('input[name=k-file-old]').val();

        conf.selectedFile = {path: conf.path, name: name};

        if(conf.idTarget != ''){
            var target = $('#'+conf.idTarget);

            if(target.length == 0){
                alert('El elemento con id '+conf.idTarget+' no fue encontrado.');
                return false;
            }

            var type = target[0].tagName.toUpperCase();

            if(type == 'INPUT' || type == 'TEXTAREA'){
                target.val(name);
            }else{
                target.html('<img src="'+conf.path+name+'" />');
            }
        }

        return true;
    }

    function getNameFile(path){
        return path.substring(path.lastIndexOf('/')+1)
    }

    function clearColDetails(modal){
        $(modal).find('.k-file-details .k-file-details-content').html('Seleccione una imagen!!');

    }

    function clearAll(modal){
        clearColDetails(modal);
        $(modal).find('.k-list-files').html('');
        $(modal).find('.k-upload-result').html('');
        $(modal).find('.k-file-details-values input[name=path-selected-file]').val('');
    }

    function htmlModal(){
        var html = '<div class="modal fade k-modal" tabindex="-1" role="dialog"> \
                        <div class="modal-dialog modal-lg"> \
                            <div class="modal-content"> \
                                <div class="modal-header"> \
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> \
                                    <h4 class="modal-title" id="myModalLabel">Administrador de archivos</h4> \
                                </div> \
                                <div class="modal-body"> \
                                    <div class="row"> \
                                        <div class="col-lg-3 k-file-details"> \
                                            <h4>Detalles</h4> \
                                            <div class="k-file-details-content">Seleccione una imagen!!</div> \
                                            <div class="k-file-details-values"> \
                                                <input type="hidden" name="path-selected-file" /> \
                                            </div> \
                                        </div> \
                                        <div class="col-lg-9 k-list-files"></div> \
                                    </div> \
                                </div> \
                                <div class="modal-footer"> \
                                    <div class="row"> \
                                        <div class="col-lg-2 text-left"> \
                                            <form action="" method="post" class="k-form-upload" enctype="multipart/form-data"> \
                                                <input type="file" name="file"/> \
                                                <button type="button" class="btn btn-default btn-sm">Subir <i class="fa fa-upload"></i></button> \
                                            </form> \
                                        </div> \
                                        <div class="col-lg-8 text-left k-upload-result"> \
                                        </div> \
                                            <div class="col-lg-2 text-right"> \
                                                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button> \
                                                <button type="button" class="btn btn-primary btn-sm k-btn-select">Seleccionar</button> \
                                            </div> \
                                        </div> \
                                    </div> \
                                </div> \
                            </div> \
                        </div> \
                    </div>';

        $('body').append(html)
    }

})( jQuery );
