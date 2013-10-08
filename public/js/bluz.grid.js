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
            $grid.on('ajax.bluz.complete', 'a.dialog', function() {
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
            $grid.on('ajax.bluz.success', 'a.ajax.confirm', function() {
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
                var $form = $(this);

                // magic like
                if ($form.find('[name=search-like]').length) {
                    $form.find('.grid-filter-search-input').val('like-' + $form.find('[name=search-like]').val());
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
                $grid.find('.grid-filter-search-input').attr('name', $a.data('filter'));

                e.preventDefault();
            });
		});

	});
	return {};
});