class Productos extends Base{

    constructor() {
        super('productos');
        this.initFunctions();
    }
    /**
     * Ejecuta por ajax el metodo Productos::create
     * 
     * @returns {JSON}
     */
    createProduct(){

        const csrfName = $('#csrf').attr('name');
        const data = {
        title: $('#title').val(),
        price: $('#price').val(),
        [csrfName]: $('#csrf').val()
        };

        this.ajaxCall('json', 'POST', this.baseUrl+'/productos', data, "successCreatedProduct", "errorProduct");
    }

    /**
     * Ejecuta por ajax el metodo Productos::update
     * 
     * @returns {JSON}
     */
    updateProduct(){

        const csrfName = $('#csrf').attr('name');
        let   id       = $('#update-product').data('id-product');
        const data = {
        title: $('#title').val(),
        price: $('#price').val(),
        [csrfName]: $('#csrf').val()
        };

        this.ajaxCall('json', 'PUT', this.baseUrl+'/productos/'+id, data, "successUpdatedProduct", "errorProduct");
    }

    /**
     * Ejecuta por ajax el metodo Productos::delete
     * 
     * @returns {JSON}
     */
    deleteProduct(id){

        const csrfName = $('#csrf').attr('name');
        const data = {
        [csrfName]: $('#csrf').val()
        };

        this.ajaxCall('json', 'DELETE', this.baseUrl+'/productos/'+id, data, "successDeletedProduct", "errorProduct");
    }

    /**
     * Ejecuta por ajax el metodo Productos::search
     * 
     * @returns {JSON}
     */
    searchProduct(){

        const csrfName = $('#csrf').attr('name');
        const data = {
        search: $('#search').val(),
        [csrfName]: $('#csrf').val()
        };

        this.ajaxCall('json', 'POST', this.baseUrl+'/productos/search', data, "successSearchedProduct", "errorProduct");
    }

    /**
     * Ejecuta por ajax el metodo Productos::searchByPage
     * 
     * @returns {JSON}
     */
    searchProductByPage(page){

        const csrfName = $('#csrf').attr('name');
        const data = {
        page: page,
        [csrfName]: $('#csrf').val()
        };

        this.ajaxCall('json', 'POST', this.baseUrl+'/productos/page', data, "successSearchedProduct", "errorProduct");
    }

    /**
     * Grafica los registros encontrados en la tabla de productos
     * 
     * @param {JSON} res 
     */
    successSearchedProduct(res){

        $('#csrf').val(res.csrf_token);

        $('#dialog').html('').hide();
        $('#tbodyProducts').html('');

        let html = '';

        $(res.data).each(function (index, obj) {

            html += '<tr id="productRow'+obj.id+'">';
            html += '<td>'+obj.id+'</td>';
            html += '<td>'+obj.title+'</td>';
            html += '<td>'+obj.price+'</td>';
            html += '<td>';
            html += '<a href="'+productos.baseUrl+'/productos/'+obj.id+'/edit" class="btn btn-primary btn-sm me-2">Editar</a>';
            html += '<button type="button" class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal"'+
                    'data-bs-target="#deleteModal" data-id-product="'+obj.id+'">Eliminar</button>';
            html += '</td>';
            html += '</tr>';    
        });

        //si no se encontraron registros
        if(!res.data.length){
            html+= '<tr> <td class="text-center" colspan="4">'+res.messages[0]+'</td></tr>';
        }

        //grafica body de la tabla productos
        $('#tbodyProducts').html(html);

        //grafica paginacion
        this.graphPagination(res);          
    }

    /**
     * Grafica los elementos de la paginacion
     * 
     * @param {JSON} res 
     */
    graphPagination(res){

        $('.page-item').removeClass('active disabled');

        $('.page-link.link-number').each(function () {
            if($(this).attr('data-page') == res.pagination.pageNumber){
                $(this).parent().addClass('active');
            }
        });
    
        $('#btnPrev').children().attr('data-page',res.pagination.pageNumber - 1);
        $('#btnNext').children().attr('data-page',res.pagination.pageNumber + 1);
    
        res.pagination.pageNumber  <= 1                         ? $('#btnPrev').addClass('disabled'): $('#btnPrev').removeClass('disabled');
        res.pagination.pageNumber  >= res.pagination.totalPages ? $('#btnNext').addClass('disabled'): $('#btnNext').removeClass('disabled');
    
        if($('#search').val() != ''){

            $('.page-number-item').remove();
            let pagesLinks = '';

            for(var i = 1; i == res.pagination.totalPages; i++){
                pagesLinks += '<li class="page-item page-number-item '+(res.pagination.pageNumber == i ? 'active': '')+'">';
                pagesLinks += '<a class="page-link link-number" href="" data-page="'+i+'">'+i+'</a>';
                pagesLinks += '</li>';
            }
            $('#btnPrev').after(pagesLinks);
        }

        $('#totalRows').html(res.pagination.totalRows);
    }

    /**
     * Remueve el registro eliminado y muestra mensaje de exito
     * 
     * @param {JSON} res 
     */
    successDeletedProduct(res){

        $('#csrf').val(res.csrf_token);

        $('#dialog').html('');

        $('#dialog').append(this.getMessages(res.messages));
        $('#dialog').removeClass('alert-danger').addClass('alert-success').show();

        //elimina la fila, recuenta el total de registros y corrige la paginacion si es necesario
        $('#productRow'+res.data.id).remove();

        let totalRows = parseInt($('#totalRows').html()) - 1;
        $('#totalRows').html(totalRows);

        if($('#tbodyProducts').children().length < 1){

            $('#tbodyProducts').html('<tr><td class="text-center" colspan="4">No hay registros</td></tr>');

            let pageNumer = parseInt($('.page-item.active').children().attr('data-page')) - 1;

            setTimeout(function () {
                $('.page-item.active').remove();
                productos.searchProductByPage(pageNumer);
            }, 1000); 
        }

        setTimeout(function () {
            $('#dialog').hide();
        }, 2000); 

    }

    /**
     * Muestra mensaje de exito al crear producto
     * 
     * @param {JSON} res 
     */
    successCreatedProduct(res){

        $('#csrf').val(res.csrf_token);

        $('#dialog').html('');

        let element = this.getMessages(res.messages);

        $('#dialog').append(element);
        $('#dialog').removeClass('alert-danger').addClass('alert-success').show();

        setTimeout(function () {
            $('#title').val('');
            $('#price').val('');
            $('#dialog').removeClass('alert-success').addClass('alert-danger').hide();
        }, 2000); 

    }

    /**
     * Muestra mensaje de exito y redirecciona a la seccion de productos
     * 
     * @param {JSON} res 
     */
    successUpdatedProduct(res){

        $('#csrf').val(res.csrf_token);

        $('#dialog').html('');

        let element = this.getMessages(res.messages);

        $('#dialog').append(element);
        $('#dialog').removeClass('alert-danger').addClass('alert-success').show();

        setTimeout(function () {
            window.location.href = $('#btnBack').attr('href');
        }, 2000); 
    
    }

    /**
     * Grafica los mensajes y muestra los errores
     * 
     * @param {JSON} res 
     */
    errorProduct(res){

        $('#csrf').val(res.csrf_token);

        console.log(res);

        $('#dialog').html('');

        let element = this.getMessages(res.messages);

        //si existe una excepcion
        if(res.responseJSON){
        element = '<ul><li>'+res.responseJSON.message+'</li><ul>';
        }

        $('#dialog').append(element);
        $('#dialog').show();
    }
    /**
     * Metodos que se llaman en el constructor y se disparan al interactuar en el DOM
     */
    initFunctions(){

        $(document).on('change',':input[required]:visible', function() {
        
            if($(this).val().length > 0){
                $(this).removeClass('error-input');  
            }else{
                $(this).addClass('error-input');
            }
        });

        $(document).on('click', '#create-product', function() {

            $('#dialog').hide();

            let isRequired = productos.checkRequired();

            if(!isRequired){
                productos.createProduct();
            }
        });

        $(document).on('click', '#update-product', function() {

            $('#dialog').hide();

            let isRequired = productos.checkRequired();

            if(!isRequired){
                productos.updateProduct();
            }
        });

        $(document).on('click', '.btn-delete', function() {

            let id = $(this).data('id-product');
            $('#productNumber').data('id-product', id).html(id);
        });

        $(document).on('click', '#delete-product', function() {

            productos.deleteProduct($('#productNumber').data('id-product'));
            $('#deleteModal').modal('toggle');
        });

        $(document).on('click', '#btn-search', function() {

            if($('#search').val() != ''){
                $('#search').removeClass('error-input');
                productos.searchProduct();
            }else{
                $('#search').addClass('error-input');
            }
        });

        $(document).on('click', '.page-link', function(e) {
            e.preventDefault();
            productos.searchProductByPage($(this).data('page'));
        });
    
    }

}

productos = new Productos();