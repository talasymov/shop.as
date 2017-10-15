<?php
$rootCategory = BF::GenerateList(DataBase::GetRootCategory(), '<span>?</span>', ["Categories_name"]);
//$echoCharacteristics = BF::GenerateList($data["characteristics"], '<tr><td><strong class="strong">?:</strong></td><td>?</td></tr>', ["cSchema_Name", "cValueValue"]);

$echoCharacteristics = "";

//AuxiliaryFn::StylePrint($data["characteristics"]);

foreach ($data["characteristics"] as $value)
{
    if($value["cOutput_Value"] == null)
    {
        $valueCharacteristic = $value["cValueValue"];
    }
    else
    {
        $valueCharacteristic = $value["cOutput_Value"];
    }

    $echoCharacteristics .= '<tr><td><strong class="strong">' . $value["cSchema_Name"] . ':</strong></td><td>' . $valueCharacteristic . '</td></tr>';
}

foreach ($data["characteristicsValue"] as $value)
{
    $valueCharacteristic = "";

    if($value["cOutput_Value"] != "")
    {
        $valueCharacteristic = $value["cOutput_Value"];

        $echoCharacteristics .= '<tr><td><strong class="strong">' . $value["cSchema_Name"] . ':</strong></td><td>' . $valueCharacteristic . '</td></tr>';
    }
}

$echoReviewsLast = BF::GenerateList($data["reviewsLast"], '<div class="one-comment"><strong>? ?</strong><span class="comment"><i class="fa fa-quote-left" aria-hidden="true"></i>?<i class="fa fa-quote-right" aria-hidden="true"></i></span><span class="date">?</span></div>', ["Users_name", "Users_surname", "ReviewText", "ReviewDate"]);
$echoReviews = BF::GenerateList($data["reviews"], '<div class="one-comment"><strong>? ?</strong><span class="comment"><i class="fa fa-quote-left" aria-hidden="true"></i>?<i class="fa fa-quote-right" aria-hidden="true"></i></span><span class="date">?</span></div>', ["Users_name", "Users_surname", "ReviewText", "ReviewDate"]);
$echoSimilarProduct = BF::GenerateList($data["similarProducts"], '<a href="/product/?"><div class="one"><img src="?" /><strong>?</strong><span class="money">? грн</span></div></a>', ["ID_product", "ProductImagesPreview", "ProductName", "ProductPrice", "ProductLastPrice"], 8, "class='popular-parent clearfix'");

$echoImages = "";
$echoImagesId = 2;

$imgExplode = explode(";", $data["product"]["ProductImages"]);

foreach ($imgExplode as $valueSub)
{
    if ($valueSub != null)
    {
        $echoImages .= '<div class="item zoom-click" data-id="' . $echoImagesId . '"><img src="' . $valueSub . '" /></div>';
        $echoImagesId++;
    }
}

$currency = ShopFn::GetCurrency();

$seo = <<<EOF
<meta name="keywords" content="{$data["product"]["ProductSeoKeywords"]}" />
<meta name="description" content="{$data["product"]["ProductSeoDesc"]}" />
EOF;

//$breadCrumbs = BF::BreadCrumbsProduct($data["product"]["ID_product"]);

$wholesale = R::getAll("SELECT * FROM Wholesale WHERE Wholesale_product = ?", [
    $data["product"]["ID_product"]
]);

$wholesaleHtml = "";

foreach ($wholesale as $value)
{
    $wholesaleHtml .= '<span>от ' . $value["Wholesale_count"] . " - " . $value["Wholesale_price"] . " грн</span><br />";
}

if($wholesaleHtml)
{
    $wholesaleHtml = <<<EOF
<div class="wholesale-html">
    <strong>Оптовые цены:</strong>
    {$wholesaleHtml}
</div>
EOF;

}

$workCategory = $data["product"]["ProductCategory"];

$thisCategoryName = R::getRow("SELECT Categories_name FROM Categories WHERE Categories_id = ?", [
    $workCategory
]);

$res = ShopFn::PrintStyleRecurs(ShopFn::GetPath($workCategory, [
    $workCategory => [
        "name" => $thisCategoryName["Categories_name"],
        "id" => $workCategory
    ]
]));

IncludesFn::printHeader($data["product"]["ProductName"], "grey", $seo);

$balance = '';
$heartClass = '';
$lastPrice = '';
$heart = 'fa-heart-o';

if(array_key_exists ($data["product"]["ID_product"], ShopFn::GetIdFromCart()))
{
    $buttonCart = '<button class="added-to-cart" data-id="' . $data["product"]["ID_product"] . '"><i class="fa fa-check" aria-hidden="true"></i>В корзине</button>';
}
else
{
    if($data["product"]["ProductCount"] > 0)
    {
        $buttonCart = '<button class="add-to-cart" data-id="' . $data["product"]["ID_product"] . '"><i class="fa fa-shopping-basket" aria-hidden="true"></i>В корзину</button>';
    }
    else
    {
        $buttonCart = '<button class="none-to-cart" data-id="' . $value["ID_product"] . '"><i class="fa fa-shopping-basket" aria-hidden="true"></i>Нет в наличии</button>';
    }
}

$menu = IncludesFn::GenerateCategoryMenu();

if(in_array($data["product"]["ID_product"], ShopFn::GetIdFromBalance()))
{
    $balance = 'active';
}

ShopFn::AddViewed($data["product"]["ID_product"]);

if(ShopFn::SearchWish($data["product"]["ID_product"]))
{
    $heart = 'fa-heart';

    $heartClass = "active";
}

if($data["product"]["ProductLastPrice"] != 0)
{
    $lastPrice = '<span class="last-price">' . number_format($data["product"]["ProductLastPrice"] * $currency["value"], 0, '', ' ') . '</span>';
}

$listCategory = [];
/*
 * Выбор характеристик
 */
$prop = R::getAll("SELECT * FROM PropertiesValues

INNER JOIN Properties ON Properties.PropertiesValues_id = PropertiesValues.Properties_id_value

INNER JOIN PropertiesParent ON PropertiesParent.PropertiesGroup_id = Properties.PropertiesValues_category

WHERE Properties_product = ?", [
    $data["product"]["ID_product"]
]);

/*
 * Генерируем массив из ID разбитый по категориям (Цвет, Размер и тд)
 */

foreach ($prop as $value)
{
    $categoryId = $value["PropertiesGroup_id"];

    if(!in_array($categoryId, $listCategory))
    {
        $listCategory[] = $categoryId;
    }
}

/*
 * Генерируем массив разбитый по категориям
 */

$resultArray = [];

foreach ($listCategory as $value)
{
    foreach ($prop as $subKey => $subValue)
    {
        if($subValue["PropertiesGroup_id"] == $value)
        {
            $resultArray[$value][] = $prop[$subKey];
        }
    }
}

$listProductsInCart = ShopFn::GetIdFromCart();

//AuxiliaryFn::StylePrint($listProductsInCart);

$div = "";

foreach ($resultArray as $product)
{
    $text = "";

    foreach ($product as $item) {
        $active = "";

        if($listProductsInCart)
        {
            if(is_array($listProductsInCart[$data["product"]["ID_product"]]["property"]))
            {
                if(in_array($item["Properties_id"], $listProductsInCart[$data["product"]["ID_product"]]["property"]))
                {
                    $active = "active";
                }
            }

        }

        $text .= '<div class="div-prop ' . $active . '" data-parent="' . $item["Properties_id"] . '">
            <span class="text-prop" data-img="' . $item["Properties_img"] . '" style="background-color: ' . $item["PropertiesValues_color"] . '"></span> 
            <span class="text-b">' . $item["PropertiesValues_name"] . '</span>
        </div>';
    }
//    $text = BF::GenerateList($product,
//        '<div class="div-prop" data-parent="?">
//            <span class="text-prop" data-img="/Images/Products/?" style="background-color: ?"></span>
//            <span class="text-b">?</span>
//        </div>',
//        ["Properties_id", "Properties_img", "PropertiesValues_color", "PropertiesValues_name"],
//        8,
//        "class='parent-property'");

    $div .= "<div class='parent-prop'><h4>" . $product[0]["PropertiesGroup_name"] . "</h4><div class='parent-property'>" . $text . "</div></div>";
}

$price = number_format($data["product"]["ProductPrice"] * $currency["value"], 0, '', ' ');

$user = R::getRow("SELECT Users_id, Users_company_name, Users_image FROM Users WHERE Users_id = ?", [
    $data["product"]["ProductUser"]
]);

$bodyText = <<<EOF
<div class="bg-menu-black" style="display: none;"></div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!--<div class="menu-top click-menu s-hide">
                <div class="menu-home  category">
                    <strong><i class="fa fa-bars" aria-hidden="true"></i> Каталог товара</strong>
                    {$menu}
                </div>
            </div>-->
            <br />
            <div class="product product-one-page">
                <div class="bread-crumb">                
                    {$res}
                    <i class="fa fa-chevron-right" aria-hidden="true"></i> <a href="" class="active">{$data["product"]["ProductName"]}</a>
                </div>
                <h1>{$data["product"]["ProductName"]}</h1>
                <strong class="code-product margin-bottom">Артикул товара: {$data["product"]["ID_product"]}</strong>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="poduct-p-image-preview zoom-click" data-id="1">
                                <img src="{$data["product"]["ProductImagesPreview"]}" />
                            </div>
                            <div class="product-list-images carouselHidden">
                                {$echoImages}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div><strong class="product-price">{$currency["left"]} {$price} {$currency["right"]} {$lastPrice}</strong>{$wholesaleHtml}</div>
                            <div class="rating">{$data["rating"]["icon"]} {$data["rating"]["value"]} ({$data["rating"]["count"]})</div>
                            {$div}                    
                            {$buttonCart}
                            <button class="heart-button top-button clear-button {$heartClass}" data-id="{$data["product"]["ID_product"]}"><i class="fa {$heart}" aria-hidden="true"></i></button>
                            <button class="balance-button clear-button {$balance}" data-id="{$data["product"]["ID_product"]}"><i class="fa fa-balance-scale" aria-hidden="true"></i></button><br />
                            <strong class="product-header">Краткие характеристики:</strong>
                            <table class="char-table">
                                {$echoCharacteristics}
                            </table>
                        </div>
                        <div class="col-md-4 border-left-review">
                            <div class="info-about-seller clearfix">
                                <b>Продавец:</b>
                                <img src="{$user["Users_image"]}">
                                <strong>{$user["Users_company_name"]}</strong>
                                <a href="">Профиль</a>
                            </div>
                            <strong class="product-header">Отзывы покупателей:</strong>
                            <div class="reviews-parent">
                                {$echoReviewsLast}
                            </div>
                            <span class="info">Чтобы оставить отзыв, необходимо приобрести товар!</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="poduct-p-description">
                                <div class="list-header-block">
                                    <strong class="head-strong active" data-class="description-product">Описание</strong>
                                    <strong class="head-strong" data-class="reviews-product">Отзывы ({$data["reviewsCount"]})</strong>
                                </div>
                                
                                <div class="description-product div-b vis">
                                    {$data["product"]["ProductDescription"]}
                                </div>
                                <div class="reviews-product reviews-parent div-b">
                                    {$echoReviews}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            {$echoSimilarProduct}
        </div>
    </div>
</div>

EOF;
