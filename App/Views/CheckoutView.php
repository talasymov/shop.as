<?php
IncludesFn::printHeader("Checkout", "grey");

if($data != false && is_array($data))
{
    $listIdCart = ShopFn::GetIdFromCart();
    $sum = number_format(ShopFn::GetCartSum(), 0, "", " ");
    $listProduct = '';
    $i = 0;

    foreach ($data as $key => $value)
    {
        $wholesale = R::getAll("SELECT * FROM Wholesale WHERE Wholesale_product = ?", [
            $value["ID_product"]
        ]);

        $price = $value["ProductPrice"];

        foreach ($wholesale as $subValue)
        {
            if($listIdCart[$value["ID_product"]]["count"] >= $subValue["Wholesale_count"])
            {
                $price = $subValue["Wholesale_price"];
            }
        }

        $price = $listIdCart[$value["ID_product"]]["count"] * $price;

        $listProduct .= "<div class='list-in-cart'>" . $listIdCart[$value["ID_product"]]["count"] . " x " . $value["ProductName"] . "<span class='price'><span class='strong'>" . number_format( $price, 0, "", " ") . '</span> грн</span></div>';

        $i++;
    }

    $buttons = "";
    $buttonsCheckOut = "";

    if(BF::IfUserInSystem() == true)
    {
        $selectDelivery = AuxiliaryFn::ArrayToSelect(ShopFn::GetListDelivery(), "design-input checkout-delivery", "ID_address", "Name", "Выберите");
        $selectPayment = AuxiliaryFn::ArrayToSelect(ShopFn::GetListPayment(), "design-input checkout-payment", "ID_paymentType", "PaymentTypeName", "Выберите");

        $buttons = <<<EOF
        <span class="header-blue">Название заказа</span> <input class="checkout-name design-input" value="1" />
        <span class="header-blue">Способ доставки</span> {$selectDelivery}
        <span class="header-blue">Способ оплаты</span> {$selectPayment}
        <span class="header-blue">Комментарий</span> <textarea class="checkout-comment design-textarea"></textarea>
EOF;

        $buttonsCheckOut = '<button class="btn btn-success checkout-confirm">Оформить заказ</button>';
    }
    else
    {
        $buttons = 'Извините, но для оформления заказа <a href="#" class="login-show">Войдите</a> или <a href="/register/">Зарегистрируйтесь</a>!';
    }
}

$bodyText = <<<EOF
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-7 col-md-offset-1">
                <div class="product big-padding">
                    <h2 class="ta-c">Оформление заказа</h2>
                    {$buttons}
                </div>
            </div>
            <div class="col-md-3">
                <div class="news-request product in-cart">
                    <strong>Итого:</strong>
                    {$listProduct}
                    <strong>К оплате <b>{$sum}</b> грн</strong>
                    {$buttonsCheckOut}
                </div>
            </div>
        </div>
    </div>
EOF;
