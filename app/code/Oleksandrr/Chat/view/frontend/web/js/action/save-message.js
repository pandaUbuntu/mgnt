define([
    'jquery',
    'Magento_Ui/js/modal/alert',
    'mage/template'
], function ($, alert, mageTemplate) {
    'use strict';

    /**
     * @param {Number} typeUser
     * @param {String} message
     */
    function getMessage(typeUser, message) {
        var template = mageTemplate('#message-template'),
            date = new Date().toLocaleTimeString();

        var field = template({
            data: {
                messageClass: 'oleksandrr-chat-user-message',
                messageAuthor: 'Guest',
                date: date,
                message: message
            }
        });

        return field;
    }

    /**
     * @param {Object} messageObj
     * @param {String} url
     */
    return function (messageObj, url) {
        var messageField = $('#oleksandrr-chat-message-field');

        return $.ajax({
            context: this,
            data: {
                'user_message': messageObj.message,
                'form_key': $.mage.cookies.get('form_key')
            },
            dataType: 'json',
            type: 'post',
            url: url,

            /** @inheritdoc */
            beforeSend: function () {
                $('body').trigger('processStart');
            },

            /** @inheritdoc */
            success: function () {
                $('body').trigger('processStop');

                if (messageObj.message.length) {
                    console.log(getMessage(0, messageObj.message));
                    console.log(messageField[0]);
                    messageField[0].append(getMessage(0, messageObj.message));

                    $('#oleksandrr-chat-textarea').val('');
                } else {
                    alert({
                        title: $.mage.__('Error'),
                        content: 'You cannot send a blank message.'
                    });
                }
            },

            /** @inheritdoc */
            error: function () {
                $('body').trigger('processStop');
                alert({
                    title: $.mage.__('Error'),
                    content: $.mage.__(
                        'Your preferences can\'t be saved. Please, contact us if ypu see this message.'
                    )
                });
            }
        });
    };
});
