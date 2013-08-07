<?php

use KzykHys\Text\Text;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class TextTest extends \PHPUnit_Framework_TestCase
{

    public function testAppend()
    {
        $text = new Text('foo');
        $this->assertEquals('foobar', $text->append('bar'));
    }

    public function testPrepend()
    {
        $text = new Text('foo');
        $this->assertEquals('barfoo', $text->prepend('bar'));
    }

    public function testWrap()
    {
        $text = new Text('foo');
        $this->assertEquals('<p>foo</p>', $text->wrap('<p>', '</p>'));
        $this->assertEquals('|<p>foo</p>|', $text->wrap('|'));
    }

    public function testLower()
    {
        $text = new Text('Abc');
        $this->assertEquals('abc', $text->lower());
    }

    public function testUpper()
    {
        $text = new Text('Abc');
        $this->assertEquals('ABC', $text->upper());
    }

    public function testTrim()
    {
        $text = new Text("\n foo\t\r\n");
        $this->assertEquals('foo', $text->trim());

        $text = new Text('/foo/');
        $this->assertEquals('foo', $text->trim('/'));
    }

    public function testRtrim()
    {
        $text = new Text("\n foo\t\r\n");
        $this->assertEquals("\n foo", $text->rtrim());

        $text = new Text('/foo/');
        $this->assertEquals('/foo', $text->rtrim('/'));
    }

    public function testLtrim()
    {
        $text = new Text("\n foo\t\r\n");
        $this->assertEquals("foo\t\r\n", $text->ltrim());

        $text = new Text('/foo/');
        $this->assertEquals('foo/', $text->ltrim('/'));
    }

    public function testEscapeHtmlText()
    {
        $text = new Text('<script></script>');
        $this->assertEquals('&lt;script&gt;&lt;/script&gt;', $text->escapeHtml());
    }

    public function testReplace()
    {
        $text = new Text('foobar');
        $this->assertEquals('fooBAR', $text->replace('/bar/', 'BAR'));

        $text = new Text('foobar');
        $text->replace('/bar/', function (Text $bar) {
            return $bar->upper();
        });
        $this->assertEquals('fooBAR', $text);
    }

    public function testReplaceString()
    {
        $text = new Text('foobar');
        $this->assertEquals('fooBAR', $text->replaceString('bar', 'BAR'));
    }

    public function testIndent()
    {
        $text = new Text(
            "This is line1\n".
            "This is line2\r\n".
            "This is line3\n"
        );

        $text->indent();

        $expected =
            "    This is line1\n".
            "    This is line2\r\n".
            "    This is line3\n";

        $this->assertEquals($expected, $text);
    }

    public function testOutdent()
    {
        $text = new Text(
            "    This is line1\n".
            "   This is line2\r\n".
            "     This is line3\n"
        );

        $text->outdent();

        $expected =
            "This is line1\n".
            "This is line2\r\n".
            " This is line3\n";

        $this->assertEquals($expected, $text);
    }

    public function testDetab()
    {
        $text = new Text("12\t56");
        $this->assertEquals("12  56", $text->detab());
    }

    public function testIsEmpty()
    {
        $text = new Text();
        $this->assertTrue($text->isEmpty());

        $text = new Text(' ');
        $this->assertFalse($text->isEmpty());
    }

    public function testIsNumeric()
    {
        $text = new Text("13.5");
        $this->assertTrue($text->isNumeric());

        $text = new Text("zero");
        $this->assertFalse($text->isNumeric());
    }

    public function testMatch()
    {
        $text = new Text("1st2nd3rd");
        $this->assertTrue($text->match('/(\d)rd+/', $matches));
        $this->assertEquals(array("3rd", "3"), $matches);
    }

    public function testSplit()
    {
        $text = new Text('a,b,c');
        $this->assertEquals(array('a', 'b', 'c'), $text->split('/,/'));
    }

    public function testLines()
    {
        $text = new Text("1\n2\n3");
        $this->assertEquals(array("1\n", "2\n", "3"), $text->lines());
    }

    public function testChars()
    {
        $text = new Text("foo");
        $this->assertEquals(array('f', 'o', 'o'), $text->chars());
    }

    public function testEachLine()
    {
        $text = new Text(
            "This is line1\n".
            "This is line2\r\n".
            "This is line3"
        );

        $text->eachLine(function (Text $line, $index) {
            return $line->prepend(($index + 1) . ': ');
        });

        $expected =
            "1: This is line1\n".
            "2: This is line2\r\n".
            "3: This is line3";

        $this->assertEquals($expected, (string)$text);
    }

    public function testLength()
    {
        $text = new Text('12345');

        $this->assertEquals(5, $text->length());
    }

    public function testCountLines()
    {
        $text = new Text(
            "This is line1\n".
            "This is line2\r\n".
            "This is line3"
        );

        $this->assertEquals(3, $text->countLines());
    }

    public function testIndexOf()
    {
        $text = new Text("0123456789");

        $this->assertEquals(3, $text->indexOf('3'));
    }

    public function testDirectAccess()
    {
        $text = new Text('12345');

        $this->assertEquals('foobar', $text->setText('foobar')->getText());
        $this->assertInternalType('string', $text->getText());
    }

    public function testSerializable()
    {
        $text1 = new Text('foo');
        $serialized = serialize($text1);
        $text2 = unserialize($serialized);

        $this->assertEquals($text1, $text2);
    }

    public function testSave()
    {
        $this->expectOutputString('foo');

        $text = new Text('foo');
        $text->save('php://output');
    }

}
