<?php
/*
echo <<<EOL
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>untitled</title>
    </head>
    <body>
        <p>
EOL;*/

header('Content-Type: text/plain');
error_reporting(E_ALL ^ E_NOTICE);
function __autoload($n) {
    require_once('./' . $n . '.php');
}

echo 'test online Github 22';
echo 'test online Github 22';

/* SELECT 

$foo = new Select;
$foo->setDebugState(true);

$foo->setOptgroup(array('label' => 'test'));
$foo->getOptgroup(0)->addOptions(
                            array(
                                new Option,
                                new Option(null, 'FOO'),
                                new Option(null, 'LOO2L'),
                                new Option(null, 'LOO3L'),
                                new Option(null, 'LOO4L')
                            )
                            //,'prepend'
                        );

//$foo->setIndentState(0);
$foo->getOptgroup(0)->setAttribute('label', 'test2');

$foo->getOptgroup(0)->unsetOption(2);
echo $foo->getOptgroup(0)->getOption(2)->caption;

$opt = $foo->getOptgroup(0)->getAllOptions();

foreach ($opt as $o) {
    $o->label ='Test';
}

$foo->addOptions(
            array(
                new Option,
                new Option
            )
            ,'prepend'
        );
        
        
//$foo->getOptgroup(2)->getOption(0)->setCaption("foo")
//                                  ->setAttribute('value', 'loooool');
//$foo->setOption(new Option);

$bar = clone $foo;
$bar->setDebugState(true);
//$bar->resetOptions();
//print_r($bar->getContent());


/* OPTION *

$option = new Option;
assert($option->getOutput() == '<option dir="ltr"></option>');

$option2 = new Option(
                    array(
                        'value'     => 'foo',
                        'selected'  => 'selected',
                        'dir'       => 'rtl'
                    ),
                    'FooBaz'
                );

assert($option2->getOutput() == '<option dir="rtl" selected="selected" value="foo">FooBaz</option>');

$select = new Select;
$select->setIndentState(false)
       ->addOptions(
                array(
                    new Option,
                    $option,
                    new Option(null, 'Foo'),
                    clone $option2,
                    array(
                        'value' => 'test'
                        )
                ),
                'prepend'
            );

assert($select->getOutput() == '<select dir="ltr"><option dir="ltr" value="test"></option><option dir="rtl" selected="selected" value="foo">FooBaz</option><option dir="ltr">Foo</option><option dir="ltr"></option><option dir="ltr"></option></select>');

$select->getOption(4)->caption = 'Bar';

assert($select->getOption(4)->getCaption() == 'Bar');

$select->getOption(1)->caption = null;

assert(!isset($select->getOption(1)->caption));

$select->unsetOption(3, false);

assert(array_keys($select->getContent()) == array(0, 1, 2, 4));
assert($select->getOutput() == '<select dir="ltr"><option dir="ltr" value="test"></option><option dir="rtl" selected="selected" value="foo"></option><option dir="ltr">Foo</option><option dir="ltr">Bar</option></select>');
assert($select->getOption(4)->getOutput() == '<option dir="ltr">Bar</option>');

$select->reIndex();
assert(array_keys($select->getContent()) == array(0, 1, 2, 3));
assert($select->getOption(3)->getOutput() == '<option dir="ltr">Bar</option>');

*/

/* OPTGROUP *

$optgroup = new Optgroup;

// Sets attribute values
$optgroup->setAttributes(
                    array(
                        'style'     =>  'color: red;',
                        'xml:lang'  =>  'en-UK',
                        'label'     =>  'Foobar'
                    )
                );

assert(is_object($optgroup) && $optgroup instanceof Optgroup);

$select = new Select;
$select->indentState = false;
$config = array(
            'Country #1' => array(
                                'city #1',
                                'city #2',
                                'city #3',
                                'city #x'
                            ),
            'Country #2' => array(
                                'city #1',
                                'city #2',
                                'city #3',
                                'city #x'
                            ),
            'Country #x' => array(
                                'city #1',
                                'city #2',
                                'city #3',
                                'city #x'
                            )
        );

while (list($country, $cities) = each($config)) {
    $optgroup = new Optgroup(
                            array(
                                'label' => $country
                            )
                        );
    while (list($cityId, $cityName) = each($cities)) {
        $optgroup->addOption(
                            new Option(
                                    array(
                                        'value' => $cityId
                                    ),
                                    $cityName
                                )
                    );
    }
    $select->addOptgroup($optgroup);
}

//echo $select;
assert($select->getOutput() == '<select dir="ltr"><optgroup dir="ltr" label="Country #1"><option dir="ltr" value="0">city #1</option><option dir="ltr" value="1">city #2</option><option dir="ltr" value="2">city #3</option><option dir="ltr" value="3">city #x</option></optgroup><optgroup dir="ltr" label="Country #2"><option dir="ltr" value="0">city #1</option><option dir="ltr" value="1">city #2</option><option dir="ltr" value="2">city #3</option><option dir="ltr" value="3">city #x</option></optgroup><optgroup dir="ltr" label="Country #x"><option dir="ltr" value="0">city #1</option><option dir="ltr" value="1">city #2</option><option dir="ltr" value="2">city #3</option><option dir="ltr" value="3">city #x</option></optgroup></select>');

$clone = clone $select;

$clone->unsetOptgroup(0);

assert($select->getOutput() != $clone->getOutput());

*/

/* TEXTAREA *

$textarea = new Textarea;

assert(is_object($textarea) && $textarea instanceof Textarea);
assert($textarea->getOutput() == '<textarea cols="40" dir="ltr" rows="6"></textarea>');

$textarea2 = new Textarea(
                        array(
                            'name'      => 'foobar',
                            'xml:lang'  => 'fr-FR',
                            'id'        => 'txt',
                            'rows'      => 12
                        ),
                        'Hello world!'
                    );

assert($textarea2->getOutput() == '<textarea cols="40" dir="ltr" id="txt" name="foobar" rows="12" xml:lang="fr-FR">Hello world!</textarea>');

$textarea3 = clone $textarea2;

assert($textarea3 == $textarea2);

$textarea3->setDebugState(true)
          ->setAttribute('style', 'border: 1px #333 dotted;')
          ->unsetAttribute('xml:lang')
          ->setContent('Stylish textarea!');

assert($textarea3->getOutput() == '<textarea cols="40" dir="ltr" id="txt" name="foobar" rows="12" style="border: 1px #333 dotted;">Stylish textarea!</textarea>');

*/


/* INPUT *

$input = new Input;

assert(is_object($input) && $input instanceof Input && $input->getOutput() == '<input dir="ltr" type="text" value="" />');

$checkbox = new Input(
                    array(
                        'type'      => 'checkbox',
                        'checked'   => 'checked',
                        'name'      => 'foobar',
                        'onclick'   => 'alert(this.name + \' updated!\');'
                    )
                );

assert($checkbox->getAttribute('name') == 'foobar');
assert($checkbox->unsetAttribute('checked') == $checkbox);
assert($checkbox->getOutput() == '<input dir="ltr" name="foobar" type="checkbox" value="" onclick="alert(this.name + \' updated!\');" />');

*/

echo $foo . '

<br /><br />

' . $bar;


//echo $bar;
/*
echo <<<EOL
                </p>
            </body>
        </html>
EOL;
*/
?>