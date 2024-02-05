<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Number;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class CustomHelper
{
    const RETURN_BOOL = 'return_bool';
    const RETURN_STRING = 'return_string';
    const RETURN_ARRAY = 'return_array';
    const RETURN_KEY = 'return_key';
    const RETURN_VALUE = 'return_value';

    public static function containStringInArray(string $string, array $array, string $returnType = self::RETURN_BOOL): bool|string
    {

        foreach ($array as $key => $value) {

            if (strpos($value, $string) !== false) { // Yoshi version

                if ($returnType === self::RETURN_VALUE) {
                    return $value;
                } elseif ($returnType === self::RETURN_KEY) {
                    return (string) $key;
                } else {
                    return true;
                }
            }
        }

        return false;
    }

    public static function removeInStringElement(string $string, array $stringArrays, string $delimiter): string
    {
        if (empty($string)) {
            return '';
        }

        $parts = explode($delimiter, $string);
        foreach ($stringArrays as $keyStringArray => $valueStringArray) {

            $keyArray = CustomHelper::containStringInArray($valueStringArray, $parts, CustomHelper::RETURN_KEY);
            array_splice($parts, $keyArray, 1);
        }

        $newString = implode($delimiter, $parts);

        return $newString;
    }

    public static function parseUrlQuery(string $url, array $removeItems = []): string
    {
        $urlQuery = parse_url($url, PHP_URL_QUERY);

        return url()->current() . '?' . CustomHelper::removeInStringElement($urlQuery ?? '', $removeItems, '&');
    }

    public static function getRawQueryString($request): string
    {
        return substr(str_replace($request->url(), '', $request->fullUrl()), 1);
    }

    public static function modifyQueryString(string $originalQueryString, array $sortOptions = [], string $returnType = self::RETURN_STRING)
    {
        // Parse the original query string
        $queryParams = collect(explode('&', $originalQueryString))
            ->filter()
            ->mapWithKeys(function ($param) {
                [$key, $value] = explode('=', $param);

                return [$key => $value];
            });

        // Merge the sort options into the query parameters
        $queryParams = $queryParams->merge($sortOptions);

        if ($returnType === self::RETURN_ARRAY) {
            return $queryParams->toArray();
        }

        // Build the new query string
        $newQueryString = $queryParams->map(function ($value, $key) {
            return "{$key}={$value}";
        })->implode('&');

        return $newQueryString;
    }

    public static function populateOldValue(string $key, array $arrayLookup): void
    {

        $oldKeys = old($key) ?? [];
        $collection = collect($arrayLookup);

        $newCollection = $collection->filter(function (string $value, int $key) use ($oldKeys) {
            if (in_array($key, $oldKeys)) {
                return $value;
            }
        });

        session()->put('_old_input.' . $key, $newCollection->toArray());
    }

    public static function replaceString(string $string, string $search, string $stringReplace = ''): string
    {
        return Str::of($string)->replace($search, $stringReplace);
    }

    public static function mergeAndUniqueBy(array|Collection $firstData, ?array $secondData, string $key = 'id'): array
    {
        // Convert the arrays to collections for easier manipulation
        $firstCollection = collect($firstData);
        $secondCollection = collect($secondData);

        // Merge the collections based on the specified key and keep the values from $secondCollection
        $mergedCollection = $firstCollection->merge($secondCollection);

        // Use unique to keep only the distinct items based on the specified key
        $uniqueMergedCollection = $mergedCollection->unique($key);

        // Convert the collection back to an array if needed
        $finalResult = $uniqueMergedCollection->values()->all();

        return $finalResult;
    }

    public static function isEmptyButNotZero(mixed $value): bool
    {
        return $value === null || $value === '' || $value === false || (is_array($value) && empty($value));
    }

    public static function numberFormat(int|float $number, ?int $precision = null, ?int $maxPrecision = null, ?string $locale = 'id')
    {
        return Number::format($number, $precision, locale: $locale);
    }

    // start like mudeng
    public static function getDateForHumanFormat(string|Carbon $dateTime): string
    {
        return (!empty($dateTime)) ? Carbon::parse($dateTime)->isoFormat('DD-MM-YYYY') : '';
    }

    public static function getDateForDB(string|Carbon $dateTime): string
    {
        return (!empty($dateTime)) ? Carbon::parse($dateTime)->isoFormat('YYYY-MM-DD') : '';
    }

    public static function getDateForHumanFormatJakarta(string|Carbon $dateTime): string
    {
        return (!empty($dateTime)) ? Carbon::parse($dateTime)->setTimezone('Asia/Jakarta')->isoFormat('DD-MM-YYYY') : '';
    }

    public static function getTimeForHumanFormatJakarta(string|Carbon $dateTime): string
    {
        return (!empty($dateTime)) ? Carbon::parse($dateTime)->setTimezone('Asia/Jakarta')->isoFormat('HH:mm:ss ZZ') : '';
    }

    public static function getDateTimeForHumanFormat(string|Carbon $dateTime): string
    {
        return (!empty($dateTime)) ? Carbon::parse($dateTime)->setTimezone('Asia/Jakarta')->isoFormat('DD-MM-YYYY HH:mm:ss ZZ') : '';
    }

    public static function getDateTimeForHumanFormatIntl(string|Carbon $dateTime): string
    {
        return (!empty($dateTime)) ? Carbon::parse($dateTime)->setTimezone('Asia/Jakarta')->isoFormat('YYYY-MM-DD HH:mm:ss ZZ') : '';
    }
    // end like mudeng

    public static function reverseMoneyMask(mixed $number, string $separator = ',')
    {
        if (is_null($number)) {
            return;
        }

        $filteredValue = str_replace($separator, '', $number);

        return $filteredValue;
    }

    public static function uploadFile($path, $file_name, $requestFile, $randomName = null)
    {
        $manager = new ImageManager(new Driver());

        $upload_loc = public_path($path . '/');
        $tmpName = (!empty($randomName) ? $randomName . '-' : '') . Str::slug($file_name) . '.webp';

        if (!File::exists($upload_loc)) {
            File::makeDirectory($upload_loc);
        }

        $manager->read($requestFile)->scaleDown(height: 500)->toWebp(90)->save(public_path($path . '/' . $tmpName));

        return $tmpName;
    }

    public static function niceExplode($delimiter, $string)
    {
        if (empty($string)) {
            return [];
        }

        $niceArray = array_filter(explode($delimiter, $string), 'strlen'); // array filter to remove empty item
        $niceArray = array_values($niceArray);

        return $niceArray;
    }
}
