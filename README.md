### Telegram notifier lite server
This service allows you to use telegram bot to send notifications to yourself or to a group without having to create 
and configure a bot.

#### How it works?
1. Start a dialogue with the bot [Notifier](https://telegram.me/notificator_lite_bot). He will send you a token.
2. Make a request using your token:
    - Endpoint: `POST https://pnmvgf8fy4.execute-api.eu-central-1.amazonaws.com/production/message`.
    - Required fields: **token**, **message**.
        - Example:
            ```http request
            POST /production/message HTTP/1.1
            Content-Type: application/x-www-form-urlencoded
            Host: pnmvgf8fy4.execute-api.eu-central-1.amazonaws.com
            Content-Length: 83
            
            token=28b51c1575706468171223b2183ed9f534f8ed7&message=Hello
            ```


#### PHP client (async)
[bu4ak/telegram-notifier-lite-client](https://github.com/Bu4ak/telegram-notifier-lite-client)

#### Stack
- PHP 7.4
- Symfony 5.1 (and packages)
- AWS 
    - Lambda
    - DynamoDb
    - SQS
- Telegram API
