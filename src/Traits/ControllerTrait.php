<?php

namespace Askedio\Laravel5ApiController\Traits;

use Askedio\Laravel5ApiController\Exceptions\NotAcceptableException;
use Askedio\Laravel5ApiController\Helpers\ApiController;
use Askedio\Laravel5ApiController\Transformers\ApiTransformer;

trait ControllerTrait
{
    /** @var $results */
    private $results;

    public function __construct()
    {
        if (isset($this->version) && app('api')->getVersion() !== $this->version) {
            throw (new NotAcceptableException('not-acceptable'))->withDetails('/application/vnd.api.'.$this->version.'+json');
        }

        $this->results = new ApiController($this);
    }

    public function index()
    {
        return $this->render([
            'success' => 200,
            'error'   => [
              'class'   => \Symfony\Component\HttpKernel\Exception\HttpException::class,
              'message' => 500,
            ],
            'results' => $this->results->index(),
        ]);
    }

    public function store()
    {
        return $this->render([
            'success' => 200,
            'error'   => [
              'class'   => \Symfony\Component\HttpKernel\Exception\HttpException::class,
              'message' => 500,
            ],
            'results' => $this->results->store(),
        ]);
    }

    public function show($idd)
    {
        return $this->render([
            'success' => 200,
            'error'   => [
              'class'   => \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
              'message' => trans('jsonapi::errors.not_found'),
            ],
            'results' => $this->results->show($idd),
        ]);
    }

    public function update($idd)
    {
        return $this->render([
            'success' => 200,
            'error'   => [
              'class'   => \Symfony\Component\HttpKernel\Exception\HttpException::class,
              'message' => 500,
            ],
            'results' => $this->results->update($idd),
        ]);
    }

    public function destroy($idd)
    {
        return $this->render([
            'success' => 200,
            'error'   => [
              'class'   => \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
              'message' => trans('jsonapi::errors.not_found'),
            ],
            'data'    => $this->results->show($idd),
            'results' => $this->results->destroy($idd),
        ]);
    }

    private function render($data)
    {
        if ($data['results']) {
            return response()->jsonapi($data['success'], (new ApiTransformer())->transform(isset($data['data']) ? $data['data'] : $data['results']));
        }

        throw new $data['error']['class']($data['error']['message']);
    }

    public function getAuth()
    {
        return isset($this->auth) ? $this->auth : false;
    }
}
