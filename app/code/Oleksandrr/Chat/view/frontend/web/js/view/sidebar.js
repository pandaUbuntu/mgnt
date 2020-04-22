define([
    'jquery',
    'ko',
    'uiComponent',
    'mage/template',
    'Magento_Customer/js/customer-data',
    'Oleksandrr_Chat/js/action/save-message',
    'Oleksandrr_Chat/js/action/get-messages',
    'mage/cookies'
], function ($, ko, Component, mageTemplate, customerData, saveMessage, getMessages) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Oleksandrr_Chat/sidebar',
            actionSaveUrl: '',
            getMessagesUrl: '',
            messages: [],
            placeholder: 'Here you can write your message'
        },
        textAreaValue: ko.observable(''),
        sidebarClass: ko.observable(''),
        newMessage: '',

        /**
         * @inheritdoc
         */
        initialize: function () {
            this._super();

            getMessages(this.getMessagesUrl);

            $(document).on(
                'oleksandrr_Chat_openChatWindow.oleksandrr_chat',
                $.proxy(this.openChatWindow, this)
            );
        },

        /**
         * @inheritdoc
         */
        initObservable: function () {
            this._super();

            this.textAreaValue.subscribe(function (newMessage) {

                var userMessage = {
                    message: newMessage,
                    'form_key': $.mage.cookies.get('form_key'),
                    isAjax: 1
                };

                saveMessage(userMessage, this.actionSaveUrl);
            }, this);

            return this;
        },

        /**
         * Open chat sidebar
         */
        openChatWindow: function () {
            this.sidebarClass('active');
        },

        sendUserMessage: function () {
        },

        /**
         * Close chat sidebar
         */
        closeSidebar: function () {
            this.sidebarClass('');
            $(document).trigger('oleksandrr_Chat_closeChatWindow.oleksandrr_chat');
        }
    });
});
