<?php
$pageName = "Свойства товаров";

IncludesFn::printHeader($pageName, "grey");

$parent = R::getAll("SELECT * FROM PropertiesParent");

$table = "";

foreach ($parent as $value)
{
    $child = R::getAll("SELECT * FROM Properties WHERE PropertiesValues_category = ?", [
        $value["PropertiesGroup_id"]
    ]);

    $tr = "";

    foreach ($child as $subValue)
    {
        $idProp = $subValue["PropertiesValues_id"];

        $tr .= '<tr>
        <td>' . $subValue["Categories_id"] . '</td>
        <td><input class="design-input edit-property' . $idProp . '" value="' . $subValue["PropertiesValues_name"] . '" /></td>
        <td>
            <input id="prop' . $idProp . '" type="color" class="design-color hidden color-property' . $idProp . '" value="' . $subValue["PropertiesValues_color"] . '" />
            <label for="prop' . $idProp . '" style="color: ' . $subValue["PropertiesValues_color"] . ';" class="btn btn-default shadow"><i class="fa fa-circle" aria-hidden="true"></i></label>
        </td>
        <td>
            <button class="save-property btn btn-default circle" data-id="' . $idProp . '"><i class="fa fa-save" aria-hidden="true"></i></button>
            <button class="delete-property btn btn-default circle" data-id="' . $idProp . '"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </td>
    </tr>';
    }

    $table .= <<<EOF
    <div class="one-prop-div">
        <h4 class="d-ib">{$value["PropertiesGroup_name"]}</h4>
        <button class="add-prop property btn btn-default f-r" data-id="{$value["PropertiesGroup_id"]}">Добавить {$value["PropertiesGroup_name"]}</button>
        <br />
        <br />
        <table class="table">
        {$tr}
        </table>
    </div>
EOF;
}

$bodyText = <<<EOF
<div class="container-fluid header-based">
    <div class="row">
        <div class="col-md-12 ta-r">
            <div class="manage-buttons">
                <button class="add-prop-parent property btn btn-default circle" ><i class="fa fa-plus" aria-hidden="true"></i></button>
            </div>
        </div>
        <div class="col-md-12">
            {$table}
        </div>
    </div>
</div>
EOF;
