class Base{
    constructor(controller = ""){
        this.getUrl  = window.location;
        this.baseUrl = this.getUrl .protocol + "//" + this.getUrl.host + "/" + this.getUrl.pathname.split('/')[1];
        this.controller = controller;
    }
    
    /**
     * Llamada Ajax
     *
     * @param dataType          tipo de respuesta
     * @param type              tipo de metodo GET O POST
     * @param url               URL de destino
     * @param data              Objeto con los datos a enviar al servidor
     * @param callback_success  Funcion que ejecuta cuando la respuesta es exitosa
     * @param callback_error    funcion que ejecuta cuando la respuesta tiene errores
     */
    ajaxCall(dataType = 'text', type = 'GET', url = null, data = {}, callback_success = null, callback_error = null){
        $.ajax({
           url: url,
           type: type,
           data: data,
           dataType: dataType,
           success: (_json) =>{
               //console.log(_json);
               if (_json.error){
                   this[callback_error](_json);
               } else {
                   this[callback_success](_json);
               }
           },            
           error: (_json) =>{
                   this[callback_error](_json);
            }
       });
    }

    /**
     * Chequea que los inputs requeridos
     * 
     * @returns {Boolean}           false si se ingresaron datos sino true
     */
    checkRequired(){

        if ($(document).find('input:required').removeClass('error-input').filter(function(){
            let check = this.value === '';
            
            if(check){  
                $(this).addClass('error-input');
            }
            
            return check }).length > 0) {
                return true;
            }
    
        return false;
    }

    /**
     * Devuelve un listado en html
     * 
     * @param {array} messages 
     * @returns 
     */
    getMessages(messages){
  
        let html = '<ul>';
    
        $.each( messages, function( index, message ) {
          html     += '<li>'+message+'</li>';
        });
    
        html     += '</ul>';
    
        return html;
    }

}
