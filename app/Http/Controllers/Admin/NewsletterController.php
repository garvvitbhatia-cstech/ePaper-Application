<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Newsletters;
use App\RouteHelper;
use App\Models\TokenHelper;
use App\Models\Responses;
use ReallySimpleJWT\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Session;
use Validator;
use Mail;
use URL;
use Cookie;
use Illuminate\Validation\Rule;

class NewsletterController extends Controller{
	
	private static $Newsletters;
    private static $TokenHelper;	
	
	public function __construct(){
		self::$Newsletters = new Newsletters();
        self::$TokenHelper = new TokenHelper();
	}

    #admin dashboard page
    public function getList(Request $request){
		if(!$request->session()->has('admin_email')){return redirect('/admin/');}
        return view('/admin/newsletter/index');
    }
    public function listPaginate(Request $request){
		if(!$request->session()->has('admin_email')){return redirect('/admin/');}
        $query = self::$Newsletters->where('status', '!=', 3);
		if($request->input('title')  && $request->input('title') != ""){
            $SearchKeyword = $request->input('title');
            $query->where('title', 'like', '%'.$SearchKeyword.'%');
		}
		$records =  $query->orderBy('id', 'DESC')->paginate(20);
		
        return view('/admin/newsletter/paginate',compact('records'));
    }

    #add new Brand
    public function addPage(Request $request){
		if(!$request->session()->has('admin_email')){return redirect('/admin/');}
		if($request->input()){
			$validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ],[
                'email.required' => 'Please enter email.',
            ]);
			if($validator->fails()){
				$errors = $validator->errors();
				if($errors->first('email')){
                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('email')));die;
				}
			}else{
				$exist = self::$Newsletters->where('email',strtolower($request->input('email')))->count();
				if($exist == 0){
					$setData['email'] = strtolower($request->input('email'));
					$record = self::$Newsletters->CreateRecord($setData);
					echo json_encode(array('heading'=>'Success','msg'=>'Newsletter added successfully'));die;
				}else{
					return json_encode(array('heading'=>'Error','msg'=>'Email already exist'));die;
				}
			}
		}	
		return view('/admin/newsletter/add-page');
    }

    #edit Brand
    public function editPage(Request $request, $row_id){
		$RowID =  base64_decode($row_id);
		if(!$request->session()->has('admin_email')){return redirect('/admin/');}
		$rowData = self::$Newsletters->where(array('id' => $RowID))->first();
        if($request->input()){
			$validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ],[
                'email.required' => 'Please enter email.',
            ]);
			if($validator->fails()){
				$errors = $validator->errors();
				if($errors->first('email')){
                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('email')));die;
				}
			}else{
				$exist = self::$Newsletters->where('email',strtolower($request->input('email')))->where('id','!=',$RowID)->count();
				if($exist == 0){
					$setData['id'] =  $RowID;
					$setData['email'] = strtolower($request->input('email'));
					self::$Newsletters->UpdateRecord($setData);
					echo json_encode(array('heading'=>'Success','msg'=>'Newsletter updated successfully'));die;
				}else{
					return json_encode(array('heading'=>'Error','msg'=>'Email already exist'));die;
				}
			}
		}		
        if(isset($rowData->id)){			
            return view('/admin/newsletter/edit-page',compact('rowData','row_id'));
        }else{
            return redirect('/admin/newsletters');
        }
    }

}