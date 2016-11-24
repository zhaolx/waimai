<?php
class IndexAction extends Action
{
    public function _initialize()
    {
        click();
        $this->assign('site_name', C('site_name'));
        $this->assign('site_keywords', C('site_keywords'));
        $this->assign('site_description', C('site_description'));
        $this->assign('site_tel', C('site_tel'));
    }
    public function index()
    {
        $this->get_cate();
        $this->get_user();
        $Good = M('Good');
        if ($_GET['cid']) {
            $Cate = M('Cate');
            $map['cid'] = array('eq', $_GET['cid']);
            $catename = $Cate->where('cid=' . $_GET['cid'])->getField('name');
        } else {
            $catename = '全部';
        }
        $map['state'] = array('eq', 1);
        import('ORG.Util.Page');
        $count = $Good->where($map)->count();
        $Page = new Page($count, 12);
        $Page->setConfig('theme', "%upPage%  %first% %prePage% %linkPage% %nextPage% %end% %downPage%");
        $show = $Page->show();
        $list = $Good->where($map)->order('g_order desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('page', $show);
        $this->assign('goodlist', $list);
        $title = "<li><a href='index.php?m=Index&a=index&cid=" . $_GET['cid'] . "'>" . $catename . "</a></li>";
        $this->assign('title', $title);
        $Slide = M('Slide');
        $slide = $Slide->order('s_order desc')->select();
        $this->assign('slide', $slide);
        $this->assign('catename', $catename);
        $this->display();
    }
    public function good()
    {
        $this->get_cate();
        $this->get_user();
        $Good = D('Good');
        $good = $Good->relation(true)->where('gid=' . $_GET['gid'])->find();
        $title = "<li><a href='".U('Index/index',array('cid'=>$good['cid']))."'>" . $good['cate_name'] . "</a></li><li>" . $good['name'] . "</li>";
        $this->assign('title', $title);
        $this->assign('catename', $good['name']);
        $this->assign('good', $good);
        $this->display();
    }
    public function order()
    {
        $Order = M('Order');
        $_POST['time'] = time();
        $_POST['state'] = 0;
        if (!empty($_POST['order_no'])) {
            if ($Order->add($_POST)) {
                $this->success('提交成功');
            } else {
                $this->error('提交失败');
            }
        } else {
            $this->error('提交失败');
        }
    }
    public function ordergood()
    {
        $Ordergood = M('Ordergood');
        if ($Ordergood->add($_POST)) {
            $this->success('提交成功');
        } else {
            $this->error('提交失败');
        }
    }
    public function mlist()
    {
        $Message = M('Message');
        import('ORG.Util.Page');
        $count = $Message->count();
        $Page = new Page($count, 20);
        $Page->setConfig('theme', "%upPage%  %first% %prePage% %linkPage% %nextPage% %end% %downPage%");
        $show = $Page->show();
        $list = $Message->order('time desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('catename', '文章列表');
        $title = "<li><a href='".U('Index/mlist')."'>文章列表</a></li>";
        $this->assign('title', $title);
        $this->display();
    }
    public function message()
    {
        $Message = M('Message');
        $m = $Message->find($_GET['id']);
        $this->assign('catename', $m['title']);
        $title = "<li><a href='".U('Index/message',array('id'=>I('cid')))."'>" . $m['title'] . "</a></li>";
        $this->assign('title', $title);
        $this->assign('m', $m);
        $this->display();
    }
    public function cart()
    {
        $this->assign('catename', '购物车');
        $this->display();
    }
    public function map()
    {
        $this->assign('site_address', C('site_address'));
        $this->assign('site_longitude', C('site_longitude'));
        $this->assign('site_latitude', C('site_latitude'));
        $this->assign('catename', '地图');
        $title = "<li><a href='".U('Index/map')."'>地图</a></li>";
        $this->assign('title', $title);
        $this->display();
    }
    protected function get_cate()
    {
        $Cate = M('Cate');
        $cate = $Cate->order('c_order desc')->select();
        $this->assign('cate', $cate);
    }
    protected function get_user()
    {
        $uid = session('uid');
        $User = M('User');
        $u = $User->where('uid=' . session('uid'))->find();
        $this->assign('user', $u);
    }
}