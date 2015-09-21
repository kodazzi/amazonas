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
            idTarget: "",
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

        // Al mostrar la ventana lanza una peticion para encontrar las imagenes en el directorio
        modal.on('shown.bs.modal', function (e) {
            showFileManager(jQuery(this), settings);
        });

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

        // Al hacer clic en el boton "seleccionar" se obtiene el archivo
        modal.find('.k-btn-select').on('click', function(){
            if(returnSelectedFile(modal, settings)){
                modal.modal('hide');
                settings.onSelect(settings.selectedFile);
            }
        });

        // Recorre todos los elementos encontrados por el selector
        this.each(function() {

            var element = jQuery(this);

            element.on('click', function(){
                settings.element = jQuery(this);
                modal.modal('show');
            });

        });

        function showFileManager(modal, settings){

            var browser_files = modal.find('.k-list-files');

            jQuery.ajax ({
                url: settings.actionDisplayFiles,
                type: "POST",
                dataType: 'json',
                data: {'allowed_types':settings.allowedTypes},
                cache: false,
                beforeSend: function(){
                    browser_files.html('');
                },
                success: function ( result ){

                    if(typeof (result.files) != "undefined"){}{

                        if(result.status == 'ok'){
                            // Cada archivo del directorio lo carga en el contenedor
                            jQuery.each(result.files, function(i, item) {
                                addFile(item, browser_files, modal, settings);
                            });
                        }else{
                            showMessage(modal, result.msg);
                        }
                    }
                },
                error: function(){

                }
            });
        }

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

            if (settings.autoSubmit) {
                form.ajaxSubmit(options);
            }
        }

        function createProgressBar() {
            this.container = $('<div class="progress">');
            this.progress = $('<div class="progress-bar progress-bar-success progress-bar-striped" style="width: 0%">').appendTo(this.container);
            return this;
        }

        function addFile(file, content_browser, modal, settings){

            // Obtiene el nombre del archivo
            var name = getNameFile(file);
            var slug = name.replace(/[^a-zA-Z0-9]+/g, '-') // remueve todo lo que no sea numeros o letras
                .replace(/\s+/g, '-') // todo lo que sea espacios lo cambia por -
                .replace(/-+/g, '-')
                .toLowerCase();

            var col = jQuery("<div class='col-lg-2'></div>");
            var thumbnail = jQuery("<div class='thumbnail' id='k-id-"+slug+"'></div>").appendTo(col);
            var img = jQuery("<img src='"+file+"'/>").appendTo(thumbnail);
            var caption = jQuery("<div class='caption'>"+name+"</div>").appendTo(thumbnail);

            jQuery(thumbnail).on('click', function(){
                showSelectedFile(modal, this, settings);
            });

            content_browser.append(col);
        }

        function showSelectedFile(modal, thumbnail, settings){
            var path = jQuery(thumbnail).find('img').attr('src');
            var thumbnails = jQuery(modal).find('.k-list-files .thumbnail');
            var name = getNameFile(path);
            var id = jQuery(thumbnail).attr('id');

            jQuery.each(thumbnails, function(i, item) {
                jQuery(item).removeClass('k-active');
            });

            jQuery(thumbnail).addClass('k-active');

            var form = jQuery('<form action="" method="post" class="form-horizontal" role="form"> \
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
                renameFile(this, settings);
                return false;
            });

            form.find(".k-btn-delete").on('click', function(){
                deleteFile(modal, form, settings);
                return false;
            });

            var img = jQuery("<img src='"+path+"' />");
            var caption = jQuery("<div class='caption'></div>").append(form);
            var selected_thumbnail = jQuery("<div class='thumbnail'></div>").append(img).append(caption);

            jQuery(modal).find('.k-file-details .k-file-details-content').html(selected_thumbnail);
            jQuery(modal).find('.k-file-details .k-file-details-values input[name=path-selected-file]').val(path);
        }

        function renameFile(form, settings){

            // Obtiene el id del thumbnail seleccionado
            var id_thumbnail = jQuery(form).find('input[name=k-id-thumbnail]').val();

            jQuery.ajax ({
                url: settings.actionRename,
                type: "POST",
                dataType: 'json',
                data: jQuery(form).serialize(),
                cache: false,
                beforeSend: function(){},
                success: function (result){
                    if(typeof (result.status) != "undefined"){}{
                        if(result.status == 'ok'){
                            jQuery("#"+id_thumbnail).find('.caption').text(result.name);

                            // Se actualiza el campo k-file-old con el nuevo nombre.
                            jQuery(form).find('input[name=k-file-old]').val(result.name);

                            Alert.success(result.msg);
                        }else{
                            Alert.error(result.msg);
                        }
                    }
                },
                error: function(){}
            });
        }

        function deleteFile(modal, form, settings){
            // Obtiene el id del thumbnail seleccionado
            var id_thumbnail = jQuery(form).find('input[name=k-id-thumbnail]').val();

            jQuery.ajax ({
                url: settings.actionDelete,
                type: "POST",
                dataType: 'json',
                data: jQuery(form).serialize(),
                cache: false,
                beforeSend: function(){},
                success: function (result){
                    if(typeof (result.status) != "undefined"){}{
                        if(result.status == 'ok'){
                            jQuery("#"+id_thumbnail).parent().remove();

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

        function returnSelectedFile(modal, settings){

            var name = jQuery(modal).find('input[name=k-file-old]').val();

            settings.selectedFile = {name: name};

            if(settings.idTarget == ''){
                var target = settings.element;
            }else{
                var target = jQuery('#'+settings.idTarget);

                if(target.length == 0){
                    alert('El elemento con id '+settings.idTarget+' no fue encontrado.');
                    return false;
                }
            }

            var type = target[0].tagName.toUpperCase();

            if(type == 'INPUT' || type == 'TEXTAREA'){
                target.val(name);
            }else{
                target.html('<img src="'+settings.path+name+'" />');
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
                                            <form action="'+settings.actionUpload+'" method="post" class="k-form-upload" enctype="multipart/form-data"> \
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
    };

}( jQuery ));