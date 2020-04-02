define([
    'jquery',
    'mage/template'
], function ($, mageTemplate) {
    'use strict';

    $.widget('oleksandrrChat.sidebar', {
        options: {
            sidebarOpenButton: '.oleksandrr-chat-open-button',
            sendButton: '#oleksandrr-chat-send-button',
            closeWindowChat: '#oleksandrr-chat-window-close-button',
            textArea: '#oleksandrr-chat-textarea',
            messageField: '#oleksandrr-chat-message-field',
            destroyButton: '#oleksandrr-chat-test-destroy'
        },

        /**
         * @private
         */
        _create: function () {
            $(document).on('oleksandrr_Chat_openChatWindow.oleksandrr_chat', $.proxy(this.openChatWindow, this));
            $(this.options.closeWindowChat).on('click.oleksandrr_chat', $.proxy(this.closeChatWindow, this));
            $(this.options.sendButton).on('click.oleksandrr_chat', $.proxy(this.sendUserMessage, this));
            $(this.options.destroyButton).on('click.oleksandrr_chat', $.proxy(this._destroy, this));
            $(this.element).show();
        },

        /**
         * @private
         */
        _destroy: function () {
            $(document).off('oleksandrr_Chat_openChatWindow.oleksandrr_chat');
            $(this.options.closeWindowChat).off('click.oleksandrr_chat');
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
        sendUserMessage: function (event) {
            event.preventDefault();
            var sender = this;

            var message = $(sender.options.textArea).val();

            if (message.length) {
                $(sender.options.messageField).append(sender.getMessage(0, message));
            }

            $(sender.options.textArea).val('');

            $.ajax({
                url: $('#oleksandrr-chat-admin-url').val() + message,
                processData: false,
                contentType: false,
                type: 'get',
                dataType: 'json'
            })
                .done(function (response) {
                    sender.sendAdminMessage(response.admin_message);
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

            var template = mageTemplate('#message_template');

            var field = template({
                data: {
                    messageClass: messageClass,
                    messageAuthor: messageAuthor,
                    date: date,
                    message: message
                }
            });

            $('#oleksandrr-chat-message-field').append(field);
        }
    });

    return $.oleksandrrChat.sidebar;
});
