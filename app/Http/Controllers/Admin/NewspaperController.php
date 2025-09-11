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

class NewspaperController extends Controller{
	
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
        return view('/admin/newspapers/index');
    }
    public function listPaginate(Request $request){
		if(!$request->session()->has('admin_email')){return redirect('/admin/');}
        $query = self::$Newspaper->where('status', '!=', 3)->where('type','Newspaper');
		if($request->input('title')  && $request->input('title') != ""){
            $SearchKeyword = $request->input('title');
            $query->where('title', 'like', '%'.$SearchKeyword.'%');
		}
		$records =  $query->orderBy('id', 'DESC')->paginate(20);
		
		foreach($records as $key => $record){
			$paperImage = self::$NewspaperImages->where('status', '!=', 3)->where('newspaper_id', $record->id)->orderBy('ordering','ASC')->first();
			$record->front_image = isset($paperImage->title) ? $paperImage->title : '';
		}
		
        return view('/admin/newspapers/paginate',compact('records'));
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
					$setData['end_date'] = $request->input('end_date');
					

                   $record = self::$Newspaper->CreateRecord($setData);
					echo json_encode(array('heading'=>'Success','msg'=>'Newspaper added successfully'));die;
			}
		}	
		return view('/admin/newspapers/add-page');
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
					$setData['end_date'] = $request->input('end_date');
                    self::$Newspaper->UpdateRecord($setData);
                echo json_encode(array('heading'=>'Success','msg'=>'Newspaper updated successfully'));die;
			}
		}		
        if(isset($rowData->id)){			
            return view('/admin/newspapers/edit-page',compact('rowData','row_id'));
        }else{
            return redirect('/admin/newspapers');
        }
    }
	#editNewspaperPage
    public function editNewspaperPage(Request $request, $row_id){
		$RowID =  base64_decode($row_id);
		if(!$request->session()->has('admin_email')){return redirect('/admin/');}
		$rowData = self::$NewspaperImages->where(array('id' => $RowID))->first();
        if($request->input()){
			
		}		
        if(isset($rowData->id)){
			$backUrl = $_SERVER['HTTP_REFERER'];	
			$cordinates = self::$NewspaperImageCordinates->where('newspaper_image_id', $rowData->id)->where('status', '!=', 3)->get();
			$allPages = self::$NewspaperImages->where('status', '!=', 3)->where('newspaper_id', $rowData->newspaper_id)->orderBy('ordering','ASC')->get();
            return view('/admin/newspapers/edit-newspaper-page',compact('rowData','row_id','cordinates','allPages','backUrl'));
        }else{
            return redirect('/admin/newspapers');
        }
    }
	#getVehicleImages
    public function getNewspaperImage(Request $request){
		$records = self::$NewspaperImages->where('newspaper_id', $request->pid)->where('status', '!=', 3)->orderBy('ordering','ASC')->get();
		return view('/admin/newspapers/newspaper_images', compact('records'));
	}
	#updateNewspaperImageOrder
    public function updateNewspaperImageOrder(Request $request){
		self::$NewspaperImages->where('id', $request->pid)->update(['ordering' => $request->ordering]);
		echo 'Success'; die;
	}
	#saveNewspaperPageCordinates
    public function saveNewspaperPageCordinates(Request $request){
		$setData['newspaper_image_id'] = $request->input('page_id');
		$setData['height'] = $request->input('height');
		$setData['width'] = $request->input('width');
		$setData['x1'] = $request->input('x1');
		$setData['y1'] = $request->input('y1');
		$setData['x2'] = $request->input('x2');
		$setData['y2'] = $request->input('y2');
		
		
		$setData['org_height'] = ($request->input('height')*$request->input('original_width'))/$request->input('display_width');
		$setData['org_width'] = ($request->input('width')*$request->input('original_width'))/$request->input('display_width');
		$setData['org_x1'] = ($request->input('x1')*$request->input('original_width'))/$request->input('display_width');
		$setData['org_y1'] = ($request->input('y1')*$request->input('original_width'))/$request->input('display_width');
		$setData['org_x2'] = ($request->input('x2')*$request->input('original_width'))/$request->input('display_width');
		$setData['org_y2'] = ($request->input('y2')*$request->input('original_width'))/$request->input('display_width');
		
		$setData['image_dispaly_width'] = $request->input('display_width');
		$setData['image_natural_width'] = $request->input('original_width');
		
		
		$record = self::$NewspaperImageCordinates->CreateRecord($setData);
		echo 'Success'; die;
	}
	#newspaperUpload
    public function newspaperUpload(Request $request){
		if($request->ajax()){
			$postData = $request->all();
			$msg = '';
            if(!empty($_FILES)){
                $msg = "Error";
				$ordering = pathinfo($_FILES['file']['name'],PATHINFO_FILENAME);
                $fileName = $_FILES['file']['name']; //Get the image
                $file_full = base_path().'/public/admin/images/newspapers/';
				$actual_image_name2 = time().rand().'.'.$request->file->extension();  
                $file_temp_name = $_FILES['file']['tmp_name'];
                $pathInfo = pathinfo(basename($fileName));
                $ext = $pathInfo['extension'];
                $checkImage = getimagesize($file_temp_name);				
                if($checkImage !== false){
                    if($request->file->move($file_full, $actual_image_name2)){
						
						if(!is_numeric($ordering)){
							$max_order = self::$NewspaperImages->where('status', '!=', 3)->where('newspaper_id', $_REQUEST['pid'])->max('ordering');
							if($max_order == ''){
								$ordering = 1;
							}else{
								$ordering = $max_order+1;
							}
						}
						
						$setData['newspaper_id'] = $_REQUEST['pid'];
						$setData['title'] = $actual_image_name2;
						$setData['ordering'] = $ordering;
						self::$NewspaperImages->CreateRecord($setData);	
                        $msg = $actual_image_name2;
                    }
                }
            }
            echo $msg;
        }
        exit;
	}

}