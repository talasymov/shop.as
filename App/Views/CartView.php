<?php
IncludesFn::printHeader("Cart", "grey");

if($data != false && is_array($data))
{
    $listProduct = '';
    $listIdCart = ShopFn::GetIdFromCart();

    $i = 0;

    foreach ($data as $key => $value)
    {
        $wholesale = R::getAll("SELECT * FROM Wholesale WHERE Wholesale_product = ?", [
            $value["ID_product"]
        ]);

        $price = $value["ProductPrice"];

        $wholesaleHtml = "";

        foreach ($wholesale as $subValue)
        {
            if($listIdCart[$value["ID_product"]]["count"] >= $subValue["Wholesale_count"])
            {
                $price = $subValue["Wholesale_price"];
            }
        }

        $propText = '';

        if(is_array($listIdCart[$value["ID_product"]]["property"]))
        {
            foreach ($listIdCart[$value["ID_product"]]["property"] as $subValue)
            {
                $prop = R::getRow("SELECT * FROM PropertiesValues
                INNER JOIN Properties ON Properties.PropertiesValues_id = PropertiesValues.Properties_id_value
                INNER JOIN PropertiesParent ON PropertiesParent.PropertiesGroup_id = Properties.PropertiesValues_category
                WHERE Properties_id = ? AND Properties_product = ?", [
                    $subValue,
                    $value["ID_product"]
                ]);

                $propText .= '<strong class="strong">' . $prop["PropertiesGroup_name"] . ':</strong> ' . $prop["PropertiesValues_name"] . '<br />';
            }
        }

        $listProduct .= '<tr>
            <td class="zoom-click ta-c"><img class="mini-img" src="' . $value["ProductImagesPreview"] . '" /></td>
            <td>' . $value["ProductName"] . '</td>
            <td>' . $propText . '</td>
            <td><input type="number" data-id="' . $value["ID_product"] . '" class="cart-one-change design-input" min="1" max="9999" value="' . $listIdCart[$value["ID_product"]]["count"] . '" /></td>
            <td>' . $price . ' грн</td>
            <td class="ta-c"><button class="delete-product-in-cart btn btn-default circle" data-id="' . $value["ID_product"] . '" data-toggle="tooltip" data-placement="left" title="Убрать из корзины"><i class="fa fa-times" aria-hidden="true"></i></button></td>
        </tr>';

        $i++;
    }

    $inCart = ShopFn::DrawProduct(ShopFn::GetProductsInCart(), 3, 4, "list");

    $bodyText = <<<EOF
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product">
                    <h2 class="ta-c">Корзина</h2>
                     <table class="table">
                        <thead class="strong">
                            <tr><th width="100">Изображение</th><th width="200">Название товара</th><th>Свойства</th><th width="140">Количество</th><th width="140">Стоимость</th><th width="100" class="ta-c">Управление</th></tr>
                        </thead>
                        <tbody>
                            {$listProduct}
                        </tbody>
                    </table>
                    <div class="center">
                        <hr />
                        <button class="btn btn-default circle save-cart-change" data-toggle="tooltip" title="Сохранить изменения"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                        <button class="btn btn-default circle clear-cart" data-toggle="tooltip" title="Очистить корзину"><i class="fa fa-trash" aria-hidden="true"></i></button>
                        <a href="/checkout/"><button class="btn btn-default circle" data-toggle="tooltip" title="Оформление заказа"><i class="fa fa-check" aria-hidden="true"></i></button></a>
                        <button class="btn btn-default circle buy-in-click" data-toggle="tooltip" title="Купить в один клик"><i class="fa fa-mouse-pointer" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-design-bg">
        <div class="modal-design">
            <button class="delete-from-cart close-btn close-modal-design"><i class="fa fa-times"></i></button>
            <h2 class="ta-c">Купить в один клик</h2>
            <br />
            <br />
            <div class="scroll-div meScroll-mini">
                <div class="container-fluid">
                    {$inCart}
                </div>
            </div>
            <h2 class="ta-c">Информация о Вас:</h2>
            <div class="ta-c margin-bottom bold-input"><br />
                <input class="design-input one-click-name" data-placeholder="Имя" />
                <input class="design-input one-click-phone" data-placeholder="Телефон" />
                <input class="design-input one-click-city" data-placeholder="Город" /><br /><br />
                <a href="/cart/"><button class="btn btn-default circle big" data-toggle="tooltip" title="Редактировать корзину"><i class="fa fa-shopping-basket"></i></button></a>
                <button class="btn btn-default circle big buyinclick" data-toggle="tooltip" title="Купить"><i class="fa fa-check"></i></button>
                <button class="btn btn-default circle big close-modal-design" data-toggle="tooltip" title="Продолжить покупки"><i class="fa fa-arrow-right"></i></button>
            </div>
        </div>
    </div>
EOF;
}
else
{
    $bodyText = <<<EOF
    <div class="clear-cart-bg">
        <span>В корзине пусто <i class="fa fa-frown-o" aria-hidden="true"></i></span>
        <i class="fa fa-shopping-basket"></i>
    </div>
EOF;

}
