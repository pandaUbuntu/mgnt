define(
  [
      'jquery',
      'jquery/ui'
  ],
  function ($) {
      'use strict';

      $.widget('oleksandrr.catalog_demoFunction', {
        options: {
            text: 'Default text'
        },

        _create: function () {
            this.append();
        },

        append: function () {
            var tag = $('<p></p>').html(this.options.text);
            $(this.element).append(tag);
        }
      });

      return $.oleksandrr.catalog_demoFunction;
  }
);