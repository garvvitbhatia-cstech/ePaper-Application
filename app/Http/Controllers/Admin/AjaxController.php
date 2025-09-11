<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\RouteHelper;
use App\Models\TokenHelper;
use App\Models\Responses;
use ReallySimpleJWT\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Session;
use Validator;
use Mail;
use URL;
use Cookie;
use Illuminate\Validation\Rule;

use App\Models\State;

class AjaxController extends Controller
{
    private static $TokenHelper;
	public function __construct(){
        self::$TokenHelper = new TokenHelper();
	}
	
	public function changePrice(Request $request){
		if(!$request->session()->has('admin_email')){echo 'SessionExpire'; die;}
		$value = $request->input('value');
		$rowID = $request->input('id');
		$field = $request->input('field');
		if(DB::table('products')->where(array('id' => $rowID))->update(array($field => $value))){
			echo "Success"; die;
		}else{
			echo "Error"; die;
		}
	}
	
	public function changeProductStatus(Request $request){
		if(!$request->session()->has('admin_email')){echo 'SessionExpire'; die;}
		$tableName = $request->input('table');
		$rowID = $request->input('rowID');
		$new_status = $request->input('status');
		
		$record = DB::table($tableName)->select('status')->where(array('id' => $rowID))->first();
		$status = $record->status;
		if($tableName != "" && $rowID != "" && $status != "" && is_numeric($rowID) && is_numeric($status)){            
            $newStatus = $new_status;
			if($newStatus == 3){
				DB::table($tableName)->where(array('id' => $rowID))->update(array('status' => $newStatus, 'moderation_status' => 'Pending'));
			}else{
				DB::table($tableName)->where(array('id' => $rowID))->update(array('status' => $newStatus));
			}			
			echo 'Success';die;
		}else{
			echo 'InvalidData'; die;
		}
    }

    public function changeStatus(Request $request){
		if(!$request->session()->has('admin_email')){echo 'SessionExpire'; die;}
		$tableName = $request->input('table');
		$rowID = $request->input('rowID');
		$status = $request->input('status');
		if($tableName != "" && $rowID != "" && $status != "" && is_numeric($rowID) && is_numeric($status)){
            $newStatus = $status == 1 ? 2 : 1;
			DB::table($tableName)->where(array('id' => $rowID))->update(array('status' => $newStatus));
			echo 'Success';die;
		}else{
			echo 'InvalidData'; die;
		}
    }
	
	public function updateOrder(Request $request){
		if($request->Ajax()){
			$postData = $request->all();
			$msg = '';
			if(isset($postData) && !empty($postData)){				
				$actualVal = 0;
				$id = $request->input('id');
				$prev = $request->input('prev');
				$modal = $request->input('modal');
				$currval = $request->input('curval');
				
				$record =  DB::table($modal)->orderBy('ordering', 'DESC')->get('ordering')->first();
				$actualVal = $record->ordering;
				if($currval <= $actualVal && $currval != 0 && is_numeric($currval)){
					$data = DB::table($modal)->where(array('ordering' => $currval))->get()->first();
					#save current row
					DB::table($modal)->where('id',$id)->update(['ordering' => $currval]);
	
					#save previous row
					DB::table($modal)->where('id',$data->id)->update(['ordering' => $prev]);
				}
				$msg = "success";			
			}			
			echo $msg;
		}
		exit;	
	}

    public function deleteRecord(Request $request){
		if(!$request->session()->has('admin_email')){echo 'SessionExpire'; die;}
		$tableName = $request->input('table');
		$rowID = $request->input('rowID');
		if($tableName != "" && $rowID != "" && is_numeric($rowID)){
            DB::table($tableName)->where(array('id' => $rowID))->update(array('status' => 3));
			echo 'Success';die;
		}else{
			echo 'InvalidData'; die;
		}
    }

    public function productsChangeStatus(Request $request){
		if(!$request->session()->has('admin_email')){echo 'SessionExpire'; die;}
		$productIDs = $request->input('productIDs');
		$status = $request->input('status');
        if(count($productIDs) == 0){
            echo 'Please select Products.';die;
        }
		if($status != "" && is_numeric($status)){
            foreach($productIDs as $rowID){
                $newStatus = $status == 1 ? 2 : 1;
                DB::table('products')->where(array('id' => $rowID))->update(array('status' => $newStatus));
            }
			echo 'Success';die;
		}else{
			echo 'InvalidData'; die;
		}
    }

    public function productsDeleteRecord(Request $request){
		if(!$request->session()->has('admin_email')){echo 'SessionExpire'; die;}
		$productIDs = $request->input('productIDs');
		$status = $request->input('status');
        if(count($productIDs) == 0){
            echo 'Please select Products.';die;
        }
        foreach($productIDs as $rowID){
            $newStatus = $status == 1 ? 2 : 1;
            //DB::table('products')->where('id', $rowID)->delete();
            DB::table('products')->where(array('id' => $rowID))->update(array('status' => 3));
        }
        echo 'Success';die;

    }

	public function saveComplain(Request $request){
		$complain = $request->input('comment');
		$rowID = $request->input('OrderId');
		if($rowID != "" && is_numeric($rowID)){
            DB::table('orders')->where(array('id' => $rowID))->update(array('complain' => $complain));
			echo 'Success';die;
		}else{
			echo 'InvalidData'; die;
		}
	}

    public function changeFeedbackEmailStatus(Request $request){
		$is_send_feedback_email = $request->input('OrderStatus');
		$rowID = $request->input('OrderId');
		if($rowID != "" && is_numeric($rowID)){
            DB::table('orders')->where(array('id' => $rowID))->update(array('is_send_feedback_email' => $is_send_feedback_email));
			echo 'Success';die;
		}else{
			echo 'InvalidData'; die;
		}
	}	
	
	public function getState(Request $request){
		if($request->ajax()){
			$country_id = $request->input('countryId');
			$states = DB::table('states')->where('country_id',$country_id)->where('status',1)->orderBy('name')->pluck('name','id');

			echo view('/admin/ajax/get_state',compact('states'));
		}
		exit;
	}
	
	public function getCity(Request $request){
		if($request->ajax()){
			$state_id = $request->input('stateId');
			$cities = DB::table('cities')->where('state_id',$state_id)->where('status',1)->orderBy('city')->pluck('city','id');

			echo view('/admin/ajax/get_city',compact('cities'));
		}
		exit;
	}

}