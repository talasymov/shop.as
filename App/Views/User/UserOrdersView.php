<?php
IncludesFn::printHeader("Orders", "grey");

$listOrders = '';
$i = 0;

foreach ($data as $key => $value)
{
    $listOrders .= '<tr><td>' . $value["OrdersGroup_id"] . '</td><td>' . $value["OrdersGroup_name"] . '</td><td>' . $value["OrdersGroup_sum"] . '</td><td>' . $value["OrdersGroup_date"] . '</td><td>' . $value["OrdersStatus_name"] . '</td><td><a href="/user/orders/' . $value["OrdersGroup_id"] . '">Просмотр</a></td></tr>';

    $i++;
}

$bodyText = <<<EOF
<h2 class="ta-l strong">Мои заказы</h2><br />
 <table class="table">
    <thead>
        <tr class="strong"><th>Номер заказа</th><th>Название заказа</th><th>Сумма</th><th>Дата добавления</th><th>Статус</th><th></th></tr>
    </thead>
    <tbody>
        {$listOrders}
    </tbody>
 </table>
EOF;
