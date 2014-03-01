Validation Rules
================

> Some extra validation rules

## slug

Validation passes if the input is a valid slug. Uses `Str::slug()`

## json

Validation passes if the input is valid JSON

## false

Validation passes if the given input field does not exist. The only real use i can imagine for this is if you need to ensure that a checkbox hasn't been checked. This is the opposite of `required`, and as such will not work with it.
