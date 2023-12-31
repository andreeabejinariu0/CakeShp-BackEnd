<?php
    namespace App\Http\Controllers\API;
    use Illuminate\Http\Request; 
    use App\Http\Controllers\Controller; 
    use App\Models\User; 
    use App\Models\Role; 
    use Illuminate\Support\Facades\Auth; 
    use Validator;
    use Illuminate\Database\Eloquent\ModelNotFoundException;
    
    class UserController extends Controller 
{
    public $successStatus = 200;
    
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(){ 
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) 
        {
            $user = Auth::user();
            $userRole = $user->role()->first();

            $user_id = $user->id;
            $user_name = $user->name;
            $success['token'] = $user->createToken('MyApp', [$userRole->role] )->accessToken;
            return response()->json(['success' => $success, 'name' => $user_name], $this-> successStatus);
            //return response()->json(['message' => "success", 'id' => $user_id, 'name' => $user_name], $this->successStatus);
        } 
        else 
        {
            //return response()->json(['error'=>'Unauthorised'], 401);
            return response()->json(['message' => "error"], 401);
        }
    }

    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
        ]);
        if ($validator->fails()) { 
            return response()->json(['message'=>"error"], 401);            
        }
            $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $user = User::create($input); 

        // Creăm înregistrarea în tabelul "roles" pentru a atribui rolul "basic" utilizatorului creat
        Role::create([
            'user_id' => $user->id,
            'role' => 'basic',
        ]);

        $user_id = $user->id;
        $user_name = $user->name;
        $success['token'] =  $user->createToken('MyApp')-> accessToken; 
        $success['name'] =  $user->name;
        return response()->json(['message'=>"success", 'id' => $user_id, 'name' => $user_name], $this-> successStatus); 
    }
    /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['message' => $user], $this-> successStatus); 
    } 

    function getUsers(Request $request) {

        return User::get();
    }

    function addUser(Request $request) {

        return User::create($request->all());
    }


    function updateUser(Request $request, $userId) {

        try {
            $user = User::findOrFail($userId);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User not found.'
            ], 403);
        }

        $user->update($request->all());

        return response()->json(['message' => 'User updated successfully.']);
    }

    function deleteUser(Request $request, $userId) {

        try {
            $user = User::find($userId);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User not found.'
            ], 403);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully.']);
    }

    function adminUser(Request $request, $userId) {
        try {
            $user = User::find($userId);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User not found.'
            ], 403);
        }
        // Actualizare coloană "role" la valoarea "admin
        $user->role()->update(['role' => 'admin']); 

        return response()->json(['message' => 'User updated to admin successfully.']);

    }

}