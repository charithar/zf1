<?php

include './tests/TestHelper.php';

include './vendor/autoload.php';

$v = new Zend_Validate_Date();
/*var_dump($v->isValid('2007-01-01'));

$valuesExpected = array(
    '2007-01-01' => true,
    '2007-02-28' => true,
    '2007-02-29' => false,
    '2008-02-29' => true,
    '2007-02-30' => false,
    '2007-02-99' => false,
    '9999-99-99' => false,
    0            => false,
    999999999999 => false,
    'Jan 1 2007' => false,
    'asdasda'    => false,
    'sdgsdg'     => false
);
foreach ($valuesExpected as $input => $result) {
    if ($v->isValid($input) != $result) {
        var_dump($input);
    }
}*/

$dateValid = '2007-08-02';
$charactersTrailing = 'something';
var_dump($v->isValid($dateValid));
var_dump($v->isValid($dateValid . $charactersTrailing));

