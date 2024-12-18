<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Core\Date;

class Date Extends Format
{
    // Get The Current Date & Time
    public static function current():string
    {
        return date(self::default());
    }

    // Date for Database
    /**
     * @param int|string $days - Default is 0;
     */
    public static function db(int|string $days = 0):string
    {
        $date = self::current();
        $days = (int) $days;
        return date(self::db(), strtotime("{$date} +{$days} days"));
    }

    // Get Past/Present/Future Date
    /**
     * @param int|string $days - Default is 0;
     */
    public static function new(int $days = 0):string
    {
        $realtime = self::current();
        return date(self::default(), strtotime("{$realtime} +{$days} days"));
    }

    // Get Date Difference
    /**
     * @param string $start - Required Argument as a string date like "2024-12-18";
     * @param string $end - Required Argument as a string date like "2024-12-19";
     */
    public static function diff(string $start, string $end):int
    {
        $diff = date_diff(date_create($start), date_create($end));
        // Return Days Difference
        return (int) $diff->format('%R%a');
    }

    // Check if Date is Past
    /**
     * @param string $date - Required Argument as a string date like "2024-12-18";
     */
    public static function isPast(string $date):bool
    {
        return date(self::default(), strtotime($date)) < self::new();
    }

    // Check if Date is Future
    /**
     * @param string $date - Required Argument as a string date like "2024-12-18";
     */
    public static function isFuture(string $date):bool
    {
        return date(self::default(), strtotime($date)) > self::new();
    }
}