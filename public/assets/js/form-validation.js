/**
 *  Document   : form-validation.js
 *  Author     : redstar
 *  Description: Script related to validate the form
 *
 **/
var FormValidation = function () {
	
	var handleSubmitForm = function() 
	{
		var SubmitForm = $('.SubmitForm');
		var error1 = $('.alert-danger', SubmitForm);
		var success1 = $('.alert-success', SubmitForm);
		
		SubmitForm.validate({
			errorElement: 'span',
			errorClass: 'help-block help-block-error',
			focusInvalid: false,
			ignore: "",
			messages: {
				select_multi: {
					maxlength: jQuery.validator.format("Max {0} items allowed for selection"),
					minlength: jQuery.validator.format("At least {0} items must be selected")
				}
			},
			rules: {
				name: {
					minlength: 2,
					required: true
				},
				email: {
					required: true,
					email: true
				},
				url: {
					required: true,
					url: true
				},
				number: {
					required: true,
					number: true
				},
				digits: {
					required: true,
					digits: true
				},
				creditcard: {
					required: true,
					creditcard: true
				},
				occupation: {
					minlength: 5,
				},
				select: {
					required: true
				},
				select_multi: {
					required: true,
					minlength: 1,
					maxlength: 3
				},
				membership: {
					required: true
				},
				service: {
					required: true,
					minlength: 2
				},
				editor1: {
					required: true
				},
				editor2: {
					required: true
				}
			},
			
			messages: { // custom messages for radio buttons and checkboxes
				membership: {
					required: "Please select a Membership type"
				},
				service: {
					required: "Please select  at least 2 types of Service",
					minlength: jQuery.validator.format("Please select  at least {0} types of Service")
				}
			},
			errorElement: "em",
			errorPlacement: function (error, element) { // render error placement for each input type
				error.addClass( "help-block" );
				if ( element.prop( "type" ) === "checkbox" ) {
					error.insertAfter( element.parent( "label" ) );
				} else {
					error.insertAfter( element );
				}
			
			},
			
			invalidHandler: function (event, validator) { //display error alert on form submit              
				success1.hide();
				error1.show();
				App.scrollTo(error1, -200);
			},

			highlight: function (element) { // hightlight error inputs
				$(element)
					.closest('.form-group').addClass('has-error'); // set error class to the control group
			},
	
			unhighlight: function (element) { // revert the change done by hightlight
				$(element)
					.closest('.form-group').removeClass('has-error'); // set error class to the control group
			},
	
			success: function (label) {
				label.closest('.form-group').removeClass('has-error'); // set success class to the control group
			},

			submitHandler: function (form) {
				success1.show();
				error1.hide();
				form[0].submit(); // submit the form
			}
		});
		
    }
	
    // basic validation
    var handleValidation1 = function() {
        // for more info visit the official plugin documentation: 
            // http://docs.jquery.com/Plugins/Validation

            var form1 = $('#form_sample_1');

            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                messages: {
                    select_multi: {
                        maxlength: jQuery.validator.format("Max {0} items allowed for selection"),
                        minlength: jQuery.validator.format("At least {0} items must be selected")
                    }
                },
                rules: {
                    name: {
                        minlength: 2,
                        required: true
                    },
                    
                    email: {
                        required: true,
                        email: true
                    },
                    url: {
                        required: true,
                        url: true
                    },
                    number: {
                        required: true,
                        number: true
                    },
                    digits: {
                        required: true,
                        digits: true
                    },
                    creditcard: {
                        required: true,
                        creditcard: true
                    },
                    occupation: {
                        minlength: 5,
                    },
                    select: {
                        required: true
                    },
                    
                },

                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success1.hide();
                    error1.show();
                    App.scrollTo(error1, -200);
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element).parent('.input-group');
                    if (cont) {
                        cont.after(error);
                    } else {
                        element.after(error);
                    }
                },

                highlight: function (element) { // hightlight error inputs
                	$(element).closest('.form-group').removeClass('has-success').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set error class to the control group
                },

                success: function (label) {
                    label.closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                },

                submitHandler: function (form) {
                    success1.show();
                    error1.hide();
                }
            });


    }

    // validation using icons
    var handleValidation2 = function() {
        // for more info visit the official plugin documentation: 
            // http://docs.jquery.com/Plugins/Validation

            var form2 = $('#form_sample_2');
            var error2 = $('.alert-danger', form2);
            var success2 = $('.alert-success', form2);

            form2.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    name: {
                        minlength: 2,
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    url: {
                        required: true,
                        url: true
                    },
                    number: {
                        required: true,
                        number: true
                    },
                    creditcard: {
                        required: true,
                        creditcard: true
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
                    success2.show();
                    error2.hide();
                    form[0].submit(); // submit the form
                }
            });


    }

    

    return {
        //main function to initiate the module
        init: function () {
			handleSubmitForm();
            handleValidation1();
            handleValidation2();

        }

    };

}();

jQuery(document).ready(function() {
	'use strict';
    FormValidation.init();
});