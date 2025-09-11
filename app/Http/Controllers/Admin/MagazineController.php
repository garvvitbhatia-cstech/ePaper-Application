<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Newspaper;
use App\Models\NewspaperImages;
use App\Models\NewspaperImageCordinates;
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

class MagazineController extends Controller{
	
	private static $Newspaper;
    private static $TokenHelper;	
	private static $NewspaperImages;
	private static $NewspaperImageCordinates;	
	
	public function __construct(){
		self::$Newspaper = new Newspaper();
        self::$TokenHelper = new TokenHelper();
		self::$NewspaperImages = new NewspaperImages();
		self::$NewspaperImageCordinates = new NewspaperImageCordinates();
	}

    #admin dashboard page
    public function getList(Request $request){
		if(!$request->session()->has('admin_email')){return redirect('/admin/');}
        return view('/admin/magazines/index');
    }
    public function listPaginate(Request $request){
		if(!$request->session()->has('admin_email')){return redirect('/admin/');}
        $query = self::$Newspaper->where('status', '!=', 3)->where('type','Magazine');
		if($request->input('title')  && $request->input('title') != ""){
            $SearchKeyword = $request->input('title');
            $query->where('title', 'like', '%'.$SearchKeyword.'%');
		}
		$records =  $query->orderBy('id', 'DESC')->paginate(20);
		
		foreach($records as $key => $record){
			$paperImage = self::$NewspaperImages->where('status', '!=', 3)->where('newspaper_id', $record->id)->orderBy('ordering','ASC')->first();
			$record->front_image = isset($paperImage->title) ? $paperImage->title : '';
		}
		
        return view('/admin/magazines/paginate',compact('records'));
    }

    #add new Brand
    public function addPage(Request $request){
		if(!$request->session()->has('admin_email')){return redirect('/admin/');}
		if($request->input()){
			$validator = Validator::make($request->all(), [
                'paper_date' => 'required',
            ],[
                'paper_date.required' => 'Please enter date.',
            ]);
			if($validator->fails()){
				$errors = $validator->errors();
				if($errors->first('paper_date')){
                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('paper_date')));die;
				}
			}else{
                if(isset($request->image) && $request->image->extension() != ""){
                        $actual_image_name = strtolower(sha1(str_shuffle(microtime(true).mt_rand(100001, 999999)).uniqid(rand().true).$request->file('image')).'.'.$request->image->extension());
                            $destination = base_path() . '/public/img/newspapers/';
                            $request->image->move($destination, $actual_image_name);
                            $setData['pdf_file'] = $actual_image_name;
                    }

		
                    $setData['type'] = $request->input('type');
					$setData['paper_date'] = $request->input('paper_date');
					

                   $record = self::$Newspaper->CreateRecord($setData);
					echo json_encode(array('heading'=>'Success','msg'=>'Magazine added successfully'));die;
			}
		}	
		return view('/admin/magazines/add-page');
    }

    #edit Brand
    public function editPage(Request $request, $row_id){
		$RowID =  base64_decode($row_id);
		if(!$request->session()->has('admin_email')){return redirect('/admin/');}
		$rowData = self::$Newspaper->where(array('id' => $RowID))->first();
        if($request->input()){
			$validator = Validator::make($request->all(), [
                'paper_date' => 'required',
            ],[
                'paper_date.required' => 'Please enter date.',
            ]);
			if($validator->fails()){
				$errors = $validator->errors();
				if($errors->first('paper_date')){
                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('paper_date')));die;
				}
			}else{
               if(isset($request->image) && $request->image->extension() != ""){
                        $actual_image_name = strtolower(sha1(str_shuffle(microtime(true).mt_rand(100001, 999999)).uniqid(rand().true).$request->file('image')).'.'.$request->image->extension());
                            $destination = base_path() . '/public/img/newspapers/';
                            $request->image->move($destination, $actual_image_name);
                            $setData['pdf_file'] = $actual_image_name;
                            if($rowData->pdf_file != ""){
                                if(file_exists($destination.$rowData->pdf_file)){
                                    unlink($destination.$rowData->pdf_file);
                                }
                            }
                    }

                    $setData['id'] =  $RowID;

                    $setData['paper_date'] = $request->input('paper_date');
                    self::$Newspaper->UpdateRecord($setData);
                echo json_encode(array('heading'=>'Success','msg'=>'Magazine updated successfully'));die;
			}
		}		
        if(isset($rowData->id)){			
            return view('/admin/magazines/edit-page',compact('rowData','row_id'));
        }else{
            return redirect('/admin/magazines');
        }
    }

}