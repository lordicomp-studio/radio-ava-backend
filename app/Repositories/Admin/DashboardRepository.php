<?php

namespace App\Repositories\Admin;

use App\Models\Article;
use App\Models\Comment;
use App\Models\DownloadRecord;
use App\Models\Payment;
use App\Models\Product;
use App\Models\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use function Sodium\compare;

class DashboardRepository
{
    private const int cacheExpirationTime = 3600;

    // simple number results
    public function getViewsCount(Carbon $fromDate) : array
    {
        return Cache::remember("adminDashboard_getViewsCount", self::cacheExpirationTime, function () use ($fromDate) {
            $timeDifferenceInHours = $fromDate->diffInHours(Carbon::now());

            $olderFromData = (clone $fromDate)->addHours(-1 * $timeDifferenceInHours);

            $olderCount = View::where('created_at', '>=', $olderFromData)
                ->where('created_at', '<=', $fromDate)
                ->count();

            $currentCount = View::where('created_at', '>=', $fromDate)
                ->count();

            $growthPercentage = ($currentCount - $olderCount) / $olderCount * 100;

            return compact('olderCount', 'currentCount', 'growthPercentage');
        });
    }

    public function getDownloadsCount(Carbon $fromDate) : array
    {
        return Cache::remember("adminDashboard_getDownloadsCount", self::cacheExpirationTime, function () use ($fromDate) {
            $timeDifferenceInHours = $fromDate->diffInHours(Carbon::now());

            $olderFromData = (clone $fromDate)->addHours(-1 * $timeDifferenceInHours);

            $olderCount = DownloadRecord::where('created_at', '>=', $olderFromData)
                ->where('created_at', '<=', $fromDate)
                ->count();

            $currentCount = DownloadRecord::where('created_at', '>=', $fromDate)
                ->count();

            $growthPercentage = ($currentCount - $olderCount) / $olderCount * 100;

            return compact('olderCount', 'currentCount', 'growthPercentage');
        });
    }

    public function getPaymentsCount(Carbon $fromDate) : array
    {
        return Cache::remember("adminDashboard_getPaymentsCount", self::cacheExpirationTime, function () use ($fromDate) {
            $timeDifferenceInHours = $fromDate->diffInHours(Carbon::now());

            $olderFromData = (clone $fromDate)->addHours(-1 * $timeDifferenceInHours);

            $olderCount = Payment::where('created_at', '>=', $olderFromData)
                ->where('created_at', '<=', $fromDate)
                ->count();

            $currentCount = Payment::where('created_at', '>=', $fromDate)
                ->count();

            $growthPercentage = ($currentCount - $olderCount) / $olderCount * 100;

            return compact('olderCount', 'currentCount', 'growthPercentage');
        });
    }

    public function getSalesAmount(Carbon $fromDate) : array
    {
        return Cache::remember("adminDashboard_getPaymentsCount", self::cacheExpirationTime, function () use ($fromDate) {
            $timeDifferenceInHours = $fromDate->diffInHours(Carbon::now());

            $olderFromData = (clone $fromDate)->addHours(-1 * $timeDifferenceInHours);

            $olderSum = Payment::where('created_at', '>=', $olderFromData)
                ->where('created_at', '<=', $fromDate)
                ->sum('price');

            $currentSum = Payment::where('created_at', '>=', $fromDate)
                ->sum('price');

            $growthPercentage = ($currentSum - $olderSum) / $olderSum * 100;

            return compact('olderSum', 'currentSum', 'growthPercentage');
        });
    }

    // pie/doughnut charts TODO add caching
    public function getViewerDevicePercentage(Carbon $fromDate) : array
    {
        return View::where('created_at', '>=', $fromDate)
            ->groupBy('device')
            ->get(['device', DB::raw('COUNT(id) as count')])
            ->toArray();
    }

    public function getViewerPlatformPercentage(Carbon $fromDate) : array
    {
        return View::where('created_at', '>=', $fromDate)
            ->groupBy('platform')
            ->get(['platform', DB::raw('COUNT(id) as count')])
            ->toArray();
    }

    public function getViewerBrowserPercentage(Carbon $fromDate) : array
    {
        return View::where('created_at', '>=', $fromDate)
            ->groupBy('browser')
            ->get(['browser', DB::raw('COUNT(id) as count')])
            ->toArray();
    }

    // item lists
    public function getTopSellingProducts(Carbon $fromDate) : array
    {
        return Product::where('created_at', '>=', $fromDate)
            ->withCount('payments')
            ->orderBy('payments_count', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function getTopRevenueProducts(Carbon $fromDate) : array
    {
        return Product::where('created_at', '>=', $fromDate)
            ->withSum('payments', 'price')
            ->orderBy('payments_sum_price', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function getMostDownloadedProducts(Carbon $fromDate) : array
    {
        return Product::where('created_at', '>=', $fromDate)
            ->withCount('downloads')
            ->orderBy('downloads_count', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function getMostViewedProducts(Carbon $fromDate) : array
    {
        return Product::where('created_at', '>=', $fromDate)
            ->withCount('views')
            ->orderBy('views_count', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function getHighestRatedProducts(Carbon $fromDate) : array
    {
        return Product::where('created_at', '>=', $fromDate)
            ->withAvg('rates', 'rate')
            ->orderBy('rates_avg_rate', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function getMostRatedProducts(Carbon $fromDate) : array
    {
        return Product::where('created_at', '>=', $fromDate)
            ->withSum('rates', 'rate')
            ->orderBy('rates_sum_rate', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function getLatestProducts(Carbon $fromDate) : array
    {
        return Product::latest()
            ->limit(5)
            ->get();
    }

    public function getMostViewedArticle(Carbon $fromDate) : array
    {
        return Article::where('created_at', '>=', $fromDate)
            ->withCount('views')
            ->orderBy('views_count', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function getLatestArticles(Carbon $fromDate) : array
    {
        return Article::latest()
            ->limit(5)
            ->get();
    }

    public function getLatestComments(Carbon $fromDate) : array
    {
        return Comment::latest()
            ->limit(5)
            ->get();
    }

    // line charts
    public function getTableLineChartDataForm(string $table, Carbon $fromDate, int $pointsCount = 10) : array
    {
        $now = Carbon::now();
        $timeDifferenceInHours = $fromDate->diffInHours($now);

        $stepLength = $timeDifferenceInHours / $pointsCount;

        $stepsArray = [];

        for ($i = 1; $i <= $pointsCount; $i++) {
            $stepsArray[$i - 1] = $fromDate->copy()->addHours($stepLength * $i);
        }

        /*SELECT
          SUM(CASE WHEN created_at > '2012-07-10 21:36:17' AND created_at <= '2013-10-03 21:36:17' THEN 1 ELSE 0 END) AS count1,
          SUM(CASE WHEN created_at > '2013-10-03 21:36:17' AND created_at <= '2014-12-27 21:36:17' THEN 1 ELSE 0 END) AS count2
        FROM download_records;*/

        $query = "SELECT ";
        for ($i = 0; $i < $pointsCount; $i++) {
            $step = $stepsArray[$i];
            $formerStep = $i > 0 ? $stepsArray[$i - 1] : $fromDate;

            $query .= "SUM(CASE WHEN created_at > '$formerStep' AND created_at <= '$step' THEN 1 ELSE 0 END) AS '$step'";

            $query .= $i != $pointsCount - 1 ? ',' : ''; // adds the ending comma to all lines except the last one
        }

        $query .= " FROM $table";

        return DB::select($query);
    }

}
