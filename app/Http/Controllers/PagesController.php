<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{

    public function welcome()
    {
        return view('pages.welcome');
    }

    public function help()
    {
        return view('pages.help');
    }

    public function agreement()
    {
        return view('pages.agreement');
    }

    public function ad()
    {
        return view('pages.ad');
    }

    public function forecastsEurUsd()
    {
        $kase = DB::connection('mysql_mig')
            ->table(env('DB_MIG_TABLE','mig_news'))
            ->orderBy('created', 'DESC')
           ->where('news_category_value', 'forecasts')
            ->paginate(10);
        $kase->setPath('forecasts-eur-usd');

        $function_text = function ($content) {
            preg_match('/<p>(.*?)<\/p>/i', $content, $paragraphs);
            return count($paragraphs) ? $paragraphs[0] : $content;
        };
        return view('pages.forecasts-eur-usd', compact('kase', 'function_text'));
    }

    public function kaseSessions()
    {
        $kase = DB::connection('mysql_mig')
            ->table(env('DB_MIG_TABLE','mig_news'))
            ->orderBy('created', 'DESC')
            ->where('news_category_value', 'finance')
            ->paginate(10);
        $kase->setPath('kase-sessions');

        $function_text = function ($content) {
            preg_match('/<p>(.*?)<\/p>/i', $content, $paragraphs);
            return count($paragraphs) ? $paragraphs[0] : $content;
        };

        return view('pages.kase-sessions', compact('kase', 'function_text'));
    }

    public function kaseSessionsView($id)
    {
        $kase = DB::connection('mysql_mig')
            ->table(env('DB_MIG_TABLE','mig_news'))
            ->where('nid', $id)->first();
        return view('pages.kase-sessions-view', compact('kase'));
    }

    public function forecastsEurUsdView($id)
    {
        $kase = DB::connection('mysql_mig')
            ->table(env('DB_MIG_TABLE','mig_news'))
            ->where('nid', $id)
            ->first();
        return view('pages.forecasts-eur-usd-view', compact('kase'));
    }

    public function informer()
    {
        return view('pages.informer');
    }

    private function getWaveType($type)
    {
        switch ($type) {
            case 'eurusd-longterm':
                return 'EURUSD / Долгосрочный период';
                break;
            case 'eurusd-current':
                return 'EURUSD / Текущая ситуация';
                break;
            case 'gbpusd-longterm':
                return 'GBPUSD / Долгосрочный период';
                break;
            case 'gbpusd-current':
                return 'GBPUSD / Текущая ситуация';
                break;
            case 'usdrub-longterm':
                return 'USDRUB / Долгосрочный период';
                break;
            case 'usdrub-current':
                return 'USDRUB / Текущая ситуация';
                break;
            case 'brent-longterm':
                return 'Нефть (Brent) / Долгосрочный период';
                break;
            case 'brent-current':
                return 'Нефть (Brent) / Текущая ситуация';
                break;
            default:
                return false;
        }
    }

    public function wave($page)
    {

        $titlePage = $this->getWaveType($page);
        if (!$titlePage) return false;

        $data = DB::connection('mysql_mig')
            ->table(env('DB_MIG_TABLE_WAVE','mig_wave'))
            ->orderBy('created', 'DESC')
            ->where('wave_type_value', $page)
            ->paginate(10);
        $data->setPath($page);

        $function_text = function ($content) {
            preg_match('/<p>(.*?)<\/p>/i', $content, $paragraphs);
            return count($paragraphs) ? $paragraphs[0] : $content;
        };

        return view('pages.wave.list', compact('data', 'function_text', 'page', 'titlePage'));
    }

    public function waveView($page, $id = 0)
    {
        $titlePage = $this->getWaveType($page);
        if (!$titlePage) return false;

        $data = DB::connection('mysql_mig')
            ->table(env('DB_MIG_TABLE_WAVE','mig_wave'))
            ->where('nid', $id)
            ->first();
        return view('pages.wave.view', compact('data', 'page', 'titlePage'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

}
