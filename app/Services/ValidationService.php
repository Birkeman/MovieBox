<?php


namespace App\Services;

use Illuminate\Foundation\Http\FormRequest;

class ValidationService
{
    public function __construct()
    {
        //
    }

    public function validateFilterData(FormRequest $request) {

        if ($this->checkIfEmpty($request)) {

            return false;
        }

        $this->fixData($request);

        $this->addDefaultValues($request);

        return true;
    }

    public function fixData(FormRequest $request) {

        if ($request->min_rating > $request->max_rating) {

            $request->max_rating = $request->min_rating + 0.1;
        }

        if ($request->min_year > $request->max_year) {

            $request->max_year = $request->min_year + 1;
        }
    }

    public function addDefaultValues(FormRequest $request) {

        if (!$request->filled('min_rating')) {

            $request->min_rating = intval(1);
        }

        if (!$request->filled('max_rating')) {

            $request->max_rating = intval(10);
        }

        if (!$request->filled('min_year')) {

            $request->min_year = intval(1970);
        }

        if (!$request->filled('max_year')) {

            $request->max_year = intval(date("Y"));
        }
    }

    public function checkIfEmpty(FormRequest $request) {

        if (empty($request->min_rating) && empty($request->max_rating) &&
            empty($request->min_year) && empty($request->max_year)) {

            return true;
        }

        return false;
    }
}
