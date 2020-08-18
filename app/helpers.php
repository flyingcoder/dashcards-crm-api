<?php

use App\Company;
use App\Notifications\CompanyNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Tolawho\Loggy\Facades\Loggy;

if (!function_exists('parseSearchParam')) {

    /**
     * @param Request $request
     * @return array|bool
     */
    function parseSearchParam(Request $request)
    {

        if (!$request->has('sort'))
            return false;

        $sort = $request->sort;

        $params = explode('|', $sort);

        if (count($params) == 1)
            return false;

        return [$params[0], $params[1]];
    }
}

if (!function_exists('secondsForHumans')) {

    /**
     * @param $seconds
     * @param bool $display_sec
     * @return string
     */
    function secondsForHumans($seconds, $display_sec = false)
    {

        $hours = floor($seconds / 3600);

        $minutes = floor(($seconds / 60) % 60);

        $seconds = $seconds % 60;

        $formatted = $hours . "h " . $minutes . "m ";

        if ($display_sec)
            $formatted .= $seconds . "s";

        return $formatted;
    }

}
if (!function_exists('parseSeconds')) {
    /**
     * @param $given_seconds
     * @param string $sep
     * @return stdClass
     */
    function parseSeconds($given_seconds, $sep = ":")
    {
        $hours = floor($given_seconds / 3600);
        $minutes = floor(($given_seconds / 60) % 60);
        $seconds = $given_seconds % 60;

        $data = new stdClass();
        $data->total_seconds = $given_seconds;
        $data->hrs = (int)$hours;
        $data->min = (int)$minutes;
        $data->sec = (int)$seconds;
        $data->readable = secondsForHumans($seconds);
        $data->format = sprintf("%02d%s%02d%s%02d", $hours, $sep, $minutes, $sep, $seconds);
        if ($hours > 99) {
            $data->format = sprintf("%d%s%02d%s%02d", $hours, $sep, $minutes, $sep, $seconds);
        }

        return $data;
    }
}
if (!function_exists('natural_language_join')) {

    /**
     * @param array $list
     * @param string $conjunction
     * @return mixed|string
     */
    function natural_language_join(array $list, $conjunction = 'and')
    {
        $last = array_pop($list);
        if ($list) {
            return implode(', ', $list) . ' ' . $conjunction . ' ' . $last;
        }
        return $last;
    }
}

if (!function_exists('random_avatar')) {

    /**
     * @param null $gender
     * @return mixed
     */
    function random_avatar($gender = null)
    {
        $avatar = [
            'male' => config('app.url') . '/img/members/alfred.png',
            'female' => config('app.url') . '/img/members/selena.png',
            'neutral' => config('app.url') . '/img/members/neutral.png',
        ];
        if (!is_null($gender) && array_key_exists(strtolower(trim($gender)), $avatar)) {
            return $avatar[strtolower(trim($gender))];
        }
        return $avatar[array_rand($avatar, 1)];
    }
}

if (!function_exists('stripos_arr')) {
    /**
     * @param $haystack
     * @param $needle
     * @return array|bool
     */
    function stripos_arr($haystack, $needle)
    {
        if (!is_array($haystack)) $haystack = array($haystack);
        foreach ($haystack as $index => $what) {
            $pos = stripos($what, $needle);
            if ($pos !== false) return [$index, $pos];
        }
        return false;
    }
}

if (!function_exists('finalSql')) {
    /**
     * @param $query
     * @return string
     */
    function finalSql($query)
    {
        $sql_str = $query->toSql();
        $bindings = $query->getBindings();

        $wrapped_str = str_replace('?', "'?'", $sql_str);

        return str_replace_array('?', $bindings, $wrapped_str);
    }
}

if (!function_exists('createLinks')) {
    /**
     * @param $content
     * @return string|string[]
     */
    function createLinks($content)
    {
        $link_regex = '/(http\:\/\/|https\:\/\/|www\.)([^\n\r\ ]+)/i';
        preg_match_all($link_regex, $content, $matches);

        foreach ($matches[0] as $url) {
            $matchUrl = strip_tags($url);
            $tagcode = '<a href="' . $matchUrl . '" target="_blank">' . $matchUrl . '</a>';
            $content = str_replace($url, $tagcode, $content);
        }

        return $content;
    }
}

if (!function_exists('createMentions')) {
    /**
     * @param $content
     * @return array
     */
    function createMentions($content)
    {
        $mention_regex = '/@([A-Za-z0-9_]+)/i';
        $mentions = array();
        preg_match_all($mention_regex, $content, $matches);

        foreach ($matches[1] as $mention) {
            $mention = trim($mention);
            $user = \App\User::where('username', '=', $mention)->first();
            if ($user) {
                $matchSearch = '@' . $mention;
                $matchPlace = '@[' . $user->id . ']';
                $content = str_replace($matchSearch, $matchPlace, $content);
                $mentions[] = $user->id;
            }
        }

        return array(
            'content' => nl2br($content),
            'mentions' => array_unique($mentions)
        );
    }
}

if (!function_exists('getMentions')) {
    /**
     * @param $content
     * @return string|string[]
     */
    function getMentions($content)
    {
        $mention_regex = '/@\[([0-9]+)\]/i';

        if (preg_match_all($mention_regex, $content, $matches)) {
            foreach ($matches[1] as $match) {
                $user = \App\User::find($match);
                if ($user) {
                    $match_search = '@[' . $match . ']';
                    $match_replace = ' <a class="profile-link" data-id="' . $user->id . '" title="' . $user->fullname . '">@' . $user->username . '</a>';
                    $content = str_replace($match_search, $match_replace, $content);
                }
            }
        }

        return $content;
    }
}

if (!function_exists('getFormattedContent')) {
    /**
     * @param $content
     * @return string|string[]
     */
    function getFormattedContent($content)
    {
        $content = createLinks($content);
        $content = getMentions($content);
        return $content;
    }
}

if (!function_exists('cleanHtml')) {
    /**
     * @param $content
     * @return mixed
     */
    function cleanHtml($content)
    {
        $cleaner = app(\App\Repositories\TemplateRepository::class);
        $content = $cleaner->cleanHtml($content);
        return $content;
    }
}

if (!function_exists('uniqUuidFrom')) {
    /**
     * @return string
     */
    function uniqUuidFrom()
    {
        $uuid = \Illuminate\Support\Str::uuid();
        return $uuid . '-' . now()->format('YmdHis');
    }
}


if (!function_exists('company_logo')) {

    /**
     * @param Company|null $company
     * @return mixed|string
     */
    function company_logo(Company $company = null)
    {
        if ($company && $company->company_logo)
            return $company->company_logo;

        return config('app.url') . '/img/logo/invoice-logo.png';
    }
}

if (!function_exists('company_notification')) {

    /**
     * @param array $data
     * @param User|null $user
     * @return mixed|string
     * @throws Exception
     */
    function company_notification($data = [], User $user = null)
    {
        try {
            if (is_null($user) && auth()->check()) {
                $user = auth()->user();
            }
            if ($user) {
                $company = $user->company();
                $formatted = array(
                    'company' => $company->id,
                    'targets' => $data['targets'] ?? [],
                    'title' => $data['title'] ?? 'Event Notification',
                    'image_url' => $data['image_url'] ?? company_logo($company),
                    'message' => $data['message'] ?? '',
                    'type' => $data['type'] ?? 'notification',
                    'path' => $data['path'] ?? null,
                    'url' => $data['url'] ?? null,
                    'notif_only' => $data['notif_only'] ?? false
                );
                Notification::send($user, new CompanyNotification($formatted));
            }
        } catch (\Exception $e) {
            Loggy::write('event',  $e->getMessage());
        }
    }
}