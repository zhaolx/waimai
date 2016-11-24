<?php
?>

<?php 
class SlideAction extends Action
{
    public function _initialize()
    {
        login();
    }
    public function index()
    {
        $Slide = M('Slide');
        import('ORG.Util.Page');
        $count = $Slide->count();
        $Page = new Page($count, 20);
        $Page->setConfig('header', '个幻灯片');
        $show = $Page->show();
        $list = $Slide->order('s_order desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
    public function del()
    {
        if ($_GET['id']) {
            $Slide = M('Slide');
            $img = $Slide->where('id=' . $_GET['id'])->getField('imgurl');
            unlink("./Public/Uploads/Slide/" . $img);
            if ($Slide->where('id=' . $_GET['id'])->delete()) {
                $this->success('删除成功', U('Slide/index'));
            } else {
                $this->error('删除失败');
            }
        }
    }
    public function add()
    {
        if ($_POST) {
            $Slide = M('Slide');
            import('ORG.Net.UploadFile');
            $upload = new UploadFile();
            $upload->maxSize = 3145728;
            $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg');
            $upload->savePath = './Public/Uploads/Slide/';
            if (!$upload->upload()) {
                $this->error($upload->getErrorMsg());
            } else {
                $info = $upload->getUploadFileInfo();
            }
            $Slide->photo = $info[0]['savename'];
            $_POST['imgurl'] = $info[0]['savename'];
            if ($Slide->add($_POST)) {
                $this->success('新增成功', U('Slide/index'));
            } else {
                $this->error('新增失败');
            }
        }
    }
}