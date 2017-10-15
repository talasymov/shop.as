<?php
$pageName = "Блог";

IncludesFn::printHeader($pageName, "grey");

$products = BF::GenerateList($data["products"], "<tr><td  class=\"ta-c\">?</td><td>?</td><td><a href=\"/dashboard/news/edit/?\"><button class='btn btn-default circle'><i class=\"fa fa-cog\" aria-hidden=\"true\"></i></button></a></td></tr>", ["news_id", "news_title", "news_id"]);

$bodyText = <<<EOF
<div class="container-fluid header-based">
    <div class="row">
        <div class="col-md-12 ta-r">
            <div class="manage-buttons">
                <h1>{$pageName}</h1>
                <a href="/dashboard/news/create/">
                    <button class="edit-product btn btn-default circle">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                </a>
            </div>
        </div>
        <div class="col-md-12">
            <table class="table">
                <thead class="strong">
                    <tr><th width="70" class="ta-c">#</th><th>Заголовок</th><th width="80"></th></tr>
                </thead>
                <tbody>
                    {$products}
                </tbody>
            </table>
        </div>
        <div class="col-md-12 ta-c">
            {$data["links"]}
        </div>
    </div>
</div>
EOF;
