<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\AdminUser;
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
use App\Models\Newsletters;
use Session;
use Validator;
use Mail;
use URL;
use Cookie;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
	private static $UserModel;
	private static $Newspaper;
	private static $TokenHelper;	
	private static $NewspaperImages;
	private static $NewspaperImageCordinates;
	private static $Newsletters;
	
	public function __construct(){
		self::$UserModel = new AdminUser();
        self::$Newspaper = new Newspaper();
        self::$TokenHelper = new TokenHelper();
		self::$NewspaperImages = new NewspaperImages();
		self::$NewspaperImageCordinates = new NewspaperImageCordinates();
		self::$Newsletters = new Newsletters();
	}
    #index
    public function index (Request $request){
        return view('index');
    }
	#newspaper
    public function newspaper (Request $request){
		
		$latestRecord = self::$Newspaper->where('status',1)->where('type','Newspaper')->orderBy('paper_date','DESC')->first();
		return redirect('/newspaper-details/'.$latestRecord->id);
		
		$records = self::$Newspaper->where('status',1)->where('type','Newspaper')->orderBy('paper_date','DESC')->get();
		foreach($records as $key => $record){
			$paperImage = self::$NewspaperImages->where('status',1)->where('newspaper_id', $record->id)->orderBy('ordering','ASC')->first();
			$record->front_image = isset($paperImage->title) ? $paperImage->title : '';
		}
        return view('newspaper',compact('records'));
    }
	#newspaperDetails
    public function newspaperDetails (Request $request,$id){
		$paperData = self::$Newspaper->where('id', $id)->first();
		$allPages = self::$NewspaperImages->where('status',1)->where('newspaper_id', $id)->orderBy('ordering','ASC')->get();
		$pages = self::$NewspaperImages->where('status',1)->where('newspaper_id', $id)->orderBy('ordering','ASC')->paginate(1);
		foreach($pages as $key => $page){
			$cordinates = self::$NewspaperImageCordinates->where('newspaper_image_id', $page->id)->where('status',1)->get();
			$page->partitions = $cordinates;
		}
		$pageNo = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
		
		#############
		$records = self::$Newspaper->select('paper_date')->where('status',1)->where('type','Newspaper')->orderBy('paper_date','DESC')->get();
		$dates = [];
		foreach($records as $key => $record){
			$dates[] = date('m-d-Y',strtotime($record->paper_date));
		}

        return view('newspaper_details',compact('pages','allPages','id','paperData','pageNo','dates'));
    }
	#newspaperDetails
    public function getNewspaper (Request $request,$date){
		$latestRecord = self::$Newspaper->select('id')->where('paper_date',$date)->first();
		return redirect('/newspaper-details/'.$latestRecord->id);
	}
	#downloadPdf
    public function downloadPdf (Request $request,$id){
		$paperData = self::$Newspaper->where('id', $id)->first();
		
		$file = $_SERVER['DOCUMENT_ROOT'].'/public/img/newspapers/'.$paperData->pdf_file;
 
		if (file_exists($file)) {
			header('Content-Type: application/pdf');
			header('Content-Disposition: attachment; filename="' . basename($file) . '"');
			header('Content-Length: ' . filesize($file));
			readfile($file);
			exit;
		} else {
			echo "File not found."; die;
		}
	}
	#saveNewsletter
    public function saveNewsletter (Request $request){
		$validator = Validator::make($request->all(), [
                'newsletetr_email' => 'required|email',
            ],[
                'newsletetr_email.required' => 'Please enter email.',
            ]);
			if($validator->fails()){
				$errors = $validator->errors();
				if($errors->first('newsletetr_email')){
                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('newsletetr_email')));die;
				}
			}else{
				$exist = self::$Newsletters->where('email',strtolower($request->input('newsletetr_email')))->count();
				if($exist == 0){
					$setData['email'] = strtolower($request->input('newsletetr_email'));
					$record = self::$Newsletters->CreateRecord($setData);
					echo json_encode(array('heading'=>'Success','msg'=>'Newsletter added successfully'));die;
				}else{
					echo json_encode(array('heading'=>'Success','msg'=>'Newsletter added successfully'));die;
				}
			}
    }
	#magazine
    public function magazine (Request $request){
		$records = self::$Newspaper->where('status',1)->where('type','Magazine')->orderBy('paper_date','DESC')->get();
		foreach($records as $key => $record){
			$paperImage = self::$NewspaperImages->where('status',1)->where('newspaper_id', $record->id)->orderBy('ordering','ASC')->first();
			$record->front_image = isset($paperImage->title) ? $paperImage->title : '';
		}
        return view('magazine',compact('records'));
    }
	#getCordinates
    public function getCordinates (Request $request){
		$pageID = 0;
		$allPages = self::$NewspaperImages->where('status',1)->where('newspaper_id', $request->id)->orderBy('ordering','ASC')->get();
		foreach($allPages as $key => $allPage){
			if(($key+1) == $request->page_no){
				$pageID = $allPage->id;
			}
		}
		$html = '';
		$cordinates = self::$NewspaperImageCordinates->where('newspaper_image_id', $pageID)->where('status',1)->get();
		foreach($cordinates as $key => $cordinate){
			$x1 = ($cordinate->org_x1*$request->width)/$cordinate->image_natural_width;
			$y1 = ($cordinate->org_y1*$request->width)/$cordinate->image_natural_width;
			$x2 = ($cordinate->org_x2*$request->width)/$cordinate->image_natural_width;
			$y2 = ($cordinate->org_y2*$request->width)/$cordinate->image_natural_width;
			$html .='<area onclick="loadModal('.round($cordinate->org_x1).','.round($cordinate->org_y1).','.round($cordinate->org_height).','.round($cordinate->org_width).')" shape="rect" coords="'.round($x1).','.round($y1).','.round($x2).','.round($y2).'" />';
		}
		echo $html; die;
    }

}
