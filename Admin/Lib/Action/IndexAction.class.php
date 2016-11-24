<?php 
class IndexAction extends Action
{
    public function _initialize()
    {
        login();
    }
    public function index()
    {
        $Order = M('Order');
        $count['Orderall'] = $Order->where('state=1')->count();
        $User = M('User');
        $count['Userall'] = $User->count();
        $Good = M('Good');
        $count['Goodall'] = $Good->count();
        $Click = M('Click');
        $click = $Click->find();
        $this->assign('count', $count);
        $this->assign('click', $click);
        $this->display();
    }
}