<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Supported input types</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">

</head>
<body>
<style>label { display: block; }</style>
<h1>Supported input types</h1>
<?php
require "../vendor/autoload.php";

// see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input

$form = new \severak\forms\form(['method'=>'post']);
$form->field('text', ['type'=>'text']);
$form->field('checkbox', ['type'=>'checkbox']);
$form->field('color', ['type'=>'color']);
$form->field('date', ['type'=>'date']);
$form->field('email', ['type'=>'email']);
$form->field('hidden', ['type'=>'hidden']);
$form->field('number', ['type'=>'number']);
$form->field('password', ['type'=>'password']);
$form->field('range', ['type'=>'range']);
$form->field('tel', ['type'=>'tel']);
$form->field('time', ['type'=>'time']);
$form->field('url', ['type'=>'url']);
$form->field('select', ['type'=>'select', 'options'=>['a', 'b', 'c']]);
$form->field('textarea', ['type'=>'textarea']);
$form->field('submit', ['type'=>'submit']);
$form->field('reset', ['type'=>'reset']);

if ($_SERVER['REQUEST_METHOD']=='POST') {
    $form->fill($_POST);
}

echo $form;

?>
</body>
</html>
