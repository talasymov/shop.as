<?php
IncludesFn::printHeader("News", "grey");

$bodyText = <<<EOF
<span class="header-blue">Заголовок новости</span>
<input class="news-name design-input" placeholder="Name" value="{$data["news_title"]}">
<input class="news-id" type="hidden" value="{$data["news_id"]}">
<span class="header-blue">Фото</span>
<input class="news-preview design-input" type="file">
<span class="header-blue">Контент</span>
<textarea id="text-content">{$data["news_content"]}</textarea>
<br />
<button class="edit-news btn btn-success">
<i class="fa fa-check" aria-hidden="true"></i>
Сохранить изменения
</button>
<button class="delete-news btn btn-danger">
<i class="fa fa-times" aria-hidden="true"></i>
Удалить новость
</button>
EOF;

$script = <<<EOF
<script src="/Libs/FrontEnd/ckeditor/ckeditor.js"></script>
<script src="/Libs/FrontEnd/core/admin.js"></script>
EOF;

