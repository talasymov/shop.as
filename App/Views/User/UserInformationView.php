<?php
IncludesFn::printHeader("User Information", "grey");

//AuxiliaryFn::StylePrint(BF::CheckUserInSystem());
//AuxiliaryFn::StylePrint(BF::ReturnInfoUser(BF::idUser));
//AuxiliaryFn::StylePrint($data);

if(count($data["delivery"]) != 0 && is_array($data["delivery"]))
{
    $listDelivery = '';
    $i = 0;

    foreach ($data["delivery"] as $key => $value)
    {
        $listDelivery .= '<tr>
            <td>' . $value["Country"] . '</td>
            <td>' . $value["Region"] . '</td>
            <td>' . $value["City"] . '</td>
            <td>' . $value["Street"] . '</td>
            <td>' . $value["Build_numb"] . '</td>
            <td>' . $value["Porch"] . '</td>
            <td>' . $value["Apartment"] . '</td>
            <td><button class="btn btn-default delete-address" data-id="' . $value["ID_address"] . '"><i class="fa fa-times" aria-hidden="true"></i></button></td>
        </tr>';

        $i++;
    }
}

$bodyText = <<<EOF
    <h2 class="ta-l strong">Информация</h2>
    <input class="idUser" type="hidden" value="{$data["user"]["Users_id"]}">
    <div class="container-fluid register">
        <div class="row">
            <div class="col-md-4">
                <h3>Личная информация</h3>
                <span class="header-blue">Фамилия</span> <input class="register-surname design-input" value="{$data["user"]["Users_surname"]}" />
                <span class="header-blue">Имя</span> <input class="register-name design-input" value="{$data["user"]["Users_name"]}" />
                <span class="header-blue">Отчество</span> <input class="register-patronymic design-input" value="{$data["user"]["Users_patronymic"]}" />
                <span class="header-blue">День рождения</span> <input class="register-birth design-input" value="{$data["user"]["Users_birth"]}" />
                <span class="header-blue">Пол</span> <input class="register-gender design-input" value="{$data["user"]["Users_gender"]}" />
            </div>
            <div class="col-md-4">
                <h3>Данные для связи</h3>
                <span class="header-blue">Почта</span> <input class="register-email design-input" value="{$data["user"]["Users_email"]}" />
                <span class="header-blue">Телефон</span> <input class="register-phone design-input" value="{$data["user"]["Users_phone"]}" />
            </div>
            <div class="col-md-4">
                <h3>Данные для входа</h3>
                <span class="header-blue">Логин</span> <input class="register-login design-input" />
                <span class="header-blue">Пароль</span> <input class="register-password design-input" type="password" />
                <span class="header-blue">Повторить пароль</span> <input class="register-repeat-password design-input"  type="password" /><br />
                <br /><button class="update-info-user-button btn btn-info">Сохранить</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <br /><button class="update-register-button btn btn-info">Сохранить</button>
            </div>
        </div>
    </div>
    <div class="container-fluid register">
        <div class="row">
            <div class="col-md-12">
            <h2 class="strong">Доставка</h2><br />
            <button class="add-address btn btn-info">Добавить адрес</button><br /><br />
                <table class="table">
                    <thead class="strong">
                        <tr>
                            <th>Страна</th>
                            <th>Область</th>
                            <th>Город</th>
                            <th>Улица</th>
                            <th>Номер дома</th>
                            <th>Подъезд</th>
                            <th>Квартира</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {$listDelivery}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
EOF;
