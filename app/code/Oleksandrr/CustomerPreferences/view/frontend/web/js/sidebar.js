define([
    'jquery',
    'oleksandrr_customerPreferences_form'
], function ($) {
    'use strict';

    $.widget('oleksandrrCustomerPreferences.sidebar', {
        options: {
            sidebarOpenButton: '.oleksandrr-customer-preferences-open-button',
            editButton: '#oleksandrr-customer-preferences-edit-button',
            closeSidebar: '#oleksandrr-customer-preferences-close-sidebar-button',
            customerPreferencesList: '#oleksandrr-customer-preferences-list',
            form: '#oleksandrr-customer-preferences-form'
        },

        /**
         * @private
         */
        _create: function () {
            $(document).on('oleksandrr_CustomerPreferences_openPreferences.oleksandrr_customerPreferences', $.proxy(this.openPreferences, this));
            $(this.options.closeSidebar).on('click.oleksandrr_customerPreferences', $.proxy(this.closePreferences, this));
            $(this.options.editButton).on('click.oleksandrr_customerPreferences', $.proxy(this.editPreferences, this));

            // make the hidden form visible after the styles are initialized
            $(this.element).show();
        },

        /**
         * @private
         */
        _destroy: function () {
            $(document).off('oleksandrr_CustomerPreferences_openPreferences.oleksandrr_customerPreferences');
            $(this.options.closeSidebar).off('click.oleksandrr_customerPreferences');
            $(this.options.editButton).off('click.oleksandrr_customerPreferences');
        },

        /**
         * Open preferences sidebar
         */
        openPreferences: function () {
            $(this.element).addClass('active');
        },

        /**
         * Close preferences sidebar
         */
        closePreferences: function () {
            $(this.element).removeClass('active');
            $(this.options.sidebarOpenButton).trigger('oleksandrr_CustomerPreferences_closePreferences');
        },

        /**
         * Open popup with the form to edit preferences
         */
        editPreferences: function () {
            $(this.options.form).data('mage-modal').openModal();
        }
    });

    return $.oleksandrrCustomerPreferences.sidebar;
});
