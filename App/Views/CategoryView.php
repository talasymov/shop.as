<?php
IncludesFn::printHeader($data["products"]["products"][0]["CategoryName"]);

//AuxiliaryFn::StylePrint($data);

$categoryId = $data["categoryId"];

$workCategory = $categoryId;

$minPrice = R::getRow("SELECT ProductPrice FROM Products WHERE ProductCategory = ? ORDER BY ProductPrice",[
    $workCategory
]);
$maxPrice = R::getRow("SELECT ProductPrice FROM Products WHERE ProductCategory = ? ORDER BY ProductPrice DESC",[
    $workCategory
]);



$listPrice = BF::ClearCode("listPrice", "str", "get");

$explodePrice = explode(",", $listPrice);

$priceStart = intval($explodePrice[0]);
$priceEnd = intval($explodePrice[1]);

if($priceStart == 0)
{
    $priceStart = $minPrice["ProductPrice"];
}

if($priceEnd == 0)
{
    $priceEnd = $maxPrice["ProductPrice"];
}

$sumPrice = $maxPrice["ProductPrice"] - $minPrice["ProductPrice"];

$mr = 12;

$thisCategoryName = R::getRow("SELECT Categories_name FROM Categories WHERE Categories_id = ?", [
    $workCategory
]);

$res = ShopFn::PrintStyleRecurs(ShopFn::GetPath($workCategory, [
    $workCategory => [
        "name" => $thisCategoryName["Categories_name"],
        "id" => $workCategory
    ]
]), $workCategory);

$sortArray = [
    1 => "По рейтингу",
    2 => "От дешевых к дорогим",
    3 => "От дорогих к дешевым",
    4 => "Недавно добавленные"
];

$listCount = [
    0 => 20,
    2 => 2,
    5 => 5,
    10 => 10,
    20 => 20,
    50 => 50,
    100 => 100
];

$sortArrayBuild = AuxiliaryFn::ArrayToSelectSimple($sortArray, "design-input list-sort", "Сортировка товара", BF::ClearCode("sortProducts", "int", "get"));
$listCountBuild = AuxiliaryFn::ArrayToSelectSimple($listCount, "design-input list-count", "Показать", BF::ClearCode("listCount", "int", "get"));

$echoFilter = "";
$selectedValue = '';

foreach ($data["filters"] as $key => $value)
{
    $header = "<strong>" . $key . "</strong>";

    $list = '';

    foreach ($value as $subValue)
    {
        $active = '';

        if(in_array($subValue["ID_cValue"], $data["listVar"])){
            $selectedValue .= "<span class='selected-filter' data-id='" . $subValue["ID_cValue"] . "'><button class='remove clear-button'><i class=\"fa fa-times\" aria-hidden=\"true\"></i></button>" . $subValue["cValueValue"] . "</span>";

            $active = "active";
        }

        $list .= '<span class="task-one-list" data-id="'.$subValue["ID_cValue"].'"><strong class="carrot-radio '.$active.'">'.$subValue["cValueValue"].'</strong></span>';
    }

    $echoFilter .= $header . $list;
}

if($selectedValue != '')
{
    $selectedValueStrong = '<strong class="strong header-filter-clear">Вы выбрали</strong>';

    $selectedValueButton = '<div class="center"><button class="clear-filter clear-button">Сбросить фильтры</button><hr /></div>';
}

$menu = IncludesFn::GenerateCategoryMenu();

$productOne = ShopFn::DrawProduct($data["products"]["products"], 4, 3, $data["view"]);

$explodePrice = explode(",", $listPrice);



$listCategory = BF::GenerateList($data["categoryData"], '<div class="col-md-3"><div class="one-product ta-c"><a href="/category/?"><img src="?"><strong class="strong">?</strong></a></div></div>', ["Categories_id", "Categories_image", "Categories_name"]);

$categoryName = $data["categoryInfo"]["Categories_name"];

if($listCategory == "")
{
    $categoryName = "";
}

//$breadCrumbs = "Bread Crumbs";

$listVarGet = BF::ClearCode("listVar", "str", "get");
$listPriceGet = BF::ClearCode("listPrice", "str", "get");
$sortProductsGet = BF::ClearCode("sortProducts", "str", "get");
$listCountGet = BF::ClearCode("listCount", "str", "get");
$viewGet = BF::ClearCode("view", "str", "get");

$bodyText = <<<EOF
<div class="bg-menu-black" style="display: none;"></div>
<div class="container">
    <div class="row">
        <div class="col-md-3 bg-white">
            <div class="menu-top click-menu s-hide">
                <div class="menu-home category">
                    <strong><i class="fa fa-bars" aria-hidden="true"></i> Каталог товара</strong>
                    {$menu}
                </div>
            </div>
            {$selectedValueStrong}
            {$selectedValue}
            {$selectedValueButton}
            <div class="menu-category">
                <strong>Диапазон цен</strong>
                <input class="design-input first price-start" placeholder="От" value="{$priceStart}">
                <input class="design-input last price-end" placeholder="До" value="{$priceEnd}">
                <div class="go-button" data-value="{$sumPrice}">
                    <span class="left" data-value="{$minPrice["ProductPrice"]}"></span>
                    <i></i>
                    <span class="right" data-value="{$maxPrice["ProductPrice"]}"></span>
                </div>
                {$echoFilter}
            </div>
            <button class="filter-search"><i class="fa fa-filter" aria-hidden="true"></i> Фильтр</button>
            <form style="display: hidden" action="/category/{$categoryId}" method="GET" id="form">
              <input type="hidden" id="listVar" name="listVar" value="{$listVarGet}"/>
              <input type="hidden" id="listPrice" name="listPrice" value="{$listPriceGet}"/>
              <input type="hidden" id="sortProducts" name="sortProducts" value="{$sortProductsGet}"/>
              <input type="hidden" id="listCount" name="listCount" value="{$listCountGet}"/>
              <input type="hidden" id="view" name="view" value="{$viewGet}"/>
            </form>
        </div>
        <div class="col-md-9">
            <div class="container-fluid popular-parent">
                <div class="row">
                    <div class="col-md-12">
                        <div class="bread-crumb">
                        {$res}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 ta-c">
                        <h3>{$thisCategoryName["Categories_name"]}</h3>
                    </div>
                    {$listCategory}
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {$sortArrayBuild}
                        {$listCountBuild}
                        <button class="view-block btn btn-default circle f-r"><i class="fa fa-th" aria-hidden="true"></i></button>
                        <button class="view-list btn btn-default circle f-r"><i class="fa fa-th-list" aria-hidden="true"></i></button>
                    </div>
                </div>
                {$productOne}
                <div class="row">
                    <div class="col-md-12 ta-c">
                        {$data["products"]["links"]}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
EOF;

$script = <<<EOF

EOF;
