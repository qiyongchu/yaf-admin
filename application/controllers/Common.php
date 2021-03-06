<?php
use Service\user\DbUserAuth;

/**
 * 加了用户验证功能的控制器
 * Class CommonController
 */
abstract class CommonController extends \Core\Controller{

    /**
     * @var array 用户登录信息
     */
	public $userInfo = [];
    /**
     * @var boolean 是否开启用户验证
     */
    public $doAuth = true;

    public function init(){
        parent::init();
        if($this->doAuth){
            $this->auth();
            if(!$this->userInfo){
                $this->redirectTo('login', 'index');
            }
            $this->getView()->userInfo = $this->userInfo;
        }
    }

    /**
     * 用户认证
     * @return bool
     */
	protected function auth(){
        $userInfo = DbUserAuth::getUserBySession();
		 if( $userInfo ){
		 	$this->userInfo = $userInfo;
             return true;
		 }

        $cookieToken = $this->cookie(DbUserAuth::TOKEN_NAME);
         if( $cookieToken ){
             $result = (new DbUserAuth)->getUserByToken($cookieToken);
             if(!$result){
                 return $result;
             }
             $this->userInfo = $result;
             return true;
         }

        return false;
	}
}