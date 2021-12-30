"use strict";	

var mainMerkas = function() {

		var inicioSesionInternet = function() {
			var url	= "http://localhost:3001/public/auth/login";
			var dataJson = {
				"email" : "prueba1@gmail.com",
					"password":"prueba"
			}
			$.ajax({
				type: "POST",
				url: url,
				dataType: "json",
				data: dataJson,
				contentType: "application/json; charset=utf-8",
				success:function(data){
					console.log(data);
				}
			});
		}

	return {
		init: function() {
				inicioSesionInternet();
		}
	}
}();


jQuery(document).ready(function() {
    mainMerkas.init();
});
