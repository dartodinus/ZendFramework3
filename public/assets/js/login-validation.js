var LoginValidation = function () {

    // validation using icons
    var handleValidation = function() {
        // for more info visit the official plugin documentation: 
		// http://docs.jquery.com/Plugins/Validation

		var form = $('#login_validation');
		var error2 = $('.alert-danger', form);
		var success2 = $('.alert-success', form);

		form.validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block help-block-error', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			ignore: "",  // validate all fields including form hidden input
			rules: {
				username: {
					minlength: 2,
					required: true
				},
				password: {
					minlength: 2,
					required: true
				},
			},

			invalidHandler: function (event, validator) { //display error alert on form submit              
				success2.hide();
				error2.show();
				App.scrollTo(error2, -200);
			},

			errorPlacement: function (error, element) { // render error placement for each input type
				var icon = $(element).parent('.input-icon').children('i');
				icon.removeClass('fa-check').addClass("fa-warning");  
				icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
			},

			highlight: function (element) { // hightlight error inputs
				$(element)
					.closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
			},

			unhighlight: function (element) { // revert the change done by hightlight

			},

			success: function (label, element) {
				var icon = $(element).parent('.input-icon').children('i');
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
				icon.removeClass("fa-warning").addClass("fa-check");
			},

			submitHandler: function (form) {
				var dataString = $(form).serialize();
				
				$.ajax({
					type: "POST",
					url: base_url +'auth',
					data: dataString,
					dataType: "json",
					success: function(data) {
		
						if(data.is_valid == 'true'){
							var url = base_url + data.message_info;
							$(location).attr('href',url);
						}else{
							sweetAlert("Authentication failed", data.message_info, "error");
							$(form).find(':input').each(function() {
								switch(this.type) {
									case 'password':
									case 'select-multiple':
									case 'select-one':
									case 'text':
									case 'textarea':
										$(this).val('');
										break;
									case 'checkbox':
									case 'radio':
										this.checked = false;
								}
							});
						}
					},
					error: function(){
					  sweetAlert("Authentication failed", 'Failed', "error");
					}
				}); 
				
			}
		});
    }

    return {
        //main function to initiate the module
        init: function () {

            handleValidation();

        }

    };

}();

jQuery(document).ready(function() {
	'use strict';
    LoginValidation.init();
});