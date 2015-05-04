// We define a function that takes one parameter named $.
(function ($) {
	
	Drupal.behaviors.display = {
		attach: function (context, settings) {
			$('header#navbar').removeClass('container');
			$('header#navbar').addClass('container-fluid');
			$('#block-search-form').removeClass('clearfix');

			$('table').addClass('table table-condensed');
			$('div.view-project-details table th').addClass('col-sm-5');
			$('div.view-project-details table td').addClass('col-sm-7');

			//$('#project-details th.views-field.views-field-field-project-adb-website').css('display','none');
			//$('#project-details td.views-field.views-field-field-project-adb-website').attr('colspan','2');

			$('#block-menu-menu-footer-menu').removeClass('clearfix');
			$('#block-menu-menu-footer-menu ul').addClass('navbar-nav');
			
			//$(window).on("throttledresize", function (event){
			//window.addEventListener('resize',function(){
			//	console.log('window resized');
			//});
		}
	};
	
	Drupal.behaviors.projectEdit = {
		attach: function (context, settings) {
			/*$('a#btn-edit-proj-details').click(function() {
				console.log('edit proj details button clicked');
				//$('div#form-project-details').show();
				//$('div#edit-project-details').show();
			});
			
			$('a#btn-edit-proj-funding').click(function() {
				console.log('edit proj funding button clicked');
				$("a[href='#funding']").parent().addClass('selected');
				//$('div#form-project-funding').show();
			});
			
			$('a#btn-edit-proj-photos').click(function() {
				console.log('edit proj photos button clicked');
				$("a[href='#photos']").parent().addClass('selected');
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
	};

	
// Here we immediately call the function with jQuery as the parameter.
}(jQuery));