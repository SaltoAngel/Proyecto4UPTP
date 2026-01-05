<?php

namespace App\Services;

class NotificationService
{
    public static function success($message, $title = '¡Éxito!')
    {
        return [
            'icon' => 'success',
            'title' => $title,
            'text' => $message,
            'timer' => 2000,
            'showConfirmButton' => false,
            'timerProgressBar' => true
        ];
    }

    public static function error($message, $title = 'Error')
    {
        return [
            'icon' => 'error',
            'title' => $title,
            'text' => $message,
            'confirmButtonText' => 'Entendido',
            'confirmButtonColor' => '#28a745'
        ];
    }

    public static function warning($message, $title = 'Advertencia')
    {
        return [
            'icon' => 'warning',
            'title' => $title,
            'text' => $message,
            'confirmButtonText' => 'Entendido',
            'confirmButtonColor' => '#28a745'
        ];
    }

    public static function info($message, $title = 'Información')
    {
        return [
            'icon' => 'info',
            'title' => $title,
            'text' => $message,
            'confirmButtonText' => 'Entendido',
            'confirmButtonColor' => '#28a745'
        ];
    }

    public static function validation($errors, $title = 'Revisa los datos')
    {
        $errorList = is_array($errors) ? $errors : [$errors];
        $html = '<ul style="text-align: left; margin: 0; padding-left: 20px;">';
        foreach ($errorList as $error) {
            $html .= '<li>' . $error . '</li>';
        }
        $html .= '</ul>';

        return [
            'icon' => 'warning',
            'title' => $title,
            'html' => $html,
            'confirmButtonText' => 'Entendido',
            'confirmButtonColor' => '#28a745'
        ];
    }
}