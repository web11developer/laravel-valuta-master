<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleCategory extends Model
{

    use SoftDeletes;
    use Sluggable;

    protected $dates = ['deleted_at'];

	protected $guarded  = array('id');
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

	/**
	 * Returns a formatted post content entry,
	 * this ensures that line breaks are returned.
	 *
	 * @return string
	 */
	public function description()
	{
		return nl2br($this->description);
	}

	/**
	 * Get the author.
	 *
	 * @return User
	 */
	public function author()
	{
		return $this->belongsTo('App\User');
	}

	/**
	 * Get the slider's images.
	 *
	 * @return array
	 */
	public function articles()
	{
		return $this->hasMany('App\Article');
	}

	/**
	 * Get the category's language.
	 *
	 * @return Language
	 */
	public function language()
	{
		return $this->belongsTo('App\Language');
	}
}
