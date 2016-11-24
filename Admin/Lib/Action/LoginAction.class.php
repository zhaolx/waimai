<?php
class LoginAction extends Action
{
    public function index()
    {
        if ($_POST) {
            if ($_POST['admin'] == 'admin' && $_POST['password'] == 'admin') {
                session('admin', $_POST['admin']);
                $this->success('登陆成功', U('Index/index'));
            } else {
                $this->error('登陆失败');
            }
        }
        $this->display();
    }
}