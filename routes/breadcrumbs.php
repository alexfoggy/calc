<?php
//Документация пакета https://github.com/davejamesmiller/laravel-breadcrumbs
//Метод parent отвечает за назначение родителя
//Метод push отвечает за размемещение имени и url в шаблоне

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

Breadcrumbs::for ('/', function ($trail) {
    $trail->push(ShowLabelById(197, LANG_ID), '/');
});

//Вывод крошек для текстовых страниц, которые относятся к parent_menu,
//так же исключаем те страницы которые не относятся к parent_menu,
//что бы избежать ошибку (Breadcrumb name "catalog" has already been registered)
if (Request::segment(2) && !Request::segment(3) && Request::segment(2) != 'cart' && Request::segment(2) != 'wish-list' && Request::segment(2) != 'search' && Request::segment(2) != 'catalog') {
    Breadcrumbs::for (Request::segment(2), function ($trail, $parent_menu) {
        $trail->parent('/');
        $trail->push($parent_menu->itemByLang->name, Request::segment(2));
    });
}

Breadcrumbs::for ('cart', function ($trail, $lang_id) {
    $trail->parent('/');
    $trail->push(ShowLabelById(3, $lang_id), 'cart');
});

Breadcrumbs::for ('fav-list', function ($trail, $lang_id) {
    $trail->parent('/');
        $trail->push(ShowLabelById(219,$lang_id), 'favorite');
});

Breadcrumbs::for ('compare', function ($trail, $lang_id) {
    $trail->parent('/');
    $trail->push(ShowLabelById(8, $lang_id), 'wish-list');
});

Breadcrumbs::for ('order', function ($trail, $lang_id) {
    $trail->parent('/');
    $trail->push(ShowLabelById(7, $lang_id), 'order');
});

Breadcrumbs::for ('search', function ($trail, $lang_id) {
    $trail->parent('/');
    $trail->push(ShowLabelById(196, $lang_id), 'search');
});

Breadcrumbs::for ('calcs', function ($trail) {
    $trail->parent('/');
    $trail->push(ShowLabelById(193,LANG_ID), 'calculator');
});

Breadcrumbs::for ('calc_parent', function ($trail, $calcSubject) {
    $trail->parent('calcs');
    $trail->push($calcSubject->itemByLang->name ?? '', 'calculator/'.$calcSubject->alias);
});

Breadcrumbs::for ('calc_item', function ($trail, $calcSubject, $calcId) {
    $trail->parent('calc_parent', $calcSubject);
    $trail->push($calcId->itemByLang->name ?? '', $calcId->alias);
});

/*Breadcrumbs::for ('goods-subject', function ($trail, $goods_subject) {
    $trail->parent('catalog');

    GetMainParent('goods_subject',$goods_subject->p_id,Request::segment(1) == 'ru' ? 3 : (Request::segment(1) == 'ro' ? 2 : ''),$p_list);

    $parent_list = [];
    if(!is_null($p_list))
        $parent_list = array_reverse($p_list);

    if ($parent_list) {
        foreach ($parent_list as $key => $one_parent_item) {
            $trail->push($one_parent_item->name, 'catalog/' . $one_parent_item->alias);
        }
    }

    $trail->push($goods_subject->name, $goods_subject->alias);
});

Breadcrumbs::for ('goods-item', function ($trail, $goods_subject, $goods_item) {

    $trail->parent('catalog');

    GetMainParent('goods_subject',$goods_subject->goods_subject_id,Request::segment(1) == 'ru' ? 3 : (Request::segment(1) == 'ro' ? 2 : ''),$p_list);

    $parent_list = [];
    if(!is_null($p_list))
        $parent_list = array_reverse($p_list);

    if ($parent_list) {
        foreach ($parent_list as $key => $one_parent_item) {
            $trail->push($one_parent_item->name, 'catalog/' . $one_parent_item->alias);
        }
    }

    $trail->push($goods_item->name);

});*/

//Пример вывода нескольких уровней
/*Breadcrumbs::for ('catalog-item', function ($trail, $gallery_subject) {
    $trail->parent('catalog', 'catalog');
    $trail->push($gallery_subject->name, 'catalog/'. $gallery_subject->alias);
});


//Breadcrumbs for final_page and if has parent
Breadcrumbs::for ('item', function ($trail, $catalog_item) {

    $trail->parent('catalog');

    GetParentList('gallery_subject', $catalog_item->p_id, $p_list, 0, 2);
    $parent_list = array_reverse($p_list);

    if ($parent_list) {
        foreach ($parent_list as $key => $one_parent_item) {
            if ($key == 1)
                $trail->push($one_parent_item->name, 'catalog/' . $one_parent_item->alias);
        }
    }

    $trail->push($catalog_item->name);
});*/
