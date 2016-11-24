<?php 
class GoodAction extends Action
{
    public function _initialize()
    {
        login();
    }
    public function cate()
    {
        $Cate = M('Cate');
        $Ordergood = M('Ordergood');
        $Good = M('Good');
        if ($_POST) {
            if ($_POST['cid']) {
                if ($Cate->where('cid=' . $_POST['cid'])->save($_POST)) {
                    $this->success('编辑成功');
                } else {
                    $this->error('编辑失败');
                }
            } else {
                if ($Cate->add($_POST)) {
                    $this->success('新增成功');
                } else {
                    $this->error('新增失败');
                }
            }
        }
        if ($_GET['type'] == "del") {
            $Good = M('Good');
            if ($Good->where('cid=' . $_GET['cid'])->find()) {
                $this->success('本分类含有子分类，不能删除！');
            } else {
                if ($Cate->where('cid=' . $_GET['cid'])->delete()) {
                    $this->success('删除成功');
                } else {
                    $this->error('删除失败');
                }
            }
        }
        import('ORG.Util.Page');
        $count = $Cate->count();
        $Page = new Page($count, 10);
        $Page->setConfig('header', '个分类');
        $show = $Page->show();
        $list = $Cate->order('c_order desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach ($list as $key => $v) {
            $list[$key]['good'] = $Good->where('cid=' . $v['cid'])->select();
            $list[$key]['count'] = 0;
            $list[$key]['totalAmount'] = 0;
            foreach ($list[$key]['good'] as $goodkey => $goodv) {
                $list[$key]['count'] = $list[$key]['count'] + $Ordergood->where('gid=' . $goodv['gid'])->sum('num');
                $list[$key]['totalAmount'] = $list[$key]['totalAmount'] + $Ordergood->where('gid=' . $goodv['gid'])->sum('num') * $goodv['price'];
            }
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
    public function getcate()
    {
        $Cate = M('Cate');
        $cate = $Cate->find($_GET['cid']);
        echo json_encode($cate);
    }
    public function goodlist()
    {
        $Good = D('Good');
        $ordergood = M('ordergood');
        import('ORG.Util.Page');
        $count = $Good->count();
        $Page = new Page($count, 10);
        $Page->setConfig('header', '个商品');
        $show = $Page->show();
        $list = $Good->relation(true)->order('g_order desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach ($list as $key => $v) {
            $list[$key]['sales'] = $ordergood->where('gid=' . $v['gid'])->sum('num');
            $sales[$key] = $ordergood->where('gid=' . $v['gid'])->sum('num');
        }
        if ($_GET['type'] == 'sales') {
            array_multisort($sales, SORT_DESC, $list);
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
    public function addgood()
    {
        $Cate = M('Cate');
        $cate = $Cate->order('c_order desc')->select();
        $this->assign('cate', $cate);
        if ($_GET['gid']) {
            $Good = M('Good');
            $good = $Good->where('gid=' . $_GET['gid'])->find();
            $this->assign('g', $good);
        }
        if ($_POST) {
            $Good = M('Good');
            import('ORG.Net.UploadFile');
            $upload = new UploadFile();
            $upload->maxSize = 3145728;
            $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg');
            $upload->savePath = './Public/Uploads/Good/';
            if (!$upload->upload()) {
            } else {
                $info = $upload->getUploadFileInfo();
            }
            if (!empty($info)) {
                $Good->photo = $info[0]['savename'];
                $_POST['img'] = $info[0]['savename'];
            }
            $_POST['infor'] = stripslashes($_POST['infor']);
            if ($Good->add($_POST, $options = array(), $replace = true)) {
                $this->success('新增成功', U('Good/goodlist'));
            } else {
                $this->error('新增失败');
            }
        }
        $this->display();
    }
    public function delgood()
    {
        if ($_GET['gid']) {
            $Good = M('Good');
            $img = $Good->where('gid=' . $_GET['gid'])->getField('img');
            unlink("./Public/Uploads/Good/" . $img);
            if ($Good->where('gid=' . $_GET['gid'])->delete()) {
                $this->success('删除成功', U('Good/goodlist'));
            } else {
                $this->error('删除失败');
            }
        }
    }
    public function changegood()
    {
        $Good = M('Good');
        $data = $Good->where('gid=' . $_POST['gid'])->setField('state', $_POST['state']);
        echo json_encode($data);
    }
}