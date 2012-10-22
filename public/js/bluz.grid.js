/**
 * Grid Spy Handler ^_^
 *
 * @author   Anton Shevchuk
 * @created  11.09.12 10:30
 */
define(['jquery', 'bluz'], function ($, bluz) {
	$(function(){
		$('[data-spy="grid"]').each(function(i, el){
			var $grid = $(el);
			var uid = $grid.data('grid');

			// TODO: work with hash from URI

			$grid.on('click.bluz.ajax', '.pagination a, thead a', function(){
				var $link = $(this);
				var href = $link.attr('href');

				if (href == '#') return false;

				$.ajax({
					url: href,
					type: 'get',
					dataType:'html',
					beforeSend:function() {
						$link.addClass('active');
						$grid.find('a, .btn').addClass('disabled');
					},
					success:function(html) {
						/*
						need to replace only content of grid
						current         loaded
						<div>           <div>
					      [...]   <--     [...]
						</div>          </div>
						 */
						$grid.html($(html).children().unwrap());
					},
					complete:function() {
//						$grid.find('a, .btn').removeClass('disabled');
					}
				});
				return false;
			});
		});
 	});
	return {};
});