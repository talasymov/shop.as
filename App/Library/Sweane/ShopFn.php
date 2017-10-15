<?php
class ShopFn
{
    const publishedArray = [
        0 => "Не опубликован",
        1 => "Опубликован"
    ];

    public static function GetCountInCart()
    {
        if (ShopFn::GetIdFromCart() != false) {
            return count(ShopFn::GetIdFromCart());
        }

        return 0;
    }

    public static function GetCountInBalance()
    {
        if (ShopFn::GetIdFromBalance() != false) {
            return count(ShopFn::GetIdFromBalance());
        }

        return 0;
    }

    public static function GetCountWish()
    {
        $count = R::getRow("SELECT COUNT(*) as Count FROM Wish WHERE Wish_user = ?", [
            BF::ReturnInfoUser(BF::idUser)
        ]);

        return $count["Count"];
    }

    public static function ClearCart()
    {
        unset($_SESSION["cartProducts"]);

        return true;
    }

    public static function ClearBalance()
    {
        unset($_SESSION["balanceProducts"]);

        return true;
    }

    public static function ClearViewed()
    {
        unset($_SESSION["viewedProducts"]);

        return true;
    }

    public static function AddViewed($idProduct)
    {
        $listProducts = ShopFn::GetIdFromViewed();

        $listProducts[$idProduct] = true;

        $_SESSION["viewedProducts"] = $listProducts;

        $listProducts = ShopFn::GetIdFromViewed();

        if (array_key_exists($idProduct, $listProducts)) {
            return true;
        }

        return true;
    }

    public static function DeleteFromCart($idProduct)
    {
        $listProducts = ShopFn::GetIdFromCart();

        unset($listProducts[$idProduct]);

        $_SESSION["cartProducts"] = $listProducts;
    }

    public static function AddToCart($idProduct, $property = false)
    {
        $listProducts = ShopFn::GetIdFromCart();

        unset($listProducts[$idProduct]);

        $listProducts[$idProduct]["count"] = 1;
        $listProducts[$idProduct]["property"] = $property;

        $_SESSION["cartProducts"] = $listProducts;

        $listProducts = ShopFn::GetIdFromCart();

        if (array_key_exists($idProduct, $listProducts)) {
            return true;
        }

        return false;
    }

    public static function ChangeCountInCart($idProduct, $count)
    {
        $listProducts = ShopFn::GetIdFromCart();

        if (array_key_exists($idProduct, $listProducts)) {
            $listProducts[$idProduct]["count"] = $count;

            $_SESSION["cartProducts"] = $listProducts;

            return true;
        }

        return false;
    }

    public static function AddToBalance($idProduct)
    {
        $listProducts = ShopFn::GetIdFromBalance();

        if (!in_array($idProduct, $listProducts)) {
            array_push($listProducts, $idProduct);

            $_SESSION["balanceProducts"] = $listProducts;

            return true;
        }

        return false;
    }

    public static function CheckOut($type)
    {
        $idFromCart = ShopFn::GetIdFromCart();

        if($idFromCart)
        {

            $orderName = BF::ClearCode("name", "str", "post");
            $orderDelivery = BF::ClearCode("delivery", "int", "post");
            $orderPayment = BF::ClearCode("payment", "int", "post");
            $orderComment = BF::ClearCode("comment", "str", "post");
            $orderSum = ShopFn::GetCartSum();
            $orderUser = BF::ReturnInfoUser(BF::idUser);

            if($type == "click")
            {
                $orderUser = 2;

                $orderPhone = BF::ClearCode("phone", "str", "post");
                $orderCity = BF::ClearCode("city", "str", "post");

                $name = "Заказ в один клик";

                $orderComment = <<<EOF
Имя: {$orderName}<br />
Телефон: {$orderPhone}<br />
Город: {$orderCity}
EOF;

                $resultGroup = R::exec("INSERT INTO OrdersGroup(OrdersGroup_name, OrdersGroup_comment, OrdersGroup_sum, OrdersGroup_user) VALUES(?, ?, ?, ?)", [
                    $name, $orderComment, $orderSum, $orderUser
                ]);
            }
            else
            {
                $resultGroup = R::exec("INSERT INTO OrdersGroup(OrdersGroup_name, OrdersGroup_delivery, OrdersGroup_payment, OrdersGroup_comment, OrdersGroup_sum, OrdersGroup_user) VALUES(?, ?, ?, ?, ?, ?)", [
                    $orderName, $orderDelivery, $orderPayment, $orderComment, $orderSum, $orderUser
                ]);
            }

            if ($idFromCart != false && ShopFn::GetCountInCart() != 0) {
                $idGroup = R::getRow("SELECT OrdersGroup_id FROM OrdersGroup ORDER BY OrdersGroup_id DESC");

                foreach ($idFromCart as $key => $value) {
                    $resultOrders = R::exec("INSERT INTO Orders(Orders_id_group, Orders_id_product, Orders_count) VALUES(?, ?, ?)", [
                        $idGroup["OrdersGroup_id"], $key, $value["count"]
                    ]);
                }
            }

            ShopFn::ClearCart();

            return $idGroup["OrdersGroup_id"];
        }

        return false;
    }

    public static function GetOrders()
    {
        return R::getAll("SELECT * FROM OrdersGroup

        INNER JOIN OrdersStatus ON OrdersStatus.OrdersStatus_id = OrdersGroup.OrdersGroup_status

        WHERE OrdersGroup_user = ?", [BF::ReturnInfoUser(BF::idUser)]);
    }

    public static function GetProductsFromOrderGroup($idGroup)
    {
        return R::getAll("SELECT * FROM Orders

        INNER JOIN Products ON Products.ID_product = Orders.Orders_id_product
        
        WHERE Orders.Orders_id_group = ?", [
            $idGroup
        ]);
    }

    public static function GetProducts($data)
    {

        if($data["search"])
        {
            $result["products"] = R::getAll("SELECT * FROM Products WHERE ProductName LIKE ? AND ProductUser = ? LIMIT ?, ?", [
                "%" . BF::ClearCode($data["search"], "str") . "%",
                BF::ReturnInfoUser(BF::idUser),
                BF::ClearCode($data["offset"], "int"),
                BF::ClearCode($data["limit"], "int")
            ]);

            $allProducts = R::getAll("SELECT * FROM Products WHERE ProductName LIKE ? AND ProductUser = ?", [
                "%". BF::ClearCode($data["search"], "str") . "%",
                BF::ReturnInfoUser(BF::idUser)
            ]);
        }
        else
        {
            $result["products"] = R::getAll("SELECT * FROM Products WHERE ProductUser = ? LIMIT ?, ?", [
                BF::ReturnInfoUser(BF::idUser),
                BF::ClearCode($data["offset"], "int"),
                BF::ClearCode($data["limit"], "int")
            ]);

            $allProducts = R::getAll("SELECT * FROM Products WHERE ProductUser = ?", [
                BF::ReturnInfoUser(BF::idUser)
            ]);
        }



        $arrayLink = AuxiliaryFn::PaginationGenerate($allProducts, $data["limit"], $data["link"]);

        $result["links"] = $arrayLink["pagination"];

        return  $result;
    }

    public static function GetClients($data)
    {
        $result["users"] = R::getAll("SELECT * FROM Users WHERE Users_permission = 0 OR Users_permission = 1 LIMIT ?, ?", [
            BF::ClearCode($data["offset"], "int"),
            BF::ClearCode($data["limit"], "int")
        ]);

        $arrayLink = AuxiliaryFn::PaginationGenerate(R::getAll("SELECT * FROM Users WHERE Users_permission = 0 OR Users_permission = 1"), $data["limit"], $data["link"], BF::ClearCode($data["offset"], "int"));

        $result["links"] = $arrayLink["pagination"];

        return  $result;
    }

    public static function GetProductsFromWish()
    {
        $product = [];

        foreach (self::GetIdFromWish() as $value)
        {
            $product[] = R::getRow("SELECT * FROM Products WHERE ID_product = ?", [
                $value["Wish_id_product"]
            ]);
        }

        return $product;
    }

    public static function DeleteFromBalance($idProduct)
    {
        $listProducts = ShopFn::GetIdFromBalance();


//        AuxiliaryFn::StylePrint($idProduct);
//        AuxiliaryFn::StylePrint($listProducts);

        if (in_array($idProduct, $listProducts)) {
            if(($key = array_search($idProduct, $listProducts)) !== FALSE){
                unset($listProducts[$key]);
            }

            $_SESSION["balanceProducts"] = $listProducts;

            return true;
        }
    }

    public static function GetIdFromViewed()
    {
        $products = BF::ClearCode("viewedProducts", "array", "session");

        if (!is_array($products)) {
            $products = [];
        }

        return $products;
    }

    public static function GetIdFromCart()
    {
        $products = BF::ClearCode("cartProducts", "array", "session");

        if (!is_array($products)) {
            $products = [];
        }

        return $products;
    }

    public static function GetIdFromBalance()
    {
        $products = BF::ClearCode("balanceProducts", "array", "session");

        if (!is_array($products)) {
            $products = [];
        }

        return $products;
    }

    public static function GetIdFromWish()
    {
        return R::getAll("SELECT Wish_id_product FROM Wish WHERE Wish_user = ?", [
            BF::ReturnInfoUser(BF::idUser)
        ]);
    }

    public static function GetJustIdFromWish()
    {
        $products = R::getAll("SELECT Wish_id_product FROM Wish WHERE Wish_user = ?", [
            BF::ReturnInfoUser(BF::idUser)
        ]);

        $arrayProduct = [];

        foreach ($products as $value)
        {
            $arrayProduct[] = $value["Wish_id_product"];
        }
        return $arrayProduct;
    }

    public static function GetProductsInCart()
    {
        $listProducts = ShopFn::GetIdFromCart();

        if (is_array($listProducts) && count($listProducts) > 0) {
            $stringArray = str_replace(",", " OR ID_product = ", implode(",", array_keys($listProducts)));

            return R::getAll("SELECT * FROM Products WHERE ID_product = " . $stringArray);
        }

        return false;
    }

    public static function GetProductsInBalance()
    {
        $listProducts = ShopFn::GetIdFromBalance();

        if (is_array($listProducts) && count($listProducts) > 0) {
            $stringArray = str_replace(",", " OR ID_product = ", implode(",", array_values($listProducts)));

            return R::getAll("SELECT * FROM Products WHERE ID_product = " . $stringArray);
        }

        return false;
    }

    public static function GetWishProducts()
    {
        $listProducts = ShopFn::GetJustIdFromWish();

        if (is_array($listProducts) && count($listProducts) > 0) {
            $stringArray = str_replace(",", " OR ID_product = ", implode(",", $listProducts));

            return R::getAll("SELECT * FROM Products WHERE ID_product = " . $stringArray);
        }

        return false;
    }

    public static function GetCharacteristic($category)
    {
        return R::getAll("SELECT * FROM CharacteristicsSchema WHERE cSchema_Category_FK = ?", [$category]);
    }

    public static function GetCartSum()
    {
        $sum = 0;

        foreach (ShopFn::GetProductsInCart() as $product)
        {
            $productInfo = self::GetIdFromCart();

            $wholesale = R::getAll("SELECT * FROM Wholesale WHERE Wholesale_product = ?", [
                $product["ID_product"]
            ]);

            $price = $product["ProductPrice"];

            foreach ($wholesale as $subValue)
            {
                if($productInfo[$product["ID_product"]]["count"] >= $subValue["Wholesale_count"])
                {
                    $price = $subValue["Wholesale_price"];
                }
            }

            $sum += $price * $productInfo[$product["ID_product"]]["count"];
        }

        return $sum;
    }

    public static function GetListDelivery()
    {
        return R::getAll("SELECT *, CONCAT(Country, ', ', Region, ', ', City, ', ', Street, ', ', Build_numb) AS Name FROM Address WHERE Address_user_id = ?", [BF::ReturnInfoUser(BF::idUser)]);
    }

    public static function GetListPayment()
    {
        return R::getAll("SELECT * FROM PaymentType");
    }

    public static function GetUserInfo()
    {
        return R::getRow("SELECT * FROM Users WHERE Users_id = ?", [BF::ReturnInfoUser(BF::idUser)]);
    }

    public static function DrawSelectPublished($valueSelect)
    {
        $select = [
            0 => "Не опубликован",
            1 => "Опубликован"
        ];
    }

    public static function Search($data = null)
    {
        $query = BF::CreateLikeQuery(["ProductName", "ProductDescription"], BF::ClearCode("query", "str", "get"));

        $result["products"] = R::getAll("SELECT * FROM Products WHERE " . $query . " LIMIT ?, ?", [
            BF::ClearCode($data["offset"], "int"),
            BF::ClearCode($data["limit"], "int")
        ]);

        $arrayLink = AuxiliaryFn::PaginationGenerate(R::getAll("SELECT * FROM Products WHERE " . $query), $data["limit"], "/search/s?query=" . BF::ClearCode("query", "str", "get") . "&" . $data["link"], BF::ClearCode($data["offset"], "int"));

        $result["links"] = $arrayLink;

        return  $result;
    }

    public static function SearchWish($search)
    {
        foreach (self::GetIdFromWish() as $value)
        {
            if($value["Wish_id_product"] == $search)
            {
                return true;
            }
        }

        return false;
    }

    public static function GetCurrency()
    {
        $currencyId = BF::ClearCode("currency", "int", "cookie");

        if($currencyId == 0)
        {
            $currencyId = 1;
        }

//        AuxiliaryFn::StylePrint("");

        $currency = R::getRow("SELECT * FROM Currency WHERE Currency_id = ?", [
            $currencyId
        ]);

        $data = [
            "left" => $currency["Currency_symbol_left"],
            "right" => $currency["Currency_symbol_right"],
            "value" => $currency["Currency_value"]
        ];

        return $data;
    }

    public static function ProductAvailability($count)
    {
        $data = [];

        if($count == 0)
        {
            $data["class"] = "empty";
            $data["icon"] = "<i class=\"fa fa-battery-empty\" aria-hidden=\"true\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Нет в наличии\"></i>";
            $data["text"] = "Нет в наличии";
        }
        else if($count < 10)
        {
            $data["class"] = "few";
            $data["icon"] = "<i class=\"fa fa-battery-quarter\" aria-hidden=\"true\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Заканчивается\"></i>";
            $data["text"] =  "Заканчивается";
        }
        else
        {
            $data["class"] = "have";
            $data["icon"] = "<i class=\"fa fa-battery-full\" aria-hidden=\"true\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Есть в наличии\"></i>";
            $data["text"] =  "Есть в наличии";
        }

        return $data;
    }

    public function GetPath($workCategory, $massive)
    {
        $thisCategory = R::getRow("SELECT * FROM Categories WHERE Categories_id = ?", [
            $workCategory
        ]);

        if(intval($thisCategory["Categories_parent"]) > 0)
        {
            $workCategory = intval($thisCategory["Categories_parent"]);

            $thisCategoryName = R::getRow("SELECT Categories_name FROM Categories WHERE Categories_id = ?", [
                $workCategory
            ]);

            $massive[$workCategory]["name"] = $thisCategoryName["Categories_name"];
            $massive[$workCategory]["id"] = $workCategory;

            return ShopFn::GetPath($workCategory, $massive);
        }
        else
        {
            return $massive;
        }
    }

    public function PrintStyleRecurs($data, $default = null)
    {
        $massive = array_reverse($data);

        $i = 1;

        $print = "<a href='/'>Главная</a> <i class=\"fa fa-chevron-right\" aria-hidden=\"true\"></i> ";

        foreach ($massive as $key => $value)
        {
            $dop = "";
            $class = "";

            if($i < count($massive))
            {
                $dop = " <i class=\"fa fa-chevron-right\" aria-hidden=\"true\"></i> ";
            }

            if($default && $value["id"] == $default)
            {
                $class = "active";
            }

            $print .= "<a href='/category/" . $value["id"] . "' class='" . $class . "'>" . $value["name"] . "</a>" . $dop;

            $i++;
        }

        return $print;
    }

    public static function GetRatingProduct($idProduct)
    {
        $rating = R::getRow("SELECT SUM(Rating_value) / COUNT(*) as Rating_sum, COUNT(*) as Rating_count FROM Rating WHERE Rating_product = ?", [
            $idProduct
        ]);

        $result["value"] = round($rating["Rating_sum"], 2);
        $result["count"] = $rating["Rating_count"];

        $rating = $rating["Rating_sum"] * 100 / 5;

        $result["icon"] = IncludesFn::ReturnRating($rating);

        return $result;
    }

    /*
     * FRONT END FUNCTIONS
     */

    public static function DesignCartInfo()
    {
        $countInCart = ShopFn::GetCountInCart();

        $classCart = "false";

        $productsInCart = <<<EOF
<span>В корзине пусто</span>
<i class="fa fa-shopping-basket"></i>
EOF;

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

        $data["count"] = $countInCart;
        $data["class"] = $classCart;
        $data["html"] = $productsInCart;

        return $data;
    }

    public static function DesignWishInfo()
    {
        $countWish = ShopFn::GetCountWish();

        $classWish = "false";

        $productsInWish = <<<EOF
<span>Желаний нет</span>
<i class="fa fa-heart-o"></i>
EOF;

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

        $data["count"] = $countWish;
        $data["class"] = $classWish;
        $data["html"] = $productsInWish;

        return $data;
    }

    public static function DesignBalanceInfo()
    {
        $countBalance = ShopFn::GetCountInBalance();

        $classBalance = "false";

        $productsInBalance = <<<EOF
<span>Тут нечего сравнивать</span>
<i class="fa fa-balance-scale"></i>
EOF;

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

        $data["count"] = $countBalance;
        $data["class"] = $classBalance;
        $data["html"] = $productsInBalance;

        return $data;
    }

    public static function DrawProduct($data, $col = null, $inline = 4, $view = "block") //Design Product
    {
        $productOne = '';
        $html = "";
        $startCount = 1;

        if($col == null)
        {
            $col = 4;
        }

        foreach ($data as $key => $value)
        {
            $balance = '';
            $heartClass = '';
            $lastPrice = '';
            $discount = '';
            $popularProduct = '';
            $novelty = '';
            $heart = 'fa-heart-o';

            $availability = ShopFn::ProductAvailability($value["ProductCount"]);

            if(array_key_exists ($value["ID_product"], ShopFn::GetIdFromCart()))
            {
                $buttonCart = '<button class="added-to-cart" data-id="' . $value["ID_product"] . '"><i class="fa fa-check" aria-hidden="true"></i> В корзине</button>';
            }
            else
            {
                if($value["ProductCount"] > 0)
                {
                    $buttonCart = '<button class="add-to-cart" data-id="' . $value["ID_product"] . '"><i class="fa fa-shopping-basket" aria-hidden="true"></i> В корзину</button>';
                }
                else
                {
                    $buttonCart = '<button class="none-to-cart" data-id="' . $value["ID_product"] . '"><i class="fa fa-shopping-basket" aria-hidden="true"></i> Нет в наличии</button>';
                }
            }

            if(in_array($value["ID_product"], ShopFn::GetIdFromBalance()))
            {
                $balance = 'active';
            }

            if(ShopFn::SearchWish($value["ID_product"]))
            {
                $heart = 'fa-heart';

                $heartClass = "active";
            }

            if($value["ProductLastPrice"] != 0)
            {
                $lastPrice = '<span class="last-price">' . $value["ProductLastPrice"] . '</span>';

//                $discount = '<span class="discount">%</span>';
//                $discount = '<span class="discount">Акция</span>';
                $discount = '<i class="fa fa-percent" data-toggle="tooltip" data-placement="bottom" title="Скидка"></i>';
            }

            if($value["ProductPopular"] != 0)
            {
//                $popularProduct = '<span class="discount popular"><i class="fa fa-rocket" aria-hidden="true"></i></span>';
//                $popularProduct = '<span class="discount popular">Топ продаж</span>';
                $popularProduct = '<i class="fa fa-rocket" data-toggle="tooltip" data-placement="bottom" title="Топ продаж"></i>';
            }
            
            if(BF::DifferenceDate($value["ProductAddDate"]) <= 12)
            {
//                $novelty = '<span class="discount novelty"><i class="fa fa-calendar" aria-hidden="true"></i></span>';
//                $novelty = '<span class="discount novelty"><i class="fa fa-clock-o"></i></span>';
                $novelty = '<i class="fa fa-clock-o" data-toggle="tooltip" data-placement="bottom" title="Недавно добавлен"></i>';
            }

            $echoCharacteristics = BF::GenerateList(ShopFn::GetCharacteristics($value["ID_product"]), '<strong class="strong">?:</strong> ?, ', ["cSchema_Name", "cValueValue"]);

            if($view == "list")
            {
                $price = '<span class="money">' . number_format($value["ProductPrice"], 0, '', ' ').'</b>' . $lastPrice . ' грн</span>';

                $productOne .= <<<EOF
                <div class="col-md-12">
                    <div class="one-product inline">
                        <a href="/product/{$value["ID_product"]}"><img src="{$value["ProductImagesPreview"]}" /></a>
                        <div class="info-block">
                            <a href="/product/{$value["ID_product"]}">
                                <strong>{$value["ProductName"]}</strong>
                            </a>
                            {$echoCharacteristics}
                            <!--<span class="availability {$availability["class"]}">{$availability["text"]}</span>
                            <div class="info-product-else">{$discount} {$popularProduct} {$novelty}</div>-->
                        </div>
                        <div class="manage-block">
                            <div class="status-product margin-bottom">
                            {$availability["icon"]} {$discount} {$popularProduct} {$novelty}
                            </div>
                            {$price}<br />
                            {$buttonCart}
                            <button class="heart-button top-button {$heartClass} clear-button" data-id="{$value["ID_product"]}" data-toggle="tooltip" data-placement="bottom" title="Желаю">
                            <i class="fa {$heart}" aria-hidden="true"></i></button>
                            <button class="balance-button clear-button {$balance}" data-id="{$value["ID_product"]}" data-toggle="tooltip" data-placement="bottom" title="В сравнение">
                            <i class="fa fa-balance-scale" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
EOF;

            }
            else
            {
                $productOne .= '
                <div class="col-md-'.$col.'">
                    <div class="one-product hover">
                        <div class="status-product">
                            ' . $availability["icon"] .$discount . $popularProduct . $novelty .'
                        </div>
                        <!--<div class="info-product-else">'.$discount . $popularProduct . $novelty .'</div>-->
                        <a href="/product/'.$value["ID_product"].'">
                            <img src="'.$value["ProductImagesPreview"].'" />
                            <strong>'.$value["ProductName"].'</strong>
                        </a>
                        <span class="money"><b>'.number_format($value["ProductPrice"], 0, '', ' ').'</b>'.$lastPrice.' грн</span><br />
                        <!--<span class="availability ' . $availability["class"] . '">' . $availability["text"] . '</span><br />-->
                        '.$buttonCart.'
                        <button class="heart-button top-button '.$heartClass.' clear-button" data-id="'.$value["ID_product"].'" data-toggle="tooltip" data-placement="bottom" title="Желаю">
                        <i class="fa '.$heart.'" aria-hidden="true"></i></button>
                        <button class="balance-button clear-button '.$balance.'" data-id="'.$value["ID_product"].'" data-toggle="tooltip" data-placement="bottom" title="В сравнение">
                        <i class="fa fa-balance-scale" aria-hidden="true"></i></button>
                        <span class="char">' . $echoCharacteristics . '</span>
                    </div>
                </div>';
            }

            if($startCount == $inline)
            {
                $html .= <<<EOF
                <div class="row perspective">
                    {$productOne}
                </div>
EOF;
                $productOne = "";
                $startCount = 0;

            }

            $startCount++;
        }

        if(--$startCount != $inline)
        {
            $html .= <<<EOF
                <div class="row perspective">
                    {$productOne}
                </div>
EOF;
        }

        return $html;
    }

    public static function GetCharacteristics($idProduct)
    {
        return R::getAll("SELECT * FROM CharacteristicsOutput

        INNER JOIN CharacteristicsSchema ON CharacteristicsSchema.ID_cSchema = CharacteristicsOutput.cOutput_id_Schema
        
        INNER JOIN CharacteristicsValue ON CharacteristicsValue.ID_cValue = CharacteristicsOutput.cOutput_id_Value
        
        WHERE CharacteristicsOutput.cOutput_id_Product = ?", [$idProduct]);
    }
}