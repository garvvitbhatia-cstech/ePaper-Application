<?php
namespace App\Helpers;
use DB;
use Session;
class Helper{
	public static function getServiceType($id){
		$records = DB::table('service_types')->where(array('id' => $id))->first();
        return $records;
	}
	public static function getService($id){
		$records = DB::table('services')->where(array('id' => $id))->first();
        return $records;
	}
	public static function getLicenseType($id){
		$records = DB::table('license_types')->where(array('id' => $id))->first();
        return $records;
	}
	public static function getLicense($id){
		$records = DB::table('licenses')->where(array('id' => $id))->first();
        return $records;
	}
	public static function getHospital($id){
		$records = DB::table('hospitals')->where(array('id' => $id))->first();
        return $records;
	}
	public static function getTotalProduct($id,$type){
		$query = DB::table('products');
		$query->where('status',1);
		if($type == 'Vendor'){
			$query->where('vendor_id',$id);
		}
		$records = $query->count();
        return $records;
	}
	public static function getTotalProductOut($id,$type){
		$query = DB::table('products');
		$query->where('status',1);
		$query->where('stock','YES');
		if($type == 'Vendor'){
			$query->where('vendor_id',$id);
		}
		$records = $query->count();
        return $records;
	}
	public static function getTotalCustomer($id,$type){
		$query = DB::table('users');
		$query->where('status',1);
		$records = $query->count();
        return $records;
	}
	public static function getTotalOrder($id,$type){
		$query = DB::table('orders');
		$query->where('payment_status','SUCCESS');
		if($type == 'Vendor'){
			//$query->where('vendor_id',$id);
		}
		$records = $query->count();
        return $records;
	}
	public static function getTotalOrderAmount($id,$type){
		$query = DB::table('orders');
		$query->where('payment_status','SUCCESS');
		if($type == 'Vendor'){
			//$query->where('vendor_id',$id);
		}
		$records = $query->get();
		$totalAmount = 0;
		foreach($records as $key => $record){
			$totalAmount = $totalAmount+$record->grand_total;
		}
        return number_format($totalAmount,2);
	}
	public static function getNoOfProducts($ids){
		$explode = explode(',',$ids);
		$totalCount = 0;
		foreach($explode as $key => $singleID){
			$records = DB::table('products')->whereRaw('FIND_IN_SET('.$singleID.', ailment_id)')->count();
			$totalCount = $totalCount+$records;
		}
		
        return $totalCount;
	}
	public static function getAilments($ids){
		$explode = explode(',',$ids);
		$ailmentName = [];
		foreach($explode as $key => $singleID){
			$row = DB::table('ailments')->where(array('id' => $singleID))->first();
			$ailmentName[] = $row->title;
		}
		return implode(', ',$ailmentName);
	}
	public static function getSpecialities($ids){
		$explode = explode(',',$ids);
		$ailmentName = [];
		foreach($explode as $key => $singleID){
			$row = DB::table('specialties')->where(array('id' => $singleID))->first();
			$ailmentName[] = $row->title;
		}
		return implode(', ',$ailmentName);
	}
	public static function getSpeciality($id){
		$records = DB::table('specialties')->where(array('id' => $id))->first();
        return $records;
	}
	public static function getProductInfo($id){
		$records = DB::table('products')->where(array('id' => $id))->first();
        return $records;
	}
	public static function getShipmentInfo($id){
		$records = DB::table('order_shipping_histories')->where(array('order_id' => $id))->orderBy('id','DESC')->get();
        return $records;
	}
	public static function getUserInfo($id){
		if($id > 0){
			$records = DB::table('users')->where(array('id' => $id))->first();
        	return $records;
		}else{
			return 'Aayush Bharat';
		}
	}
	public static function getUserName($id){
		if($id > 0){
			$records = DB::table('users')->where(array('id' => $id))->first();
        	return $records->name;
		}else{
			return 'Aayush Bharat';
		}
	}
	public static function getSubscriptionInfo($id){
		$records = DB::table('subscriptions')->where(array('id' => $id))->first();
        return $records;
	}
	public static function getOrderItems($id){
		$records = DB::table('order_items')->where(array('order_id' => $id))->get();
        return $records;
	}
	public static function getProductImages($id){		
		$productImages = DB::table('product_images')->where(array('product_id' => $id))->orderBy('img_ordering', 'ASC')->get();
		$count = 0;
		foreach($productImages as $key => $val){
			if(!empty($val->image)){
				$data = $val->image;
				$count = 1;
				break;	
			}
		}
		$response = '';
		if($count == 1){
			$response = $data;
		}		
        return $response;
	}
	
	public static function getSellProductStatus($pid,$vendor_id){
		$record = DB::table('products')->where(array('vendor_pid' => $pid,'vendor_id' => $vendor_id))->first();
		//print_r($record);die;
		$status = NULL;
		if(isset($record->id)){
			$status = $record->vendor_sale_status;
		}
		return $status;
	}
	
	public static function getOrderStatus($ordId = NULL,$field = NULL){
		if($ordId != ''){
			$query = DB::table('orders')->select($field)->where('id',$ordId)->first();			
			return $query->$field;
		}else{
			return NULL;
		}		
	}
	
	public static function getShippingMethod($shipping_id = NULL,$field = NULL){
		if($shipping_id != ''){
			$query = DB::table('shipping_methods')->select($field)->where('id',$shipping_id)->first();			
			return $query->$field;
		}else{
			return NULL;
		}
	}
	
	public static function getPrescriptionOrder($order_id = NULL){
		$row_id = NULL;
		if($order_id != ''){
			$query = DB::table('orders')->select(['id'])->where('prescription_order_id',$order_id)->first();
			if(isset($query->id)){
				$row_id = $query->id;
			}
		}
		return $row_id;
	}
	
	public static function getOrder($ordId = NULL){
		if($ordId != ''){
			return DB::table('orders')->where('id',$ordId)->first();
		}else{
			return NULL;
		}		
	}
	
	public static function getSubCategory($categoryList=NULL,$parentId=NULL,$editId=NULL){		
		$list = '<option value="0">Root</option>';
		if(!empty($categoryList)){
			foreach($categoryList as $keys => $vals):
				$seleted = '';
				$disabled = '';
				$newList = DB::table('categories')->where('parent_id',$vals->id)->get();
				if($parentId == $vals->id){$seleted = 'selected="selected"';}
				$list .= '<option '.$seleted.' value="'.$vals->id.'">'.ucwords($vals->title).'</option>';
				foreach($newList as $nKey => $nVal):
					$seleted2 = $seleted3 = '';
					$disabled2 = $disabled3 = '';
						if($parentId == $nVal->id){$seleted2 = 'selected="selected"';}
						$list .= '<option '.$seleted2.' value="'.$nVal->id.'"> → '.ucwords($nVal->title).'</option>';
						$newList2 = DB::table('categories')->where('parent_id',$nVal->id)->get();
						foreach($newList2 as $nKey => $nVal2):
						$seleted3 = '';
						$disabled3 = '';
						if($parentId == $nVal2->id){$seleted3 = 'selected="selected"';}
						$list .= '<option '.$seleted3.' value="'.$nVal2->id.'"> → → '.ucwords($nVal2->title).'</option>';
						endforeach;
				endforeach;
			endforeach;
			return $list;
		}
	}
	
	#get sub category  data
    public static function getNavigationCategory($categoryList=NULL,$parentId=NULL,$editId=NULL){
		$list = '<option value="0">Root</option>';
		if(!empty($categoryList)){
			foreach($categoryList as $keys => $vals):
				$seleted = '';
				$disabled = '';
				$menuPageTitle = '';
				$newList = DB::table('header_navigations')->where('parent_id',$keys)->get();
				if($parentId == $keys){$seleted = 'selected="selected"';}
				if($editId == $keys){$disabled = 'disabled="disabled"';}
				$list .= '<option '.$seleted.' '.$disabled.' value="'.$keys.'">'.ucwords($vals).'</option>';				
				foreach($newList as $nKey => $nVal):
					if(isset($nVal->menu_page_id) && !empty($nVal->menu_page_id)){
						$menuPageTitle = $this->getCmsPagesTitle($nVal->menu_page_id);	
						$list .= '<option disabled="disabled" value=""> → '.$menuPageTitle.'</option>';
					}					
				endforeach;				
			endforeach;			
			return $list;
		}       
	}
	
}
?>