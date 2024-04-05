<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use App\Models\User;
use Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

if (!function_exists('sql_trim_lower')) {
    function sql_trim_lower(string $string)
    {
        return 'ltrim(rtrim(lower(' . $string . ')))';
    }
}

if (!function_exists('get_fa_icon_by_ext')) {
    function get_fa_icon_by_ext(string $extension)
    {
        $extension = strtolower($extension);
        $imageExtensions = ['jpg', 'jpeg', 'png', 'tiff', 'gif', 'bmp'];
        $videoExtensions = ['mpg', 'mp4', 'mov', 'mkv'];
        $musicExtensions = ['mp3', 'wav', 'acc'];
        $archiveExtensions = ['zip', 'rar', 'tar', '7z', 'cab', 'dmg'];
        $codeExtensions = ['c', 'cpp', 'cs', 'ino', 'swift'];
        if (in_array($extension, $imageExtensions)) {
            return 'far fa-file-image';
        }
        if ($extension == 'pdf') {
            return 'fas fa-file-pdf';
        }
        if ($extension == 'html') {
            return 'fab fa-html5';
        }
        if ($extension == 'ppt' || $extension == 'pptx') {
            return 'fas fa-file-powerpoint"';
        }
        if ($extension == 'csv') {
            return 'fas fa-file-csv"';
        }
        if ($extension == 'xls' || $extension == 'xlsx') {
            return 'fas fa-file-excel"';
        }
        if ($extension == 'word') {
            return 'fas fa-file-word';
        }
        if (in_array($extension, $videoExtensions)) {
            return 'fas fa-file-video';
        }
        if (in_array($extension, $archiveExtensions)) {
            return 'fas fa-file-archive';
        }
        if (in_array($extension, $musicExtensions)) {
            return 'fas fa-file-music';
        }
        if (in_array($extension, $codeExtensions)) {
            return 'far fa-file-code';
        }

        return 'far fa-file';
    }
}

if (!function_exists('str_trim_lower')) {
    function str_trim_lower(string $string)
    {
        return strtolower(trim($string));
    }
}

if (!function_exists('str_pad_left_two_digits')) {
    function str_pad_left_two_digits($string)
    {
        return str_pad($string, 2, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('find_and_place_mark_element')) {
    function find_and_place_mark_element(string $subject, string $search)
    {
        $matchStartIndex = stripos($subject, $search);
        if ($matchStartIndex != -1) {
            $matchingKeyword = substr($subject, $matchStartIndex, strlen($search));
            $subject = str_replace($matchingKeyword, '<mark>' . $matchingKeyword . '</mark>', $subject);
        }
        return $subject;
    }
}

if (!function_exists('db_record_exists')) {
    function db_record_exists(string $table, string $column, $value, $include = null, $excludeRecordId = null)
    {
        $record = DB::table($table)->where(DB::raw(sql_trim_lower($column)), '=', $value);

        if ($excludeRecordId) {
            $record = $record->where('id', '!=', $excludeRecordId);
        }
        if ($include) {
            $record = $record->where($include['column'], '=', $include['value']);
        }

        return (bool)$record->get()->count();
    }
}

if (!function_exists('readeble_file_size')) {
    function readeble_file_size(int $size, string $unit = '')
    {
        if ((!$unit && $size >= 1 << 30) || $unit == "GB")
            return number_format($size / (1 << 30), 2) . " GB";
        if ((!$unit && $size >= 1 << 20) || $unit == "MB")
            return number_format($size / (1 << 20), 2) . " MB";
        if ((!$unit && $size >= 1 << 10) || $unit == "KB")
            return number_format($size / (1 << 10), 2) . " KB";
        return number_format($size) . " bytes";
    }
}

if (!function_exists('str_date_format')) {
    function str_date_format($year, $month, $date, $seperator = "/")
    {
        $values = [$year, str_pad($month, 2, '0', STR_PAD_LEFT), str_pad($date, 2, '0', STR_PAD_LEFT)];
        return implode($seperator, $values);
    }
}

if (!function_exists('custom_date_format')) {
    function custom_date_format($year, $month, $day)
    {
        if (strlen($year) == 0 && strlen($month) == 0 && strlen($day) == 0) {
            return '';
        }
        $format = '';
        $readFormat = '';
        $strDate = '';
        if (strlen($month) > 0) {
            if (strlen($year) > 0){
                $format = 'M, ';
            }
            else{
                $format = 'M';
            }
            $readFormat = 'm';
            $strDate = $month;
        }
        if (strlen($day) > 0) {
            if (strlen($month) > 0) {
                $format = 'M, j ';
                $readFormat = 'm-d';
                $strDate = $month . '-' . $day;
            } else {
                $format = 'j ';
                $readFormat = 'd';
                $strDate = $day;
            }
        }
        if (strlen($year) > 0) {
            $format = $format . 'Y';
            if (strlen($month) > 0 || strlen($day) > 0) {
                $readFormat = 'Y-' . $readFormat;
                $strDate = $year . '-' . $strDate;
            } else {
                return $year;
            }
        }

        $date = \DateTime::createFromFormat($readFormat, $strDate);
        return $date->format($format);
    }

}



class Helper
{
    public static function applClasses()
    {
        // default data array
        $DefaultData = [
            'mainLayoutType' => 'vertical',
            'theme' => 'light',
            'sidebarCollapsed' => false,
            'navbarColor' => '',
            'horizontalMenuType' => 'floating',
            'verticalMenuNavbarType' => 'floating',
            'footerType' => 'static', //footer
            'layoutWidth' => 'full',
            'showMenu' => true,
            'bodyClass' => '',
            'bodyStyle' => '',
            'pageClass' => '',
            'pageHeader' => true,
            'contentLayout' => 'default',
            'blankPage' => false,
            'defaultLanguage' => 'en',
            'direction' => env('MIX_CONTENT_DIRECTION', 'ltr'),
        ];

        // if any key missing of array from custom.php file it will be merge and set a default value from dataDefault array and store in data variable
        $data = array_merge($DefaultData, config('custom.custom'));

        // All options available in the template
        $allOptions = [
            'mainLayoutType' => array('vertical', 'horizontal'),
            'theme' => array('light' => 'light', 'dark' => 'dark-layout', 'bordered' => 'bordered-layout', 'semi-dark' => 'semi-dark-layout'),
            'sidebarCollapsed' => array(true, false),
            'showMenu' => array(true, false),
            'layoutWidth' => array('full', 'boxed'),
            'navbarColor' => array('bg-primary', 'bg-info', 'bg-warning', 'bg-success', 'bg-danger', 'bg-dark'),
            'horizontalMenuType' => array('floating' => 'navbar-floating', 'static' => 'navbar-static', 'sticky' => 'navbar-sticky'),
            'horizontalMenuClass' => array('static' => '', 'sticky' => 'fixed-top', 'floating' => 'floating-nav'),
            'verticalMenuNavbarType' => array('floating' => 'navbar-floating', 'static' => 'navbar-static', 'sticky' => 'navbar-sticky', 'hidden' => 'navbar-hidden'),
            'navbarClass' => array('floating' => 'floating-nav', 'static' => 'navbar-static-top', 'sticky' => 'fixed-top', 'hidden' => 'd-none'),
            'footerType' => array('static' => 'footer-static', 'sticky' => 'footer-fixed', 'hidden' => 'footer-hidden'),
            'pageHeader' => array(true, false),
            'contentLayout' => array('default', 'content-left-sidebar', 'content-right-sidebar', 'content-detached-left-sidebar', 'content-detached-right-sidebar'),
            'blankPage' => array(false, true),
            'sidebarPositionClass' => array('content-left-sidebar' => 'sidebar-left', 'content-right-sidebar' => 'sidebar-right', 'content-detached-left-sidebar' => 'sidebar-detached sidebar-left', 'content-detached-right-sidebar' => 'sidebar-detached sidebar-right', 'default' => 'default-sidebar-position'),
            'contentsidebarClass' => array('content-left-sidebar' => 'content-right', 'content-right-sidebar' => 'content-left', 'content-detached-left-sidebar' => 'content-detached content-right', 'content-detached-right-sidebar' => 'content-detached content-left', 'default' => 'default-sidebar'),
            'defaultLanguage' => array('en' => 'en', 'fr' => 'fr', 'de' => 'de', 'pt' => 'pt'),
            'direction' => array('ltr', 'rtl'),
        ];

        //if mainLayoutType value empty or not match with default options in custom.php config file then set a default value
        foreach ($allOptions as $key => $value) {
            if (array_key_exists($key, $DefaultData)) {
                if (gettype($DefaultData[$key]) === gettype($data[$key])) {
                    // data key should be string
                    if (is_string($data[$key])) {
                        // data key should not be empty
                        if (isset($data[$key]) && $data[$key] !== null) {
                            // data key should not be exist inside allOptions array's sub array
                            if (!array_key_exists($data[$key], $value)) {
                                // ensure that passed value should be match with any of allOptions array value
                                $result = array_search($data[$key], $value, 'strict');
                                if (empty($result) && $result !== 0) {
                                    $data[$key] = $DefaultData[$key];
                                }
                            }
                        } else {
                            // if data key not set or
                            $data[$key] = $DefaultData[$key];
                        }
                    }
                } else {
                    $data[$key] = $DefaultData[$key];
                }
            }
        }

        //layout classes
        $layoutClasses = [
            'theme' => $data['theme'],
            'layoutTheme' => $allOptions['theme'][$data['theme']],
            'sidebarCollapsed' => $data['sidebarCollapsed'],
            'showMenu' => $data['showMenu'],
            'layoutWidth' => $data['layoutWidth'],
            'verticalMenuNavbarType' => $allOptions['verticalMenuNavbarType'][$data['verticalMenuNavbarType']],
            'navbarClass' => $allOptions['navbarClass'][$data['verticalMenuNavbarType']],
            'navbarColor' => $data['navbarColor'],
            'horizontalMenuType' => $allOptions['horizontalMenuType'][$data['horizontalMenuType']],
            'horizontalMenuClass' => $allOptions['horizontalMenuClass'][$data['horizontalMenuType']],
            'footerType' => $allOptions['footerType'][$data['footerType']],
            'sidebarClass' => 'menu-expanded',
            'bodyClass' => $data['bodyClass'],
            'bodyStyle' => $data['bodyStyle'],
            'pageClass' => $data['pageClass'],
            'pageHeader' => $data['pageHeader'],
            'blankPage' => $data['blankPage'],
            'blankPageClass' => '',
            'contentLayout' => $data['contentLayout'],
            'sidebarPositionClass' => $allOptions['sidebarPositionClass'][$data['contentLayout']],
            'contentsidebarClass' => $allOptions['contentsidebarClass'][$data['contentLayout']],
            'mainLayoutType' => $data['mainLayoutType'],
            'defaultLanguage' => $allOptions['defaultLanguage'][$data['defaultLanguage']],
            'direction' => $data['direction'],
        ];
        // set default language if session hasn't locale value the set default language
        if (!session()->has('locale')) {
            app()->setLocale($layoutClasses['defaultLanguage']);
        }

        // sidebar Collapsed
        if ($layoutClasses['sidebarCollapsed'] == 'true') {
            $layoutClasses['sidebarClass'] = "menu-collapsed";
        }

        // blank page class
        if ($layoutClasses['blankPage'] == 'true') {
            $layoutClasses['blankPageClass'] = "blank-page";
        }

        return $layoutClasses;
    }

    public static function updatePageConfig($pageConfigs)
    {
        $demo = 'custom';
        if (isset($pageConfigs)) {
            if (count($pageConfigs) > 0) {
                foreach ($pageConfigs as $config => $val) {
                    Config::set('custom.' . $demo . '.' . $config, $val);
                }
            }
        }
    }

    public static function getUserStatistics()
    {
        $userStatistics = [];
        $groupedUsers = DB::select(DB::raw('SELECT roles.name AS role_name, COUNT(users.id) AS user_count FROM users INNER JOIN roles ON users.role_key = roles.key GROUP BY users.role_key'));
        foreach ($groupedUsers as $user) {
            $userStatistics[$user->role_name] = $user->user_count;
        }
        return $userStatistics;
    }

    public static function getLastMemberRegistrationDate()
    {
        $lastDate = User::max('created_at');
        if ($lastDate) {
            return Carbon::parse($lastDate)->diffForHumans();
        }
        return '1 sec ago';
    }

    public static function currentNavbarIndex()
    {
        if (!Auth::user()) {
            return 2;
        }
        if (Auth::user()->role_key === 'admin') {
            return 0;
        }
        if (Auth::user()->role_key === 'member') {
            return 2;
        }
        if (Auth::user()->role_key === 'data-entry') {
            return 3;
        }
    }

    public static function findAndPlaceMarkElement($fullString, $findingKeyword)
    {
        $matchStartIndex = stripos($fullString, $findingKeyword);
        if ($matchStartIndex != -1) {
            $matchingKeyword = substr($fullString, $matchStartIndex, strlen($findingKeyword));
            $fullString = str_replace($matchingKeyword, '<mark>' . $matchingKeyword . '</mark>', $fullString);
        }
        return $fullString;
    }

    public static $alertMessages = [
        'create-success' => 'Record created successfully.',
        'create-error' => 'Something went wrong while creating the record',
        'update-success' => 'Record updated successfully.',
        'update-error' => 'Something went wrong while updating the record',
        'delete-success' => 'Record deleted successfully.',
        'delete-error' => 'Something went wrong while deleting the record'
    ];
}
