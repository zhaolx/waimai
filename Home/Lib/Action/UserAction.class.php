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
                $this->success('��½�ɹ�', U('Index/index'));
            } else {
                $this->success('��½�ɹ�', U('Index/index'));
            }
        } else {
            $this->error('��½ʧ��');
        }
    }
    public function register()
    {
        $User = M('User');
        $_POST['regtime'] = time();
        $_POST['password'] = md5(md5($_POST['password']));
        if ($User->where('tel="' . $_POST['tel'] . '"')->getField('uid')) {
            $this->error('���ֻ����Ѿ���ע�ᣡ');
        } else {
            if ($User->add($_POST)) {
                $uid = $User->where('tel="' . $_POST['tel'] . '" and password="' . $_POST['password'] . '"')->getField('uid');
                session('uid', $uid);
                $this->success('ע��ɹ���', U('Index/index'));
            } else {
                $this->error('ע��ʧ�ܣ�');
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
        $Page->setConfig('header', '������');
        $show = $Page->show();
        $list = $Order->where('uid=' . session('uid'))->order('time desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('catename', "�û�����");
        $title = "<li><a href='".U('User/edit')."'>�û�����</a></li>";
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
                $this->success('ɾ���ɹ�');
            } else {
                $this->error('ɾ��ʧ��');
            }
        } else {
            $this->error('ɾ��ʧ��');
        }
    }
    public function add()
    {
        $User = M('User');
        if ($User->where('uid=' . session('uid'))->save($_POST)) {
            $this->success('�༭�ɹ�');
        } else {
            $this->error('�༭ʧ��');
        }
    }
    public function logout()
    {
        session(null);
        $this->success('�˳��ɹ���', U('Index/index'));
    }
    protected function is_login()
    {
        $uid = session('uid');
        if (empty($uid)) {
            header("location:".U('Index/index'));
        }
    }
}