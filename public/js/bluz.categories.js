/**
 * @author   Viacheslav Nogin
 * @created  11.09.12 10:30
 */
/*global define,require*/
define(['jquery', 'bluz', 'bluz.notify', 'bluz.tools', 'bluz.ajax'], function ($, bluz, notify, tools) {
    "use strict";

    $(function () {

        $('.category-page-wrapper').on('change', '.select-tree select', function () {
            let $this= $(this);
            $.ajax({
                url: '/categories/tree',
                data: {
                    id: $this.val()
                },
                success: function (resp) {
                    $('.tree-wrapper').remove();
                    $('.tree-container').html($(resp).find('.tree-wrapper'));
                }
            });
        });

        $('.tree-container').on('success.ajax.bluz', '.remove', function () {
            $(this).parent().parent().remove();
        }).on('success.ajax.bluz', '.remove-root', function () {
            let $list = $('.root-category-list');
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
                }
            });
        }).on('success.form.bluz', '.category-form', function (e) {
            $.ajax({
                url: '/categories/tree',
                success: function (resp) {
                    $('.category-page-wrapper').html($(resp).children().unwrap());
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
                }
            });
            return false;
        }).on('blur', '#name', function () {
            let $alias = $("#alias");
            if ($alias.val() === "") {
                let title = tools.alias($(this).val());
                $alias.val(title);
            }
        });
    });

    return {};
});