### How to use it in another class? ###
**here's an example**

```php
  use DemoProject\App\Services\InputSanitizer;
  $data = [
        'name' => 'some dummy text',
        'rows' => 10,
        'random_data' => [
            ['email' => 'email2@email.com'],
            ['email' => '<script>email3@email.com</script>'],
            ['source' => 'https://<script>www.google.com</script>']
        ]
    ];

    $sanitizedData = (new InputSanitizer())->sanitizeData($data);
    update_option('demo-project-data', maybe_serialize($sanitizedData), 'no');
```
