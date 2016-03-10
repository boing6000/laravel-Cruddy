<?php

namespace Askedio\Laravel5ApiController\Helpers;

use Validator;
use Request;

class ControllerHelper
{
    private $_modal;
    private $request;

    public function __construct($modal)
    {
        $this->_modal = $modal;
        $this->_modal->checkIncludes();
        $this->request = Request();
    }

    public function index()
    {
        $results = $this->_modal->setSort($this->request->input('sort'));

        if ($this->request->input('search') && $this->_modal->isSearchable()) {
            $results->search($this->request->input('search'));
        }

        return $results->paginate(($this->request->input('limit') ?: '10'));
    }

    /* TO-DO: need to update to accept and validate json api spec array */
    public function store()
    {
        if ($errors = $this->validate('create')) {
            return ['errors' => $errors];
        } else {
            return $this->_modal->create($this->cleanRequest());
        }
    }

    public function show($id)
    {
        return $this->_modal->find($id);
    }

    /* TO-DO: need to update to accept and validate json api spec array */
    public function update($id)
    {
        if ($errors = $this->validate('update')) {
            return ['errors' => $errors];
        } else {
            $_modal = $this->_modal->find($id);

            return $_modal
              ? (
                  $_modal->update($this->cleanRequest())
                  ? $_modal
                  : false
                )
              : false;
        }
    }

    public function destroy($id)
    {
        $_modal = $this->_modal->find($id);

        return $_modal ? $_modal->delete() : false;
    }

    /* TO-DO: need to update to accept and validate json api spec array */
    private function cleanRequest()
    {
        $_allowed = $this->_modal->getFillable();
        $request = $this->request->json()->all();

        foreach ($request as $var => $val) {
            if (!in_array($var, $_allowed)) {
                unset($request[$var]);
            }
        }

        return $request;
    }

    /* TO-DO: need to update to accept and validate json api spec array */
    private function validate($action)
    {
        $validator = Validator::make($this->request->json()->all(), $this->_modal->getRule($action));
        $_errors = [];
        $e = $validator->errors()->toArray();
        foreach ($validator->errors()->toArray() as $_field => $_err) {
            $_errors[] = [
            'source' => ['pointer' => $_field],
            'title'  => config('errors.invalid_attribute.title'),
            'detail' => implode(' ', $_err),
          ];
        }

        return $validator->fails() ? $_errors : false;
    }

  
}
