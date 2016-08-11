<?php

namespace MVStudio\Telegraph;

function array_filter_recursive(array $array, $callback = NULL)
{
    foreach ($array as $index => $value) {
        if (is_array($value)) {
            $array[$index] = array_filter_recursive($value, $callback);
        }
    }
    return array_filter($array, $callback);
}

function is_not_null($value) {
    return !is_null($value);
}

class Client implements ClientInterface
{
    private $client;

    public function __construct(string $baseUri)
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $baseUri,
            'timeout' => 10,
            'http_errors' => false
        ]);
    }

    public function send (array $options)
    {
        $requestBody = [
            'serviceName' => isset($options['service']) ? $options['service'] : null,
            'emailOptions' => [
                'to' => isset($options['to']) ? $options['to'] : null,
                'from' => isset($options['from']) ? $options['from'] : null,
                'replyTo' => isset($options['replyTo']) ? $options['replyTo'] : null,
                'subject' => isset($options['subject']) ? $options['subject'] : null,
                'html' => isset($options['html']) ? $options['html'] : null,
                'text' => isset($options['text']) ? $options['text'] : null
            ]
        ];

        if (isset($options['attachments']) && is_array($options['attachments'])) {
            $attachments = $options['attachments'];
            $requestBody['emailOptions']['attachments'] = array_map($attachments, function ($attachment) {
                return [
                    'content-type' => isset($attachment['content-type']) ? $attachment['content-type'] : null,
                    'filename' => isset($attachment['filename']) ? $attachment['filename'] : null,
                    'content' => isset($attachment['content']) ? $attachment['content'] : null
                ];
            });
        }

        $response = $this->client->post('/api/send', [
            'json' => array_filter_recursive($requestBody, __NAMESPACE__ . '\is_not_null')
        ]);

        $statusCode = $response->getStatusCode();

        if ($statusCode !== 200) {
            $responseBody = json_decode($response->getBody());
            $error = $responseBody->error;
            throw SendException::fromJSON($error);
        }
    }

    public function sendTemplate (array $options)
    {
        $requestBody = [
            'serviceName' => isset($options['service']) ? $options['service'] : null,
            'templateName' => isset($options['name']) ? $options['name'] : null,
            'templateLanguage' => isset($options['language']) ? $options['language'] : null,
            'templateData' => isset($options['data']) ? $options['data'] : null,
            'emailOptions' => [
                'to' => isset($options['to']) ? $options['to'] : null
            ]
        ];

        if (isset($options['attachments']) && is_array($options['attachments'])) {
            $attachments = $options['attachments'];
            $requestBody['emailOptions']['attachments'] = array_map($attachments, function ($attachment) {
                return [
                    'content-type' => isset($attachment['content-type']) ? $attachment['content-type'] : null,
                    'filename' => isset($attachment['filename']) ? $attachment['filename'] : null,
                    'content' => isset($attachment['content']) ? $attachment['content'] : null
                ];
            });
        }

        $response = $this->client->post('/api/send-template', [
            'json' => array_filter_recursive($requestBody, __NAMESPACE__ . '\is_not_null')
        ]);

        $statusCode = $response->getStatusCode();

        if ($statusCode !== 200) {
            $responseBody = json_decode($response->getBody());
            $error = $responseBody->error;
            throw SendException::fromJSON($error);
        }
    }
}
