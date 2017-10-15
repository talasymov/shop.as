<?php
IncludesFn::printHeader("Редактирование товара", "grey");

//$selectPublished = AuxiliaryFn::ArrayToSelectSimple(ShopFn::publishedArray, "design-input product-published", "Выберите", $data["products"]["ProductPublished"]);

//$bodyText = <<<EOF
//<h2 class="ta-c">Редактирование товара</h2>
//<div class="container-fluid">
//    <div class="row">
//        <div class="col-md-6">
//            <h3 class="ta-c">Основная информация</h3>
//            <span class="header-blue">Название продукта</span>
//            <input class="product-name design-input" placeholder="Name" value="{$data["products"]["ProductName"]}">
//            <span class="header-blue">Описание продукта</span>
//            <input class="product-description design-input" placeholder="Description" value="{$data["products"]["ProductDescription"]}">
//            <span class="header-blue">Цена</span>
//            <input class="product-price design-input" placeholder="Price" value="{$data["products"]["ProductPrice"]}">
//            <span class="header-blue">Закупочная цена</span>
//            <input class="product-purchase-price design-input" value="{$data["products"]["ProductPurchasePrice"]}">
//            <span class="header-blue">Старая цена</span>
//            <input class="product-lastPrice design-input" placeholder="Last Price" value="{$data["products"]["ProductLastPrice"]}">
//            <!--<span class="header-blue">Категория</span>-->
//            <input class="product-category design-input" type="hidden" placeholder="Category" value="{$data["products"]["ProductCategory"]}">
//
//            <span class="header-blue">Фото продукта</span>
//            <input class="product-preview design-input" type="file">
//            <input class="product-id" type="hidden" value="{$data["products"]["ID_product"]}">
//            <span class="header-blue">Другие фото продукта</span>
//            <input class="list-files design-input" type="file">
//            <input class="list-files design-input" type="file">
//            <input class="list-files design-input" type="file">
//            <input class="list-files design-input" type="file">
//            <input class="list-files design-input" type="file">
//            <h3 class="ta-c">SEO</h3>
//            <span class="header-blue">Описание</span>
//            <textarea class="product-seo-desc design-textarea" placeholder="">{$data["products"]["ProductSeoDesc"]}</textarea>
//            <span class="header-blue">Ключевые слова</span>
//            <textarea class="product-seo-keywords design-textarea" placeholder="">{$data["products"]["ProductSeoKeywords"]}</textarea>
//        </div>
//        <div class="col-md-6">
//            <h3 class="ta-c">Характеристики</h3>
//            <table class="table">
//                {$data["characteristics"]}
//            </table>
//            <span class="header-blue">Публикация</span>
//            {$selectPublished}
//            <hr />
//            <button class="edit-product btn btn-info"><i class="fa fa-cloud" aria-hidden="true"></i> Редактировать</button>
//            <button class="delete-product btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i> Удалить</button>
//        </div>
//    </div>
//</div>
//EOF;

$propertiesParentLi = BF::GenerateList(R::getAll("SELECT * FROM PropertiesParent"), "<li class='one-prop-li' data-id='?'><a>?</a></li>", ["PropertiesGroup_id", "PropertiesGroup_name"]);

$properties = R::getAll("SELECT * FROM PropertiesParent");

$propertiesDiv = "";

foreach ($properties as $value)
{
    $child = R::getAll("SELECT * FROM Properties WHERE PropertiesValues_category = ?", [
        $value["PropertiesGroup_id"]
    ]);

    $tr = "";

    foreach ($child as $subValue)
    {
        $idProp = $subValue["PropertiesValues_id"];

        $tr .= '<tr>
                <td style="width: 30px"><input class="checkbox-property" data-id="' . $idProp . '" type="checkbox"></td>
                <td>' . $subValue["PropertiesValues_name"] . '</td>
                <td><div class="slct-modal-div-image">
                    <img src="">
                    <span class="slct-name">Выберите фото</span>
                    <button class="clear-button property-image" data-url=""><i class="fa fa-camera" aria-hidden="true"></i></button>
                </div></td>
                <td><input class="design-input price-prop" /></td>
            </tr>';
    }

    $table = <<<EOF
    <table class="propTable table" data-id="">
        <thead class="strong">
            <tr><th></th><th>Название</th><th>Фото товара</th><th>Цена</th></tr>
        </thead>
        <tbody>
            {$tr}
        </tbody>
    </table>
EOF;

    $propertiesDiv .= <<<EOF
    <div class="prop-block" data-id="{$value["PropertiesGroup_id"]}">
        <div class="list-div"><strong>{$value["PropertiesGroup_name"]}</strong><button><i class="fa fa-chevron-down" aria-hidden="true"></i></button></div>
        <div class="table-div">{$table}</div>
    </div>
EOF;

}

$imagesProduct = explode(";", $data["products"]["ProductImages"]);
$imagesProductHtml = "";

for ($i = 1; $i <= 8; $i++)
{
    $y = $i - 1;
    $url = "";

    if(isset($imagesProduct[$y]))
    {
        $url = $imagesProduct[$y];
    }

    $imagesProductHtml .= <<<EOF
<div class="col-sm-3" style="height: 150px;">
    <div class="slct-modal-div-image">
        <img src="{$url}">
        <span class="slct-name">Выберите фото</span>
        <button class="clear-button product-image-one product-image-{$i}" data-url="{$url}"><i class="fa fa-camera" aria-hidden="true"></i></button>
    </div>
</div>
EOF;
}

$pageName = "Добавление товара";

$bodyText = <<<EOF
<div class="card-product">
    <h3>Карточка товара</h3>
    <ul class="tab-manager">
        <li data-class="1" class="active">Основное</li>
        <li data-class="2">Характеристики</li>
        <li data-class="3">Свойства</li>
        <li data-class="6">Оптовые продажи</li>
        <li data-class="4">SEO</li>
        <li data-class="5">Изображения</li>
    </ul>
</div>
<input class="product-id" type="hidden" value="{$data["products"]["ID_product"]}">
<div class="one-tab tab-1 view">
    <table>
        <tr>
            <td><span class="header-blue">Фото продукта</span></td>
            <td>
                <div class="slct-modal-div-image">
                    <img src="{$data["products"]["ProductImagesPreview"]}">
                    <span class="slct-name"><i class="fa fa-camera" aria-hidden="true"></i></span>
                    <button class="clear-button product-preview" data-url="{$data["products"]["ProductImagesPreview"]}"><i class="fa fa-paperclip" aria-hidden="true"></i></button>
                </div>
            </td>
        </tr>
        <tr>
            <td><span class="header-blue">Название продукта</span></td><td><input class="product-name design-input" value="{$data["products"]["ProductName"]}"></td>
        </tr>
        <tr>
            <td><span class="header-blue">Категория продукта</span></td><td>
                <div class="slct-modal-div select-category-char-3" data-key="asdu12nd">
                    <span class="slct-num">0</span>
                    <span class="slct-name">Выберите категорию</span>
                    <button class="clear-button"><i class="fa fa-folder-open" aria-hidden="true"></i></button>
                </div>
            </td>
        </tr>
        <tr>
            <td><span class="header-blue">Артикул</span></td>
            <td><input class="product-vendor-code design-input" value="{$data["products"]["ProductVendorCode"]}"></td>
        </tr>
        <tr>
            <td><span class="header-blue">Описание продукта</span></td><td><textarea class="product-description design-textarea">{$data["products"]["ProductDescription"]}</textarea></td>
        </tr>
        <tr>
            <td><span class="header-blue">Цена</span></td><td><input class="product-price design-input" value="{$data["products"]["ProductPrice"]}"></td>
        </tr>
        <tr>
            <td><span class="header-blue">Закупочная цена</span></td><td><input class="product-purchase-price design-input" value="{$data["products"]["ProductPrice"]}"></td>
        </tr>
        <tr>
            <td><span class="header-blue">Старая цена</span></td><td><input class="product-lastPrice design-input" value="{$data["products"]["ProductLastPrice"]}"></td>
        </tr>
        <tr>
            <td><span class="header-blue">Габариты (см)</span></td>
            <td><input data-placeholder="Длина" class="product-dimensions-length design-input mini-input"> <input data-placeholder="Ширина" class="product-dimensions-width design-input mini-input"> <input data-placeholder="Высота" class="product-dimensions-height design-input mini-input"></td>
        </tr>
        <tr>
            <td><span class="header-blue">Масса нетто (кг)</span></td>
            <td><input class="product-netto design-input" value="{$data["products"]["ProductWeight"]}"></td>
        </tr>
        <tr>
            <td><span class="header-blue">В наличии (шт)</span></td>
            <td><input class="product-have design-input" value="{$data["products"]["ProductCount"]}"></td>
        </tr>
        <tr>
            <td><span class="header-blue">Тип продажи</span></td>
            <td>
                <select class="design-input product-type">
                    <option value="0">Только розница</option>
                    <option value="1">Только опт</option>
                    <option value="2">Розница и опт</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><span class="header-blue">Статус</span></td>
            <td>
                <select class="design-input product-status">
                    <option value="0">Не выбрано</option>
                    <option value="1">Скоро в наличии</option>
                    <option value="2">Снят с производства</option>
                </select>
            </td>
        </tr>
    </table>
</div>
<div class="one-tab tab-2">
<table class="characteristicTable table">
    {$selectCharacteristic}
</table>
<div class="checkCharacteristic">
    <div class="subCategoryPaste"></div>
</div>
</div>
<div class="one-tab tab-3">
    <!--<div class="menu-grey">
        <div class="dropdown select-prop-parent d-ib">
          <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">Выбрать свойство
          <span class="caret"></span></button>
          <ul class="dropdown-menu">
            {$propertiesParentLi}
          </ul>
        </div>
        <button class="btn btn-primary">Добавить свойство</button>
    </div>-->
    {$propertiesDiv}
</div>
<div class="one-tab tab-4">
    <table>
        <tr>
            <td><span class="header-blue">Описание</span>Укажите в описании полное название продукта</td>
            <td><textarea class="product-seo-desc design-textarea" placeholder="">{$data["products"]["ProductSeoDesc"]}</textarea></td>
        </tr>
        <tr>
            <td><span class="header-blue">Ключевые слова</span></td>
            <td><textarea class="product-seo-keywords design-textarea" placeholder="">{$data["products"]["ProductSeoKeywords"]}</textarea></td>
        </tr>
    </table>
</div>
<div class="one-tab tab-6">
    <table class="table">
        <thead class="strong">
            <tr><th width="150">Цена</th><th>Количество</th><th width="70" class="ta-c"></th></tr>
        </thead>
        <tbody>
        <tr>
            <td></td>
            <td></td>
            <td class="ta-c"><button class="add-wholesale btn btn-default circle"><i class="fa fa-plus" aria-hidden="true"></i></button></td>
        </tr>
        <tr class="hidden base-wholesale">
            <td><input class="design-input whole-sale-price" /></td>
            <td><input class="design-input whole-sale-count" /></td>
            <td class="ta-c"><button class="remove-wholesale btn btn-default circle"><i class="fa fa-minus" aria-hidden="true"></i></button></td>
        </tr>
        </tbody>
    </table>
</div>
<div class="one-tab tab-5">
    <div class="container-fluid">
        <div class="row">
            {$imagesProductHtml} 
        </div>
    </div>
</div>
<div class="footer-dashboard-buttons">
<button class="edit-product btn btn-info f-w-b">Сохранить товар</button>
</div>
EOF;
