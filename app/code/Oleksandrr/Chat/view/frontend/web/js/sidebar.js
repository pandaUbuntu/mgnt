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
            destroyButton: '#oleksandrr-chat-test-destroy',
            saveUrl: '',
            getUrl: ''
        },

        /**
         * @private
         */
        _create: function () {
            $(document).on('oleksandrr_Chat_openChatWindow.oleksandrr_chat', $.proxy(this.openChatWindow, this));
            $(this.options.closeWindowChat).on('click.oleksandrr_chat', $.proxy(this.closeChatWindow, this));
            $(this.options.sendButton).on('click.oleksandrr_chat', $.proxy(this.sendUserMessage, this));
            $(this.options.destroyButton).on('click.oleksandrr_chat', $.proxy(this._destroy, this));

            $.ajax({
                context: this,
                dataType: 'json',
                type: 'get',
                url: this.options.getUrl
            })
            .done(function (response) {
                this.showPreviousMessages(response.list);
            })
            .fail(function (error) {
                console.log(error);
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
            this.options.saveUrl = 'https://alexandrr.local/oleksandrr-chat-controllers-message/message/save';
            this.options.getUrl = 'https://alexandrr.local/oleksandrr-chat-controllers-message/message/messages';
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

            var message = $(this.options.textArea).val();

            if (message.length) {
                $(this.options.messageField).append(this.getMessage(0, message));
            } else {
                alert('You cannot send a blank message.');
                return false;
            }

            $(this.options.textArea).val('');

            $.ajax({
                context: this,
                data: {
                    'user_message': message
                },
                dataType: 'json',
                type: 'post',
                url:  this.options.saveUrl
            })
            .done(function (response) {
                this.sendAdminMessage(response.admin_message);
            })
            .fail(function (error) {
                console.log(error);
            });
        },

        sendAdminMessage: function (message) {
            $(this.options.messageField).append(this.getMessage(1, message));
        },

        getMessage: function (typeUser, message) {
            var messageClass = 'oleksandrr-chat-user-message',
                messageAuthor = 'User',
                template = mageTemplate('#message_template'),
                date = new Date().toLocaleTimeString();

            if (typeUser) {
                messageClass = 'oleksandrr-chat-admin-message';
                messageAuthor = 'Admin';
            }

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
            var context = this;
            $.each(list, function (index, item) {
                var date = new Date(item.created_at);

                $('#oleksandrr-chat-message-field').append(
                    context.createMessage(item.author_name, date.toLocaleTimeString(), item.message)
                );
            });
        },

        createMessage: function (authorName, date, message) {
            var template = mageTemplate('#message_template');

            return template({
                data: {
                    date: date,
                    message: message,
                    messageAuthor: authorName,
                    messageClass: 'oleksandrr-chat-user-message'
                }
            });
        }
    });

    return $.oleksandrrChat.sidebar;
});
