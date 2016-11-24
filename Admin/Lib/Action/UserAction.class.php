<?php 
class UserAction extends Action
{
    public function _initialize()
    {
        login();
    }
    public function index()
    {
        $User = M('User');
        $Order = M('Order');
        import('ORG.Util.Page');
        $count = $User->count();
        $Page = new Page($count, 20);
        $Page->setConfig('header', '个会员');
        $show = $Page->show();
        $list = $User->order('regtime desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach ($list as $key => $v) {
            $list[$key]['order_count'] = $Order->where('uid=' . $v['uid'])->count();
            $list[$key]['totalAmount'] = $Order->where('uid=' . $v['uid'])->sum('totalAmount');
            $order_count[$key] = $Order->where('uid=' . $v['uid'])->count();
            $totalAmount[$key] = $Order->where('uid=' . $v['uid'])->sum('totalAmount');
        }
        if ($_GET['type'] == 'totalAmount') {
            array_multisort($totalAmount, SORT_DESC, $list);
        } elseif ($_GET['type'] == 'order_count') {
            array_multisort($order_count, SORT_DESC, $list);
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
    public function logout()
    {
        session(null);
		$url = U('Login/index');
        header("location:".$url);
    }
}