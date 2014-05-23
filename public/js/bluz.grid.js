/**
 * Grid Spy Handler ^_^
 *
 * @author   Anton Shevchuk
 * @created  11.09.12 10:30
 */
/*global define,require*/
define(['jquery', 'bluz'], function ($, bluz) {
	"use strict";
	$(function () {
		$('[data-spy="grid"]').each(function (i, el) {
			var $grid = $(el);
			// TODO: work with hash from URI
            if (!$grid.data('url')) {
                $grid.data('url', window.location.pathname);
            }

            // pagination, sorting and filtering over AJAX
			$grid.on('click.bluz.ajax', '.pagination li a, thead a, .navbar a.ajax', function () {
				var $link = $(this),
					href = $link.attr('href');

				if (href === '#') {
					return false;
				}

				$.ajax({
					url: href,
					type: 'get',
					dataType: 'html',
					beforeSend: function () {
						$link.addClass('active');
						$grid.find('a, .btn').addClass('disabled');
					},
					success: function (html) {
						/*
						need to replace only content of grid
						current         loaded
						<div>           <div>
						[...]   <--     [...]
						</div>          </div>
						 */
                        $grid.data('url', href);
						$grid.html($(html).children().unwrap());
					}
				});
				return false;
			});
            // refresh grid if form was success sent
            $grid.on('complete.ajax.bluz', 'a.dialog', function() {
                $.ajax({
                    url: $grid.data('url'),
                    type: 'get',
                    dataType: 'html',
                    beforeSend: function () {
                        $grid.find('a, .btn').addClass('disabled');
                    },
                    success: function (html) {
                        $grid.html($(html).children().unwrap());
                    }
                });
            });
            // refresh grid if confirmed ajax button (e.g. delete record)
            $grid.on('success.ajax.bluz', 'a.ajax.confirm', function() {
                $.ajax({
                    url: $grid.data('url'),
                    type: 'get',
                    dataType: 'html',
                    beforeSend: function () {
                        $grid.find('a, .btn').addClass('disabled');
                    },
                    success: function (html) {
                        $grid.html($(html).children().unwrap());
                    }
                });
            });

            // apply filter form
            $grid.on('submit.bluz.ajax', 'form.filter-form', function () {
                var $form = $(this),
                    $searchInput = $form.find('.grid-filter-search-input');

                // magic like
                if ($form.find('[type=search]').length) {
                    // erase old filter and create new
                    $searchInput.val(
                        $searchInput.val().replace(/-\w*/g, '') + '-' + $form.find('[type=search]').val()
                    );
                }

                $.ajax({
                    url: $form.attr('action'),
                    type: 'get',
                    data: $form.serializeArray(),
                    dataType: 'html',
                    beforeSend: function () {
                        $form.addClass('disabled');
                        $grid.find('a, .btn').addClass('disabled');
                    },
                    success: function (html) {
                        $grid.html($(html).children().unwrap());
                    }
                });
                return false;
            });
            // magic control for like plugin
            $grid.on('click.bluz.grid', '.grid-filter-search a', function(e){
                var $a = $(this);
                $grid.find('.grid-filter-search .dropdown-toggle').html($a.text() + ' <span class="caret"></span>');
                $grid.find('.grid-filter-search-input').attr('name', $a.data('filter'))
                    .val($a.data('filter-type') + '-');

                e.preventDefault();
            });
		});

	});
	return {};
});