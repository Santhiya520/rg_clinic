<?php

namespace App\Helpers;

class StringHelper
{
    /**
     * Encode special characters for database storage
     * Only single and double quotes
     */
    public static function encodeQuotes(?string $text): string
    {
        if ($text === null) {
            return '';
        }
        return str_replace(["'", '"'], ['#1sp#', '#2sp#'], $text);
    }

    /**
     * Decode special characters for display
     * Only single and double quotes
     */
    public static function decodeQuotes(?string $text): string
    {
        if ($text === null) {
            return '';
        }
        return str_replace(['#1sp#', '#2sp#'], ["'", '"'], $text);
    }

    /**
     * Encode quotes in array values
     */
    public static function encodeQuotesInArray(array $data, array $fields = ['name']): array
    {
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $data[$field] = self::encodeQuotes($data[$field]);
            }
        }
        return $data;
    }

    /**
     * Decode quotes in model or array
     */
    public static function decodeQuotesInItem($item, array $fields = ['name'])
    {
        if (is_array($item)) {
            foreach ($fields as $field) {
                if (array_key_exists($field, $item)) {
                    $item[$field] = self::decodeQuotes($item[$field]);
                }
            }
            return $item;
        } elseif (is_object($item)) {
            foreach ($fields as $field) {
                if (property_exists($item, $field)) {
                    $item->$field = self::decodeQuotes($item->$field);
                }
            }
            return $item;
        }
        return $item;
    }
}