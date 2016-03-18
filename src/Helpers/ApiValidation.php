<?php

namespace Askedio\Laravel5ApiController\Helpers;

use Askedio\Laravel5ApiController\Exceptions\BadRequestException;


/**
 * Intended to validate the ApiObjects collection.
 */
class ApiValidation
{

  private $objects;

    public function __construct($objects)
    {
        $this->objects=$objects;
        $this->validateIncludes();
        $this->validateFields();
        $this->validateRequests();
    }

    public function validateRequests()
    {
        if (!request()->isMethod('post') && !request()->isMethod('patch')) {
            return;
        }

        $request = request()->json()->all();
        $fillable = $this->objects->getFillables();

        $errors = array_diff(array_keys($request), $fillable->flatten()->all());
        if (!empty($errors)) {
            throw (new BadRequestException('invalid_filter'))->withDetails($errors);
        }
    }

  /**
   * Check if includes get variable is valid.
   *
   * @return void
   */
  public function validateIncludes()
  {
      $allowed = $this->objects->getIncludes();
      $includes = app('api')->includes();

      $errors = array_diff($includes->all(), $allowed->all());

      if (!empty($errors)) {
          throw (new BadRequestException('invalid_include'))->withDetails($errors);
      }
  }

  /**
   * Validate fields belong.
   *
   * @return array
   */
  public function validateFields()
  {
      $fields = app('api')->fields();
      $columns = $this->objects->getColumns();
      $includes = $this->objects->getIncludes();

      $errors = array_diff($fields->keys()->all(), $includes->all());
      if (!empty($errors)) {
          throw (new BadRequestException('invalid_filter'))->withDetails($errors);
      }

      $errors = $fields->map(function ($item, $key) use ($columns) {
        return array_diff($item, $columns->get($key));
      })->flatten()->all();

      if (!empty($errors)) {
          throw (new BadRequestException('invalid_filter'))->withDetails($errors);
      }
  }
}