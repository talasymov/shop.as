<?php
IncludesFn::printHeader("Register", "grey");

$bodyText = <<<EOF
<div class="container-fluid register">
    <div class="row">
        <div class="col-md-3 col-md-offset-1">
            <h3>Личная информация</h3>
            <span class="header-blue">Фамилия</span> <input class="register-surname design-input" value="1" />
            <span class="header-blue">Имя</span> <input class="register-name design-input" value="1" />
            <span class="header-blue">Отчество</span> <input class="register-patronymic design-input" value="1" />
            <span class="header-blue">День рождения</span> <input class="register-birth design-input" value="1" />
            <span class="header-blue">Пол</span> <input class="register-gender design-input" value="1" />
        </div>
        <div class="col-md-3">
            <h3>Данные для связи</h3>
            <span class="header-blue">Почта</span> <input class="register-email design-input" value="1@" />
            <span class="header-blue">Телефон</span> <input class="register-phone design-input" value="1" />
        </div>
        <div class="col-md-3">
            <h3>Данные для входа</h3>
            <span class="header-blue">Логин</span> <input class="register-login design-input" />
            <span class="header-blue">Пароль</span> <input class="register-password design-input" type="password" />
            <span class="header-blue">Повторить пароль</span> <input class="register-repeat-password design-input"  type="password" />
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <button class="register-button">Регистрация</button>
        </div>
    </div>
</div>
EOF;
