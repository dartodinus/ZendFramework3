// JavaScript Document
var l = window.location;


var base_url = l.protocol + "//" + l.host + "/" + l.pathname.split('/')[1] + "/";
var base_url_reload = l.protocol + "//" + l.host + "/" + l.pathname.split('/')[1] + "/" + l.pathname.split('/')[2];


/**
var base_url = l.protocol + "//" + l.host + "/" + l.pathname.split('/')[0];
var base_url_reload = l.protocol + "//" + l.host + "/" + l.pathname.split('/')[0] + "/" + l.pathname.split('/')[1];
var param_req	= window.location.href.split('?')[0];
*/
