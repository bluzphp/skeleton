/**
 * @author   Viacheslav Nogin
 * @created  11.09.12 10:30
 */
/*global define,require*/
define(['jquery', 'bluz', 'bluz.notify', 'bluz.tools', 'bluz.ajax', 'vendor/jquery.mjs.nestedSortable'], function ($, bluz, notify, tools) {
    "use strict";

    $(function () {
        function sortableInit() {
            $('.sortable').nestedSortable({
                handle: 'div',
                items: 'li',
                toleranceElement: '> div'
            });
        }

        sortableInit();

        $('.category-page-wrapper').on('change', '.select-tree select', function () {

            var $this= $(this);
            $.ajax({
                url: '/categories/tree',
                data: {
                    id: $this.val()
                },
                success: function (resp) {
                    $('.tree-wrapper').remove();
                    $('.tree-container').html($(resp).find('.tree-wrapper'));

                    sortableInit();
                }
            });

        }).on('click', '#save', function (e) {

            $('.sortable li').each(function (key, value) {
                $(this).attr('data-order', key);
            });

            var arraied = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});

            $.ajax({
                url: 'categories/order',
                type: 'post',
                data: {
                    tree: JSON.stringify(arraied),
                    treeParent: $('.tree-header').attr('data-parent-id')
                },
                success: function (responce) {
                    notify.addSuccess('Tree has been saved');
                }
            });
        });

        $('.tree-container').on('success.ajax.bluz', '.remove', function () {
            $(this).parent().parent().remove();
        }).on('success.ajax.bluz', '.remove-root', function () {
            var $list = $('.root-category-list');
                $list.find('[value=' + $list.val() + ']').remove();

            if ($list.children().length === 0) {
                $('.message-wrapper').children().remove();
                $('.message-wrapper').append('<h3 class="not-one-category">There are no categories</h3>');
            }

            $list.trigger('change');
        });

        $('body').on('success.form.bluz', '.category-edit', function () {
            $.ajax({
                url: '/categories/tree',
                data: {
                    id: $('.select-tree select').val()
                },
                success: function (resp) {
                    $('.tree-wrapper').remove();
                    $('.tree-container').html($(resp).find('.tree-wrapper'));

                    sortableInit();
                }
            });
        }).on('success.form.bluz', '.category-form', function (e) {
            $.ajax({
                url: '/categories/tree',
                success: function (resp) {
                    $('.category-page-wrapper').html($(resp).children().unwrap());
                    sortableInit();
                }
            });
        }).on('success.form.bluz', '.add-child', function () {
            $.ajax({
                url: '/categories/tree',
                data: {
                    id: $('.select-tree select').val()
                },
                success: function () {
                    $('.root-category-list').trigger('change');
                    sortableInit();
                }
            });
            return false;
        }).on('blur', '#name', function () {
            var $alias = $("#alias");
            if ($alias.val() === "") {
                var title = tools.alias($(this).val());
                $alias.val(title);
            }
        });

    });

    return {};
});