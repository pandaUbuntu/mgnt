define([
    'jquery'
    // 'oleksandrr_customerPreferences_form'
], function ($) {
    'use strict';

    $.widget('oleksandrrChat.sidebar', {
        options: {
            sidebarOpenButton: '.oleksandrr-chat-open-button',
            editButton: '#oleksandrr-chat-edit-button',
            closeWindowChat: '#oleksandrr-chat-window-close-button'
        },

        /**
         * @private
         */
        _create: function () {
            $(document).on('oleksandrr_Chat_openChatWindow.oleksandrr_chat', $.proxy(this.openChatWindow, this));
            $(this.options.closeWindowChat).on('click.oleksandrr_chat', $.proxy(this.closeChatWindow, this));
            $(this.options.editButton).on('click.oleksandrr_chat', $.proxy(this.sendMessage, this));
            // make the hidden form visible after the styles are initialized
            $(this.element).show();
        },

        /**
         * @private
         */
        _destroy: function () {
            $(document).off('oleksandrr_Chat_openChatWindow.oleksandrr_chat');
            $(this.options.closeSidebar).off('click.oleksandrr_chat');
            // $(this.options.editButton).off('click.oleksandrr_chat');
        },

        /**
         * Open preferences sidebar
         */
        openChatWindow: function () {
            $(this.element).addClass('active');
        },

        /**
         * Close preferences sidebar
         */
        closeChatWindow: function () {
            $(this.element).removeClass('active');
            $(this.options.sidebarOpenButton).trigger('oleksandrr_Chat_closeChatWindow');
        },

        /**
         * Print user message
         */
        sendMessage: function () {

        }
    });

    return $.oleksandrrChat.sidebar;
});
