<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class HasParent implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $count;
    private $parent;
    public function __construct($exchange_id,$parent)
    {
        $this->parent = $parent;
        if ($exchange_id == 0) {
            $this->count = 0;
        } else {
            $this->count = DB::table('exchangers')->where('parent', $exchange_id)->count();
        }
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return  $this->parent == 0 || $this->count == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'У этого обменника есть филиалы, родительское поле должно быть Без родителя';
    }
}
