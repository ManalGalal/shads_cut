<?php

namespace App\Traits;

use Illuminate\Support\Facades\App;

trait Localizable {
    protected $langs = ["en", "ar"];
    public $is_localizable = true;
    public function __construct($attributes = []) {
        foreach ($this->localizable as $localized_attribute) {
            $this->appends[] = $localized_attribute;
            foreach ($this->langs as $lang) {
                $this->hidden[] = $localized_attribute . '_' . $lang;
            }
        }
        // hide original attributes like attribute_en, attribute_ar
        return parent::__construct($attributes);
    }
    public function __get($attribute) {
        $isLocalizable = in_array($attribute, $this->localizable);
        if ($isLocalizable) {
            $lang = App::getLocale();
            $lang_exist = in_array($lang, $this->langs);
            if (!$lang_exist) {
                $lang = "en";
            }
            return $this->{$attribute . '_' . $lang};
        }
        return parent::__get($attribute);
    }
    public function __call($method, $args) {
        foreach ($this->localizable as $attribute) {
            if ($method === $this->getMethodNameForAttribute($attribute)) {
                return $this->{$attribute};
            }
        }
        return parent::__call($method, $args);
    }
    public function disable_localization() {
        foreach ($this->localizable as $localized_attribute) {
            // add the localizable attribute to $hidden array
            $this->hidden[] = $localized_attribute;
            foreach ($this->langs as $lang) {

                // find element that was localized and remove it from hidden
                // name => remove name_en, ar
                $index_of_element = array_search($localized_attribute . '_' . $lang, $this->hidden);
                if ($index_of_element === false) {
                    continue;
                }
                unset($this->hidden[$index_of_element]);
            }
        }
        return $this;
    }
    public function getMethodNameForAttribute($attribute) {
        $formated_attribute = "";
        if (str_contains($attribute, '_')) {
            $attributes = explode('_', $attribute);

            foreach ($attributes as $single) {
                $formated_attribute = $formated_attribute . ucfirst($single);
            }
            return "get" . $formated_attribute . "Attribute";
        }
        return "get" . ucfirst($attribute) . "Attribute";
    }
}
