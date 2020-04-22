define([
    'jquery',
    'mage/template'
], function ($, mageTemplate) {
    'use strict';

    /**
     * @param {String} authorName
     * @param {String} date
     * @param {String} message
     */
    function createMessage(authorName, date, message) {
        var template = mageTemplate('#message-template');

        return template({
            data: {
                date: date,
                message: message,
                messageAuthor: authorName,
                messageClass: 'oleksandrr-chat-user-message'
            }
        });
    }

    /**
     * @param {String} url
     */
    return function (url) {
        var responseData = '';

        $.ajax({
            context: this,
            dataType: 'json',
            type: 'get',
            url: url,

            /** @inheritdoc */
            beforeSend: function () {
                $('body').trigger('processStart');
            },

            /** @inheritdoc */
            success: function (response) {
                $('body').trigger('processStop');

                /**
                 * @var {Object} item
                 * @var {String} item.created_at
                 * @var {String} item.author_name
                 * @var {String} item.message
                 */
                $.each(response.list, function (index, item) {
                    var date = new Date(item.created_at);

                    $('#oleksandrr-chat-message-field').append(
                        createMessage(item.author_name, date.toLocaleTimeString(), item.message)
                    );
                });
            },

            /** @inheritdoc */
            error: function () {
                $('body').trigger('processStop');
            }
        });

        return responseData;
    };
});
