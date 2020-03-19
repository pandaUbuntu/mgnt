define([
    'jquery',
    'mage/template'
], function ($) {
    'use strict';

    $.widget('oleksandrrChat.sidebar', {
        options: {
            sidebarOpenButton: '.oleksandrr-chat-open-button',
            sendButton: '#oleksandrr-chat-send-button',
            closeWindowChat: '#oleksandrr-chat-window-close-button',
            textArea: '#oleksandrr-chat-textarea',
            messageField: '#oleksandrr-chat-message-field',
            action: '/oleksandrr-chat-controllers-message/message',
            baseUrl: 'https://alexandrr.local'
        },

        /**
         * @private
         */
        _create: function () {
            $(document).on('oleksandrr_Chat_openChatWindow.oleksandrr_chat', $.proxy(this.openChatWindow, this));
            $(this.options.closeWindowChat).on('click.oleksandrr_chat', $.proxy(this.closeChatWindow, this));
            $(this.options.sendButton).on('click.oleksandrr_chat', $.proxy(this.sendUserMessage, this));
            // make the hidden form visible after the styles are initialized
            $(this.element).show();
        },

        /**
         * @private
         */
        _destroy: function () {
            $(document).off('oleksandrr_Chat_openChatWindow.oleksandrr_chat');
            $(this.options.closeSidebar).off('click.oleksandrr_chat');
            $(this.options.sendButton).off('click.oleksandrr_chat');
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
        sendUserMessage: function () {
            var message = $(this.options.textArea).val();

            if (message.length) {
                $(this.options.messageField).append(this.getMessage(0, message));
            }

            $(this.options.textArea).val('');
            this.sendAdminMessage('Test');

            $.ajax({
                url: this.options.baseUrl + this.options.action + '/userMessage/' + message,
                processData: false,
                contentType: false,
                type: 'get',
                dataType: 'json'
            })
                .done(function (response) {

            })
                .fail(function (error) {
                    console.log(JSON.stringify(error));
                });
        },

        /**
         *
         */
        sendAdminMessage: function (message) {
            $(this.options.messageField).append(this.getMessage(1, message));
        },

        getMessage: function (typeUser, message) {
            var messageClass = 'oleksandrr-chat-user-message';
            var messageAuthor = 'User';
            var date = new Date().toLocaleTimeString();

            if (typeUser) {
                messageClass = 'oleksandrr-chat-admin-message';
                messageAuthor = 'Admin';
            }


        }
    });

    return $.oleksandrrChat.sidebar;
});
