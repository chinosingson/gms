// We define a function that takes one parameter named $.
(function ($) {
	
	
	Drupal.behaviors.projectEdit = {
		attach: function (context, settings) {
			$('table').addClass('table table-condensed');
		
		
			$('button#btn-edit-proj-details').click(function() {
				console.log('edit proj details button clicked');
				//$('div#form-project-details').show();
			});
			
			/*$('button#btn-edit-proj-funding').click(function() {
				console.log('edit proj funding button clicked');
				//$('div#form-project-funding').show();
			});
			
			$('button#btn-edit-proj-photos').click(function() {
				console.log('edit proj photos button clicked');
				//$('div#form-project-photos').show();
			});
			
			$('button#btn-edit-proj-outputs').click(function() {
				console.log('edit proj outputs button clicked');
				//$('div#form-project-outputs').show();
			});
			
			$('button#btn-edit-proj-impacts').click(function() {
				console.log('edit proj impacts button clicked');
				//$('div#form-project-impacts').show();
			}); */
			
			
		}
	}
	

	
// Here we immediately call the function with jQuery as the parameter.
}(jQuery));