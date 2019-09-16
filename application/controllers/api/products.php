<?php

class Products extends CI_Controller {

    public $code=404;
    public $status = 'error';
    public $msg='';
    public $details='';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('manage/common_model');
    }

    public function index()
    {
        $user_id = $this->input->post('userid');
        if($user_id!='')
        {
            $val = " product.*,category.CategoryName as CategoryName,reference_price.Price as RefPrice";

            $join = array(
                array(
                    'table'=>'category',
                    'condition'=>'category.Id = product.CatId',
                    'jointype'=> 'left'
                ),
                array(
                    'table'=>'fhs_reference_price',
                    'condition'=>'fhs_reference_price.ProductId = product.Id AND fhs_reference_price.UserId ='.$user_id,
                    'jointype'=> 'left'
                ),

            );
            $array = array('product.Status !=' => 'Delete','category.Status'=>'Enable');

            $list_records = $this->common_model->get_joins('product', $val, $join, $array,'product.Id DESC')->result();

            $temp = array();
            foreach ($list_records as $r){
                    $temp[$r->CatId]['catId'] = $r->CatId;
                    $temp[$r->CatId]['catName'] = $r->CategoryName;


                    if ($r->Image != '') {
                        $url = site_url('uploads/product/' . $r->Image);
                    }else{
                        $url = '';
                    }
                    $temp[$r->CatId]['productList']['pImage'] = $url;
                    array_push($temp[$r->CatId]['productList'], array('pId' => $r->Id, 'pName' => $r->ProductName, 'pPrice' => $r->Price, 'rPrice' => $r->RefPrice, 'pImage' => $url));
            }

            $this->code = '200';
            $this->status = 'success';
            if(!empty($list_records)){
                $this->details = array(
                    'catList' => $temp
                );
            }else{
                $this->msg = "No record found.";
            }



        }else{
            $this->msg = "You have not authority to open this page.";
        }

        $this->output();
    }

    private function output()
    {
        $resp=array(
            "code"=>$this->code,
            "status"=>$this->status,
            "msg"=>$this->msg,
            "user_data"=>$this->details
        );
        echo json_encode($resp);
    }



}
