<?php

namespace App\Traits;

use App\Enums\ResponseCode;
use App\Helpers\RespondHepler;
use Symfony\Component\HttpFoundation\Response;

trait JsonRespondController {

    /**
     * @var int
     */
    protected $statusCode = Response::HTTP_OK;

    /**
     * @var int|string
     */
    protected $code = ResponseCode::SUCCESS;

    /**
     * @var string
     */
    protected $message = '';

    /**
     * Summary of setStatusCode
     * @param int $statusCode
     * @return mixed
     */
    public function setStatusCode(int $statusCode) {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Summary of getStatusCode
     * @return int
     */
    public function getStatusCode() {
        return $this->statusCode;
    }

    /**
     * Summary of setCode
     * @param int|string $code
     * @return mixed
     */
    public function setCode(int|string $code) {
        $this->code = $code;

        return $this;
    }

    /**
     * Summary of getCode
     * @return int|string
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * Summary of setMessage
     * @param string $message
     * @return mixed
     */
    public function setMessage(string $message) {
        $this->message = $message;

        return $this;
    }

    /**
     * Summary of getMessage
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * Summary of setDefaultMessageByCode
     * @return mixed
     */
    public function setDefaultMessageByCode() {
        $this->message = ($this->message !== '') ? $this->message : __('messages.' . $this->code);

        return $this;
    }

    public function response(array $data = [], array $errors = [], array $headers = []) {
        return RespondHepler::formatJsonResponseData(
            $this->code,
            $this->message,
            $this->statusCode,
            $data,
            $errors,
            $headers
        );
    }

    public function responseSuccess(array $data = [], array $headers = []) {

        return $this->setCode(ResponseCode::SUCCESS)
            ->setDefaultMessageByCode()
            ->setStatusCode(Response::HTTP_OK)
            ->response($data, [], $headers);
    }

    public function responseBadRequest(array $errors = [], array $headers = []) {

        return $this->setCode(ResponseCode::BAD_REQUEST)
            ->setDefaultMessageByCode()
            ->setStatusCode(Response::HTTP_BAD_REQUEST)
            ->response([], $errors, $headers);
    }
}