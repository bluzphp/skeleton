/**
 * Grid Spy Handler ^_^
 *
 * @author   Anton Shevchuk
 * @created  11.09.12 10:30
 */
import '../vendor/jquery/jquery.js';

$(function () {
    $('[data-spy=grid]').each(function (i, el) {
        let $grid = $(el);
        if (!$grid.data('url')) {
            $grid.data('url', window.location.pathname);
        }

        function loadGrid(url) {
            $grid.data('url', url);
            window.history.pushState('', '', url);
            $.ajax({
                url: url,
                type: 'get',
                dataType: 'html',
                beforeSend: disableControls,
                success: refreshGrid
            });
        }

        function disableControls() {
            $grid.find('a, .btn').addClass('disabled');
        }

        function refreshGrid(html) {
            /*
             need to replace only content of grid
             current     loaded
             <div>       <div>
             [...]   <--   [...]
             </div>      </div>
             */
            $grid.html($(html).children().unwrap());
        }

        function reloadGrid() {
            let url = $grid.data('url');
            loadGrid(url);
        }

        // pagination, sorting and filtering over AJAX
        $grid.on('click.bluz.ajax', '.pagination li a, thead a, .navbar a[data-ajax]', function () {
            let $link = $(this);
            $link.addClass('active');
            let href = $link.attr('href');
            if (href === '#') {
                return false;
            }
            loadGrid(href);
            return false;
        });
        // refresh grid if form was success sent
        $grid.on('success.bluz.dialog', 'a[data-ajax-dialog]', reloadGrid);
        // refresh grid if confirmed ajax button (e.g. delete record)
        $grid.on('success.bluz.ajax', 'a[data-confirm]', reloadGrid);

        // apply filter form
        $grid.on('submit.bluz.ajax', 'form.filter-form', function () {
            let $form = $(this);
            let $searchInput = $form.find('.grid-filter-search-input');

            // magic like
            if ($form.find('[type=search]').length) {
                // erase old filter and create new
                $searchInput.val(
                    $searchInput.val().replace(/-.*/g, '') + '-' + $form.find('[type=search]').val()
                );
            }

            $form.addClass('disabled');

            $.ajax({
                url: $form.attr('action'),
                type: 'get',
                data: $form.serializeArray(),
                dataType: 'html',
                beforeSend: disableControls,
                success: refreshGrid
            });
            return false;
        });
        // magic control for like plugin
        $grid.on('click.bluz.grid', '.grid-filter-search a', function (e) {
            let $a = $(this);
            $grid.find('.grid-filter-search .dropdown-toggle').html($a.text() + ' <span class="caret"></span>');
            $grid.find('.grid-filter-search-input')
                .attr('name', $a.data('filter'))
                .val($a.data('filter-type') + '-');

            e.preventDefault();
        });
    });
});
