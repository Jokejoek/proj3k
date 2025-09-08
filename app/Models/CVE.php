<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cve extends Model
{
    protected $table      = 'pj_vulnerabilities'; // ชื่อตารางของคุณ
    protected $primaryKey = 'cve_id';
    public $incrementing  = false;
    protected $keyType    = 'string';

    // ให้ Route Model Binding หา record ด้วย cve_id (เช่น CVE-2025-1234)
    public function getRouteKeyName()
    {
        return 'cve_id';
    }

    protected $fillable = [
        'cve_id','title','description','severity','cvss_score',
        'vendor','product','image_url','year','exploit_available',
        'published_date','last_modified'
    ];
    public function views()
    {
        return $this->morphMany(\App\Models\ContentView::class, 'viewable');
    }
}
