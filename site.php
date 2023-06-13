<?php

use VirtualStore\Page;
use VirtualStore\Models\Category;

function renderIndex($vars, $container)
{
    $page = $container->get(VirtualStore\Page::class);
    $page->renderPage('index');
}

function category($vars, $container)
{
    $category = $container->get(VirtualStore\Models\Category::class);
    $category->get((int) $vars['idcategory']);
    $page = $container->get(VirtualStore\Page::class);
    $page->renderPage('category', ['category' => $category->getValues(), 'products' => []]);

}