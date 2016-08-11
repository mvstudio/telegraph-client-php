# telegraph-client-php
PHP Client for telegraph service

## API

send and sendTemplate method will throw a `MVStudio\Telegraph\SendException`
in case of api or delivery error.

### Client::send($options)

| key | description |
|-----|-------------|
| service     | Backend service to use (optional) |
| to          | Recipient (can be an array of email) |
| from        | Sender "name <name@domain.tld>" or "name@domain.tld" |
| subject     | Email subject |
| html        | HTML content (optional if text is set) |
| text        | Text content (optional is html is set) |
| attachments | Attachments (optional, see attachemnts section) |

#### Example

```php
use MVStudio\Telegraph\Client;

$client = new Client('http://localhost:14620');
$client->send([
    'to' => 'foo@bar.com',
    'from' => 'bar@foo.com',
    'subject' => 'Hello world',
    'html' => '<strong>Hello World!</strong>'
]);
```

### Client::sendTemplate

| key | description |
|-----|-------------|
| service     | Backend service to use (optional) |
| to          | Recipient (can be an array of email) |
| name        | Template name |
| language    | Template language (optional) |
| data        | Template data |
| attachments | Attachments (optional, see attachemnts section) |

#### Example

```php
use MVStudio\Telegraph\Client;

$client = new Client('http://localhost:14620');
$client->sendTemplate([
    'to' => 'foo@bar.com',
    'name' => 'mytemplate',
    'language' => 'fr',
    'data' => [
        'foo' => 'bar',
        'bar' => 42
    ]
]);
```

### Attachments

| key | description |
|-----|-------------|
| content-type  | MIME type |
| filename      | File name (i.e: document.pdf) |
| content       | File content base64 encoded |
