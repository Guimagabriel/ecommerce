<?php

function adminForgot($vars, $container)
{
    $page = $container->get(VirtualStore\PageAdmin::class);
    $page->setOptions(false);
    $page->renderPage('forgot');
}

function adminPostForgot($vars, $container)
{
    $user = VirtualStore\Models\User::getForgot($_POST['email']);
}