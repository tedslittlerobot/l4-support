L4 Support
==========

> Some Helper classes and functions for Laravel 4

#### NB. This is still all subject to change

- __[Html Macro](docs/html.md)__
- __[Functions](docs/functions.md)__
- __[Repository](docs/repository.md)__
- __[Validation Rules](docs/validation.md)__
- __[Orderer](docs/orderer.md)__
- __[Order Enforcer](docs/order-enforcer.md)__
- __[Slugifier](docs/slugifier.md)__


## Installation

Add the following to your composer.json file:

```json
"repositories": [
    {
        "type": "git",
        "url": "https://github.com/tedslittlerobot/l4-support"
    }
],
```

Then add `"tlr/l4-support": "1.*"` to your composer.json's require object.

For the HTML macro, and the Validation rules, you may want to add `Tlr\Support\SupportServiceProvider` to your `providers` list in `app.php`.
