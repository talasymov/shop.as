<?php
$pageName = "Заказ № " . $data["group"]["OrdersGroup_id"];

IncludesFn::printHeader($pageName, "grey");

$orders = ShopFn::DrawProduct($data["products"], 3, 4);

$status = AuxiliaryFn::ArrayToSelect(R::getAll("SELECT * FROM OrdersStatus"), "select-status design-input", "OrdersStatus_id", "OrdersStatus_name", "Выберите статус", $data["group"]["OrdersGroup_status"]);

$payment = R::getRow("SELECT PaymentTypeName FROM PaymentType WHERE ID_paymentType = ?", [
    $data["group"]["OrdersGroup_payment"]
]);

$delivery = R::getRow("SELECT * FROM Address WHERE ID_address = ?", [
    $data["group"]["OrdersGroup_delivery"]
]);

$bodyText = <<<EOF
<div class="container-fluid header-based">
    <div class="row">
        <div class="col-md-12 ta-r">
            <div class="manage-buttons">
                <h1>{$pageName}</h1>
                <a href="/dashboard/orders"><button class="btn btn-default circle" data-toggle="tooltip" data-placement="bottom" data-original-title="Вернуться назад"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
                <a href="/dashboard/contacts/{$data["group"]["Users_id"]}"><button class="btn btn-default circle" data-toggle="tooltip" data-placement="bottom" data-original-title="Профиль пользователя"><i class="fa fa-user" aria-hidden="true"></i></button></a>
            </div>
        </div>
    </div>
    {$orders}
    <div class="row">
        <div class="col-md-12 ta-r">
            <div class="manage-buttons">
                <h1>Информация о заказе</h1>
                <button class="btn btn-default save-one-order circle" data-id="{$data["group"]["OrdersGroup_id"]}" data-toggle="tooltip" data-placement="left" data-original-title="Сохранить изменения"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
            </div>
        </div>
        <div class="col-md-12">
            <table class="table">
                <tbody>
                    <tr><th class="strong" width="200">Номер заказа</th><td>{$data["group"]["OrdersGroup_id"]}</td></tr>
                    <tr><th class="strong">Название заказа</th><td>{$data["group"]["OrdersGroup_name"]}</td></tr>
                    <tr><th class="strong">Сумма</th><td>{$data["group"]["OrdersGroup_sum"]}</td></tr>
                    <tr><th class="strong">Доставка</th><td>{$delivery["Country"]}, {$delivery["Region"]}, {$delivery["City"]}, {$delivery["Street"]}, {$delivery["Build_numb"]}, {$delivery["Porch"]}, {$delivery["Apartment"]}</td></tr>
                    <tr><th class="strong">Способ оплаты</th><td>{$payment["PaymentTypeName"]}</td></tr>
                    <tr><th class="strong">Дата добавления</th><td>{$data["group"]["OrdersGroup_date"]}</td></tr>
                    <tr><th class="strong">Комментарий</th><td>{$data["group"]["OrdersGroup_comment"]}</td></tr>
                    <tr><th class="strong">Статус</th><td>{$status}</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
EOF;
