class Users extends Base{

    constructor() {
      super('users');
      this.initFunctions();
    }
    
    /**
     * Ejecuta por ajax el metodo Users::login
     * 
     * @returns {JSON}
     */
    login(){

      const csrfName = $('#csrf').attr('name');
      const data = {
        user: $('#user').val(),
        pass: $('#pass').val(),
        [csrfName]: $('#csrf').val()
      };

      this.ajaxCall('json', 'POST', this.baseUrl+'/auth/users/login', data, "successLogin", "errorLogin");
    }
    
    /**
     * Redirecciona a la seccion de productos
     * 
     * @param {JSON} res 
     */
    successLogin(res){
  
      $('#csrf').val(res.csrf_token);
  
      $('#dialog').html('');
  
      let element = this.getMessages(res.messages);
  
      $('#dialog').append(element);
      $('#dialog').removeClass('alert-danger').addClass('alert-success').show();
  
      setTimeout(function () {
        window.location.href = $('#btn-login').data('url');
    }, 1000); 
  
    }
    
    /**
     * Grafica los mensajes y muestra los errores
     * 
     * @param {JSON} res 
     */
    errorLogin(res){
  
      $('#csrf').val(res.csrf_token);
  
      console.log(res);
  
      $('#dialog').html('');
  
      let element = this.getMessages(res.messages);
  
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
  
      $(document).on('click', '#btn-login', function() {
  
        $('#dialog').hide();
  
        let isRequired = users.checkRequired();
  
        if(!isRequired){
            users.login();
        }
      });
     
    }
  
  }
  
  users = new Users();