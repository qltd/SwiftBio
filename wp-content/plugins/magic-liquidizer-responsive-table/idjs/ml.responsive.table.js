/*
 * Plugin Name: Magic Liquidizer Responsive Table
 * Plugin URI: http://www.innovedesigns.com/wordpress/magic-liquidizer-responsive-table-rwd-you-must-have-wp-plugin/
 * Author: Elvin D.
 * Description: A simple and lightweight plugin that converts HTML table into responsive. After activation, go to Dashboard > Magic Liquidizer Lite > Table.
 * Version: 2.0.1
 * Author URI: http://innovedesigns.com/author/esstat17
 */
(function ($) {

  $.fn.responsiveTable = function (options) {

    var settings = $.extend({

      // Selectors
      /**
       * The header columns selector.
       * Default: 'thead td, thead th';
       * e.g. 'tr th', ...
       */
      headerSelector: 'thead td, thead th, tr th',
      /**
       * The body rows selector.
       * Default: 'tbody tr';
       * e.g. 'tr', ...
       */
      bodyRowSelector: 'tbody tr, tr',

      // Elements
      /**
       * The responsive rows container
       * element.
       * Default: '<dl></dl>';
       * e.g. '<ul></ul>'.
       */
      rowElement: '<dl></dl>',
      /**
       * The responsive column title
       * container element.
       * Default: '<dt></dt>';
       * e.g. '<li></li>'.
       */
      columnTitleElement: '<dt></dt>',
      /**
       * The responsive column value container element.
       * Default: '<dd></dd>';
       * e.g. '<li></li>'.
       */
      columnValueElement: '<dd></dd>',

      // Or false to disable.
      enable: true

    }, options);

    var tableHTML = '',
      display = '',
      titles = [],
      index = 0,
      jSum = 0,
      row = '',
      dt = '',
      dd = '';
    return this.each(function (o, e) {
      tableHTML = $(this);
      display = $('<div class="ml-responsive-table ml-responsive-table-' + o + '" />');
      // Table Title 
      tableHTML.find(settings.headerSelector).each(function (i, e) {
        // var title = $(e).html();
        titles[i] = $(e).html();
      });
      // What to use in the Index: Is it J or I ?
      tableHTML.find(settings.bodyRowSelector).each(function () {
        $(this).children('td').each(function (j) {
          jSum = jSum + j;
        })
      });
      // Display Mobile Table
      tableHTML.find(settings.bodyRowSelector).each(function (i, e) {

        // Row
        row = $(settings.rowElement);
        row.addClass('ml-grid ml-clearfix ml-row-' + i);

        // Column
        $(this).children('td').each(function (j, d) {

          index = jSum == 0 ? i : j,
            dt = $(settings.columnTitleElement),
            dd = $(settings.columnValueElement);

          dt.addClass('ml-title ml-table');
          dt.html(titles[index]);

          dd.addClass('ml-value ml-table');
          dd.html($(this).html());

          // Empty if there no value
          if ($.trim($(this).html()) == '') {
            dd.addClass('ml-empty');
            dt.addClass('ml-empty');
          }

          row.append(dt).append(dd);
        });

        display.append(row);
      });

      if (settings.enable) {
        // Hide table. We might need it again!
        tableHTML.hide();

        // Rename all the elements in the table to avoid duplicate ids
        // Based on https://wordpress.org/support/topic/duplicate-content-36/
        if (tableHTML.attr('id') !== undefined) {
          tableHTML.attr('id', 'temp-' + tableHTML.attr('id'));
        }

        $('*', tableHTML).each(function () {
          if ($(this).attr('id') !== undefined) {
            $(this).attr('id', 'temp-' + $(this).attr('id'));
          }
        });

        // Display responsive version after table.
        tableHTML.after(display);

      } else {

        $(".ml-responsive-table").detach();

        // Restore the original ids
        if (tableHTML.attr('id') !== undefined) {
          tableHTML.attr('id', tableHTML.attr('id').replace('temp-', ''));
        }

        $('*', tableHTML).each(function () {

          var orig_id = $(this).attr('id');
          if (orig_id !== undefined) {
            var new_id = orig_id.replace('temp-', '');
            $(this).attr('id', new_id);
          }

        });

        // Show table 
        tableHTML.show();
      }
    });
  };


})(jQuery);

(function ($) {
  $.fn.MagicLiquidizerTable = function (options) {
    var settings = $.extend({
      table: '1',
      breakpoint: '720',
      whichelement: 'table',
      headerSelector: 'thead td, thead th, tr th',
      bodyRowSelector: 'tbody tr, tr',
    }, options);
    return this.each(function () {

      function responsiveTableFn() {

        var viewwidth = $(window).width();
        /** Media screens **/
        if (viewwidth < settings.breakpoint) { // Tablet and Smartphone Screens 
          if (!$('html.rwd-table').length > 0) {
            $('html').addClass('rwd-table');
          }
          if (settings.table == '1' && !$('.ml-responsive-table').length > 0) {
            $(settings.whichelement).responsiveTable({
              enable: true,
              headerSelector: settings.headerSelector,
              bodyRowSelector: settings.bodyRowSelector,
            });
          }
        } else {
          if ($('html.rwd-table').length > 0) {
            $('html').removeClass('rwd-table');
          }
          if (settings.table == '1') {
            $(settings.whichelement).responsiveTable({
              enable: false
            })
          }
        }
      } // responsiveTableFn() ends
      $(window).resize(responsiveTableFn).ready(responsiveTableFn);
    }); // each fn ends
  }; // MagicLiquidizer fn

}(jQuery));