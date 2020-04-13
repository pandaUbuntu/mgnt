define([
    'jquery',
    'jquery/ui',
    'mage/translate'
], function ($) {
    'use strict';

    $.widget('oleksandrrCustomerPreferences.openButton', {
        options: {
            hideButton: true
        },

        /**
         * @private
         */
        _create: function () {
            $(this.element).on('click.oleksandrr_customerPreferences', $.proxy(this.openPreferences, this));
            $(this.element).on('oleksandrr_CustomerPreferences_closePreferences.oleksandrr_customerPreferences', $.proxy(this.closePreferences, this));
        },

        /**
         * jQuery(jQuery('.oleksandrr-customer-preferences-open-button').get(0)).data('oleksandrrCustomerPreferencesOpenButton').destroy()
         * @private
         */
        _destroy: function () {
            $(this.element).off('click.oleksandrr_customerPreferences');
            $(this.element).off('oleksandrr_CustomerPreferences_closePreferences.oleksandrr_customerPreferences');
        },

        /**
         * Open preferences sidebar
         */
        openPreferences: function () {
            $(document).trigger('oleksandrr_CustomerPreferences_openPreferences');

            if (this.options.hideButton) {
                $(this.element).removeClass('active');
            }
        },

        /**
         * Close preferences sidebar
         */
        closePreferences: function () {
            $(this.element).addClass('active');
        }
    });

    return $.oleksandrrCustomerPreferences.openButton;
});
