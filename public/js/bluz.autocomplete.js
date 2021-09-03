/**
 * Autocomplete
 *
 * @author Anton Shevchuk
 */
import '../vendor/jquery/jquery.js'
import './bluz.ajax.js'

/**
 * Autocomplete for input fields
 * <code>
 *   <script type="module">
 *     import { autocomplete } from '<?=$this->baseUrl('/js/bluz.autocomplete.js');?>'
 *     autocomplete('#login')
 *   </script>
 * </code>
 * @param {String} input selector
 */
export let autocomplete = function (input) {
  let $input = $(input)

  let source = $input.data('ajax-load')
  if (!source) {
    throw new Error('Undefined `data-ajax-load` attribute')
  }
  let target = $input.data('ajax-target')
  if (!target) {
    throw new Error('Undefined `data-ajax-target` attribute')
  }
  let $target = $(target)
  if ($target.length === 0) {
    throw new Error('Element defined by `data-ajax-target` not found')
  }
  let key = $input.data('ajax-item-key') || $input.attr('name')
  let value = $input.data('ajax-item-value') || 'id'

  let ajaxRequest

  let currentFocus = -1

  /**
   * Prepare DOM
   */
  $input.wrap('<div class="autocomplete"></div>')

  let $container = $input.parent()

  $container.append('<div class="autocomplete-items"></div>')

  let $items = $container.find('.autocomplete-items')

  /**
   * Initial input handler
   */
  $input.on('keydown', function (e) {
    /*If the ANY key is pressed,
    clear the target value:*/
    $target.val('')

    if (e.keyCode === 40) {
      /*If the arrow DOWN key is pressed,
      increase the currentFocus variable:*/
      currentFocus++
      /*and and make the current item more visible:*/
      setActiveItem(currentFocus)
    } else if (e.keyCode === 38) {
      /*If the arrow UP key is pressed,
      decrease the currentFocus variable:*/
      currentFocus--
      /*and and make the current item more visible:*/
      setActiveItem(currentFocus)
    } else if (e.keyCode === 13) {
      /*If the ENTER key is pressed, prevent the form from being submitted,*/
      e.preventDefault()
      if (currentFocus > -1) {
        /*and simulate a click on the "active" item:*/
        $items.find('div').eq(currentFocus).click()
      } else {
        $items.find('div:contains("'+$input.val()+'")').click()
      }
    } else if ($input.val().length) {
      search($input.val())
    } else {
      destroyListItems()
    }
  })


  /**
   * Search data for autocomplete by AJAX request
   * @param term
   */
  function search (term) {
    let request = {}
    request[$input.attr('name')] = term + '%' // for LIKE SQL queries

    ajaxRequest?.abort();

    ajaxRequest = $.ajax({
      url: source,
      type: $input.data('ajax-method') || 'get',
      dataType: 'json',
      data: request
    }).done(response => {
      destroyListItems()
      response.forEach(element => buildListItem(element))
    })
  }

  /**
   * Create item based on received data
   * @param element
   */
  function buildListItem (element) {
    let $item = $('<div>')
    $item
      .data('key', element[key])
      .data('value', element[value])
      .text(element[key])
      .click(function() {
        $input.val(element[key])
        $target.val(element[value])
        destroyListItems()
        return false;
      })

    $items.append($item);
  }

  /**
   * Remove all created items
   */
  function destroyListItems() {
    $items.find('div').remove();
  }

  /**
   * Classify an item as "active"
   * @param index
   */
  function setActiveItem(index) {
    /*start by removing the "active" class on all items:*/
    removeActive()
    let total = $items.find('div').length;

    if (index >= total) currentFocus = 0
    if (index < 0) currentFocus = (total - 1)
    /*add class "autocomplete-active":*/
    $items.find('div').eq(currentFocus).addClass('autocomplete-active')
  }

  /**
   * Removing the "active" class on all items
   */
  function removeActive() {
    $items.find('.autocomplete-active').removeClass('autocomplete-active');
  }

  /*execute a function when someone clicks in the document:*/
  document.addEventListener('click', function (e) {
    if (e.target !== $input.get(0) && e.target !== $items.get(0)) {
      destroyListItems()
    }
  })
}
