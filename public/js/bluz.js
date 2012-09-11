/**
 * Bluz super global
 * @author   Anton Shevchuk
 * @created  11.09.12 10:02
 */
define(['jquery'], function($) {

	$(function(){
		// TODO: require other modules if needed
	});

	return {
		log:function(error, text){
			if (console != undefined) {
				console.error(error, "Response Text:", text);
			}
		}
	}
});