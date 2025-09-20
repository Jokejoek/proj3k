<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cve extends Model
{   
    protected $table      = 'pj_vulnerabilities';
    protected $primaryKey = 'cve_id';
    public $incrementing  = false;
    protected $keyType    = 'string';
    public $timestamps    = false; // ใช้ published_date/last_modified แล้ว

    /** รายการล่าสุดไว้ใช้หน้า main */
    public function scopeRecent($q, $limit = 8)
    {
        return $q->orderByDesc('published_date')
                 ->orderByDesc('last_modified')
                 ->limit($limit)
                 ->select(['cve_id','description']);
    }

    public function getRouteKeyName() { return 'cve_id'; }

    protected $fillable = [
        'cve_id','title','description','severity','cvss_score',
        'vendor','product','image_url','year','exploit_available',
        'published_date','last_modified','created_by_user_id'
    ];

    protected $casts = [
        'cvss_score'        => 'float',
        'year'              => 'integer',
        'exploit_available' => 'boolean',
        'published_date'    => 'datetime',
        'last_modified'     => 'datetime',
    ];

    // คอมเมนต์หน้าอ่าน: เก็บ view ผ่าน morph (ของคุณถูกแล้ว)
    public function views()
    {
        return $this->morphMany(\App\Models\ContentView::class, 'viewable');
    }

    /** Scope กรอง/ค้นหา/เรียงสำหรับหน้า List */
    public function scopeFilter($q, array $f)
    {
        $q->when($f['q'] ?? null, fn($qq,$kw) =>
            $qq->where(fn($w)=>$w->where('cve_id','like',"%$kw%")
                                 ->orWhere('title','like',"%$kw%")
                                 ->orWhere('vendor','like',"%$kw%")
                                 ->orWhere('product','like',"%$kw%"))
        );

        $q->when($f['severity'] ?? null, fn($qq,$sev) => $qq->whereIn('severity',(array)$sev));
        $q->when($f['year'] ?? null,     fn($qq,$yr)  => $qq->where('year',(int)$yr));
        $q->when($f['vendor'] ?? null,   fn($qq,$v)   => $qq->where('vendor','like',"%$v%"));
        $q->when($f['product'] ?? null,  fn($qq,$p)   => $qq->where('product','like',"%$p%"));
        $q->when(($f['exploitable'] ?? '') !== '', fn($qq)=>$qq->where('exploit_available',1));

        $q->when($f['sort'] ?? null, function($qq,$sort){
            return match($sort){
                'cvss_desc'      => $qq->orderByDesc('cvss_score'),
                'cvss_asc'       => $qq->orderBy('cvss_score'),
                'published_asc'  => $qq->orderBy('published_date'),
                'year_desc'      => $qq->orderByDesc('year'),
                'year_asc'       => $qq->orderBy('year'),
                default          => $qq->orderByDesc('published_date'),
            };
        }, fn($qq)=>$qq->orderByDesc('published_date'));
    }
}
