L4 Support
==========

> Some Helper classes and functions for Laravel 4

#### NB. This is still all subject to change

- __[Html Macro](docs/html.md)__
- _[Repository](docs/repository.md) (documentation not yet finished)_
- _[Functions](docs/functions.md) (documentation not yet finished)_

- _[Orderer](docs/orderer.md) (documentation not yet finished)_
- _[Order Enforcer](docs/order-enforcer.md) (documentation not yet finished)_
- _[Slugifier](docs/slugifier.md) (documentation not yet finished)_
- _[Validation](docs/validation.md) (documentation not yet finished)_


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

Then, you can `composer require tlr/l4-support` to add this to your composer.json and download it.

For the HTML macro, and the Validation rules, you may want to add `Tlr\Support\SupportServiceProvider` to your `providers` list in `app.php`.
