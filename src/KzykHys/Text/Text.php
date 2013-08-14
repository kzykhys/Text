<?php

namespace KzykHys\Text;

/**
 * Text manipulation
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class Text implements \Serializable
{

    /**
     * @var string
     */
    private $text;

    /**
     * @param string $text
     */
    public function __construct($text = '')
    {
        $this->text = (string) $text;
    }

    /**
     * Append the string
     *
     * @param string $text
     *
     * @return Text
     */
    public function append($text)
    {
        $this->text .= (string) $text;

        return $this;
    }

    /**
     * Prepend the string
     *
     * @param string $text
     *
     * @return Text
     */
    public function prepend($text)
    {
        $this->text = (string) $text . $this->text;

        return $this;
    }

    /**
     * Surround text with given string
     *
     * @param string $start
     * @param string $end
     *
     * @return Text
     */
    public function wrap($start, $end = null)
    {
        $this->text = $start . $this->text . (is_null($end) ? $start : $end);

        return $this;
    }

    /**
     * Make a string lowercase
     *
     * @return Text
     */
    public function lower()
    {
        $this->text = strtolower($this->text);

        return $this;
    }

    /**
     * Make a string uppercase
     *
     * @return Text
     */
    public function upper()
    {
        $this->text = strtoupper($this->text);

        return $this;
    }

    /**
     * Strip whitespace (or other characters) from the beginning and end of a string
     *
     * @param string $charList Optionally, the stripped characters can also be specified using the charlist parameter.
     *                         Simply list all characters that you want to be stripped. With .. you can specify a range of characters.
     *
     * @return Text
     */
    public function trim($charList = null)
    {
        if (is_null($charList)) {
            $this->text = trim($this->text);
        } else {
            $this->text = trim($this->text, $charList);
        }

        return $this;
    }

    /**
     * Strip whitespace (or other characters) from the end of a string
     *
     * @param string $charList You can also specify the characters you want to strip, by means of the charlist parameter.
     *                         Simply list all characters that you want to be stripped. With .. you can specify a range of characters.
     *
     * @return Text
     */
    public function rtrim($charList = null)
    {
        if (is_null($charList)) {
            $this->text = rtrim($this->text);
        } else {
            $this->text = rtrim($this->text, $charList);
        }

        return $this;
    }

    /**
     * Strip whitespace (or other characters) from the beginning of a string
     *
     * @param string $charList You can also specify the characters you want to strip, by means of the charlist parameter.
     *                         Simply list all characters that you want to be stripped. With .. you can specify a range of characters.
     *
     * @return Text
     */
    public function ltrim($charList = null)
    {
        if (is_null($charList)) {
            $this->text = ltrim($this->text);
        } else {
            $this->text = ltrim($this->text, $charList);
        }

        return $this;
    }

    /**
     * Convert special characters to HTML entities
     *
     * @param int $option
     *
     * @return Text
     */
    public function escapeHtml($option = ENT_QUOTES)
    {
        $this->text = htmlspecialchars($this->text, $option, 'UTF-8', false);

        return $this;
    }

    /**
     * Perform a regular expression search and replace
     *
     * @param string          $pattern     The pattern to search for. It can be either a string or an array with strings.
     * @param string|callable $replacement The string or an array with strings to replace.
     *                                     If $replacement is the callable, a callback that will be called and passed an array of matched elements in the subject string.
     *
     * @return Text
     */
    public function replace($pattern, $replacement)
    {
        if (is_callable($replacement)) {
            $this->text = preg_replace_callback($pattern, function ($matches) use ($replacement) {
                $args = array_map(function ($item) {
                    return new Text($item);
                }, $matches);

                return call_user_func_array($replacement, $args);
            }, $this->text);
        } else {
            $this->text = preg_replace($pattern, $replacement, $this->text);
        }

        return $this;
    }

    /**
     * Replace all occurrences of the search string with the replacement string
     *
     * @param string|array $search  The value being searched for, otherwise known as the needle. An array may be used to designate multiple needles.
     * @param string|array $replace The replacement value that replaces found search values. An array may be used to designate multiple replacements.
     *
     * @return Text
     */
    public function replaceString($search, $replace)
    {
        $this->text = str_replace($search, $replace, $this->text);

        return $this;
    }

    /**
     * Add one level of line-leading spaces
     *
     * @param int $spaces The number of spaces
     *
     * @return Text
     */
    public function indent($spaces = 4)
    {
        $this->replace('/^/m', str_repeat(' ', $spaces));

        return $this;
    }

    /**
     * Remove one level of line-leading tabs or spaces
     *
     * @param int $spaces The number of spaces
     *
     * @return Text
     */
    public function outdent($spaces = 4)
    {
        $this->replace('/^(\t|[ ]{1,' . $spaces . '})/m', '');

        return $this;
    }

    /**
     * Convert tabs to spaces
     *
     * @param int $spaces
     *
     * @return $this
     */
    public function detab($spaces = 4)
    {
        $this->replace('/(.*?)\t/', function (Text $w, Text $string) use ($spaces) {
            return $string . str_repeat(' ', $spaces - $string->length() % $spaces);
        });

        return $this;
    }

    /**
     * Determine whether a variable is empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->text);
    }

    /**
     * Finds whether a variable is a number or a numeric string
     *
     * @return bool
     */
    public function isNumeric()
    {
        return is_numeric($this->text);
    }

    /**
     * Perform a regular expression match
     *
     * @param string     $pattern The pattern to search for, as a string.
     * @param array|null $matches If matches is provided, then it is filled with the results of search.
     *
     * @return boolean
     */
    public function match($pattern, &$matches = null)
    {
        return preg_match($pattern, $this->text, $matches) > 0;
    }

    /**
     * Split string by a regular expression
     *
     * @param string $pattern The pattern to search for, as a string.
     * @param int    $flags   [optional] PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_OFFSET_CAPTURE
     *
     * @return Text[]|array
     */
    public function split($pattern, $flags = PREG_SPLIT_DELIM_CAPTURE)
    {
        return array_map(function ($item) {
            return new static($item);
        }, preg_split($pattern, $this->text, -1, $flags));
    }

    /**
     * Split string by a line break
     *
     * @param string $pattern [optional] The pattern to search for, as a string.
     *
     * @return array|Text[]
     */
    public function lines($pattern = '/(\r?\n)/')
    {
        $lines  = preg_split($pattern, $this->text, -1, PREG_SPLIT_DELIM_CAPTURE);
        $chunk  = array_chunk($lines, 2);
        $result = array();

        foreach ($chunk as $values) {
            $result[] = new Text(implode('', $values));
        }

        return $result;
    }

    /**
     * Convert a string to an array
     *
     * @return array
     */
    public function chars($l = 0)
    {
        if ($l > 0) {
            $ret = array();
            $len = mb_strlen($this->text, "UTF-8");
            for ($i = 0; $i < $len; $i += $l) {
                $ret[] = mb_substr($this->text, $i, $l, "UTF-8");
            }
            return $ret;
        }
        return preg_split("//u", $this->text, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * Apply a user function to every line of the string
     *
     * @param callable $callback
     *
     * @return Text
     */
    public function eachLine(callable $callback)
    {
        $lines = $this->lines();

        foreach ($lines as $index => $line) {
            $line = new static($line);
            $lines[$index] = (string) call_user_func_array($callback, array($line, $index));
        }

        $this->text = implode('', $lines);

        return $this;
    }

    /**
     * Gets the length of a string
     *
     * @return int Returns the number of characters in string str having character encoding encoding.
     *             A multi-byte character is counted as 1.
     */
    public function length()
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($this->text, 'UTF-8');
        }

        // @codeCoverageIgnoreStart
        return preg_match_all("/[\\\\x00-\\\\xBF]|[\\\\xC0-\\\\xFF][\\\\x80-\\\\xBF]*/", $this->text);
        // @codeCoverageIgnoreEnd
    }

    /**
     * Returns the number of lines
     *
     * @return int
     */
    public function countLines()
    {
        return count($this->lines());
    }

    /**
     * Set internal string value
     *
     * @param string $text
     *
     * @return Text
     */
    public function setText($text)
    {
        $this->text = (string) $text;

        return $this;
    }

    /**
     * Find the position of the first occurrence of a substring in a string
     *
     * @param string $needle
     * @param int    $offset
     *
     * @return int The position as an integer. If needle is not found, indexOf will return boolean false.
     */
    public function indexOf($needle, $offset = 0)
    {
        return strpos($this->text, $needle, $offset);
    }

    /**
     * Returns internal string value
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Write a string to a file
     *
     * @param string $path
     *
     * @return int|boolean
     */
    public function save($path)
    {
        return file_put_contents($path, $this->text);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getText();
    }

    /**
     * String representation of object
     *
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize($this->text);
    }

    /**
     * Constructs the object
     *
     * @param string $serialized The string representation of the object.
     */
    public function unserialize($serialized)
    {
        $this->text = unserialize($serialized);
    }

}
