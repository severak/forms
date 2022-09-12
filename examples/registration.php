<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">

</head>
<body>
<style>label { display: block; }</style>
<h1>Registration form</h1>
<?php
require "../vendor/autoload.php";

// registration form
$form = new severak\forms\form(['method'=>'POST']);
$form->field('username', ['label'=>'User name / URL', 'required'=>true]);
$form->field('email', ['label'=>'E-mail', 'type'=>'email', 'required'=>true]);
$form->field('password', ['label'=>'Password', 'type'=>'password', 'required'=>true]);
$form->field('password_again', ['label'=>'and again', 'type'=>'password', 'required'=>true]);
$form->field('terms_agreement', ['label'=>'I agree with terms of service', 'type'=>'checkbox']);
$form->field('register', ['label'=>'Register new account', 'type'=>'submit']);

$form->rule('username', function($name) {
    return preg_match('~^[a-z]([a-z0-9]{2,})$~', $name)===1;
}, 'Bad username format: 3 or more lower case letters and numbers allowed, must start with letter.');

$form->rule('password_again', function($password, $fields) {
    return $password==$fields['password'];
}, 'Must match previous password.');

$form->rule('terms_agreement', function($agreed){
    return !empty($agreed);
}, 'You cannot use our service without terms agreement.');

if ($_SERVER['REQUEST_METHOD']=='POST' && $form->fill($_POST) && $form->validate()) {
    // form was successfully sent and data were valid
    if ($databaseInsertFailed) {
        // you can also manually error messages AFTER validation, e.g. when something went wrong with databse
        $form->error('register', 'Something went wrong while adding you. Please try again later.');
    }
}

// display form as HTML
echo $form;

?>
</body>
</html>