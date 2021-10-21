<?php

use Illuminate\Http\UploadedFile;
use Vinkla\Hashids\Facades\Hashids;
use App\Repositories\FileRepository;
use Infrastructure\Service\FarazSms;
use App\Enumerations\FileCategoryEnums;

if (!function_exists('sendSmsByPattern')) {

    function sendSmsByPattern($mobile, $pattern, $parameter = array())
    {
        $farazNotification = new FarazSms();

       return $farazNotification->sendSmsByPattern(
            $mobile,
            $parameter,
            $pattern
        );
    }
}


if (!function_exists('auth_user')) {

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null|\App\Models\User
     */
    function auth_user()
    {
        return auth()->user();
    }
}

//convert persian to english
if (!function_exists('toEnglishNumber')){
    function toEnglishNumber($number) {
        $farsiNumbers = ['۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '۰'];
        $englishNumbers = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];

        return str_replace($farsiNumbers, $englishNumbers, $number);
    }
}

if (!function_exists('storage_app_path')) {

    /**
     * @param $path
     * @return string
     */
    function storage_app_path($path): string
    {
        return storage_path('app' . DIRECTORY_SEPARATOR . $path);
    }
}

if (!function_exists('generateAddress')) {

    function generateAddress(UploadedFile $file, string $directory, int $entityId)
    {
        try {
            $directory = $directory . DIRECTORY_SEPARATOR . Hashids::encode($entityId);

            return $file->store($directory);
        } catch (\Throwable $exception) {
            throw new \Exception('امکان ارسال فایل وجود ندارد!');
        }
    }
}

if (!function_exists('uploadFile')) {

    /**
     * @throws Exception
     */
    function uploadFile(UploadedFile $file, string $directory, int $entityId): array
    {
        $repo = new FileRepository();

        return $repo->create([
            'address' => generateAddress($file, $directory, $entityId),
            'contentType' => $file->getType(),
            'category' => FileCategoryEnums::DOCUMENT,
        ]);
    }
}
