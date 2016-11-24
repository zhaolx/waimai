<?php
?>

<?php 
class UserAction extends Action
{
    public function _initialize()
    {
        click();
        $this->assign('site_name', C('site_name'));
        $this->assign('site_keywords', C('site_keywords'));
        $this->assign('site_description', C('site_description'));
        $this->assign('site_tel', C('site_tel'));
    }
    public function login()
    {
        $User = M('User');
        $u = $User->where('tel="' . $_POST['tel'] . '" and password="' . md5(md5($_POST['password'])) . '"')->find();
        if ($u['uid']) {
            session('uid', $u['uid']);
            if ($u['name']) {
                $this->success('登陆成功', U('Index/index'));
            } else {
                $this->success('登陆成功', U('Index/index'));
            }
        } else {
            $this->error('登陆失败');
        }
    }
    public function register()
    {
        $User = M('User');
        $_POST['regtime'] = time();
        $_POST['password'] = md5(md5($_POST['password']));
        if ($User->where('tel="' . $_POST['tel'] . '"')->getField('uid')) {
            $this->error('该手机号已经被注册！');
        } else {
            if ($User->add($_POST)) {
                $uid = $User->where('tel="' . $_POST['tel'] . '" and password="' . $_POST['password'] . '"')->getField('uid');
                session('uid', $uid);
                $this->success('注册成功！', U('Index/index'));
            } else {
                $this->error('注册失败！');
            }
        }
    }
    public function edit()
    {
        $this->is_login();
        $User = M('User');
        $u = $User->where('uid=' . session('uid'))->find();
        $this->assign('u', $u);
        $Order = M('Order');
        import('ORG.Util.Page');
        $count = $Order->where('uid=' . session('uid'))->count();
        $Page = new Page($count, 10);
        $Page->setConfig('header', '个订单');
        $show = $Page->show();
        $list = $Order->where('uid=' . session('uid'))->order('time desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('catename', "用户中心");
        $title = "<li><a href='".U('User/edit')."'>用户中心</a></li>";
        $this->assign('title', $title);
        $this->display();
    }
    public function getorder()
    {
        $Ordergood = M('Ordergood');
        $list = $Ordergood->where('order_no=' . $_GET['order_no'])->select();
        echo json_encode($list);
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
    public function add()
    {
        $User = M('User');
        if ($User->where('uid=' . session('uid'))->save($_POST)) {
            $this->success('编辑成功');
        } else {
            $this->error('编辑失败');
        }
    }
    public function logout()
    {
        session(null);
        $this->success('退出成功！', U('Index/index'));
    }
    protected function is_login()
    {
        $uid = session('uid');
        if (empty($uid)) {
            header("location:".U('Index/index'));
        }
    }
}