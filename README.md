Text - Simple 1 Class Text Manipulation Library
-----------------------------------------------

[![Latest Stable Version](https://poser.pugx.org/kzykhys/text/v/stable.png)](https://packagist.org/packages/kzykhys/text)
[![Build Status](https://travis-ci.org/kzykhys/Text.png?branch=master)](https://travis-ci.org/kzykhys/Text)
[![Coverage Status](https://coveralls.io/repos/kzykhys/Text/badge.png?branch=master)](https://coveralls.io/r/kzykhys/Text?branch=master)

Do you remember [PHP's string functions][strings]?
If not, just wrap you text with `Text`! It will save a minute on your coding.

`Text` is extracted from [kzykhys/Ciconia][ciconia]. this is used for markdown processing.

Installation
------------

Modify your composer.json and run `php composer.phar update`

``` json
{
    "require": {
        "kzykhys/text":"~1.0.0"
    }
}
```

Requirements
------------

**PHP5.4+**

Get Started
-----------

`Text` acts like a string

```
<?php

use KzykHys\Text\Text;

$text = new Text('Lorem Ipsum');
echo $text;

// Lorem Ipsum
```

`Text` can also be called statically

```
<?php

use KzykHys\Text\Text;

$text = Text::create('Lorem Ipsum');
echo $text;

// Lorem Ipsum
```

Manipulation methods are *chainable*:

``` php
$text = new Text('foo');
$text
    ->append('bar')     // foobar
    ->prepend('baz')    // bazfoobar
    ->wrap('-')         // -bazfoobar-
    ->upper()           // -BAZFOOBAR-
    ->lower()           // -bazfoobar-
    ->trim('-')         // bazfoobar
    ->rtrim('r')        // bazfooba
    ->ltrim('b')        // azfooba
;
```

Special note for `replace()`

``` php
$text = new Text('FooBarBaz');
$text->replace('/Foo(Bar)(Baz)/', function (Text $whole, Text $bar, Text $baz) {
    return $bar->upper()->append($baz->lower());
});
echo $text;

// BARbaz
```

If the second argument is [callable], callback takes at least one parameter. The whole match being first, and matched subpatterns.
All parameters are `Text` instance.


API
---

### Manipulation (Chainable)

Method                           | Description
---------------------------------|--------------
create($text)                    | Create a new Text instance.
append($text)                    | Append the string.
prepend($text)                   | Prepend the string.
wrap($start, \[$end])            | Surround text with given string.
lower()                          | Make a string lowercase.
upper()                          | Make a string uppercase.
trim(\[$charList])               | Strip whitespace (or other characters) from the beginning and end of a string.
rtrim(\[$charList])              | Strip whitespace (or other characters) from the end of a string.
ltrim(\[$charList])              | Strip whitespace (or other characters) from the beginning of a string.
escapeHtml(\[$option])           | Convert special characters to HTML entities.
replace($pattern, $replacement)  | Perform a regular expression search and replace. If $replacement is the callable, a callback that will be called and passed an array of matched elements in the subject string.
replaceString($search, $replace) | Replace all occurrences of the search string with the replacement string.
indent(\[$spaces])               | Add one level of line-leading spaces.
outdent(\[$spaces])              | Remove one level of line-leading tabs or spaces.
detab(\[$spaces])                | Convert tabs to spaces.
eachLine($callback)              | Apply a user function to every line of the string.

### Test

Method                           | Description
---------------------------------|--------------
isEmpty()                        | Determine whether a variable is empty
isNumeric()                      | Finds whether a variable is a number or a numeric string
match($pattern, \[&$matches])    | Perform a regular expression match

### Miscellaneous

Method                           | Description
---------------------------------|--------------
split($pattern, \[$flags])       | Split string by a regular expression
lines(\[$pattern])               | Split string by a line break
chars()                          | Convert a string to an array
length()                         | Gets the length of a string
countLines()                     | Gets the number of lines
indexOf($needle, \[$offset])     | Find the position of the first occurrence of a substring in a string

### Filesystem

Method                           | Description
---------------------------------|--------------
save($path)                      | Write a string to a file

License
-------

The MIT License

Author
------

Kazuyuki Hayashi (@kzykhys)

[ciconia]:  https://github.com/kzykhys/Ciconia
[strings]:  http://www.php.net/manual/en/ref.strings.php
[callable]: http://www.php.net/manual/en/language.types.callable.php