<?php

namespace App\Http\Controllers\v2\auth;
use Laravel\Lumen\Routing\Controller as BaseController;

// method
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;

// define models start
// db_hr
use App\Models\hrModels\hrUsersModel;

// db_project_management
use App\Models\pmModels\authModel;

// define models end


class TokenController extends BaseController
{

    public function __construct(){
        // builder models
        $this->hrUsersModel = new hrUsersModel;
        $this->authModel = new authModel;
    }

    public function getLoginProject(Request $request){
        
        //define data
        $user_id = $request->input('user_id');
        $token = $request->input('token');
        
        $query = DB::connection('db_hr')->table('login')
        ->select(
            'login.user_id',
            'login.token',
            'login.created_at',
        )
        ->where('login.user_id',$user_id)
        ->where('login.token',$token)
        ->get();

        $user = array();
        if($query){
            $status = 200;
            $message = "Success";
            $user = array(
                'username'=>'admin@mail.com',
                'password'=>'password',
            );
            
        }else{
            $status = 401;
            $message = "Forbidden";
        }
        
        $response = array(
            "status"=>$status,
            "message"=>$message,
            "user"=>$user,
        );

        return response()->json($response);
    }

    function get(Request $request){ 

        //define data
        $user_id = $request->input('user_id');
        $token = $request->input('token');
        
        $query = DB::connection('db_hr')->table('login')
        ->select(
            'login.user_id',
            'login.token',
            'login.created_at',
        )
        ->where('login.user_id',$user_id)
        ->where('login.token',$token)
        ->get();

        $data_login = array();
        if($query){
            $status = 200;
            $message = "Success";
            foreach($query as $list){
                $data_login = array(
                    "user_id"=>$list->user_id,
                    "token"=>$list->token,
                    "created_at"=>$list->created_at,
                );
            }
        }else{
            $status = 401;
            $message = "Forbidden";
        }
        
        $response = array(
            "status"=>$status,
            "message"=>$message,
            "metadata"=>$data_login,
        );

        return response()->json($response);
        
    }

    public function store(Request $request){

        $email = $request->username;
        $password = $request->password;
        $ip = $request->getClientIp();
        $agent = $request->userAgent();
        if(isset($request->agent)){
            $agent = $request->agent;
        }
        $timestamp = gmdate('Y-m-d H:i:s', time()+(60*60*7));

        $conditions_user = array(
            array('users.email', $email),
            array('users.active', 1),
        );

        $user = $this->hrUsersModel->get($conditions_user);

        $response = '';
        if(count($user)>0){
            if(Hash::check($password, $user['password'])){

                $conditions_auth = array(
                    array('auth.user_id', $user['id']),
                    array('auth.agent', $agent),
                    array('auth.active', 1),
                );
                $auth = $this->authModel->get($conditions_auth);

                if(count($auth)>0){
                    // update active token
                    $data_auth_update = array(
                        "active"=>0,
                        "updated_at"=>$timestamp,
                    );
            
                    DB::connection('db_project_management')->table('auth')
                    ->where($conditions_auth)
                    ->update(
                        $data_auth_update
                    );
                }

                // renew token
                $token = Uuid::uuid4();

                $data = array(
                    "user_id"=>$user['id'],
                    "token"=>$token,
                    "client"=>$ip,
                    "agent"=>$agent,
                    "created_at"=>$timestamp,
                    "updated_at"=>$timestamp,
                );
        
                DB::connection('db_project_management')->table('auth')
                ->insert(
                    $data
                );

                $status = "200";
                $message = "OK.";
                $response = $token;
                
            }else{
                $status = "401";
                $message = "Unauthorized.";
            }
            
        }else{
            $status = "404";
            $message = "Not Found.";
            
        }
        
        $response = array(
            "status"=>$status,
            "message"=>$message,
            "response"=>$response,
        );

        return response()->json($response);

    }

    public function destroy(Request $request, $token){

        $timestamp = gmdate('Y-m-d H:i:s', time()+(60*60*7));

        $status = "404";
        $message = "Not Found.";
        $response = '';
        
        $conditions_auth = array(
            array('auth.token', $token),
            array('auth.active', 1),
        );
        $auth = $this->authModel->get($conditions_auth);

        if(count($auth)>0){
            $data = array(
                "active"=>0,
                "updated_at"=>$timestamp,
            );
    
            DB::connection('db_project_management')->table('auth')
            ->where('token', $token)
            ->update(
                $data
            );

            $status = "200";
            $message = "OK.";
            $response = $token;
        }

        $response = array(
            "status"=>$status,
            "message"=>$message,
            "response"=>$response,
        );

        return response()->json($response);
    }

}

?>