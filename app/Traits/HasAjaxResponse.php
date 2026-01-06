<?php

namespace App\Traits;

use App\Services\NotificationService;

trait HasAjaxResponse
{
    protected function ajaxSuccess($message = 'Operación exitosa', $data = [])
    {
        return response()->json(array_merge([
            'success' => true,
            'message' => $message,
            'notification' => NotificationService::success($message)
        ], $data));
    }

    protected function ajaxError($message = 'Error en la operación', $errors = null, $status = 400)
    {
        $response = [
            'success' => false,
            'message' => $message,
            'notification' => NotificationService::error($message)
        ];

        if ($errors) {
            $response['errors'] = $errors;
            $response['notification'] = NotificationService::validation($errors);
        }

        return response()->json($response, $status);
    }

    protected function ajaxValidationError($errors, $message = 'Datos inválidos')
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'notification' => NotificationService::validation($errors)
        ], 422);
    }
}