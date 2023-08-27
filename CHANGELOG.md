# Changelog

## v0.0.4 - 2023-08-27

- [feat] Added a `propertyNames` property to the `Validated` class
- [change] Rules without a message set will return null. Use `passed()` or `failed()` methods to determine passing validations.
- [change] The `Result` class can no longer be cast as a string. Use the `message()` method instead.
- [fix] Properties without rules will no longer be included in the results.

## v0.0.3 - 2023-08-27

- [feat] Added support to get messages for all the class properties.

## v0.0.2 - 2023-08-26

A few changes to make quick validation of an entire class a little easier.

- [feat] Added passed and failed methods to the Validated class.
- [change] The second parameters of the property method is now nullable.
- [fix] Minor bug fix converting array to string when validation array length.
- [tests] Added a test for custom rules.
- [tests] Added a test for validating the length of an array.
