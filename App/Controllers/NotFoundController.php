<?php
class NotFoundController extends Controller
{
    public function IndexAction($params = null)
    {
        $this->view->GetTemplate("DefaultPage.php", "NotFoundView.php");
    }
}