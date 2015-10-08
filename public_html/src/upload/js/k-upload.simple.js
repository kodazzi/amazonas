(function ( $ ) {

    $.fn.upload = function( options ) {

        // This is the easiest way to have default options.
        var settings = $.extend({
            url: "",
            actionDisplayFiles: "/upload/display-files",
            actionUpload: "/upload/files",
            actionRename: "/upload/rename-file",
            actionDelete: "/upload/delete-file",
            allowedTypes: "jpg,jpeg,png",
            returnType: "json",
            idFieldTarget: "",
            idShow: "",
            autoSubmit: true,
            returnOnlyText: false,
            selectedFile: null,
            element: null,
            onSelect: function(file){}
        }, options );

        var modal = $(".k-modal");

        // Si ya existe la ventana no la crea.
        if(modal.length == 0){
            htmlModal(settings);
            modal = $('body').find('.k-modal');
        }

        // Al cerrar la ventana se limpia el contenedor
        modal.on('hidden.bs.modal', function (e) {
            clearAll(this);
        });

        var form = modal.find('form.k-form-upload');

        // Agrega evento click al boton upload para que muestre la ventana "Carga de archivos"
        form.find("button[type=button]").on('click', function(){
            form.find("input[type=file]").click();
        });

        // Agrega el evento change al campo tipo file.
        form.find('input[type="file"]').on('change', function() {
            changeInputFile(modal, form, this, settings);
        });

        // Recorre todos los elementos encontrados por el selector
        this.each(function() {
            var element = jQuery(this);
            element.on('click', function(){
                settings.element = jQuery(this);
                modal.modal('show');
            });
        });

        function changeInputFile(modal, form, input, settings){

            // Obtiene el nombre del archivo seleccionado.
            var filenameStr = jQuery(input).val();

            // Verifica el tipo de archivo.
            if (! isFileTypeAllowed(input, settings, filenameStr)) {
                showMessage(modal, '<p class="text-danger"><span class="fa fa-exclamation-triangle"></span> El tipo de archivo seleccionado no est&aacute; permitido</p>' );
                return;
            }

            var bar = new createProgressBar();
            modal.find('.k-upload-result').html(bar.container);

            ajaxFormSubmit(modal, form, input, bar, settings);
        }

        function ajaxFormSubmit(modal, form, input, bar, settings){

            var options = {
                cache: false,
                contentType: false,
                processData: false,
                forceSync: false,
                dataType: settings.returnType,
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
                            returnSelectedFile(result.path_http, settings);
                            modal.modal('hide');
                        }else{
                            showMessage(modal, result.msg);
                        }
                    }
                },
                error: function() {
                    //showMessage( obj, '<p class="text-danger">Ocurrio un error, por favor intente mas tarde.</p>', 'error' );
                }
            };

            if (settings.autoSubmit) {
                form.ajaxSubmit(options);
            }
        }

        function createProgressBar() {
            this.container = $('<div class="progress">');
            this.progress = $('<div class="progress-bar progress-bar-success progress-bar-striped" style="width: 0%">').appendTo(this.container);
            return this;
        }

        function returnSelectedFile(file, settings){

            var name = getNameFile(file);

            if(settings.idFieldTarget == ''){
                var target = settings.element;
            }else{
                var target = jQuery('#'+settings.idFieldTarget);

                if(target.length == 0){
                    alert('El elemento con id '+settings.idFieldTarget+' no fue encontrado.');
                    return false;
                }
            }

            var type = target[0].tagName.toUpperCase();

            if(type == 'INPUT' || type == 'TEXTAREA'){
                target.val(name);
            }

            if(settings.idShow != ''){
                var element = jQuery('#'+settings.idShow);

                if(element.length == 0){
                    alert('El elemento con id '+settings.idShow+' no fue encontrado.');
                    return false;
                }

                element.html('<img src="'+file+'" />');
            }

            return true;
        }

        function clearColDetails(modal){
            jQuery(modal).find('.k-file-details .k-file-details-content').html('Seleccione una imagen!!');
        }

        function clearAll(modal){
            clearColDetails(modal);
            jQuery(modal).find('.k-list-files').html('');
            jQuery(modal).find('.k-upload-result').html('');
            jQuery(modal).find('.k-file-details-values input[name=path-selected-file]').val('');
        }

        function getNameFile(path){
            return path.substring(path.lastIndexOf('/')+1)
        }

        function isFileTypeAllowed( input, settings, fileName ) {
            var fileExtensions = settings.allowedTypes.toLowerCase().split(",");
            var ext = fileName.split('.').pop().toLowerCase();
            if (settings.allowedTypes != "*" && jQuery.inArray(ext, fileExtensions) < 0) {
                return false;
            }
            return true;
        }

        function showMessage(modal, msg){
            modal.find('.k-upload-result').html(msg);
        }

        function htmlModal(settings){
            var html = '<div class="modal fade k-modal" tabindex="-1" role="dialog"> \
                        <div class="modal-dialog"> \
                            <div class="modal-content"> \
                                <div class="modal-header"> \
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> \
                                    <h4 class="modal-title" id="myModalLabel">Administrador de archivos</h4> \
                                </div> \
                                <div class="modal-body"> \
                                    <div class="row"> \
                                        <div class="col-lg-2"> \
                                            <p> \
                                                <form action="'+settings.actionUpload+'" method="post" class="k-form-upload" enctype="multipart/form-data"> \
                                                    <input type="file" name="file"/> \
                                                    <button type="button" class="btn btn-primary">Subir <i class="fa fa-upload"></i></button> \
                                                </form> \
                                            </p> \
                                        </div> \
                                        <div class="col-lg-10 text-left k-upload-result"></div> \
                                    </div> \
                                    <div class="row"><div class="col-lg-12"><p class="text-info">Por favor, haga clic en el bot&oacute;n</p></div></div>\
                                </div> \
                                <div class="modal-footer"> \
                                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button> \
                                </div> \
                            </div> \
                        </div> \
                    </div>';

            $('body').append(html)
        }
    };

}( jQuery ));