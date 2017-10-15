<?php
//$menu = BF::GenerateList(DataBase::GetMenu(), '<a href="?">?</a>', ["Link", "Name"]);
$contacts = BF::GenerateList(DataBase::GetContacts(), '<span class="one-phone"><i class="fa fa-phone" aria-hidden="true"></i> ?</span>', ["phones_text"]);
$countInCart = ShopFn::GetCountInCart();
$countWish = ShopFn::GetCountWish();
$countBalance = ShopFn::GetCountInBalance();

$productsInCart = <<<EOF
<span>В корзине пусто</span>
<i class="fa fa-shopping-basket"></i>
EOF;

$productsInBalance = <<<EOF
<span>Тут нечего сравнивать</span>
<i class="fa fa-balance-scale"></i>
EOF;

$productsInWish = <<<EOF
<span>Желаний нет</span>
<i class="fa fa-heart-o"></i>
EOF;

$classCart = "false";
$classWish = "false";
$classBalance = "false";

if($countInCart > 0)
{
    $classCart = "active";

    $productsInCart = BF::GenerateList(ShopFn::GetProductsInCart(), '<div class="mini-product clearfix"><img src="?"/><span>?</span><button class="delete-from-cart delete-product-in-cart" data-id="?"><i class="fa fa-times"></i></button></div>', ["ProductImagesPreview", "ProductName", "ID_product"]);

    $productsInCart = <<<EOF
    <strong>Корзина</strong>
    <hr />
        <div>
            {$productsInCart}
        </div>
    <hr />
    <a href="/cart/" class="go-to">Перейти в корзину</a>
EOF;
}

if($countBalance > 0)
{
    $classBalance = "active";

    $productsInBalance = BF::GenerateList(ShopFn::GetProductsInBalance(), '<div class="mini-product clearfix"><img src="?"/><span>?</span><button class="delete-from-cart delete-from-balance" data-id="?"><i class="fa fa-times"></i></button></div>', ["ProductImagesPreview", "ProductName", "ID_product"]);

    $productsInBalance = <<<EOF
    <strong>Сравнение</strong>
    <hr />
    {$productsInBalance}
    <hr />
    <a href="/balance/" class="go-to">Перейти к сравнению</a>
EOF;
}

if($countWish > 0)
{
    $classWish = "active";

    $productsInWish = BF::GenerateList(ShopFn::GetWishProducts(), '<div class="mini-product clearfix"><img src="?"/><span>?</span><button class="delete-from-cart delete-product-from-wish" data-id="?"><i class="fa fa-times"></i></button></div>', ["ProductImagesPreview", "ProductName", "ID_product"]);

    $productsInWish = <<<EOF
    <strong>Мои желания</strong>
    <hr />
    {$productsInWish}
    <hr />
    <a href="/user/wish" class="go-to">Мои желания</a>
EOF;
}

if(BF::IfUserInSystem() == false)
{
    $buttotUserInfo = <<<EOF
    <strong>Вход в личный кабинет</strong>
    <span class="header-login">Логин</span>
    <input class="login-input" type="email" name="email">
    <span class="header-login">Пароль</span>
    <input class="password-input" type="password" name="password">
    <a href="" class="forgot">Забыли пароль?</a>
    <button class="btn-login">Войти</button>
    <span class="header-span">Нет учетной записи?</span>
    <a href="/register/">Регистрация</a>
    <hr />
    <strong>Вход через соц сети</strong>
    <script src="//ulogin.ru/js/ulogin.js"></script>
    <div id="uLogin" data-ulogin="display=panel;theme=flat;fields=first_name,last_name;providers=vkontakte,facebook,google,odnoklassniki;hidden=;redirect_uri=http%3A%2F%2Fstore.sweane%2Fauth%2F;mobilebuttons=0;"></div>
EOF;
}
else if(BF::ReturnInfoUser(BF::permissionUser) == 777)
{
    $activeUserButton = "active";

    $buttotUserInfo = <<<EOF
    <ul>
        <li><a href="/dashboard/">Статистика</a></li>
        <li><a href="/dashboard/orders">Заказы</a></li>
        <li><a href="/dashboard/products">Товары</a></li>
        <li><a href="/dashboard/category">Категории товара</a></li>
        <li><a href="/dashboard/characteristics">Характеристики товара</a></li>
        <li><a href="/dashboard/news">Новости</a></li>
        <li><a href="/dashboard/news">Контакты</a></li>
    </ul>
    <button class="quit-user">Выйти</button>
EOF;
}
else {
    $activeUserButton = "active";

    $buttotUserInfo = <<<EOF
    <ul>
        <li><a href="/user/information">Моя информация</a></li>
        <li><a href="/user/orders">Мои заказы</a></li>
        <li><a href="/user/wish">Мои желания</a></li>
    </ul>
    <button class="quit-user">Выйти</button>
EOF;
}

$Header = <<<EOF
<div class="container-fluid menu-inline">
    <div class="row">
        <div class="col-md-7">
            <div class="menu-top">
                <a href="/delivery/">Доставка и оплата</a>
                <a href="/aboutus/">О нас</a>
                <a href="/contacts/">Контакты</a>
                <a href="/faq/">FAQ</a>
                <a class="blue" href="http://champ.in.ua/">Рекламное агентство</a>
            </div>
        </div>
        <div class="col-md-5 ta-r">
            <!--<div class="dropdown">
            <button class="btn btn-default" type="button" data-toggle="dropdown" aria-expanded="true">
            <span class="text">UAH</span></button>
            <ul class="dropdown-menu dropdown-menu-right shadow-none change-currency">
                <li><a data-id="1">UAH</a></li>
                <li><a data-id="2">RUB</a></li>
                <li><a data-id="3">USD</a></li>
            </ul>
            </div>-->
            <button class="callback-phone clear-button"><i class="fa fa-phone" aria-hidden="true"></i> Обратный звонок</button>
        </div>
    </div>
</div>

<div class="container-fluid header-info">
    <div class="row">
        <div class="col-md-12">
            <!--<a href="/"><img src="/Images/Home/logo.svg" /></a>-->
            <a href="/"><img src="/Images/Icons/new-logo-none-02.svg" /></a>
            <div class="mini-bars">
                <button><i class="fa fa-bars" aria-hidden="true"></i></button>
                {$menu}
            </div>
            <div class="search-top">
                <form action="/search/" method="get">
                    <input type="text" class="search-top-input" name="query" data-placeholder="Что будем искать?">
                    <button class="search-top-btn clear-button"><i class="fa fa-search" aria-hidden="true"></i></button>
                </form>
            </div>
            <div class="top-phones">
                {$contacts}
            </div>
            <div class="buttons-parent">
                <span class="top-div-balance" data-toggle="tooltip" data-placement="bottom" title="Сравнение">
                    <button class="top-button clear-button"><span class="count {$classBalance}">{$countBalance}</span><i class="fa fa-balance-scale" aria-hidden="true"></i></button>
                    <div class="drop-down-top balance {$classBalance}">
                        {$productsInBalance}
                    </div>
                </span>
                <span class="top-div-wish" data-toggle="tooltip" data-placement="bottom" title="Мои желания">
                    <button class="top-button clear-button"><span class="count {$classWish}">{$countWish}</span><i class="fa fa-heart-o" aria-hidden="true"></i></button>
                    <div class="drop-down-top wish {$classWish}">
                        {$productsInWish}
                    </div>
                </span>
                <span class="top-div-cart" data-toggle="tooltip" data-placement="bottom" title="Корзина">
                    <button class="top-button clear-button"><span class="count {$classCart}">{$countInCart}</span><i class="fa fa-shopping-basket" aria-hidden="true"></i></button>
                    <div class="drop-down-top cart {$classCart}">                    
                        {$productsInCart}
                    </div>
                </span>
                <button data-toggle="tooltip" data-placement="left" title="Мой профиль" class="top-button clear-button login-show {$activeUserButton}"><i class="fa fa-user-o" aria-hidden="true"></i></button>
                <div class="drop-user ta-c">
                    {$buttotUserInfo}
                </div>
            </div>
        </div>
    </div>
</div>
<button class="go-top-button"><i class="fa fa-arrow-up"></i></button>
EOF;

print($Header);
