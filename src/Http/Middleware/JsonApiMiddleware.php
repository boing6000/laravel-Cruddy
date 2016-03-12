<?php

namespace Askedio\Laravel5ApiController\Http\Middleware;

use Askedio\Laravel5ApiController\Exceptions\BadRequestException;
use Askedio\Laravel5ApiController\Exceptions\NotAcceptableException;
use Askedio\Laravel5ApiController\Exceptions\UnsupportedMediaTypeException;
use Askedio\Laravel5ApiController\Helpers\Api;
use Closure;

class JsonApiMiddleware
{
    private $request;

    /**
     * Filter requests based on Accept and Content-Type matches.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->request = $request;
        $this->checkGetVars();
        $this->checkAccept();
        $this->checkContentType();

        return $next($request);
    }

    /**
     * Check GET input variaibles.
     *
     * @return void
     */
    private function checkGetVars()
    {
        if (!$this->request->isMethod('get')) {
            return false;
        }

        $_check = array_except($this->request->all(), config('jsonapi.allowed_get'));
        if (empty($_check)) {
            return false;
        }

        $_errors = [];

        foreach (array_keys($_check) as $_field) {
            array_push($_errors, [
            'code'   => 0,
            'source' => ['pointer' => $_field],
            'title'  => config('errors.invalid_get.title'),
          ]);
        }

        $exception = new BadRequestException('invalid_get');
        throw $exception->withDetails(['errors' => $_errors]);
    }

    /**
     * Check Accept Header.
     *
     * @return void
     */
    private function checkAccept()
    {
        $matches = preg_match('/application\/vnd\.api\.([\w\d\.]+)\+([\w]+)/', $this->request->header('Accept'), $matches);

        app('api')->setVersion(isset($matches[1]) ? $matches[1] : config('jsonapi.version'));

        if ($matches || $this->request->header('Accept') == config('jsonapi.accept') || !config('jsonapi.strict')) {
            return;
        }

        $exception = new NotAcceptableException('not-acceptable');
        throw $exception->withDetails(config('jsonapi.accept'));
    }

    /**
     * Check Content-Type Header.
     *
     * @return void
     */
    private function checkContentType()
    {
        if ($this->request->header('Content-Type') == config('jsonapi.content_type') || !config('jsonapi.strict')) {
            return;
        }

        $exception = new UnsupportedMediaTypeException('unsupported-media-type');
        throw $exception->withDetails(config('jsonapi.content_type'));
    }
}
