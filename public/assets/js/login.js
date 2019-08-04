/**
 *  Document   : login.js
 *  Author     : redstar
 *  Description: login form script
 *
 **/

// Toggle Function
$(document).on('click','.toggle',function(){ 
	'use strict';
  // Switches the Icon and form
	
  if($(this).children('i').attr('class')=='fa fa-user-circle')
  {
	  $('.toggle').hide();
	  $('.formLogin').slideUp("slow");
  }

  else
  {
	  $('.toggle').hide();
	  $('.formLogin').slideDown("slow");
	  $('.formReset').slideUp("slow");
		 
  }
});


$(document).on('click','.forgetPassword a',function(){ 
	'use strict';
	
 	$('.toggle').show();
	// Switches the Icon and form
	$('.toggle').children('i').removeClass('fa-user-circle');
	$('.toggle').children('i').addClass('fa-times');
	$('.formLogin').slideUp("slow");
	$('.formReset').slideDown("slow");
});