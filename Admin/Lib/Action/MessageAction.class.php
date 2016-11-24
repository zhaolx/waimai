<?php 
class MessageAction extends Action
{
    public function _initialize()
    {
        login();
    }
    public function index()
    {
        $this->display();
    }
    public function add()
    {
        if ($_POST) {
            $Message = M('Message');
            if ($_POST['id']) {
                if ($Message->where('id=' . $_POST['id'])->save($_POST)) {
                    $this->success('编辑成功', U('Message/mlist'));
                } else {
                    $this->error('编辑失败');
                }
            } else {
                $_POST['time'] = time();
                if ($Message->add($_POST)) {
                    $this->success('发布成功', U('Message/mlist'));
                } else {
                    $this->error('发布失败');
                }
            }
        }
        if ($_GET['id']) {
            $Message = M('Message');
            $m = $Message->where('id=' . $_GET['id'])->find();
            $this->assign('m', $m);
        }
        $this->display();
    }
    public function del()
    {
        if ($_GET['id']) {
            $Message = M('Message');
            if ($Message->where('id=' . $_GET['id'])->delete()) {
                $this->success('删除成功', U('Message/mlist'));
            } else {
                $this->error('删除失败');
            }
        }
    }
    public function mlist()
    {
        $Message = M('Message');
        import('ORG.Util.Page');
        $count = $Message->count();
        $Page = new Page($count, 20);
        $Page->setConfig('header', '个资讯');
        $show = $Page->show();
        $list = $Message->order('time desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
}