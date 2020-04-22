define([
    'jquery',
    'jquery/ui',
    'mage/translate'
], function ($) {
    'use strict';

    $.widget('oleksandrrChat.openButton', {
        options: {
            hideButton: true
        },

        /**
         * @private
         */
        _create: function () {
            $(this.element).on('click.oleksandrr_chat', $.proxy(this.openChatWindow, this));
            $(this.element).on('oleksandrr_Chat_closeChatWindow.oleksandrr_chat', $.proxy(this.closeChatWindow, this));

            this.hash = Math.random().toString(36).substr(2, 9);
            $(document).on(
                'oleksandrr_Chat_closeChatWindow.oleksandrr_chat',
                $.proxy(this.closeChatWindow, this)
            );
        },

        /**
         * @private
         */
        _destroy: function () {
            $(this.element).off('click.oleksandrr_chat');
            $(this.element).off('oleksandrr_Chat_closeChatWindow.oleksandrr_chat');
        },

        /**
         * Open chat sidebar
         */
        openChatWindow: function () {
            $(document).trigger('oleksandrr_Chat_openChatWindow');

            if (this.options.hideButton) {
                $(this.element).removeClass('active');
            }
        },

        /**
         * Close chat sidebar
         */
        closeChatWindow: function () {
            $(this.element).addClass('active');
            console.log('test');
        }
    });

    return $.oleksandrrChat.openButton;
});
