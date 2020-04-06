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
            var sender = this;

            $(document).on('oleksandrr_Chat_openChatWindow.oleksandrr_chat', $.proxy(this.openChatWindow, this));
            $(this.options.closeWindowChat).on('click.oleksandrr_chat', $.proxy(this.closeChatWindow, this));
            $(this.options.sendButton).on('click.oleksandrr_chat', $.proxy(this.sendUserMessage, this));
            $(this.options.destroyButton).on('click.oleksandrr_chat', $.proxy(this._destroy, this));

            $.ajax({
                url: $('#oleksandrr-chat-admin-url-get').val(),
                type: 'get',
                dataType: 'json'
            })
                .done(function (response) {
                    sender.showPreviousMessages(response.list);
                });

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
                url: $('#oleksandrr-chat-admin-url-save').val(),
                type: 'post',
                dataType: 'json',
                data: {
                    user_message: message
                }
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
        },

        showPreviousMessages: function (list) {
            var sender = this;
            $.each(list, function (index, item) {
                var date = new Date(item.created_at);

                $('#oleksandrr-chat-message-field').append(
                    sender.createMessage(item.author_name, date.toLocaleTimeString(), item.message)
                );
            });
        },

        createMessage: function (authorName, date, message) {
            var template = mageTemplate('#message_template');

            return template({
                data: {
                    messageClass: 'oleksandrr-chat-user-message',
                    messageAuthor: authorName,
                    date: date,
                    message: message
                }
            });
        }
    });

    return $.oleksandrrChat.sidebar;
});
