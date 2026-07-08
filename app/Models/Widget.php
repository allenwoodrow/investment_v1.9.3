<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class Widget extends Model
{
    use HasFactory;
    protected $table = "widget";

    protected $fillable = [
        'integration', 'html'
    ];


    protected $appends = ['safeHtml'];

    function getSafeHtmlAttribute() {
        return base64_decode($this->html);
    }
}
