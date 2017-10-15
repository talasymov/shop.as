<?php
require_once (DIR_INCLUDE . "Header.php");

$idUser = BF::ReturnInfoUser(BF::idUser);

$page = <<<EOF
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="menu-top information-page click-menu">
                <div class="menu-home">
                    <strong><i class="fa fa-bars" aria-hidden="true"></i> Профиль № {$idUser}</strong>
                    <ul>
                        <li><a href="/user/">Главная</a></li>
                        <li><a href="/user/orders">Мои заказы</a></li>
                        <li><a href="/user/wish">Мои желания</a></li>
                        <li><a href="/user/reviews">Мои отзывы</a></li>
                        <li><a href="/user/information">Информация</a></li>
                        <li><a href="/user/partner">Партнерская программа</a></li>
                    </ul>
                    <div class="center"><button class="quit-user btn btn-info">Выйти из магазина <i class="fa fa-sign-out" aria-hidden="true"></i></button></div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="product">
                {$bodyText}
            </div>
        </div>
    </div>
</div>
EOF;

print($page);

require_once (DIR_INCLUDE . "Footer.php");
