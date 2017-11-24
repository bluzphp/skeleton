/**
 * Addon for jQueryUI autocomplete
 *
 * @author Anton Shevchuk
 * @created  23.11.2017 11:42
 */

/* global define,require*/
define(['jquery', 'jqueryui'], function ($) {
  'use strict';

  // workaround for jQueryUI styles
  $('<style>.ui-helper-hidden-accessible > div { display: none; }</style>').appendTo('head');

  function autocomplete() {
    let $this = $(this);
    let source = $this.data('ajax-load');
    if (!source) {
      throw new Error('Undefined `data-ajax-load` attribute');
    }
    let target = $this.data('ajax-target');
    if (!target) {
      throw new Error('Undefined `data-ajax-target` attribute');
    }
    let $target = $(target);
    if ($target.length === 0) {
      throw new Error('Element defined by `data-ajax-target` not found');
    }
    let uid = $this.data('ajax-item-id') || 'id';
    let label = $this.data('ajax-item-label') || $this.attr('name');

    $this.autocomplete({
      minLength: 2,
      autoFocus: true,
      source: (request, response) => {
        let requestData = {};
        requestData[$this.attr('name')] =  request.term + '%'; // for LIKE SQL queries

        $.ajax({
          url: source,
          type: $this.data('ajax-method') || 'post',
          dataType: 'json',
          data: requestData,
          success: responseData => {
            response( responseData );
          }
        });
      },
      search: () => {
        $target.val('');
      },
      response: () => {
      },
      focus: (event, ui) => {
        $this.val(ui.item[label]);
        $target.val(ui.item[uid]);
        event.preventDefault();
      },
      select: (event, ui) => {
        $this.val(ui.item[label]);
        $target.val(ui.item[uid]);
        event.preventDefault();
      }
    });

    // change default renders for widget
    $this.data( 'ui-autocomplete' )._renderMenu = function ( ul, items ) {
      ul.attr('class', 'dropdown-menu' );
      ul.css('z-index', 9999);
      $.each( items, (index, item) => this._renderItemData( ul, item ));
    };

    $this.data( 'ui-autocomplete' )._renderItem = function ( ul, item ) {
      return $( '<li><div class="dropdown-item">' + item[label] + '</div></li>' )
        .css( 'cursor', 'pointer')
        .data( 'item.autocomplete', item )
        .appendTo( ul );
    };
  }

  // setup autocomplete for all input[data-ajax-load]
  $(() => $('input[data-ajax-load]').each(autocomplete));
});
