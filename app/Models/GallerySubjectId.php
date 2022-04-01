<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GallerySubjectId extends Model
{
    protected $table = 'gallery_subject_id';

    protected $fillable = [
        'p_id', 'alias', 'active', 'deleted', 'level', 'position', 'leader', 'new', 'img', 'height', 'width'
    ];

    public static $globalLangId;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        self::$globalLangId = !is_null(request()->get('lang')) ? (int)request()->get('lang') : LANG_ID;
    }

    public function galleryItemId(){
        return $this->hasOne('App\Models\GalleryItemId', 'gallery_subject_id', 'id');
    }

    public function children()
    {
        return $this->hasMany('App\Models\GallerySubjectId', 'p_id', 'id');
    }

    public function itemByLang()
    {
        return $this->hasOne('App\Models\GallerySubject', 'gallery_subject_id', 'id')->where('lang_id', self::$globalLangId);
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\GallerySubjectId', 'p_id', 'id');
    }

    public function galleryMedia(){
        return $this->hasmany('App\Models\GalleryItemId', 'gallery_subject_id', 'id');
    }

}
