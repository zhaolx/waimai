<?php 
class OrderAction extends Action
{
    public function _initialize()
    {
        login();
    }
    public function index()
    {
        $Order = M('Order');
        import('ORG.Util.Page');
        $count = $Order->count();
        $Page = new Page($count, 20);
        $Page->setConfig('header', '个订单');
        $show = $Page->show();
        $list = $Order->order('time desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
    public function getorder()
    {
        $Ordergood = M('Ordergood');
        $list = $Ordergood->where('order_no=' . $_GET['order_no'])->select();
        echo json_encode($list);
    }
    public function changeorder()
    {
        $Order = M('Order');
        $data = $Order->where('order_no=' . $_POST['order_no'])->setField('state', $_POST['state']);
        echo json_encode($data);
    }
    public function delorder()
    {
        $Order = M('Order');
        $Ordergood = M('Ordergood');
        if ($Ordergood->where('order_no=' . $_GET['order_no'])->delete()) {
            if ($Order->where('order_no=' . $_GET['order_no'])->delete()) {
                $this->success('删除成功');
            } else {
                $this->error('删除失败');
            }
        } else {
            $this->error('删除失败');
        }
    }
}