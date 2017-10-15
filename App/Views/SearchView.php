<?php
IncludesFn::printHeader("Search", "grey");

$echoProduct = ShopFn::DrawProduct($data["result"]["products"], 3);

$query = BF::ClearCode("query", "str", "get");

$bodyText = <<<EOF
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="container-fluid popular-parent">
                <div class="row">
                    <div class="col-md-12 ta-c">
                        <h3>По запросу "{$query}"</h3>
                        Найдено совпадений: {$data["result"]["links"]["countAll"]}
                    </div>
                </div>
                <div class="row">
                    {$echoProduct}
                </div>
                <div class="row ta-c">
                    {$data["result"]["links"]["pagination"]}
                </div>
            </div>
        </div>
    </div>
</div>
EOF;

