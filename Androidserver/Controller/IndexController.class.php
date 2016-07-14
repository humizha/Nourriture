<?php
namespace Androidserver\Controller;

use Think\Controller\RestController;

class IndexController extends RestController
{

    public function index()
    {
        $this->response([1], 'json');
    }


    public function upload_dish()
    {
        $userName = $_POST['userName'];
        $dish_name = $_POST['dish_name'];
        $description = $_POST['description'];
        $evaluation = $_POST['evaluation'];
        $column = M('user');
        $condition['userName'] = $userName;
        $userNo = $column->where($condition)->field('userNo')->select();
        $dish = M("dish");
        $data["userNo"] = $userNo;
        $data["dish_name"] = $dish_name;
        $data["description"] = $description;
        $data["evaluation"] = $evaluation;
        $result = $dish->add($data);
        if ($result == true) {
            $this->response('', 'json');
        } else {
            //500表示Internal Server Error,http://baike.baidu.com/link?url=GQRX4hXdWQ9zSxSCX-ZL-yp8L6nQowIyU-s2-_bjNRH50ayjERTJmZc7vkcYenPfqkKGlV-Eq_vvn1UROgvRTa#5_1
            $this->response('', 'json', 500);
        }
    }

    public function admin_table_search()
    {
        $response = array();
        $dish_name = $_POST['dishname'];
        if ($dish_name == "") {
            $response["return_code"] = 1;
            $response["message"] = "请输入菜名";
        } else {
            $colum = M("dish");
            $result = $colum->where("dish_name like '%$dish_name%'")->select();
            $response["return_code"] = 0;
            $response["message"] = "查找成功";
            $response["data"] = $result;
        }
        $this->response($response, 'json');
    }

    public function add_user()
    {
        $response = array();
        $username = $_POST['username'];
        $password = $_POST['password'];
        $phonenum = $_POST['phonenum'];
        if ($username == "") {
            $response["return_code"] = 1;
            $response["message"] = "用户名为空";
        } elseif ($password == "") {
            $response["return_code"] = 1;
            $response["message"] = "密码为空";
        } else {
            $user = M("user");
            $condition['userName'] = $username;
            $result = $user->where($condition)->select();
            if (empty($result)) {
                $data["userName"] = $username;
                $data["password"] = $password;
                $data["email"] = $phonenum;
                $user->add($data);
                $response["return_code"] = 0;
                $response["message"] = "注册成功";
            } else {
                $response["return_code"] = 1;
                $response["message"] = "用户名已存在";
            }
        }
        // var_dump($response);
        //    var_dump();
        echo json_encode($response);
    }

    public function login_user()
    {

        $response = array(
            "return_code" => 1,
            'message' => "请输入用户名和密码"
        );

        $username = $_POST['username'];
        $password = $_POST['password'];
        $user = M("user");
        $condition['userName'] = $username;
        $result = $user->where($condition)->field('password')->select();
        if (!empty($result)) {
            if ($password == $result[0]["password"]) {
                $response["return_code"] = 0;
                $response["message"] = "登录成功";
             } else {
                $response["return_code"] = 2;
                $response["message"] = "密码错误";
             }
        } else {
            $response["return_code"] = 3;
            $response["message"] = "用户名不存在";
        }
            $this->response($response, 'json');
    }

    public function search_restaurant(){
        $response = array(
            "return_code" => 1,
            'message' => "There is no restaurant near you"
        );
        $location = $_POST['location'];
        if ($location != "") {
            $restaurant = M("restaurant");
            $result = $colum->where("addr like '%$location%'")->select();
            $response["return_code"] = 0;
            $response["message"] = "查找成功";
            $response["data"] = $result;  
        }                           
        $this->response($response, 'json');

    }
}
