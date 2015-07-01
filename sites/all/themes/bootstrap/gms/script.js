// We define a function that takes one parameter named $.
(function ($) {
	
	Drupal.behaviors.display = {
		attach: function (context, settings) {
			//console.log(Drupal.settings.pathToTheme.pathToTheme);
			$('table').addClass('table table-condensed');

			// navigation
			$('header#navbar').removeClass('container');
			$('header#navbar').addClass('container-fluid');
			$('#block-search-form').removeClass('clearfix');

			$('#block-user-login a.popup-element-title').addClass('btn disabled');
			//$('#menu-text-portfolio a').addClass('disabled');

			// dashboard icon
			//$('a.menu_icon.menu-dashboard.menu-915').html('&nbsp;');
			//$('a.menu_icon.menu-dashboard.menu-915').attr("data-toggle","tooltip");
			//$('a.menu_icon.menu-dashboard.menu-915').attr("data-placement","auto");
			//$('a.menu_icon.menu-dashboard.menu-915').prop("title", "Dashboard");
			
			// login icon
			//$('#menu-link-login').addClass('btn disabled');
			//$('#block-user-login div a.btn').html('&nbsp;');
			//$('#block-user-login div a.btn').attr("data-toggle","tooltip");
			//$('#block-user-login div a.btn').attr("data-placement","auto");
			//$('#block-user-login div a.btn').prop("title","Login");
			
			// add project
			//$('#menu-link-add-project').addClass('btn disabled');

			// print
			$('body.page-maps-projects #menu-link-print').addClass('btn disabled');
			$('body.page-node-edit #menu-link-print').addClass('btn disabled');
			//$('body.page-dashboard #menu-link-print').addClass('btn disabled');
			//$('body.page-search-node #menu-link-print').addClass('btn disabled');
			//$('#block-menu-menu-add-project ul li a').html('&nbsp');
			//$('#block-menu-menu-add-project ul li a').addClass('btn disabled');
			//$('#block-menu-menu-add-project ul li a').attr("data-toggle", "tooltip");
			//$('#block-menu-menu-add-project ul li a').attr("data-placement","auto");
			//$('#block-menu-menu-add-project ul li a').prop("title","Add Project");

			$('body.page-about, body.page-contact, body.page-disclaimer, body.page-user').ready(function(){
				$('html').css('height','100%');
				//$('div.main-container').css('height','100%');
				// set to full page size on first render
				headerHeight = $('header#navbar').height();
				pageHeaderHeight = $('h2.page-header').outerHeight();
				//console.log('pageheaderheight: ' + pageHeaderHeight);
				footerHeight = $('footer.footer').height();
				totalHeight = window.innerHeight-headerHeight-pageHeaderHeight-footerHeight;
				//console.log('totalheight: ' + totalHeight);
				$(document).ready(function (){
					//console.log('window ready');
					$('body.page-user .main-container .region-content').css('height',totalHeight);
					$('body.page-about .main-container .region-content').css('height',totalHeight);
					$('body.page-disclaimer .main-container .region-content').css('height',totalHeight);
					$('body.page-contact .main-container .region-content').css('height',totalHeight);
					$('body.page-contact .main-container .region-content').css('height',totalHeight);
				});
				
				$(window).bind('resize',function (){
					headerHeight = $('header#navbar').height();
					pageHeaderHeight = $('h2.page-header').outerHeight();
					//console.log('pageheaderheight: ' + pageHeaderHeight);
					footerHeight = $('footer.footer').height();
					totalHeight = window.innerHeight-headerHeight-pageHeaderHeight-footerHeight;
					$('body.page-about .main-container .region-content').css('height',totalHeight);
					$('body.page-disclaimer .main-container .region-content').css('height',totalHeight);
					$('body.page-contact .main-container .region-content').css('height',totalHeight);
				});
			});
			
			// front page
			// main map
			// set height to full page
			$('body.page-maps-projects').ready(function(){
				$('html:has(body.page-maps-projects)').css('height','100%');
				
				// set to full page size on first render
				headerHeight = $('header#navbar').height();
				footerHeight = $('footer.footer').height();
				$(document).ready(function (){
					//console.log('window ready');
					//$('#geolocation-views-project-locations-maps-projects').css('height',window.innerHeight-headerHeight-footerHeight);
					$('#ip-geoloc-map-of-view-project_locations-maps_projects').css('height',window.innerHeight-headerHeight-footerHeight);
					$('#ip-geoloc-map-of-view-project_locations-maps_no_results').css('height',window.innerHeight-headerHeight-footerHeight);
				});
				
				// auto-resize on window resize
				$(window).bind('resize', function(){
					//console.log('window resized');
					headerHeight = $('header#navbar').height();
					footerHeight = $('footer.footer').height();
					//$('#geolocation-views-project-locations-maps-projects').css('width',window.innerWidth);
					//$('#geolocation-views-project-locations-maps-projects').css('height',window.innerHeight-headerHeight-footerHeight);
					$('#ip-geoloc-map-of-view-project_locations-maps_projects').css('width',window.innerWidth);
					$('#ip-geoloc-map-of-view-project_locations-maps_projects').css('height',window.innerHeight-headerHeight-footerHeight);
					$('#ip-geoloc-map-of-view-project_locations-maps_no_results').css('width',window.innerWidth);
					$('#ip-geoloc-map-of-view-project_locations-maps_no_results').css('height',window.innerHeight-headerHeight-footerHeight);
				});
				
				$('#edit-c').attr('size',2);
				$('#edit-t').attr('size',2);
				$('#edit-st').attr('size',2);
				$('#edit-se').attr('size',2);
				$('#edit-submit-project-locations').removeClass('btn-default');
				$('#edit-submit-project-locations').addClass('btn-primary');
				$('#edit-reset').removeClass('btn-default');
				$('#edit-reset').addClass('btn-primary');
			});
			
			// search page
			// set height to full page
			//$('body.page-search').css('height','100%');
			$('body.page-search').ready(function (){
				$('html:has(body.page-search)').css('height','100%');

				$(document).ready(function (){
					// set to full page size on first render
					headerHeight = $('header#navbar').height();
					titleHeight = $('h2.page-header').height();
					footerHeight = $('footer.footer').height();
					searchTotalsHeight = $('#search-page-totals').height();
					containerHeight = (window.innerHeight-headerHeight-titleHeight-footerHeight-searchTotalsHeight-21);
					$('body.page-search #search-page-results-container').css('height',containerHeight);
				});

				// auto-resize on window resize
				$(window).bind('resize', function(){
					//console.log($('html').height());
					//console.log($('body').height());
					//console.log('window resized');
					headerHeight = $('header#navbar').height();
					titleHeight = $('h2.page-header').height();
					searchTotalsHeight = $('#search-page-totals').height();
					footerHeight = $('footer.footer').height();
					containerHeight = (window.innerHeight-headerHeight-titleHeight-footerHeight-searchTotalsHeight-21);
					$('body.page-search #search-page-results-container').css('width',window.innerWidth-23);
					$('body.page-search #search-page-results-container').css('height',containerHeight);
				});
			});
			
			// dashboard
			$('body.page-dashboard').ready(function(){
				$('html:has(body.page-dashboard)').css('height','100%');
				// set to full page size on first render
				$(document).ready(function (){
					headerHeight = $('header#navbar').height();
					footerHeight = $('footer.footer').height();
					titleHeight = $('h2.page-header').height();
					containerHeight = (window.innerHeight-headerHeight-titleHeight-footerHeight-21);
					//console.log('window ready');
					$('body.page-dashboard #dashboard-page').css('width',window.innerWidth);
					$('body.page-dashboard #dashboard-page').css('height',containerHeight);
				});

				// auto-resize on window resize
				$(window).bind('resize', function(){
					//console.log('window resized');
					headerHeight = $('header#navbar').height();
					footerHeight = $('footer.footer').height();
					titleHeight = $('h2.page-header').height();
					containerHeight = (window.innerHeight-headerHeight-titleHeight-footerHeight-21);
					$('body.page-dashboard #dashboard-page').css('width',window.innerWidth);
					$('body.page-dashboard #dashboard-page').css('height',containerHeight);
				});
			
				// charts
				$('#mini-panel-dashboard_charts div.panel-panel').removeClass('col-lg-4');
				$('#mini-panel-dashboard_charts div.panel-panel').addClass('col-sm-4 col-md-4');
				
				//$('#projects-per-sector-block-1 > div > div:nth-child(1) > div > svg > g:nth-child(3) > path:nth-child(7)').attr('fill','#cccccc');
				//$('#projects-per-sector-block-1 > div:nth-child(1) > div:nth-child(1) > div:nth-child(1) > svg:nth-child(1) > g:nth-child(3) > path:nth-child(7)').attr('fill','#cccccc');
				
				//var ctrlnext = $('#projects-per-sector-block-1 > div > div:nth-child(1) > div > svg');
				// photo carousel
				$('#views-bootstrap-carousel-1 a.carousel-control span').removeClass('icon-next');
				$('#views-bootstrap-carousel-1 a.carousel-control span').removeClass('icon-prev');
				$('#views-bootstrap-carousel-1 a.carousel-control.right span').attr('id','carousel-control-right');
				$('#views-bootstrap-carousel-1 a.carousel-control.left span').attr('id','carousel-control-left');
				//$('#views-bootstrap-carousel-1 a.carousel-control.right span').html('&gt;');
				//$('#views-bootstrap-carousel-1 a.carousel-control.left span').html('&lt;');
			});

			// project page
			$('body.node-type-project').ready(function(){
				$('html:has(body.node-type-project)').css('height','100%');
				// set to full page size on first render
				$(document).ready(function (){
					//console.log('window ready');
					headerHeight = $('header#navbar').height();
					titleHeight = $('#project-title-container').height();
					footerHeight = $('footer.footer').height();
					if (window.innerWidth > 991) {
						offset = 5;
					} else {
						offset = 0;
					}
					containerHeight = (window.innerHeight-headerHeight-titleHeight-footerHeight+offset);
					$('body.node-type-project #project-info-container').css('height',containerHeight);
				});

				// auto-resize on window resize
				$(window).bind('resize', function(){
					//console.log('window resized');
					headerHeight = $('header#navbar').height();
					titleHeight = $('#project-title-container').height();
					footerHeight = $('footer.footer').height();
					if (window.innerWidth > 991) {
						offset = 5;
					} else {
						offset = 0;
					}
					//console.log('offset: '+offset);
					containerHeight = (window.innerHeight-headerHeight-titleHeight-footerHeight+offset);
					$('body.node-type-project #project-info-container').css('width',window.innerWidth);
					$('body.node-type-project #project-info-container').css('height',containerHeight);
				});
			
				// project details
				$('div.view-project-details table th').addClass('col-sm-5');
				$('div.view-project-details table th').css('white-space','nowrap');
				$('div.view-project-details table th').css('min-width','130px');
				$('div.view-project-details table td').addClass('col-sm-7');

				// photo carousel
				$('#views-bootstrap-carousel-1 a.carousel-control span').removeClass('icon-next');
				$('#views-bootstrap-carousel-1 a.carousel-control span').removeClass('icon-prev');
				$('#views-bootstrap-carousel-1 a.carousel-control.right span').attr('id','carousel-control-right');
				$('#views-bootstrap-carousel-1 a.carousel-control.left span').attr('id','carousel-control-left');
				//$('body.node-type-project #views-bootstrap-carousel-1 a.carousel-control.right span').html('&gt;');
				//$('body.node-type-project #views-bootstrap-carousel-1 a.carousel-control.left span').html('&lt;');
			});
			// footer
			$('#block-menu-menu-footer-menu').removeClass('clearfix');
			$('#block-menu-menu-footer-menu ul').addClass('navbar-nav');
			

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