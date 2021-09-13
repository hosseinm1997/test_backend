<?php

namespace Infrastructure\Service;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class JsonResponseTransformer
{
    public function transform($content, Throwable $e = null)
    {
        $statusCode = 200;

        //if has error
        if ($e) {

            $statusCode = 0;
            $errorDetails = null;

            //handle some exceptions
            if($e instanceof ModelNotFoundException)
                $e = new NotFoundHttpException(trans('errors.Entity not found!'));
            elseif ($e instanceof AuthorizationException)
                $statusCode = 403;

            if ($e instanceof HttpExceptionInterface) {
                $statusCode = $e->getStatusCode();
            } elseif ($e instanceof AuthenticationException) {
                $statusCode = 401;
            } elseif ($e instanceof ValidationException) {
                $statusCode = 422;
                $errorDetails = $this->transformValidationException($e);
            }
            $statusCode = $statusCode ?: 500;

            //default message
            $defaultMessage = trans('متاسفانه خطایی رخ داده است');

            if ($statusCode == 403)
                $defaultMessage = trans('errors.Access denied');

            $content = [
                'code'    => $statusCode,
                'message' => $statusCode == 500 ? trans('متاسفانه خطایی رخ داده است') : ($e->getMessage() ?: $defaultMessage),
                'details' => $errorDetails
            ];
        }

        $mainResponse = is_array($content) ? $content : json_decode($content, true);

        $response = ['error' => $mainResponse];
        if ($statusCode == 200) {
            $message = null;
            if (is_array($mainResponse) && isset($mainResponse['message'])) {
                $message = $mainResponse['message'];
                unset($mainResponse['message']);
            }

            if (!isset($mainResponse['data']))
                $mainResponse = ['data' => $mainResponse];

            $response = [
                'message' => $message,
                'outcome' => $mainResponse ?: null
            ];
        }

        return response()->json($response)->setStatusCode($statusCode);
    }

    /**
     * @param $exception
     * @return null
     */
    private function transformValidationException($exception)
    {
        $errors = null;

        //TODO: refactor this method later

        if ($exception->validator->failed()) {
            foreach ($exception->validator->failed() as $key => $rules) {
                $firstRule = array_key_first($rules);
                $firstRule = strpos($firstRule, '\\') ? Arr::last(explode('\\', $firstRule)) : $firstRule;
                $errors[] = [
                    'key'     => $key,
                    'type'    => $firstRule,
                    'code'    => $this->getValidationRuleCode($firstRule),
                    'message' => $exception->validator->getMessageBag()->first($key),
                ];
            }
        } elseif ($exception->validator->messages()->messages()) {
            foreach ($exception->validator->messages()->messages() as $key => $rules) {
                $errors[] = [
                    'key'     => $key,
                    'type'    => 'generic',
                    'code'    => 0,
                    'message' => $exception->validator->getMessageBag()->first($key),
                ];
            }
        }

        return $errors;
    }

    /**
     * @param $rule
     * @return int|mixed
     */
    private function getValidationRuleCode($rule)
    {
        $rule = strtolower($rule);
        $mapRuleCode = [
            'required'               => 1,
            'emailorphone'           => 2,
            'in'                     => 3,
            'phonerule'              => 4,
            'array'                  => 5,
            'questionlimitationrole' => 6,
            'min'                    => 7,
            'max'                    => 8,
            'regex'                  => 9,
            'unique'                 => 10,
            'confirmed'              => 11,
        ];

        if (array_key_exists($rule, $mapRuleCode))
            return $mapRuleCode[$rule];
        return 0;
    }

}
