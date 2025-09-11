<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class NewspaperImageCordinates extends Authenticatable{

  use HasFactory, Notifiable;

	protected $table = 'newspaper_image_cordinates';

    protected $fillable = [
		'newspaper_image_id',
		'height',
		'width',
		'x1',
		'y1',
		'x2',
		'y2',
		'org_height',
		'org_width',
		'org_x1',
		'org_y1',
		'org_x2',
		'org_y2',
		'status',
		'image_dispaly_width',
		'image_natural_width'
    ];

	public function GetRecordById($id){
		return $this::where('id', $id)->first();
	}

	public function UpdateRecord($Details){
		$Record = $this::where('id', $Details['id'])->update($Details);
		return true;
	}

	public function CreateRecord($Details){
		$Record = $this::create($Details);
		return $Record;
	}

}